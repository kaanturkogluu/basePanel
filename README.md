# 🚗 Motor PHP Framework

Modern, güvenli ve performanslı PHP web uygulama framework'ü.

## ✨ Özellikler

### 🔐 **Güvenlik**
- **CSRF Protection**: Cross-Site Request Forgery koruması
- **Session Security**: Güvenli session yönetimi
- **Password Hashing**: Güvenli şifre hashleme (PASSWORD_DEFAULT)
- **Input Validation**: Giriş verilerinin doğrulanması
- **Rate Limiting**: API istek sınırlama
- **XSS Protection**: Cross-site scripting koruması

### 🏗️ **Mimari**
- **MVC Pattern**: Model-View-Controller tasarım deseni
- **Singleton Pattern**: Tek instance yönetimi
- **Autoloader**: Otomatik sınıf yükleme
- **Router System**: Gelişmiş yönlendirme sistemi
- **Middleware Support**: Ara katman desteği

### 📱 **Frontend**
- **Responsive Design**: Mobil uyumlu tasarım
- **Bootstrap Integration**: Bootstrap 5 entegrasyonu
- **Font Awesome**: Modern ikon kütüphanesi
- **Custom CSS**: Özelleştirilmiş stil dosyaları
- **JavaScript Framework**: Modern JS notification sistemi

### 🗄️ **Veritabanı**
- **Database Abstraction**: Veritabanı soyutlama katmanı
- **Model System**: Gelişmiş model sistemi
- **Query Builder**: SQL sorgu oluşturucu
- **Migration Support**: Veritabanı migration desteği

### 🔄 **Session & Cache**
- **Session Management**: Gelişmiş session yönetimi
- **Flash Messages**: Geçici mesaj sistemi
- **Timeout Control**: Otomatik session timeout
- **IP Validation**: IP adresi doğrulama
- **User Agent Check**: Tarayıcı bilgisi kontrolü

## 🚀 **Kurulum**

### Gereksinimler
- PHP 7.4+
- MySQL 5.7+ / MariaDB 10.2+
- Apache/Nginx web server
- Composer (önerilen)

### Kurulum Adımları
```bash
# Repository'yi klonlayın
git clone https://github.com/your-repo/motor.git

# Proje dizinine gidin
cd motor

# Gerekli dosya izinlerini ayarlayın
chmod 755 -R assets/
chmod 644 -R classes/
chmod 644 -R controllers/

# Veritabanını kurun
mysql -u root -p < models/Database/panel.sql
mysql -u root -p < models/Database/seeders.sql

# Web server'ı yapılandırın
# Apache: .htaccess dosyasını etkinleştirin
# Nginx: nginx.conf dosyasını yapılandırın
```

## 📁 **Dosya Yapısı**

```
motor/
├── assets/                 # Statik dosyalar
│   ├── css/               # Stil dosyaları
│   ├── js/                # JavaScript dosyaları
│   └── images/            # Görseller
├── classes/                # Core sınıflar
│   ├── Session.php        # Session yönetimi
│   ├── Router.php         # Yönlendirme sistemi
│   ├── CSRF.php           # CSRF koruması
│   ├── Request.php        # HTTP istek işleme
│   └── RateLimiter.php    # İstek sınırlama
├── controllers/            # Controller dosyaları
│   └── loginController.php # Giriş işlemleri
├── core/                   # Çekirdek dosyalar
│   ├── autoloader.php     # Otomatik yükleyici
│   ├── config.php         # Yapılandırma
│   ├── db.php             # Veritabanı bağlantısı
│   └── auth.php           # Kimlik doğrulama
├── models/                 # Model dosyaları
│   ├── BaseModel.php      # Temel model sınıfı
│   ├── Users.php          # Kullanıcı modeli
│   └── Database/          # Veritabanı dosyaları
├── pages/                  # Sayfa dosyaları
│   ├── giris.php          # Giriş sayfası
│   ├── anasayfa.php       # Ana sayfa
│   └── 404.php            # Hata sayfası
└── template/               # Şablon dosyaları
    ├── header.php          # Sayfa başlığı
    ├── footer.php          # Sayfa sonu
    ├── navbar.php          # Navigasyon
    └── sidebar.php         # Yan menü
```

## 🛠️ **Kullanım**

### Session Yönetimi
```php
$session = Session::getInstance();

// Veri saklama
$session->set('user_id', 123);
$session->set('username', 'admin');

// Veri alma
$userId = $session->get('user_id');

// Veri kontrolü
if ($session->has('user_id')) {
    // Kullanıcı giriş yapmış
}

// Session temizleme
$session->destroy();
```

### Flash Mesajlar
```php
// Flash mesaj oluşturma
$session->setFlash('success', 'İşlem başarılı!');
$session->setFlash('error', 'Bir hata oluştu!');

// Flash mesajları alma
$messages = $session->getFlash();
```

### Router Kullanımı
```php
$router = Router::getInstance();

// URL oluşturma
$url = Router::baseUrl() . 'pages/giris.php';

// Yönlendirme
$router->redirect($url);
```

### CSRF Koruması
```php
$csrf = CSRF::getInstance();

// Token oluşturma
$token = $csrf->getToken();

// Form'da kullanma
echo $csrf->getTokenInputField();

// Token doğrulama
if ($csrf->verifyToken($_POST['csrf_token'])) {
    // İşlem güvenli
}
```

## 🔧 **Yapılandırma**

### Veritabanı Ayarları
`core/config.php` dosyasında veritabanı bağlantı bilgilerini düzenleyin:

```php
define('DB_HOST', 'localhost');
define('DB_NAME', 'motor_db');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');
```

### Session Ayarları
`classes/Session.php` dosyasında session timeout sürelerini ayarlayın:

```php
private $sessionTimeout = 300; // 5 dakika
private $flashTimeout = 300;   // Flash mesaj ömrü
```

## 🚨 **Güvenlik Özellikleri**

### Session Güvenliği
- Otomatik session timeout
- IP adresi doğrulama
- User-Agent kontrolü
- Session ID yenileme
- Güvenli cookie ayarları

### CSRF Koruması
- Her form için benzersiz token
- Token süre kontrolü
- Otomatik token yenileme

### Giriş Güvenliği
- Şifre hashleme
- Brute force koruması
- Rate limiting
- Session hijacking koruması

## 📱 **Responsive Tasarım**

### Bootstrap 5 Entegrasyonu
- Modern grid sistemi
- Mobil öncelikli tasarım
- Responsive bileşenler
- Custom CSS override'ları

### JavaScript Framework
- Modern notification sistemi
- AJAX form handling
- Dynamic content loading
- Touch gesture support

## 🔄 **API Desteği**

### RESTful Endpoints
- JSON response format
- HTTP status codes
- Error handling
- Rate limiting

### AJAX Support
- Fetch API entegrasyonu
- CSRF token validation
- Response handling
- Error management

## 📊 **Performans**

### Optimizasyonlar
- Autoloader ile lazy loading
- Session cleanup
- Database connection pooling
- Cache management

### Monitoring
- Session timeout tracking
- Request logging
- Error logging
- Performance metrics

## 🧪 **Test**

### Test Dosyaları
- Unit test examples
- Integration test setup
- Performance testing
- Security testing

## 📚 **Dokümantasyon**

### API Reference
- Class documentation
- Method descriptions
- Parameter types
- Return values

### Examples
- Basic usage examples
- Advanced scenarios
- Best practices
- Common patterns

## 🤝 **Katkıda Bulunma**

1. Fork yapın
2. Feature branch oluşturun (`git checkout -b feature/amazing-feature`)
3. Commit yapın (`git commit -m 'Add amazing feature'`)
4. Push yapın (`git push origin feature/amazing-feature`)
5. Pull Request oluşturun

## 📄 **Lisans**

Bu proje MIT lisansı altında lisanslanmıştır. Detaylar için `LICENSE` dosyasına bakın.

## 📞 **İletişim**

- **Proje Linki**: [https://github.com/kaanturkogluu/motor](https://github.com/kaanturkogluu/motor)
 

## 🙏 **Teşekkürler**
 
---

**Motor PHP Framework** - Modern web uygulamaları için güçlü, güvenli ve esnek çözüm. 