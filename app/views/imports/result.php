<?php
/**
 * Import Results View
 * Show import completion status and logs
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Results</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1000px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .result-card {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            padding: 30px;
            margin-bottom: 20px;
            text-align: center;
        }
        .result-icon {
            font-size: 48px;
            margin-bottom: 15px;
        }
        .result-status {
            font-size: 24px;
            font-weight: 700;
            margin-bottom: 10px;
        }
        .result-status.success {
            color: #27ae60;
        }
        .result-status.partial {
            color: #f39c12;
        }
        .result-message {
            color: #666;
            font-size: 14px;
            line-height: 1.6;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin: 30px 0;
        }
        .stat-card {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            border-left: 4px solid #667eea;
        }
        .stat-card.success {
            border-left-color: #27ae60;
        }
        .stat-card.error {
            border-left-color: #e74c3c;
        }
        .stat-label {
            color: #999;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 8px;
        }
        .stat-value {
            color: #333;
            font-size: 28px;
            font-weight: 700;
        }
        .progress-bar {
            width: 100%;
            height: 20px;
            background: #ecf0f1;
            border-radius: 4px;
            overflow: hidden;
            margin: 20px 0;
        }
        .progress-fill {
            height: 100%;
            background: linear-gradient(90deg, #27ae60 0%, #229954 100%);
            transition: width 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 12px;
            font-weight: 600;
        }
        .logs-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .section-header {
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
            padding: 15px 20px;
            border-left: 4px solid #667eea;
        }
        .section-title {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .log-entries {
            max-height: 400px;
            overflow-y: auto;
        }
        .log-entry {
            padding: 12px 20px;
            border-bottom: 1px solid #ecf0f1;
            display: flex;
            gap: 12px;
            align-items: flex-start;
            font-size: 13px;
        }
        .log-entry:last-child {
            border-bottom: none;
        }
        .log-row {
            color: #999;
            font-weight: 600;
            min-width: 50px;
            text-align: right;
        }
        .log-status {
            display: inline-block;
            width: 20px;
            height: 20px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-size: 11px;
            font-weight: bold;
            flex-shrink: 0;
        }
        .log-status.success {
            background: #27ae60;
        }
        .log-status.error {
            background: #e74c3c;
        }
        .log-message {
            flex: 1;
            color: #555;
        }
        .log-message.error {
            color: #a93226;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 30px;
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
        }
        .btn-primary:hover {
            background: #5568d3;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        .btn-secondary {
            background: #ecf0f1;
            color: #333;
        }
        .btn-secondary:hover {
            background: #d5dbdb;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.6;
        }
        .alert-warning {
            background: #fef5e7;
            color: #d68910;
            border: 1px solid #f8d7a1;
        }
        @media (max-width: 768px) {
            .button-group {
                flex-direction: column;
            }
            button {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📊 Import Results</h1>
        
        <!-- Result Summary -->
        <div class="result-card">
            <?php if ($import['error_count'] == 0): ?>
                <div class="result-icon">✓</div>
                <div class="result-status success">Import Completed Successfully!</div>
                <div class="result-message">
                    All <?php echo htmlspecialchars($import['row_count']); ?> rows have been successfully imported.
                </div>
            <?php else: ?>
                <div class="result-icon">⚠</div>
                <div class="result-status partial">Import Completed with Errors</div>
                <div class="result-message">
                    <?php echo htmlspecialchars($import['success_count']); ?> rows imported successfully, 
                    <?php echo htmlspecialchars($import['error_count']); ?> errors occurred.
                </div>
            <?php endif; ?>
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card success">
                <div class="stat-label">Successful</div>
                <div class="stat-value"><?php echo htmlspecialchars($import['success_count']); ?></div>
            </div>
            <div class="stat-card error">
                <div class="stat-label">Errors</div>
                <div class="stat-value"><?php echo htmlspecialchars($import['error_count']); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total Rows</div>
                <div class="stat-value"><?php echo htmlspecialchars($import['row_count']); ?></div>
            </div>
        </div>
        
        <!-- Progress Bar -->
        <div class="progress-bar">
            <div class="progress-fill" style="width: <?php echo round(($import['success_count'] / max($import['row_count'], 1)) * 100); ?>%">
                <?php echo round(($import['success_count'] / max($import['row_count'], 1)) * 100); ?>%
            </div>
        </div>
        
        <!-- Error Alert -->
        <?php if ($import['error_count'] > 0): ?>
            <div class="alert alert-warning">
                <strong>Review Errors:</strong> Some rows had errors during import. Check the logs below to see which rows failed and why.
            </div>
        <?php endif; ?>
        
        <!-- Import Logs -->
        <?php if (!empty($logs)): ?>
            <div class="logs-section">
                <div class="section-header">
                    <div class="section-title">Import Logs</div>
                </div>
                <div class="log-entries">
                    <?php foreach ($logs as $log): ?>
                        <div class="log-entry">
                            <div class="log-row">#<?php echo htmlspecialchars($log['row_number']); ?></div>
                            <div class="log-status <?php echo $log['status'] === 'error' ? 'error' : 'success'; ?>">
                                <?php echo $log['status'] === 'error' ? '✕' : '✓'; ?>
                            </div>
                            <div class="log-message <?php echo $log['status'] === 'error' ? 'error' : ''; ?>">
                                <?php echo htmlspecialchars($log['message']); ?>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            </div>
        <?php endif; ?>
        
        <!-- Action Buttons -->
        <div class="button-group">
            <button onclick="window.location.href='/imports/form'" class="btn-primary">Import Another File</button>
            <button onclick="window.location.href='/dashboard'" class="btn-secondary">Back to Dashboard</button>
        </div>
    </div>
</body>
</html>
