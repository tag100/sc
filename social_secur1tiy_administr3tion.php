<?php
// Visitor counter
$counter_file = 'visitor_count.txt';
$visitor_count = file_exists($counter_file) ? (int)file_get_contents($counter_file) + 1 : 1;
file_put_contents($counter_file, $visitor_count);

// Get visitor info
$ip = $_SERVER['REMOTE_ADDR'];
$ua = $_SERVER['HTTP_USER_AGENT'];
$time = date('Y-m-d H:i:s');
$isp = gethostbyaddr($ip); // Get ISP/domain info

// Detect operating system and device type
$isMobile = preg_match('/mobile|android|iphone|ipad/i', $ua);
$os = 'Unknown OS';

if (stripos($ua, 'Win') !== false) {
    $os = 'Windows';
} elseif (stripos($ua, 'Mac') !== false) {
    $os = 'Mac';
} elseif (stripos($ua, 'Linux') !== false) {
    $os = 'Linux';
} else {
    $os = 'Unknown OS';
}

// Fetch geolocation data
$geoData = @json_decode(file_get_contents("https://ipapi.co/$ip/json/"), true);
$location = "{$geoData['city'] ?? 'Unknown'}, {$geoData['region'] ?? 'Unknown'}, {$geoData['country_name'] ?? 'Unknown'}";

// Determine platform
$platform = $os;
$deviceType = $isMobile ? '(Mobile)' : '(Desktop)';

// Format the Telegram message
$message = "```\n"
          ."🚀 New SSA WEBSITE VISIT DETECTED!\n"
          ."══════════════════════════════\n"
          ."👤 Visitor No: $visitor_count\n"
          ."🌐 IP Address: $ip\n"
          ."📍 Location: $location\n"
          ."📡 ISP: ".($isp ?: 'Unknown')."\n"
          ."💻 Device: $ua\n"
          ."🖥️ Platform: $platform $deviceType\n"
          ."🕒 Time: $time\n"
          ."══════════════════════════════\n"
          ."🔔 VISITOR TRACKING ACTIVE!\n"
          ."```";

// Send to Telegram
$botToken = '7284066719:AAEncc3VY27DzgRzCLnuNTLpyIJf5MrrCos';
$chatId = '7724482403';
$url = "https://api.telegram.org/bot$botToken/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'MarkdownV2',
    'disable_web_page_preview' => true
];

// Use cURL if available (more reliable)
if (function_exists('curl_init')) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    $response = curl_exec($ch);
    curl_close($ch);
} else {
    $response = file_get_contents($url.'?'.http_build_query($data));
}

// Log the Telegram response (optional, for debugging purposes)
file_put_contents('telegram_log.txt', $response . "\n", FILE_APPEND);
