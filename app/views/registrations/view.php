<?php
/**
 * Admin - Registration Detail View
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Details</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: 0 auto;
        }
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
        }
        h1 {
            color: #333;
            font-size: 28px;
        }
        .back-link {
            color: #667eea;
            text-decoration: none;
            font-weight: 600;
        }
        .back-link:hover {
            text-decoration: underline;
        }
        .card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
        }
        .card-header {
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .card-title {
            color: #333;
            font-weight: 600;
            font-size: 16px;
        }
        .status-badge {
            display: inline-block;
            padding: 6px 12px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-pending {
            background: #fef5e7;
            color: #d68910;
        }
        .status-approved {
            background: #d5f4e6;
            color: #0b5345;
        }
        .status-rejected {
            background: #fadbd8;
            color: #a93226;
        }
        .card-content {
            padding: 20px;
        }
        .section {
            margin-bottom: 25px;
        }
        .section-title {
            color: #667eea;
            font-weight: 600;
            font-size: 14px;
            text-transform: uppercase;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #f0f0f0;
        }
        .info-row {
            display: grid;
            grid-template-columns: 200px 1fr;
            gap: 15px;
            margin-bottom: 12px;
            padding: 10px 0;
        }
        .info-label {
            color: #666;
            font-weight: 600;
            font-size: 13px;
        }
        .info-value {
            color: #333;
            font-size: 13px;
        }
        .action-buttons {
            display: flex;
            gap: 10px;
            margin-top: 30px;
            border-top: 1px solid #ecf0f1;
            padding-top: 20px;
        }
        button {
            padding: 12px 24px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-approve {
            background: #27ae60;
            color: white;
            flex: 1;
        }
        .btn-approve:hover:not(:disabled) {
            background: #229954;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }
        .btn-reject {
            background: #e74c3c;
            color: white;
            flex: 1;
        }
        .btn-reject:hover:not(:disabled) {
            background: #c0392b;
            box-shadow: 0 2px 8px rgba(231, 76, 60, 0.3);
        }
        .btn-back {
            background: #ecf0f1;
            color: #333;
            flex: 1;
        }
        .btn-back:hover {
            background: #d5dbdb;
        }
        button:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
        }
        .alert-info {
            background: #d6eaf8;
            color: #1a5276;
            border: 1px solid #aed6f1;
        }
        .notes-box {
            background: #faf8f3;
            border-left: 4px solid #f39c12;
            padding: 15px;
            border-radius: 2px;
            font-size: 13px;
            line-height: 1.6;
        }
        @media (max-width: 640px) {
            .container {
                padding: 0;
            }
            .info-row {
                grid-template-columns: 1fr;
            }
            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>Registration Details</h1>
            <a href="/registrations/list" class="back-link">← Back to List</a>
        </div>
        
        <!-- Personal Information -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Personal Information</div>
                <span class="status-badge status-<?php echo strtolower($registration['status']); ?>">
                    <?php echo ucfirst($registration['status']); ?>
                </span>
            </div>
            <div class="card-content">
                <div class="section">
                    <div class="info-row">
                        <span class="info-label">Full Name:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['athlete_name']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Date of Birth:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['athlete_dob']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Gender:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['gender'] ?? 'Not specified'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Contact Information -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Contact Information</div>
            </div>
            <div class="card-content">
                <div class="section">
                    <div class="info-row">
                        <span class="info-label">Email Address:</span>
                        <span class="info-value">
                            <a href="mailto:<?php echo htmlspecialchars($registration['athlete_email']); ?>">
                                <?php echo htmlspecialchars($registration['athlete_email']); ?>
                            </a>
                        </span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Phone Number:</span>
                        <span class="info-value">
                            <a href="tel:<?php echo htmlspecialchars($registration['athlete_phone']); ?>">
                                <?php echo htmlspecialchars($registration['athlete_phone']); ?>
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Organization Information -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Organization Information</div>
            </div>
            <div class="card-content">
                <div class="section">
                    <div class="info-row">
                        <span class="info-label">Club/Organization:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['club_name'] ?? 'Not specified'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Region:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['region_name'] ?? 'Not specified'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Bib Number:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['bib_number'] ?? '-'); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Event:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['event_name'] ?? 'N/A'); ?></span>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Additional Notes -->
        <?php if (!empty($registration['notes'])): ?>
            <div class="card">
                <div class="card-header">
                    <div class="card-title">Additional Notes</div>
                </div>
                <div class="card-content">
                    <div class="notes-box">
                        <?php echo nl2br(htmlspecialchars($registration['notes'])); ?>
                    </div>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Registration Metadata -->
        <div class="card">
            <div class="card-header">
                <div class="card-title">Registration Information</div>
            </div>
            <div class="card-content">
                <div class="section">
                    <div class="info-row">
                        <span class="info-label">Registration ID:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['id']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Submitted:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['created_at']); ?></span>
                    </div>
                    <div class="info-row">
                        <span class="info-label">Last Updated:</span>
                        <span class="info-value"><?php echo htmlspecialchars($registration['updated_at']); ?></span>
                    </div>
                </div>
                
                <?php if ($registration['status'] === 'pending'): ?>
                    <div class="alert alert-info" style="margin-top: 15px;">
                        <strong>Action Required:</strong> Please review this registration and either approve or reject it using the buttons below.
                    </div>
                <?php endif; ?>
                
                <!-- Action Buttons -->
                <div class="action-buttons">
                    <form method="POST" action="/registrations/<?php echo htmlspecialchars($registration['id']); ?>/approve" style="flex: 1;">
                        <button type="submit" class="btn-approve" <?php echo $registration['status'] !== 'pending' ? 'disabled' : ''; ?>>
                            ✓ Approve Registration
                        </button>
                    </form>
                    <form method="POST" action="/registrations/<?php echo htmlspecialchars($registration['id']); ?>/reject" style="flex: 1;" onsubmit="return confirm('Are you sure you want to reject this registration?');">
                        <button type="submit" class="btn-reject" <?php echo $registration['status'] !== 'pending' ? 'disabled' : ''; ?>>
                            ✕ Reject Registration
                        </button>
                    </form>
                    <button onclick="window.history.back()" class="btn-back">Back</button>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
