# E-Ticaret Sitesi ve Admin Paneli

Bu proje Railway'de çalışacak şekilde optimize edilmiş bir PHP e-ticaret sitesi ve admin panelidir.

## Özellikler

- 🛒 E-ticaret sitesi (A101 tarzı)
- 👨‍💼 Admin paneli
- 💳 Ödeme sistemi
- 📊 İstatistikler ve loglar
- 🔐 Güvenli giriş sistemi
- 📱 Responsive tasarım

## Railway'de Deployment

### 1. GitHub'a Yükleme

```bash
git init
git add .
git commit -m "Initial commit"
git branch -M main
git remote add origin https://github.com/kullaniciadi/repo-adi.git
git push -u origin main
```

### 2. Railway'de Proje Oluşturma

1. [Railway Dashboard](https://railway.app/dashboard)'a gidin
2. "New Project" butonuna tıklayın
3. "Deploy from GitHub repo" seçin
4. GitHub repository'nizi seçin
5. "Deploy Now" butonuna tıklayın

### 3. Environment Variables Ayarlama

Railway Dashboard > Project > Variables bölümünde şu değişkenleri ekleyin:

```
DB_HOST=your_database_host
DB_USER=your_database_username
DB_PASS=your_database_password
DB_NAME=your_database_name
```

### 4. MySQL Veritabanı Ekleme

1. Railway Dashboard'da "New" > "Database" > "Add MySQL" seçin
2. Veritabanı oluşturulduktan sonra connection bilgilerini kopyalayın
3. Bu bilgileri Environment Variables olarak ekleyin

### 5. Veritabanı Kurulumu

Projenin çalışması için aşağıdaki tabloları oluşturmanız gerekiyor:

```sql
-- Admin tablosu
CREATE TABLE admin (
    id INT AUTO_INCREMENT PRIMARY KEY,
    kullanici_adi VARCHAR(255) NOT NULL,
    sifre VARCHAR(255) NOT NULL,
    yetki VARCHAR(50) DEFAULT 'Admin',
    ip_adresi VARCHAR(45),
    son_gorulme DATETIME
);

-- Ürünler tablosu
CREATE TABLE urunler (
    id INT AUTO_INCREMENT PRIMARY KEY,
    urun_adi VARCHAR(255) NOT NULL,
    urun_fiyati DECIMAL(10,2) NOT NULL,
    urun_resmi TEXT,
    urun_markasi VARCHAR(100),
    urun_kategorisi INT
);

-- Loglar tablosu
CREATE TABLE logs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    durum VARCHAR(255),
    tarih DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Sepet tablosu
CREATE TABLE sepet (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_adresi VARCHAR(45),
    urunler TEXT
);

-- Admin ayarları tablosu
CREATE TABLE admin_settings (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_filter TINYINT(1) DEFAULT 0,
    banner_durum TINYINT(1) DEFAULT 0,
    banner_url TEXT,
    banner_yonlendirme INT
);

-- Diğer tablolar...
```

## Yerel Geliştirme

### Gereksinimler

- PHP 8.2+
- MySQL/MariaDB
- Web sunucusu (Apache/Nginx)

### Kurulum

1. Projeyi klonlayın:
```bash
git clone https://github.com/kullaniciadi/repo-adi.git
cd repo-adi
```

2. `config.php` dosyasında veritabanı bilgilerini güncelleyin

3. Veritabanını oluşturun ve tabloları import edin

4. Web sunucusunu başlatın

## Dosya Yapısı

```
├── index.php              # Ana sayfa
├── config.php             # Veritabanı konfigürasyonu
├── vercel.json           # Vercel konfigürasyonu
├── monke/                # Admin paneli
│   ├── index.php         # Admin giriş
│   ├── main.php          # Admin ana sayfa
│   ├── function/         # PHP fonksiyonları
│   ├── Data/            # Veri işleme dosyaları
│   └── assets/          # CSS, JS, resimler
├── payment/             # Ödeme sayfaları
├── assets/              # Site varlıkları
└── README.md           # Bu dosya
```

## Güvenlik

- Tüm şifreler hash'lenmiştir
- SQL injection koruması
- XSS koruması
- CSRF koruması
- IP filtreleme sistemi

## Destek

Herhangi bir sorun yaşarsanız GitHub Issues bölümünden bildirebilirsiniz.

## Lisans

Bu proje özel kullanım içindir.
