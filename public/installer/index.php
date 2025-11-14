<?php
/**
 * Installation Wizard
 * WordPress-style installation process
 */

define('ROOT_PATH', dirname(dirname(__DIR__)));
define('STORAGE_PATH', ROOT_PATH . '/storage');

// Check if already installed
$installLock = STORAGE_PATH . '/.install.lock';
if (file_exists($installLock)) {
    header('Location: /');
    exit;
}

// Get current step
$step = $_GET['step'] ?? 1;
$step = (int)$step;

// Handle form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    handleStep($step);
    exit;
}

function handleStep($step) {
    switch ($step) {
        case 1:
            checkRequirements();
            break;
        case 2:
            setupDatabase();
            break;
        case 3:
            setupOrganization();
            break;
        case 4:
            setupAdmin();
            break;
        case 5:
            setupSettings();
            break;
        case 6:
            finishInstallation();
            break;
    }
}

function checkRequirements() {
    $requirements = [
        'php_version' => version_compare(phpversion(), '7.4', '>='),
        'mysqli' => extension_loaded('mysqli'),
        'json' => extension_loaded('json'),
        'fileinfo' => extension_loaded('fileinfo'),
    ];
    
    header('Content-Type: application/json');
    echo json_encode([
        'success' => array_reduce($requirements, fn($c, $item) => $c && $item, true),
        'requirements' => $requirements
    ]);
}

function setupDatabase() {
    $host = $_POST['db_host'] ?? 'localhost';
    $user = $_POST['db_user'] ?? '';
    $pass = $_POST['db_password'] ?? '';
    $name = $_POST['db_name'] ?? '';
    
    try {
        $conn = new mysqli($host, $user, $pass);
        if ($conn->connect_error) {
            throw new Exception($conn->connect_error);
        }
        
        // Create database
        $conn->query("CREATE DATABASE IF NOT EXISTS `$name`");
        $conn->select_db($name);
        
        // Test connection
        $result = $conn->query("SELECT 1");
        
        // Save configuration
        $env = "DB_HOST=$host\nDB_USER=$user\nDB_PASS=$pass\nDB_NAME=$name\n";
        file_put_contents(ROOT_PATH . '/.env', $env);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'message' => 'Database configured']);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

function setupOrganization() {
    $org = [
        'name' => $_POST['org_name'] ?? '',
        'email' => $_POST['org_email'] ?? '',
        'phone' => $_POST['org_phone'] ?? '',
        'address' => $_POST['org_address'] ?? '',
    ];
    
    $_SESSION['organization'] = $org;
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
}

function setupAdmin() {
    $admin = [
        'username' => $_POST['admin_username'] ?? '',
        'email' => $_POST['admin_email'] ?? '',
        'password' => password_hash($_POST['admin_password'] ?? '', PASSWORD_BCRYPT),
    ];
    
    $_SESSION['admin'] = $admin;
    
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
}

function setupSettings() {
    $_SESSION['settings'] = $_POST;
    header('Content-Type: application/json');
    echo json_encode(['success' => true]);
}

function finishInstallation() {
    try {
        // Create install lock
        touch(STORAGE_PATH . '/.install.lock');
        
        // Create .env file with all settings
        $env = file_get_contents(ROOT_PATH . '/.env') ?: '';
        $env .= "APP_KEY=base64:" . base64_encode(random_bytes(32)) . "\n";
        file_put_contents(ROOT_PATH . '/.env', $env);
        
        header('Content-Type: application/json');
        echo json_encode(['success' => true, 'redirect' => '/']);
    } catch (Exception $e) {
        header('Content-Type: application/json');
        http_response_code(400);
        echo json_encode(['success' => false, 'error' => $e->getMessage()]);
    }
}

// Render installer page
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Athletics Federation - Installation</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 10px;
            box-shadow: 0 20px 60px rgba(0,0,0,0.3);
            max-width: 600px;
            width: 100%;
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 40px 30px;
            text-align: center;
        }
        .header h1 { font-size: 28px; margin-bottom: 10px; }
        .progress-bar {
            width: 100%;
            height: 4px;
            background: rgba(255,255,255,0.3);
            margin-top: 20px;
        }
        .progress { height: 100%; background: white; width: 16.66%; }
        .content { padding: 40px; }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: 500;
            color: #333;
        }
        input, select, textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102,126,234,0.1);
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
        }
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            cursor: pointer;
            font-weight: 500;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
        }
        .btn-primary:hover {
            background: #5568d3;
        }
        .btn-secondary {
            background: #e0e0e0;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d0d0d0;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        .alert-success {
            background: #d4edda;
            color: #155724;
            border: 1px solid #c3e6cb;
        }
        .alert-error {
            background: #f8d7da;
            color: #721c24;
            border: 1px solid #f5c6cb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Athletics Federation Management System</h1>
            <p>Installation Wizard - Step <?php echo $step; ?> of 6</p>
            <div class="progress-bar">
                <div class="progress" style="width: <?php echo ($step * 16.66); ?>%"></div>
            </div>
        </div>
        
        <div class="content">
            <?php renderStep($step); ?>
        </div>
    </div>

    <script>
        function nextStep() {
            const form = document.querySelector('form');
            if (form && !form.checkValidity()) {
                alert('Please fill in all required fields');
                return;
            }
            
            const currentStep = <?php echo $step; ?>;
            window.location.href = '?step=' + (currentStep + 1);
        }
        
        function prevStep() {
            const currentStep = <?php echo $step; ?>;
            if (currentStep > 1) {
                window.location.href = '?step=' + (currentStep - 1);
            }
        }
    </script>
</body>
</html>

<?php
function renderStep($step) {
    switch ($step) {
        case 1:
            echo '<h2>Step 1: System Requirements</h2>';
            echo '<p>Checking system requirements...</p>';
            echo '<div id="requirements"></div>';
            echo '<script>
                fetch("?step=1", {method: "POST"})
                .then(r => r.json())
                .then(d => {
                    let html = "<ul>";
                    for (let key in d.requirements) {
                        html += "<li>" + (d.requirements[key] ? "✓" : "✗") + " " + key + "</li>";
                    }
                    html += "</ul>";
                    document.getElementById("requirements").innerHTML = html;
                });
            </script>';
            break;
            
        case 2:
            echo '<h2>Step 2: Database Configuration</h2>';
            echo '<form method="POST">';
            echo '<div class="form-group">';
            echo '<label>Database Host:</label>';
            echo '<input type="text" name="db_host" value="localhost" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Database User:</label>';
            echo '<input type="text" name="db_user" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Database Password:</label>';
            echo '<input type="password" name="db_password">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Database Name:</label>';
            echo '<input type="text" name="db_name" placeholder="athletics_federation" required>';
            echo '</div>';
            echo '<div class="button-group">';
            echo '<button type="button" class="btn-secondary" onclick="prevStep()">Back</button>';
            echo '<button type="button" class="btn-primary" onclick="nextStep()">Next</button>';
            echo '</div>';
            echo '</form>';
            break;
            
        case 3:
            echo '<h2>Step 3: Organization Details</h2>';
            echo '<form method="POST">';
            echo '<div class="form-group">';
            echo '<label>Organization Name:</label>';
            echo '<input type="text" name="org_name" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Email:</label>';
            echo '<input type="email" name="org_email">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Phone:</label>';
            echo '<input type="tel" name="org_phone">';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Address:</label>';
            echo '<textarea name="org_address" rows="3"></textarea>';
            echo '</div>';
            echo '<div class="button-group">';
            echo '<button type="button" class="btn-secondary" onclick="prevStep()">Back</button>';
            echo '<button type="button" class="btn-primary" onclick="nextStep()">Next</button>';
            echo '</div>';
            echo '</form>';
            break;
            
        case 4:
            echo '<h2>Step 4: Admin User</h2>';
            echo '<form method="POST">';
            echo '<div class="form-group">';
            echo '<label>Username:</label>';
            echo '<input type="text" name="admin_username" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Email:</label>';
            echo '<input type="email" name="admin_email" required>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Password:</label>';
            echo '<input type="password" name="admin_password" required>';
            echo '</div>';
            echo '<div class="button-group">';
            echo '<button type="button" class="btn-secondary" onclick="prevStep()">Back</button>';
            echo '<button type="button" class="btn-primary" onclick="nextStep()">Next</button>';
            echo '</div>';
            echo '</form>';
            break;
            
        case 5:
            echo '<h2>Step 5: System Settings</h2>';
            echo '<form method="POST">';
            echo '<div class="form-group">';
            echo '<label>Timezone:</label>';
            echo '<select name="timezone">';
            echo '<option value="UTC">UTC</option>';
            echo '<option value="Africa/Nairobi" selected>Africa/Nairobi</option>';
            echo '<option value="Africa/Johannesburg">Africa/Johannesburg</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="form-group">';
            echo '<label>Language:</label>';
            echo '<select name="language">';
            echo '<option value="en">English</option>';
            echo '<option value="sw">Swahili</option>';
            echo '</select>';
            echo '</div>';
            echo '<div class="button-group">';
            echo '<button type="button" class="btn-secondary" onclick="prevStep()">Back</button>';
            echo '<button type="button" class="btn-primary" onclick="nextStep()">Next</button>';
            echo '</div>';
            echo '</form>';
            break;
            
        case 6:
            echo '<h2>Installation Complete!</h2>';
            echo '<div class="alert alert-success">';
            echo 'Your Athletics Federation Management System is ready!';
            echo '</div>';
            echo '<p>Click the button below to finish installation and login.</p>';
            echo '<div class="button-group">';
            echo '<form method="POST" style="flex: 1;">';
            echo '<button type="submit" class="btn-primary" style="width: 100%;">Complete Installation</button>';
            echo '</form>';
            echo '</div>';
            break;
    }
}
?>
