<?php
/**
 * Registration Confirmation Page
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Confirmed</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 20px;
        }
        .container {
            background: white;
            border-radius: 8px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
            padding: 40px;
            max-width: 600px;
            width: 100%;
            text-align: center;
        }
        .success-icon {
            width: 60px;
            height: 60px;
            background: #27ae60;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 20px;
            font-size: 36px;
            color: white;
        }
        h1 {
            color: #27ae60;
            margin-bottom: 10px;
            font-size: 28px;
        }
        .subtitle {
            color: #666;
            margin-bottom: 30px;
            font-size: 14px;
            line-height: 1.6;
        }
        .info-section {
            background: #ecf0f1;
            border-radius: 4px;
            padding: 20px;
            margin: 30px 0;
            text-align: left;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
            border-bottom: 1px solid #bdc3c7;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            color: #555;
            font-weight: 600;
            font-size: 13px;
        }
        .info-value {
            color: #333;
            font-size: 13px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 600;
            margin-top: 10px;
        }
        .status-pending {
            background: #fef5e7;
            color: #d68910;
        }
        .status-approved {
            background: #d5f4e6;
            color: #0b5345;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin: 20px 0;
            font-size: 13px;
            line-height: 1.6;
        }
        .alert-info {
            background: #d6eaf8;
            color: #1a5276;
            border: 1px solid #aed6f1;
        }
        .button-group {
            margin-top: 30px;
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }
        button, a.btn {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
            text-decoration: none;
            display: inline-block;
        }
        .btn-primary {
            background: #667eea;
            color: white;
            flex: 1;
            min-width: 150px;
        }
        .btn-primary:hover {
            background: #5568d3;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-secondary {
            background: #ecf0f1;
            color: #333;
            flex: 1;
            min-width: 150px;
        }
        .btn-secondary:hover {
            background: #d5dbdb;
        }
        @media (max-width: 640px) {
            .container {
                padding: 20px;
            }
            h1 {
                font-size: 24px;
            }
            .button-group {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>Registration Successful!</h1>
        <p class="subtitle">
            Thank you for registering. Your registration has been submitted and is now pending admin approval.
        </p>
        
        <div class="info-section">
            <div class="info-row">
                <span class="info-label">Athlete Name:</span>
                <span class="info-value"><?php echo htmlspecialchars($registration['athlete_name'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Email:</span>
                <span class="info-value"><?php echo htmlspecialchars($registration['athlete_email'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Phone:</span>
                <span class="info-value"><?php echo htmlspecialchars($registration['athlete_phone'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Date of Birth:</span>
                <span class="info-value"><?php echo htmlspecialchars($registration['athlete_dob'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Registration ID:</span>
                <span class="info-value"><?php echo htmlspecialchars($registration['id'] ?? ''); ?></span>
            </div>
            <div class="info-row">
                <span class="info-label">Status:</span>
                <span class="info-value">
                    <span class="status-badge status-pending">Pending Approval</span>
                </span>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>What's Next?</strong><br>
            Your registration is under review. You'll receive an email confirmation once your registration is approved by the event administrator.
        </div>
        
        <div class="button-group">
            <a href="/" class="btn btn-primary">Back to Home</a>
            <a href="/events" class="btn btn-secondary">View Events</a>
        </div>
    </div>
</body>
</html>
