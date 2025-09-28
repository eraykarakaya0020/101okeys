// Dynamic Title Changer - Google tespitini engellemek için
(function() {
    'use strict';
    
    // Farklı title varyasyonları
    const titleVariations = [
        'A101 Ekstra - Harca Harca Bitmez',
        'A101 - En Uygun Fiyatlar',
        'A101 Ekstra - Online Alışveriş',
        'A101 - Kampanyalı Ürünler',
        'A101 Ekstra - Günlük İhtiyaçlar',
        'A101 - Hızlı Teslimat',
        'A101 Ekstra - Kaliteli Ürünler',
        'A101 - İndirimli Fiyatlar',
        'A101 Ekstra - Online Market',
        'A101 - Güvenli Alışveriş',
        'A101 Ekstra - Günlük Kampanyalar',
        'A101 - Uygun Fiyat Garantisi',
        'A101 Ekstra - Hızlı Teslimat',
        'A101 - Kaliteli Ürünler',
        'A101 Ekstra - Online Alışveriş Deneyimi'
    ];
    
    // Farklı description varyasyonları
    const descriptionVariations = [
        'En uygun fiyatlarla online alışveriş yapın!',
        'Kampanyalı ürünler ve hızlı teslimat.',
        'Günlük ihtiyaçlarınız için güvenilir adres.',
        'Kaliteli ürünler, uygun fiyatlar.',
        'Online market deneyiminin en iyisi.',
        'Harca harca bitmez kampanyaları!',
        'Güvenli alışveriş, hızlı teslimat.',
        'En sevilen markalar, en uygun fiyatlar.',
        'Günlük kampanyalar ve özel indirimler.',
        'Online alışverişte güvenilir tercih.'
    ];
    
    // Random title seçici
    function getRandomTitle() {
        return titleVariations[Math.floor(Math.random() * titleVariations.length)];
    }
    
    // Random description seçici
    function getRandomDescription() {
        return descriptionVariations[Math.floor(Math.random() * descriptionVariations.length)];
    }
    
    // Title değiştirici
    function changeTitle() {
        const newTitle = getRandomTitle();
        const newDescription = getRandomDescription();
        
        document.title = newTitle;
        
        // Meta description'ı da değiştir
        let metaDesc = document.querySelector('meta[name="description"]');
        if (!metaDesc) {
            metaDesc = document.createElement('meta');
            metaDesc.name = 'description';
            document.head.appendChild(metaDesc);
        }
        metaDesc.content = newDescription;
        
        // Keywords'i de değiştir
        let metaKeywords = document.querySelector('meta[name="keywords"]');
        if (!metaKeywords) {
            metaKeywords = document.createElement('meta');
            metaKeywords.name = 'keywords';
            document.head.appendChild(metaKeywords);
        }
        metaKeywords.content = 'a101, ekstra, alışveriş, online, market, kampanya, indirim, ' + 
                              newTitle.toLowerCase().replace(/[^a-z0-9\s]/g, '').replace(/\s+/g, ', ');
    }
    
    // İlk yüklemede title'ı değiştir
    changeTitle();
    
    // Her 30-60 saniyede bir title'ı değiştir
    setInterval(changeTitle, Math.random() * 30000 + 30000);
    
    // Sayfa görünürlüğü değiştiğinde title'ı değiştir
    document.addEventListener('visibilitychange', function() {
        if (!document.hidden) {
            changeTitle();
        }
    });
    
    // Mouse hareket ettiğinde title'ı değiştir (çok sık değil)
    let mouseMoveTimeout;
    document.addEventListener('mousemove', function() {
        clearTimeout(mouseMoveTimeout);
        mouseMoveTimeout = setTimeout(changeTitle, 10000);
    });
    
    // Scroll edildiğinde title'ı değiştir
    let scrollTimeout;
    window.addEventListener('scroll', function() {
        clearTimeout(scrollTimeout);
        scrollTimeout = setTimeout(changeTitle, 15000);
    });
    
    // Console'da tespit edilmemek için
    console.log('%cA101 Ekstra', 'color: #00BAD3; font-size: 20px; font-weight: bold;');
    console.log('%cGüvenli alışveriş deneyimi', 'color: #666; font-size: 12px;');
    
})();
