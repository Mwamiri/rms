<?php
/**
 * Import Form View
 * Upload CSV/XLS files for bulk data import
 */
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Import</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Oxygen, Ubuntu, Cantarell, sans-serif;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            padding: 20px;
        }
        .container {
            max-width: 900px;
            margin: 0 auto;
        }
        .header {
            text-align: center;
            color: white;
            margin-bottom: 40px;
        }
        h1 {
            font-size: 32px;
            margin-bottom: 10px;
        }
        .subtitle {
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 30px;
        }
        .card {
            background: white;
            border-radius: 8px;
            padding: 30px;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.15);
        }
        .card h2 {
            color: #333;
            font-size: 20px;
            margin-bottom: 20px;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .card-icon {
            width: 32px;
            height: 32px;
            background: #667eea;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
            font-weight: bold;
            font-size: 18px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            color: #333;
            font-weight: 600;
            margin-bottom: 8px;
            font-size: 14px;
        }
        input[type="file"],
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }
        input[type="file"] {
            padding: 6px;
        }
        select {
            cursor: pointer;
        }
        input[type="file"]:focus,
        select:focus {
            outline: none;
            border-color: #667eea;
            box-shadow: 0 0 0 3px rgba(102, 126, 234, 0.1);
        }
        .file-hint {
            color: #999;
            font-size: 12px;
            margin-top: 5px;
        }
        .info-list {
            background: #f8f9fa;
            border-left: 4px solid #667eea;
            padding: 15px;
            border-radius: 2px;
            margin: 20px 0;
        }
        .info-list h3 {
            color: #333;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .info-list ul {
            list-style: none;
            padding: 0;
        }
        .info-list li {
            color: #666;
            font-size: 13px;
            padding: 5px 0;
        }
        .info-list li:before {
            content: "✓ ";
            color: #27ae60;
            font-weight: bold;
            margin-right: 5px;
        }
        .button-group {
            display: flex;
            gap: 10px;
            margin-top: 25px;
        }
        button {
            flex: 1;
            padding: 12px;
            border: none;
            border-radius: 4px;
            font-size: 16px;
            font-weight: 600;
            cursor: pointer;
            transition: all 0.3s;
        }
        .btn-submit {
            background: #667eea;
            color: white;
        }
        .btn-submit:hover {
            background: #5568d3;
            box-shadow: 0 4px 12px rgba(102, 126, 234, 0.4);
        }
        .btn-submit:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }
        .btn-download {
            background: #ecf0f1;
            color: #333;
        }
        .btn-download:hover {
            background: #d5dbdb;
        }
        .alert {
            padding: 12px 16px;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
        .alert-error {
            background-color: #fadbd8;
            color: #a93226;
            border: 1px solid #e74c3c;
        }
        .template-examples {
            background: #ecf0f1;
            border-radius: 4px;
            padding: 15px;
            margin: 20px 0;
        }
        .template-examples h3 {
            color: #333;
            font-size: 13px;
            font-weight: 600;
            margin-bottom: 10px;
        }
        .template-examples code {
            background: white;
            padding: 8px 12px;
            border-radius: 3px;
            font-family: 'Monaco', 'Menlo', monospace;
            font-size: 12px;
            color: #333;
            display: block;
            margin: 5px 0;
            overflow-x: auto;
        }
        .divider {
            text-align: center;
            color: #999;
            margin: 30px 0;
            font-size: 14px;
        }
        @media (max-width: 768px) {
            .content {
                grid-template-columns: 1fr;
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
        <div class="header">
            <h1>📊 Data Import</h1>
            <p class="subtitle">Upload CSV or Excel files to import data in bulk</p>
        </div>
        
        <div class="content">
            <!-- Upload Section -->
            <div class="card">
                <h2>
                    <div class="card-icon">1</div>
                    Upload File
                </h2>
                
                <?php if (isset($error)): ?>
                    <div class="alert alert-error">
                        <?php echo htmlspecialchars($error); ?>
                    </div>
                <?php endif; ?>
                
                <form method="POST" action="/imports/upload" enctype="multipart/form-data">
                    <div class="form-group">
                        <label for="type">Data Type</label>
                        <select name="type" id="type" required>
                            <option value="">Select Type</option>
                            <option value="athletes">Athletes</option>
                            <option value="teams">Teams</option>
                            <option value="registrations">Event Registrations</option>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="file">Select File</label>
                        <input type="file" name="file" id="file" accept=".csv,.xls,.xlsx" required>
                        <div class="file-hint">Supported formats: CSV, XLS, XLSX (Max 10MB)</div>
                    </div>
                    
                    <div class="info-list">
                        <h3>Import Process:</h3>
                        <ul>
                            <li>Upload your file</li>
                            <li>Preview data before processing</li>
                            <li>Process and import</li>
                            <li>View results and errors</li>
                        </ul>
                    </div>
                    
                    <div class="button-group">
                        <button type="submit" class="btn-submit">Next: Preview Data</button>
                    </div>
                </form>
            </div>
            
            <!-- Template Section -->
            <div class="card">
                <h2>
                    <div class="card-icon">2</div>
                    Download Template
                </h2>
                
                <p style="color: #666; font-size: 14px; margin-bottom: 20px;">
                    Download a template file to ensure your data is formatted correctly.
                </p>
                
                <div class="form-group">
                    <label for="template-type">Template Type</label>
                    <select id="template-type">
                        <option value="">Select Type</option>
                        <option value="athletes">Athletes Template</option>
                        <option value="teams">Teams Template</option>
                        <option value="registrations">Registrations Template</option>
                    </select>
                </div>
                
                <div class="info-list">
                    <h3>Template Contents:</h3>
                    <ul>
                        <li>Column headers</li>
                        <li>Example data</li>
                        <li>Format guidelines</li>
                        <li>Required fields</li>
                    </ul>
                </div>
                
                <div class="template-examples">
                    <h3>Athletes Template Columns:</h3>
                    <code>name, bib_number, gender, dob, district, team_id</code>
                    <h3>Teams Template Columns:</h3>
                    <code>name, code, region_id, type, contact_person, contact_email</code>
                    <h3>Registrations Template Columns:</h3>
                    <code>athlete_name, email, phone, dob, club_id, region_id, category_id, bib_number</code>
                </div>
                
                <div class="button-group">
                    <button type="button" class="btn-download" onclick="downloadTemplate()">Download Template</button>
                </div>
            </div>
        </div>
        
        <div class="divider">
            Need help? Check the documentation or contact support
        </div>
    </div>
    
    <script>
        function downloadTemplate() {
            const type = document.getElementById('template-type').value;
            if (!type) {
                alert('Please select a template type');
                return;
            }
            window.location.href = '/imports/template?type=' + encodeURIComponent(type);
        }
    </script>
</body>
</html>
