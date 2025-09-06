<?php

/**
 * Rate Limiter Sınıfı
 * API ve form isteklerini sınırlamak için kullanılır
 */

require_once __DIR__ . '/Session.php';

class RateLimiter
{
    private static $instance = null;
    private $session;
    
    // Varsayılan limitler
    private $limits = [
        'login_attempts' => [
            'max_attempts' => 5,
            'time_window' => 900, // 15 dakika
            'block_duration' => 900 // 15 dakika blok
        ],
        'api_requests' => [
            'max_attempts' => 100,
            'time_window' => 3600, // 1 saat
            'block_duration' => 1800 // 30 dakika blok
        ],
        'password_reset' => [
            'max_attempts' => 3,
            'time_window' => 3600, // 1 saat
            'block_duration' => 3600 // 1 saat blok
        ],
        'file_upload' => [
            'max_attempts' => 10,
            'time_window' => 3600, // 1 saat
            'block_duration' => 1800 // 30 dakika blok
        ]
    ];
    
    private function __construct()
    {
        $this->session = Session::getInstance();
    }
    
    /**
     * Singleton instance döndür
     */
    public static function getInstance()
    {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }
    
    /**
     * Rate limit kontrolü
     */
    public static function checkLimit($type, $identifier = null)
    {
        $instance = self::getInstance();
        return $instance->isAllowed($type, $identifier);
    }
    
    /**
     * İstek izin veriliyor mu kontrol et
     */
    public function isAllowed($type, $identifier = null)
    {
        if (!isset($this->limits[$type])) {
            return true; // Limit tanımlanmamışsa izin ver
        }
        
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $limit = $this->limits[$type];
        
        // Mevcut istekleri al
        $requests = $this->getRequests($type, $identifier);
        
        // Süresi dolmuş istekleri temizle
        $requests = $this->cleanupExpiredRequests($requests, $limit['time_window']);
        
        // Limit kontrolü
        if (count($requests) >= $limit['max_attempts']) {
            // Son istek zamanını kontrol et
            $lastRequest = end($requests);
            $timeSinceLastRequest = time() - $lastRequest;
            
            // Blok süresi dolmuş mu?
            if ($timeSinceLastRequest < $limit['block_duration']) {
                return false; // Hala blokta
            } else {
                // Blok süresi dolmuş, istekleri temizle
                $this->clearRequests($type, $identifier);
                return true;
            }
        }
        
        return true;
    }
    
    /**
     * İstek kaydet
     */
    public function recordRequest($type, $identifier = null)
    {
        if (!isset($this->limits[$type])) {
            return;
        }
        
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $requests = $this->getRequests($type, $identifier);
        
        // Yeni istek ekle
        $requests[] = time();
        
        // İstekleri kaydet
        $this->saveRequests($type, $identifier, $requests);
    }
    
    /**
     * Kalan istek sayısını get et
     */
    public function getRemainingRequests($type, $identifier = null)
    {
        if (!isset($this->limits[$type])) {
            return -1; // Limit yok
        }
        
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $limit = $this->limits[$type];
        
        $requests = $this->getRequests($type, $identifier);
        $requests = $this->cleanupExpiredRequests($requests, $limit['time_window']);
        
        $remaining = $limit['max_attempts'] - count($requests);
        return max(0, $remaining);
    }
    
    /**
     * Blok süresini get et
     */
    public function getBlockTimeRemaining($type, $identifier = null)
    {
        if (!isset($this->limits[$type])) {
            return 0;
        }
        
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $limit = $this->limits[$type];
        
        $requests = $this->getRequests($type, $identifier);
        
        if (count($requests) < $limit['max_attempts']) {
            return 0; // Blok yok
        }
        
        $lastRequest = end($requests);
        $blockEndTime = $lastRequest + $limit['block_duration'];
        $remaining = $blockEndTime - time();
        
        return max(0, $remaining);
    }
    
    /**
     * İstekleri temizle
     */
    public function clearRequests($type, $identifier = null)
    {
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $this->session->remove("rate_limit_{$type}_{$identifier}");
    }
    
    /**
     * Tüm limitleri temizle
     */
    public function clearAllLimits($identifier = null)
    {
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        
        foreach (array_keys($this->limits) as $type) {
            $this->clearRequests($type, $identifier);
        }
    }
    
    /**
     * Limit ayarlarını güncelle
     */
    public function updateLimit($type, $maxAttempts, $timeWindow, $blockDuration = null)
    {
        if (isset($this->limits[$type])) {
            $this->limits[$type]['max_attempts'] = $maxAttempts;
            $this->limits[$type]['time_window'] = $timeWindow;
            
            if ($blockDuration !== null) {
                $this->limits[$type]['block_duration'] = $blockDuration;
            }
        }
    }
    
    /**
     * Yeni limit türü ekle
     */
    public function addLimitType($type, $maxAttempts, $timeWindow, $blockDuration = null)
    {
        $this->limits[$type] = [
            'max_attempts' => $maxAttempts,
            'time_window' => $timeWindow,
            'block_duration' => $blockDuration ?: $timeWindow
        ];
    }
    
    /**
     * Limit istatistikleri
     */
    public function getLimitStats($identifier = null)
    {
        $identifier = $identifier ?: $this->getDefaultIdentifier();
        $stats = [];
        
        foreach (array_keys($this->limits) as $type) {
            $requests = $this->getRequests($type, $identifier);
            $requests = $this->cleanupExpiredRequests($requests, $this->limits[$type]['time_window']);
            
            $stats[$type] = [
                'current_requests' => count($requests),
                'max_attempts' => $this->limits[$type]['max_attempts'],
                'remaining' => $this->getRemainingRequests($type, $identifier),
                'block_time_remaining' => $this->getBlockTimeRemaining($type, $identifier),
                'is_blocked' => count($requests) >= $this->limits[$type]['max_attempts']
            ];
        }
        
        return $stats;
    }
    
    /**
     * Varsayılan identifier get et
     */
    private function getDefaultIdentifier()
    {
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * İstekleri get et
     */
    private function getRequests($type, $identifier)
    {
        $key = "rate_limit_{$type}_{$identifier}";
        return $this->session->get($key, []);
    }
    
    /**
     * İstekleri kaydet
     */
    private function saveRequests($type, $identifier, $requests)
    {
        $key = "rate_limit_{$type}_{$identifier}";
        $this->session->set($key, $requests);
    }
    
    /**
     * Süresi dolmuş istekleri temizle
     */
    private function cleanupExpiredRequests($requests, $timeWindow)
    {
        $currentTime = time();
        $cutoffTime = $currentTime - $timeWindow;
        
        return array_filter($requests, function($timestamp) use ($cutoffTime) {
            return $timestamp > $cutoffTime;
        });
    }
    
    /**
     * IP adresi geçerli mi kontrol et
     */
    public function isValidIP($ip)
    {
        return filter_var($ip, FILTER_VALIDATE_IP) !== false;
    }
    
    /**
     * Proxy IP'leri için gerçek IP'yi get et
     */
    public function getRealIP()
    {
        $headers = [
            'HTTP_CF_CONNECTING_IP', // Cloudflare
            'HTTP_X_FORWARDED_FOR',  // Standard proxy
            'HTTP_X_REAL_IP',        // Nginx
            'HTTP_CLIENT_IP'         // Client IP
        ];
        
        foreach ($headers as $header) {
            if (isset($_SERVER[$header])) {
                $ip = $_SERVER[$header];
                
                // X-Forwarded-For için ilk IP'yi al
                if ($header === 'HTTP_X_FORWARDED_FOR') {
                    $ip = trim(explode(',', $ip)[0]);
                }
                
                if ($this->isValidIP($ip)) {
                    return $ip;
                }
            }
        }
        
        return $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }
    
    /**
     * Whitelist IP kontrolü
     */
    public function isWhitelisted($ip)
    {
        $whitelist = [
            '127.0.0.1',    // Localhost
            '::1',          // IPv6 localhost
            // Diğer güvenilir IP'ler buraya eklenebilir
        ];
        
        return in_array($ip, $whitelist);
    }
    
    /**
     * Blacklist IP kontrolü
     */
    public function isBlacklisted($ip)
    {
        $blacklist = $this->session->get('ip_blacklist', []);
        
        if (isset($blacklist[$ip])) {
            $blockUntil = $blacklist[$ip];
            
            if (time() < $blockUntil) {
                return true; // Hala blokta
            } else {
                // Blok süresi dolmuş, blacklist'ten çıkar
                unset($blacklist[$ip]);
                $this->session->set('ip_blacklist', $blacklist);
                return false;
            }
        }
        
        return false;
    }
    
    /**
     * IP'yi blacklist'e ekle
     */
    public function addToBlacklist($ip, $duration = 3600)
    {
        $blacklist = $this->session->get('ip_blacklist', []);
        $blacklist[$ip] = time() + $duration;
        $this->session->set('ip_blacklist', $blacklist);
    }
    
    /**
     * IP'yi blacklist'ten çıkar
     */
    public function removeFromBlacklist($ip)
    {
        $blacklist = $this->session->get('ip_blacklist', []);
        unset($blacklist[$ip]);
        $this->session->set('ip_blacklist', $blacklist);
    }
}
?>
