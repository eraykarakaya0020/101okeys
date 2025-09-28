# A101 E-Commerce Platform

Bu proje, A101 e-ticaret platformu için geliştirilmiş bir PHP uygulamasıdır.

## Özellikler

- **Modern E-ticaret Arayüzü**: A101'in orijinal tasarımına uygun modern arayüz
- **Güvenli Ödeme Sistemi**: Çoklu banka desteği ile güvenli ödeme işlemleri
- **Admin Paneli**: Kapsamlı yönetim paneli (TXMD)
- **SEO Optimizasyonu**: Dinamik title ve meta tag desteği
- **Proxy Sistemi**: Dış kaynaklar için güvenli proxy yapısı
- **Railway Uyumlu**: Railway platformunda çalışacak şekilde yapılandırılmış

## Teknik Detaylar

### Veritabanı Konfigürasyonu
- **Host**: yamanote.proxy.rlwy.net
- **Port**: 44635
- **Database**: adminer_a101
- **Username**: root

### Klasör Yapısı
- `txmd/` - Admin paneli (eski monke klasörü)
- `payment/` - Ödeme sayfaları
- `assets/` - CSS, JS ve görsel dosyalar
- `proxy.php` - Dış kaynaklar için proxy

### Önemli Dosyalar
- `config.php` - Veritabanı ve genel konfigürasyon
- `index.php` - Ana sayfa
- `sepet.php` - Sepet sayfası
- `odeme.php` - Ödeme sayfası
- `txmd/index.php` - Admin paneli girişi

## Kurulum

1. Projeyi Railway'e deploy edin
2. Veritabanı bağlantı bilgilerini kontrol edin
3. `txmd/` klasörüne admin paneli üzerinden erişin

## Güvenlik

- Proxy sistemi ile dış kaynaklar güvenli şekilde yüklenir
- IP filtreleme ve VPN kontrolü
- Güvenli veritabanı bağlantıları

## Lisans

Bu proje özel kullanım içindir.

## Geliştirici

TXMD Development Team
