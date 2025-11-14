<?php
/**
 * Admin - Registration List
 * View and manage pending registrations
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Registration Management</title>
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
        .controls {
            background: white;
            padding: 20px;
            border-radius: 8px;
            margin-bottom: 20px;
            display: flex;
            gap: 15px;
            align-items: center;
            flex-wrap: wrap;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.08);
        }
        .search-box {
            flex: 1;
            min-width: 200px;
        }
        .search-box input {
            width: 100%;
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        .filter-group {
            display: flex;
            gap: 10px;
        }
        select {
            padding: 10px 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            cursor: pointer;
        }
        button {
            padding: 10px 16px;
            border: none;
            border-radius: 4px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-primary {
            background: #667eea;
            color: white;
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
        }
        .btn-approve {
            background: #27ae60;
            color: white;
        }
        .btn-approve:hover {
            background: #229954;
        }
        .btn-reject {
            background: #e74c3c;
            color: white;
        }
        .btn-reject:hover {
            background: #c0392b;
        }
        .btn-view {
            background: #3498db;
            color: white;
        }
        .btn-view:hover {
            background: #2980b9;
        }
        .no-data {
            text-align: center;
            padding: 40px;
            color: #999;
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
            .controls {
                flex-direction: column;
            }
            .search-box {
                order: 1;
            }
            .filter-group {
                order: 2;
                width: 100%;
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
        <h1>Registration Management</h1>
        
        <!-- Statistics -->
        <div class="stats">
            <div class="stat-card">
                <div class="stat-label">Pending</div>
                <div class="stat-value"><?php echo $stats['pending'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Approved</div>
                <div class="stat-value"><?php echo $stats['approved'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Rejected</div>
                <div class="stat-value"><?php echo $stats['rejected'] ?? 0; ?></div>
            </div>
            <div class="stat-card">
                <div class="stat-label">Total</div>
                <div class="stat-value"><?php echo ($stats['pending'] ?? 0) + ($stats['approved'] ?? 0) + ($stats['rejected'] ?? 0); ?></div>
            </div>
        </div>
        
        <!-- Controls -->
        <div class="controls">
            <div class="search-box">
                <input type="text" id="searchInput" placeholder="Search by name, email, or phone...">
            </div>
            <div class="filter-group">
                <select id="statusFilter">
                    <option value="">All Status</option>
                    <option value="pending">Pending</option>
                    <option value="approved">Approved</option>
                    <option value="rejected">Rejected</option>
                </select>
                
                <?php if (!empty($events)): ?>
                    <select id="eventFilter">
                        <option value="">All Events</option>
                        <?php foreach ($events as $event): ?>
                            <option value="<?php echo htmlspecialchars($event['id']); ?>">
                                <?php echo htmlspecialchars($event['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <button class="btn-primary" onclick="exportCSV()">Export CSV</button>
        </div>
        
        <!-- Registrations Table -->
        <div class="table-wrapper">
            <?php if (!empty($registrations)): ?>
                <table id="registrationTable">
                    <thead>
                        <tr>
                            <th>Athlete Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Date of Birth</th>
                            <th>Event</th>
                            <th>Status</th>
                            <th>Submitted</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($registrations as $reg): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($reg['athlete_name']); ?></td>
                                <td><?php echo htmlspecialchars($reg['athlete_email']); ?></td>
                                <td><?php echo htmlspecialchars($reg['athlete_phone']); ?></td>
                                <td><?php echo htmlspecialchars($reg['athlete_dob']); ?></td>
                                <td><?php echo htmlspecialchars($reg['event_name'] ?? 'N/A'); ?></td>
                                <td>
                                    <span class="status-badge status-<?php echo strtolower($reg['status']); ?>">
                                        <?php echo ucfirst($reg['status']); ?>
                                    </span>
                                </td>
                                <td><?php echo htmlspecialchars($reg['created_at']); ?></td>
                                <td>
                                    <div class="action-buttons">
                                        <form method="POST" action="/registrations/<?php echo htmlspecialchars($reg['id']); ?>/approve" style="display: inline;">
                                            <button type="submit" class="btn-sm btn-approve" <?php echo $reg['status'] === 'pending' ? '' : 'disabled'; ?>>Approve</button>
                                        </form>
                                        <form method="POST" action="/registrations/<?php echo htmlspecialchars($reg['id']); ?>/reject" style="display: inline;">
                                            <button type="submit" class="btn-sm btn-reject" <?php echo $reg['status'] === 'pending' ? '' : 'disabled'; ?> onclick="return confirm('Are you sure you want to reject this registration?');">Reject</button>
                                        </form>
                                        <button class="btn-sm btn-view" onclick="viewDetails(<?php echo htmlspecialchars($reg['id']); ?>)">View</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <div class="no-data">
                    <p>No registrations found.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
    
    <script>
        function viewDetails(id) {
            window.location.href = '/registrations/' + id;
        }
        
        function exportCSV() {
            window.location.href = '/registrations/export';
        }
        
        // Simple client-side filtering
        document.getElementById('searchInput')?.addEventListener('keyup', filterTable);
        document.getElementById('statusFilter')?.addEventListener('change', filterTable);
        
        function filterTable() {
            const searchText = document.getElementById('searchInput')?.value.toLowerCase() || '';
            const statusFilter = document.getElementById('statusFilter')?.value.toLowerCase() || '';
            const rows = document.querySelectorAll('#registrationTable tbody tr');
            
            rows.forEach(row => {
                const text = row.textContent.toLowerCase();
                const status = row.querySelector('.status-badge')?.textContent.toLowerCase() || '';
                
                const searchMatch = searchText === '' || text.includes(searchText);
                const statusMatch = statusFilter === '' || status.includes(statusFilter);
                
                row.style.display = searchMatch && statusMatch ? '' : 'none';
            });
        }
    </script>
</body>
</html>
