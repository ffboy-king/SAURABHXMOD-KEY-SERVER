<?php
// ==========================================
// CONFIGURATION (SETTINGS)
// ==========================================

// 1. Secret Key (Yeh WAHI honi chahiye jo C++ Code me hai: "Vm8Lk...")
$secret_key = "Vm8Lk7Uj2JmsjCPVPVjrLa7zgfx3uz9E"; 

// 2. Shortlink URL
$shortlink_url = "https://earnlinks.in/mL7p"; 

// 3. Database File (Jahan keys save hongi)
$key_file = "keys.txt"; 

// ==========================================
// PART 1: APP LOGIN API (C++ Connection)
// ==========================================

// Agar App se Request aayi hai (POST request with user_key)
if (isset($_POST['user_key']) && isset($_POST['serial'])) {
    
    header('Content-Type: application/json');
    
    $user_key = $_POST['user_key'];
    $serial = $_POST['serial']; // UUID from App
    $game = $_POST['game'] ?? 'Unknown';

    // Step A: Check karein ki Key database (keys.txt) me exist karti hai ya nahi
    $file_content = file_exists($key_file) ? file_get_contents($key_file) : "";
    
    // Ham check kar rahe hain ki kya user_key file ke andar likhi hui hai?
    if (strpos($file_content, $user_key) !== false) {
        
        // Step B: Agar Key Valid hai, to Token Generate karo (MD5 Logic)
        // Formula: PUBG-KEY-UUID-SECRET (Same as C++)
        $auth_string = "PUBG-" . $user_key . "-" . $serial . "-" . $secret_key;
        $token = md5($auth_string);
        
        $response = [
            "status" => true,
            "data" => [
                "token" => $token,           // Login Token
                "rng" => time(),             // Time
                "EXP" => "Access Granted"    // Expiry Text
            ]
        ];
    } else {
        // Step C: Agar Key Valid nahi hai
        $response = [
            "status" => false,
            "reason" => "Key Invalid or Not Found in Database!"
        ];
    }
    
    echo json_encode($response);
    exit(); // Yahin ruk jao, niche ka HTML mat dikhao
}

// ==========================================
// PART 2: WEBSITE UI (Browser View)
// ==========================================

$generated_key = "";
$show_key = false;

// Check karein ki user Shortlink complete karke aaya hai ya nahi
if (isset($_GET['completed']) && $_GET['completed'] == 'yes') {
    $show_key = true;

    function generateRandomString($length = 4) {
        return strtoupper(substr(bin2hex(random_bytes($length)), 0, $length));
    }
    
    // Key Generate karo
    $generated_key = "SAURABHXMOD-" . generateRandomString(4) . "-" . generateRandomString(4) . "-" . generateRandomString(4);
    
    // Key ko File me Save karo (Taaki App login kar paye)
    $entry = $generated_key . " | IP: " . $_SERVER['REMOTE_ADDR'] . " | Date: " . date("Y-m-d H:i:s") . "\n";
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
        body {
            background-color: #0a0a0a;
            color: #e0e0e0;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }
        .container {
            background: #111111;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 0 20px rgba(0, 255, 136, 0.2);
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
        .accent { color: #00ff88; }
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
        <a href="index.php" style="color: #00ff88; text-decoration: none; margin-top: 20px; display: block;">Generate Another</a>

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
