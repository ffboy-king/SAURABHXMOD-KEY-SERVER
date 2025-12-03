<?php
// ==========================================
// कॉन्फ़िगरेशन (यहाँ अपनी लिंक डालें)
// ==========================================

// नीचे "PASTE_YOUR_EARNLINK_HERE" हटाकर अपनी Earnlink वाली छोटी लिंक डालें
$shortlink_url = "PASTE_YOUR_EARNLINK_HERE"; 

// जहाँ कीज़ सेव होंगी
$key_file = "keys.txt"; 

// ==========================================
// लॉजिक (Logic)
// ==========================================

$generated_key = "";
$show_key = false;

// चेक करें कि क्या यूज़र शॉर्टलिंक पूरा करके वापस आया है?
if (isset($_GET['completed']) && $_GET['completed'] == 'yes') {
    $show_key = true;

    // रैंडम की जेनरेट करने का फंक्शन
    function generateRandomString($length = 4) {
        return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
    }
    
    // की का फॉर्मेट (जैसे: KEY-XXXX-XXXX-XXXX)
    $generated_key = "KEY-" . generateRandomString(4) . "-" . generateRandomString(4) . "-" . generateRandomString(4);
    
    // की को फाइल में सेव करें (रिकॉर्ड के लिए)
    $entry = $generated_key . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | Time: " . date("Y-m-d H:i:s") . "\n";
    file_put_contents($key_file, $entry, FILE_APPEND);
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Secure Key Generator</title>
    <style>
        /* यह CSS उस साइट जैसा डार्क थीम देगी */
        body {
            background-color: #0a0a0a; /* गहरा काला बैकग्राउंड */
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #111111; /* कार्ड का बैकग्राउंड */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2); /* हल्का हरा ग्लो */
            text-align: center;
            max-width: 400px;
            width: 90%;
            border: 1px solid #333;
        }
        h1 {
            margin-bottom: 25px;
            font-weight: 300;
            color: #fff;
            letter-spacing: 1px;
        }
        .accent { color: #00ff88; } /* हरा रंग हाइलाइट के लिए */
        
        /* की दिखाने वाला बॉक्स */
        .key-display-box {
            background: #000;
            border: 2px dashed #00ff88;
            color: #00ff88;
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: bold;
            padding: 20px;
            margin: 30px 0;
            border-radius: 8px;
            word-break: break-all;
            letter-spacing: 2px;
            position: relative;
        }

        /* बटन का डिज़ाइन */
        .btn-generate {
            display: inline-block;
            background: linear-gradient(45deg, #00ff88, #00b862);
            color: #000;
            padding: 15px 30px;
            border: none;
            border-radius: 30px;
            font-size: 18px;
            font-weight: bold;
            text-decoration: none;
            cursor: pointer;
            transition: transform 0.2s, box-shadow 0.2s;
            box-shadow: 0 5px 15px rgba(0, 255, 136, 0.4);
            width: 80%;
        }
        .btn-generate:hover {
            transform: translateY(-3px);
            box-shadow: 0 8px 20px rgba(0, 255, 136, 0.6);
        }
        .info-text {
            margin-top: 20px;
            font-size: 14px;
            color: #888;
        }
        .status-icon { font-size: 50px; margin-bottom: 20px; }
    </style>
</head>
<body>

<div class="container">
    <?php if ($show_key): ?>
        <div class="status-icon">🎉</div>
        <h1>Key <span class="accent">Generated!</span></h1>
        <p>Your access key is ready.</p>
        
        <div class="key-display-box" id="keyBox">
            <?php echo $generated_key; ?>
        </div>
        <p class="info-text">Copy this key and use it in the application.</p>
        <a href="god.php" style="color: #00ff88; text-decoration: none; margin-top: 20px; display: block;">Generate Another</a>

    <?php else: ?>
        <div class="status-icon">🛡️</div>
        <h1>Generate <span class="accent">Access Key</span></h1>
        <p>To prevent spam, please complete a quick verification step to get your key.</p>
        
        <a href="<?php echo $shortlink_url; ?>" class="btn-generate" target="_blank">
            VERIFY & GENERATE KEY
        </a>
        <p class="info-text">You will be redirected to complete verification.</p>
    <?php endif; ?>
</div>

</body>
</html>
