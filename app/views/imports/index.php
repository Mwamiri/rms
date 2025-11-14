<?php
/**
 * Imports List View
 * View all import operations
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Import History</title>
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
        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            flex-wrap: wrap;
            gap: 15px;
        }
        h1 {
            color: #333;
            font-size: 28px;
        }
        .btn-new {
            padding: 12px 24px;
            background: #667eea;
            color: white;
            border: none;
            border-radius: 4px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            display: inline-block;
            transition: all 0.3s;
        }
        .btn-new:hover {
            background: #5568d3;
            box-shadow: 0 2px 8px rgba(102, 126, 234, 0.3);
        }
        .table-wrapper {
            background: white;
            border-radius: 8px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
            padding: 15px;
            text-align: left;
            color: #555;
            font-weight: 600;
            font-size: 13px;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }
        td {
            padding: 15px;
            border-bottom: 1px solid #ecf0f1;
            font-size: 13px;
        }
        tbody tr:hover {
            background: #f8f9fa;
        }
        .status-badge {
            display: inline-block;
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 11px;
            font-weight: 600;
            text-transform: uppercase;
        }
        .status-completed {
            background: #d5f4e6;
            color: #0b5345;
        }
        .status-processing {
            background: #fef5e7;
            color: #d68910;
        }
        .status-failed {
            background: #fadbd8;
            color: #a93226;
        }
        .action-buttons {
            display: flex;
            gap: 8px;
        }
        .btn-sm {
            padding: 6px 10px;
            border: none;
            border-radius: 3px;
            font-size: 12px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.2s;
            background: #3498db;
            color: white;
        }
        .btn-sm:hover {
            background: #2980b9;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
        }
        .progress-bar {
            width: 100px;
            height: 6px;
            background: #ecf0f1;
            border-radius: 3px;
            overflow: hidden;
        }
        .progress-fill {
            height: 100%;
            background: #27ae60;
            transition: width 0.3s;
        }
        .stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
            margin-bottom: 20px;
        }
        .stat-card {
            background: white;
            padding: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
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
        @media (max-width: 768px) {
            .header {
                flex-direction: column;
                align-items: flex-start;
            }
            table {
                font-size: 12px;
            }
            th, td {
                padding: 10px;
            }
            .action-buttons {
                flex-direction: column;
            }
            .btn-sm {
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>📁 Import History</h1>
            <a href="/imports/form" class="btn-new">+ New Import</a>
        </div>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Total Imports</div>
                <div class="stat-value"><?php echo $stats['total'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Completed</div>
                <div class="stat-value"><?php echo $stats['completed'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Processing</div>
                <div class="stat-value"><?php echo $stats['processing'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Failed</div>
                <div class="stat-value"><?php echo $stats['failed'] ?? 0; ?></div>
            </div>
        </div>
        
        <!-- Imports Table -->
        <div class="table-wrapper">
            <?php if (!empty($imports)): ?>
                <table>
                    <thead>
                        <tr>
                            <th>File Name</th>
                            <th>Type</th>
                            <th>Rows</th>
                            <th>Success</th>
                            <th>Errors</th>
                            <th>Progress</th>
                            <th>Status</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($imports as $import): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($import['original_filename']); ?></td>
                                <td><?php echo ucfirst(htmlspecialchars($import['type'])); ?></td>
                                <td><?php echo htmlspecialchars($import['row_count']); ?></td>
                                <td><strong><?php echo htmlspecialchars($import['success_count']); ?></strong></td>
                                <td><?php echo htmlspecialchars($import['error_count']); ?></td>
                                <td>
                                    <div class="progress-bar">
                                        <div class="progress-fill" style="width: <?php echo round(($import['success_count'] / max($import['row_count'], 1)) * 100); ?>%"></div>
                                    </div>
                                </td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($import['status']); ?>">
                                        <?php echo ucfirst($import['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo date('M d, Y', strtotime($import['created_at'])); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <button class="btn-sm" onclick="viewDetails(<?php echo htmlspecialchars($import['id']); ?>)">View</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No imports yet. <a href="/imports/form" style="color: #667eea; text-decoration: none; font-weight: 600;">Start an import</a></p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function viewDetails(id) {
            window.location.href = '/imports/result/' + id;
        }
    </script>
</body>
</html>
