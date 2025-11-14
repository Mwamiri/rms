<?php
/**
 * Import Preview View
 * Preview data before processing
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import Preview</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: #f5f7fa;
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        h1 {
            color: #333;
            margin-bottom: 30px;
            font-size: 28px;
        }
        .info-bar {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 15px;
        }
        .file-info {
            display: flex;
            gap: 20px;
            flex-wrap: wrap;
        }
        .info-item {
            display: flex;
            flex-direction: column;
            gap: 3px;
        }
        .info-label {
            color: #999;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .info-value {
            color: #333;
            font-size: 16px;
            font-weight: 600;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
            line-height: 1.6;
        }
        .alert-info {
            background: #d6eaf8;
            color: #1a5276;
            border: 1px solid #aed6f1;
        }
        .alert-warning {
            background: #fef5e7;
            color: #d68910;
            border: 1px solid #f8d7a1;
        }
        .preview-section {
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            margin-bottom: 20px;
            overflow: hidden;
        }
        .section-header {
            background: #f8f9fa;
            border-bottom: 1px solid #ecf0f1;
            padding: 15px 20px;
        }
        .section-title {
            color: #333;
            font-weight: 600;
            font-size: 14px;
        }
        .table-wrapper {
            overflow-x: auto;
        }
        table {
            width: 100%;
            border-collapse: collapse;
        }
        thead {
            background: #f8f9fa;
            border-bottom: 2px solid #ecf0f1;
        }
        th {
            padding: 12px 15px;
            text-align: left;
            color: #555;
            font-weight: 600;
            font-size: 12px;
            text-transform: uppercase;
            white-space: nowrap;
        }
        td {
            padding: 12px 15px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        tbody tr:nth-child(even) {
            background: #fafbfc;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin: 20px 0;
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
        .btn-process {
            background: #27ae60;
            color: white;
        }
        .btn-process:hover {
            background: #229954;
            box-shadow: 0 2px 8px rgba(39, 174, 96, 0.3);
        }
        .btn-back {
            background: #ecf0f1;
            color: #333;
        }
        .btn-back:hover {
            background: #d5dbdb;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(150px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 15px;
            border-radius: 4px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
            text-align: center;
        }
        .stat-label {
            color: #999;
            font-size: 12px;
            font-weight: 600;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .stat-value {
            color: #333;
            font-size: 24px;
            font-weight: 700;
        }
        .truncate {
            max-width: 200px;
            overflow: hidden;
            text-overflow: ellipsis;
            white-space: nowrap;
        }
        @media (max-width: 768px) {
            .info-bar {
                flex-direction: column;
                align-items: flex-start;
            }
            .button-group {
                flex-direction: column;
            }
            button {
                width: 100%;
            }
            th, td {
                padding: 8px 10px;
                font-size: 12px;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Data Preview</h1>
        
        <div class="info-bar">
            <div class="file-info">
                <div class="info-item">
                    <span class="info-label">File Name</span>
                    <span class="info-value truncate"><?php echo htmlspecialchars($import['original_filename']); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Type</span>
                    <span class="info-value"><?php echo ucfirst(htmlspecialchars($import['type'])); ?></span>
                </div>
                <div class="info-item">
                    <span class="info-label">Rows</span>
                    <span class="info-value"><?php echo htmlspecialchars($import['row_count']); ?></span>
                </div>
            </div>
            <div class="button-group">
                <form method="POST" action="/imports/upload" style="display: inline;">
                    <button type="button" class="btn-back" onclick="window.location.href='/imports/form'">← Upload Different File</button>
                </form>
            </div>
        </div>
        
        <div class="alert alert-info">
            <strong>Review Your Data:</strong> The preview below shows the first 10 rows of your imported file. Please verify the data is correct before processing.
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Rows</div>
                <div class="stat-value"><?php echo htmlspecialchars($import['row_count']); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Columns</div>
                <div class="stat-value"><?php echo count($headers ?? []); ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">File Size</div>
                <div class="stat-value"><?php echo round($import['file_size'] / 1024, 1); ?> KB</div>
            </div>
        </div>
        
        <!-- Preview Table -->
        <div class="preview-section">
            <div class="section-header">
                <div class="section-title">Data Preview (First 10 Rows)</div>
            </div>
            <div class="table-wrapper">
                <table>
                    <thead>
                        <tr>
                            <?php if (!empty($headers)): ?>
                                <?php foreach ($headers as $header): ?>
                                    <th><?php echo htmlspecialchars($header); ?></th>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (!empty($preview_data)): ?>
                            <?php foreach ($preview_data as $row_index => $row): ?>
                                <tr>
                                    <?php foreach ($row as $cell): ?>
                                        <td><?php echo htmlspecialchars($cell); ?></td>
                                    <?php endforeach; ?>
                                </tr>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <tr>
                                <td colspan="<?php echo count($headers ?? []); ?>" style="text-align: center; color: #999;">
                                    No data to preview
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Action Buttons -->
        <div class="button-group" style="margin-top: 30px;">
            <form method="POST" action="/imports/process" style="flex: 1;">
                <input type="hidden" name="import_id" value="<?php echo htmlspecialchars($import['id']); ?>">
                <button type="submit" class="btn-process" style="width: 100%;">✓ Process Import</button>
            </form>
            <button type="button" class="btn-back" onclick="window.location.href='/imports/form'">Cancel</button>
        </div>
    </div>
</body>
</html>
