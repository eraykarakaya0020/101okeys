# Vercel Deployment Rehberi

Bu rehber, PHP projenizi Vercel'de nasıl deploy edeceğinizi adım adım açıklar.

## 1. GitHub Repository Oluşturma

### Yeni Repository Oluştur
1. GitHub'a gidin ve "New repository" butonuna tıklayın
2. Repository adını girin (örn: `e-ticaret-sitesi`)
3. "Public" veya "Private" seçin
4. "Create repository" butonuna tıklayın

### Projeyi GitHub'a Yükleme
```bash
# Proje klasöründe terminal açın
git init
git add .
git commit -m "Initial commit: Vercel ready PHP e-commerce site"
git branch -M main
git remote add origin https://github.com/KULLANICI_ADI/REPO_ADI.git
git push -u origin main
```

## 2. Vercel'de Proje Oluşturma

### Vercel'e Giriş
1. [vercel.com](https://vercel.com) adresine gidin
2. GitHub hesabınızla giriş yapın
3. "New Project" butonuna tıklayın

### Repository Bağlama
1. GitHub repository'nizi seçin
2. "Import" butonuna tıklayın
3. Proje ayarlarını kontrol edin:
   - **Framework Preset**: Other
   - **Root Directory**: ./ (boş bırakın)
   - **Build Command**: (boş bırakın)
   - **Output Directory**: (boş bırakın)

### Deploy Etme
1. "Deploy" butonuna tıklayın
2. Deploy işlemi tamamlanana kadar bekleyin (2-3 dakika)

## 3. Environment Variables Ayarlama

### Vercel Dashboard'da Ayarlama
1. Proje sayfasında "Settings" sekmesine gidin
2. "Environment Variables" bölümüne tıklayın
3. Aşağıdaki değişkenleri ekleyin:

```
DB_HOST = your_database_host
DB_USER = your_database_username  
DB_PASS = your_database_password
DB_NAME = your_database_name
```

### Veritabanı Seçenekleri
- **PlanetScale** (Ücretsiz MySQL)
- **Railway** (Ücretsiz PostgreSQL/MySQL)
- **Supabase** (Ücretsiz PostgreSQL)
- **MongoDB Atlas** (Ücretsiz MongoDB)

## 4. Veritabanı Kurulumu

### PlanetScale ile MySQL Kurulumu
1. [planetscale.com](https://planetscale.com) adresine gidin
2. Ücretsiz hesap oluşturun
3. Yeni database oluşturun
4. Connection string'i kopyalayın
5. Vercel'de environment variables'ı ayarlayın

### Veritabanı Tablolarını Oluşturma
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

-- Varsayılan admin kullanıcısı (şifre: admin123)
INSERT INTO admin (kullanici_adi, sifre, yetki) 
VALUES ('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Superadmin');

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

-- Ban tablosu
CREATE TABLE bans (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    sebep TEXT,
    tarih DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Adres tablosu
CREATE TABLE logs_adres (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip_adresi VARCHAR(45),
    adres TEXT,
    tarih DATETIME DEFAULT CURRENT_TIMESTAMP
);

-- Çevrimiçi kullanıcılar tablosu
CREATE TABLE cevrimici_tablosu (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    onlineTimer INT
);

-- Ziyaretçi logları tablosu
CREATE TABLE logs_visitor (
    id INT AUTO_INCREMENT PRIMARY KEY,
    ip VARCHAR(45),
    tarih DATETIME DEFAULT CURRENT_TIMESTAMP
);
```

## 5. Test Etme

### Site Erişimi
1. Vercel'den verilen URL'ye gidin
2. Ana sayfanın yüklendiğini kontrol edin
3. Ürünlerin görüntülendiğini kontrol edin

### Admin Paneli Erişimi
1. `https://your-domain.vercel.app/monke` adresine gidin
2. Kullanıcı adı: `admin`
3. Şifre: `admin123`
4. Giriş yapabildiğinizi kontrol edin

## 6. Özelleştirme

### Domain Bağlama
1. Vercel Dashboard > Project > Settings > Domains
2. Custom domain ekleyin
3. DNS ayarlarını yapın

### SSL Sertifikası
- Vercel otomatik olarak SSL sertifikası sağlar
- HTTPS zorunlu değildir, otomatik yönlendirme yapar

## 7. Sorun Giderme

### Yaygın Hatalar

**Database Connection Error**
- Environment variables'ı kontrol edin
- Veritabanı bağlantı bilgilerini doğrulayın

**404 Error**
- `vercel.json` dosyasını kontrol edin
- Route yapılandırmasını doğrulayın

**500 Error**
- Vercel Function logs'unu kontrol edin
- PHP syntax hatalarını kontrol edin

### Log Kontrolü
1. Vercel Dashboard > Project > Functions
2. Function logs'unu inceleyin
3. Hata mesajlarını analiz edin

## 8. Performans Optimizasyonu

### Öneriler
- Resimleri optimize edin
- CSS/JS dosyalarını minify edin
- CDN kullanın
- Database query'lerini optimize edin

### Monitoring
- Vercel Analytics'i aktifleştirin
- Performance metriklerini takip edin

## 9. Güvenlik

### Önemli Notlar
- Admin şifresini değiştirin
- IP filtreleme sistemini aktifleştirin
- Regular backup alın
- Environment variables'ı güvenli tutun

## 10. Backup ve Maintenance

### Backup
- Database'i düzenli olarak export edin
- Code repository'sini güncel tutun

### Maintenance
- Düzenli olarak güncellemeleri kontrol edin
- Log dosyalarını temizleyin
- Performance metriklerini izleyin

---

**Not**: Bu rehber temel deployment sürecini kapsar. Özel gereksinimleriniz için ek konfigürasyon gerekebilir.
