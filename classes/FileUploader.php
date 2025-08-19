<?php
/**
 * SecureUploader
 * - finfo ile MIME doğrulaması
 * - Uzantı <-> MIME eşleşmesi
 * - Boyut limiti
 * - Benzersiz, güvenli dosya adı
 * - is_uploaded_file + move_uploaded_file
 * - upload/.htaccess ile script çalıştırmayı engelleme (Apache)
 * - İsteğe bağlı: sadece görsel doğrulaması (getimagesize)
 */
class SecureUploader
{
    private string $destDir;
    private int $maxSize; // bytes
    private array $allowedMime;  // ['image/jpeg' => ['jpg','jpeg'], ...]
    private bool $imagesOnly;
    private bool $createHtaccess;

    public function __construct(
        string $destDir,
        int $maxSize = 5_000_000, // ~5MB
        array $allowedMime = [],
        bool $imagesOnly = false,
        bool $createHtaccess = true
    ) {
        $this->destDir = rtrim($destDir, DIRECTORY_SEPARATOR);
        $this->maxSize = $maxSize;
        $this->imagesOnly = $imagesOnly;
        $this->createHtaccess = $createHtaccess;

        // Güvenli varsayılanlar
        $this->allowedMime = $allowedMime ?: [
            'image/jpeg' => ['jpg', 'jpeg'],
            'image/png' => ['png'],
            'image/gif' => ['gif'],
            'image/webp' => ['webp'],
            // İsterseniz dokümanları eklersiniz:
            // 'application/pdf' => ['pdf'],
        ];

        $this->prepareDestination();
    }

    private function prepareDestination(): void
    {
        // Dizin yoksa oluştur
        if (!is_dir($this->destDir)) {
            if (!mkdir($concurrentDirectory = $this->destDir, 0700, true) && !is_dir($concurrentDirectory)) {
                throw new RuntimeException('Yükleme klasörü oluşturulamadı: ' . $this->destDir);
            }
        }

        // Yazılabilir mi?
        if (!is_writable($this->destDir)) {
            throw new RuntimeException('Yükleme klasörü yazılabilir değil: ' . $this->destDir);
        }

        // Apache için script çalıştırmayı engelle
        if ($this->createHtaccess) {
            $ht = $this->destDir . DIRECTORY_SEPARATOR . '.htaccess';
            if (!file_exists($ht)) {
                // PHP/CGI çalıştırma engeli + dizin listelemesini kapat
                $content = "Options -Indexes\n<FilesMatch \"\\.(php|phar|phtml|cgi|pl|py|sh)$\">\n  Deny from all\n</FilesMatch>\n";
                @file_put_contents($ht, $content);
            }
        }
    }

    /** Tek dosya yükle */
    public function upload(array $file): array
    {
        $this->assertValidFileArray($file);
        $this->assertNoPhpErrors($file['error']);
        $this->assertSize($file['size']);

        // Gerçek MIME tespiti
        $mime = $this->detectMime($file['tmp_name']);
        $this->assertAllowedMime($mime);

        // Uzantı doğrulaması
        $ext = $this->guessExtension($file['name'], $mime);
        $this->assertMimeExtensionConsistency($mime, $ext);

        // Sadece görsel modu
        if ($this->imagesOnly) {
            $this->assertIsImage($file['tmp_name']);
        }

        // Güvenli, benzersiz isim
        $safeName = $this->generateSafeName($ext);

        // Alt klasör (isteğe bağlı – tarih bazlı)
        $subdir = date('Y/m/d');
        $targetDir = $this->destDir . DIRECTORY_SEPARATOR . $subdir;
        if (!is_dir($targetDir) && !mkdir($targetDir, 0700, true) && !is_dir($targetDir)) {
            throw new RuntimeException('Alt klasör oluşturulamadı: ' . $targetDir);
        }

        $targetPath = $targetDir . DIRECTORY_SEPARATOR . $safeName;

        // Sadece HTTP upload edilmiş dosyayı kabul et
        if (!is_uploaded_file($file['tmp_name'])) {
            throw new RuntimeException('Geçersiz yükleme kaynağı.');
        }

        if (!move_uploaded_file($file['tmp_name'], $targetPath)) {
            throw new RuntimeException('Dosya taşınamadı.');
        }

        // Son kontrol: permission sıkılaştır (Linux)
        @chmod($targetPath, 0600);

        return [
            'success' => true,
            'path' => $targetPath,
            'relative' => $subdir . '/' . $safeName,
            'filename' => $safeName,
            'mime' => $mime,
            'size' => (int) $file['size'],
            'ext' => $ext,
            // Güvenlik gereği orijinal isim döndürmemeyi tercih edebilirsin;
            // UI'da göstermek istiyorsan sanitize ederek döndür.
            'original' => $this->sanitizeFilename($file['name']),
        ];
    }

    /** Çoklu dosya yükle (HTML: name="files[]") */
    public function uploadMany(array $files): array
    {
        $results = [];
        if (!isset($files['name']) || !is_array($files['name'])) {
            throw new InvalidArgumentException('Çoklu yükleme formatı hatalı.');
        }
        $count = count($files['name']);
        for ($i = 0; $i < $count; $i++) {
            $single = [
                'name' => $files['name'][$i],
                'type' => $files['type'][$i] ?? '',
                'tmp_name' => $files['tmp_name'][$i],
                'error' => $files['error'][$i],
                'size' => $files['size'][$i],
            ];
            try {
                $results[] = $this->upload($single);
            } catch (Throwable $e) {
                $results[] = [
                    'success' => false,
                    'error' => $e->getMessage(),
                    'name' => $this->sanitizeFilename($single['name']),
                ];
            }
        }
        return $results;
    }

    /** ————— Yardımcılar / Doğrulamalar ————— */

    private function assertValidFileArray(array $file): void
    {
        foreach (['name', 'tmp_name', 'error', 'size'] as $k) {
            if (!array_key_exists($k, $file)) {
                throw new InvalidArgumentException('Yükleme alanı eksik: ' . $k);
            }
        }
    }

    private function assertNoPhpErrors(int $code): void
    {
        if ($code === UPLOAD_ERR_OK)
            return;

        $map = [
            UPLOAD_ERR_INI_SIZE => 'Dosya boyutu php.ini limitini aşıyor.',
            UPLOAD_ERR_FORM_SIZE => 'Dosya boyutu form limiti aşıyor.',
            UPLOAD_ERR_PARTIAL => 'Dosya kısmen yüklendi.',
            UPLOAD_ERR_NO_FILE => 'Dosya seçilmedi.',
            UPLOAD_ERR_NO_TMP_DIR => 'Geçici klasör eksik.',
            UPLOAD_ERR_CANT_WRITE => 'Diske yazılamadı.',
            UPLOAD_ERR_EXTENSION => 'PHP uzantısı yüklemeyi durdurdu.',
        ];
        $msg = $map[$code] ?? 'Bilinmeyen yükleme hatası: ' . $code;
        throw new RuntimeException($msg);
    }

    private function assertSize(int $size): void
    {
        if ($size <= 0) {
            throw new RuntimeException('Boş dosya.');
        }
        if ($size > $this->maxSize) {
            throw new RuntimeException('Dosya boyutu çok büyük (limit: ' . $this->maxSize . ' bayt).');
        }
    }

    private function detectMime(string $tmpPath): string
    {
        $finfo = new finfo(FILEINFO_MIME_TYPE);
        $mime = $finfo->file($tmpPath);
        if (!$mime || $mime === 'application/octet-stream') {
            throw new RuntimeException('MIME tespit edilemedi.');
        }
        return $mime;
    }

    private function assertAllowedMime(string $mime): void
    {
        if (!array_key_exists($mime, $this->allowedMime)) {
            throw new RuntimeException('İzin verilmeyen dosya türü: ' . $mime);
        }
    }

    private function guessExtension(string $originalName, string $mime): string
    {
        // Orijinal uzantıyı al (güvenlik için sadece gösterge)
        $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
        // MIME’a uygun bilinen uzantılardan ilkini seç
        $valid = $this->allowedMime[$mime] ?? [];
        if (!$valid) {
            throw new RuntimeException('MIME için uzantı eşlemesi yok: ' . $mime);
        }
        // Orijinal ext uygunsa onu kullan, değilse ilk geçerli ext
        if ($ext && in_array($ext, $valid, true)) {
            return $ext;
        }
        return $valid[0];
    }

    private function assertMimeExtensionConsistency(string $mime, string $ext): void
    {
        $valid = $this->allowedMime[$mime] ?? [];
        if (!in_array($ext, $valid, true)) {
            throw new RuntimeException('Uzantı ile MIME uyumsuz: ' . $ext . ' ~ ' . $mime);
        }
    }

    private function assertIsImage(string $tmpPath): void
    {
        $info = @getimagesize($tmpPath);
        if ($info === false) {
            throw new RuntimeException('Yüklenen dosya geçerli bir görsel değil.');
        }
        // Basit piksel sınırı (DoS’a karşı)
        $maxPixels = 40_000_000; // ~40MP
        if (!empty($info[0]) && !empty($info[1]) && ($info[0] * $info[1] > $maxPixels)) {
            throw new RuntimeException('Görsel boyutları aşırı büyük.');
        }
    }

    private function generateSafeName(string $ext): string
    {
        // Rastgele, çakışmaya dayanıklı
        $rand = bin2hex(random_bytes(16));
        return $rand . '.' . $ext;
    }

    private function sanitizeFilename(string $name): string
    {
        // Sadece görüntü amaçlı (orijinali asla kaydetme!)
        $name = preg_replace('/[^\p{L}\p{N}\.\-_]+/u', '_', $name);
        return trim($name, '._-');
    }

    /** İsteğe bağlı setter'lar */
    public function setMaxSize(int $bytes): void
    {
        $this->maxSize = $bytes;
    }
    public function setImagesOnly(bool $flag): void
    {
        $this->imagesOnly = $flag;
    }
    public function setAllowedMime(array $map): void
    {
        $this->allowedMime = $map;
    }
}
