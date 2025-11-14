# 🚀 Deployment Guide - Upload to Server

## Step 1: Files to Upload

### New Files to Upload (Phase 2)

```
app/controllers/
├── ImportController.php                    (NEW - 280 lines)
└── RegistrationController.php              (NEW - 270 lines)

app/models/
├── Import.php                              (NEW - 130 lines)
└── Registration.php                        (NEW - 160 lines)

app/services/
└── FileImportService.php                   (NEW - 210 lines)

app/views/imports/
├── form.php                                (NEW - 400+ lines)
├── preview.php                             (NEW - 300+ lines)
├── result.php                              (NEW - 350+ lines)
└── index.php                               (NEW - 300+ lines)

app/views/registrations/
├── public_form.php                         (NEW - 500+ lines)
├── confirm.php                             (NEW - 200+ lines)
├── list.php                                (NEW - 400+ lines)
└── view.php                                (NEW - 350+ lines)

config/
├── routes.php                              (UPDATED - add 15 routes)
└── schema.php                              (UPDATED - add 5 tables)
```

### Existing Files (Leave Unchanged)
- `public/index.php` - Keep as is
- `installer/index.php` - Keep as is
- `core/` directory - Keep as is
- `app/controllers/` (EventsController, etc.) - Keep as is
- `app/models/` (Event, Athlete, etc.) - Keep as is
- All other existing files

---

## Step 2: Using FTP/File Manager

### Using Filezilla (FTP Client)

1. **Connect to Your Server**
   ```
   Host: your-domain.com  (or IP address)
   Username: your FTP username
   Password: your FTP password
   Port: 21 (or ask your hosting provider)
   ```

2. **Navigate to Root Directory**
   ```
   /public_html/RMS/RMS_New/  (or your path)
   ```

3. **Upload New Files** (Drag & Drop)
   ```
   Right side of Filezilla = Your Server
   
   Create these directories if they don't exist:
   □ app/views/imports/
   □ app/views/registrations/
   
   Upload files:
   □ app/controllers/ImportController.php
   □ app/controllers/RegistrationController.php
   □ app/models/Import.php
   □ app/models/Registration.php
   □ app/services/FileImportService.php
   □ app/views/imports/ (4 files)
   □ app/views/registrations/ (4 files)
   ```

4. **Update Config Files**
   ```
   ✓ Download config/routes.php
   ✓ Open in text editor
   ✓ Add 15 new routes (see below)
   ✓ Upload back to server
   
   ✓ Download config/schema.php
   ✓ Open in text editor
   ✓ Add 5 new tables (see below)
   ✓ Upload back to server
   ```

### Using cPanel File Manager

1. **Login to cPanel**
   ```
   https://your-domain.com:2083/
   ```

2. **Navigate to File Manager**
   ```
   Home > File Manager
   ```

3. **Go to Your Directory**
   ```
   public_html/RMS/RMS_New/
   ```

4. **Create New Directories**
   ```
   Right-click > Create New > Folder
   □ app/views/imports/
   □ app/views/registrations/
   ```

5. **Upload Files**
   ```
   Right-click > Upload
   Select all new files and upload
   ```

---

## Step 3: Update Configuration Files

### Update config/routes.php

Add these 15 routes to the file (after existing routes):

```php
    // ===== IMPORT ROUTES =====
    $router->register('GET', '/imports', 'ImportController@index');
    $router->register('GET', '/imports/form', 'ImportController@form');
    $router->register('POST', '/imports/upload', 'ImportController@upload');
    $router->register('POST', '/imports/preview', 'ImportController@preview');
    $router->register('POST', '/imports/process', 'ImportController@process');
    $router->register('GET', '/imports/result/:id', 'ImportController@result');
    $router->register('GET', '/imports/template', 'ImportController@template');
    
    // ===== REGISTRATION ROUTES =====
    $router->register('GET', '/registrations/form', 'RegistrationController@form');
    $router->register('POST', '/registrations/submit', 'RegistrationController@submit');
    $router->register('GET', '/registrations/confirm', 'RegistrationController@confirm');
    $router->register('GET', '/registrations/list', 'RegistrationController@list');
    $router->register('GET', '/registrations/:id', 'RegistrationController@view');
    $router->register('POST', '/registrations/:id/approve', 'RegistrationController@approve');
    $router->register('POST', '/registrations/:id/reject', 'RegistrationController@reject');
    $router->register('POST', '/registrations/bulk-approve', 'RegistrationController@bulkApprove');
    $router->register('GET', '/registrations/export', 'RegistrationController@export');
```

### Update config/schema.php

Add these 5 tables to the array (look for the `$tables` array):

```php
    // Imports tracking table
    'imports' => [
        'fields' => [
            'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
            'type' => 'VARCHAR(50)',
            'filename' => 'VARCHAR(255)',
            'filepath' => 'VARCHAR(255)',
            'original_filename' => 'VARCHAR(255)',
            'file_size' => 'INT',
            'row_count' => 'INT',
            'success_count' => 'INT',
            'error_count' => 'INT',
            'status' => "VARCHAR(50) DEFAULT 'pending'",
            'notes' => 'LONGTEXT',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ]
    ],
    
    // Import logs table
    'import_logs' => [
        'fields' => [
            'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
            'import_id' => 'INT',
            'row_number' => 'INT',
            'status' => "VARCHAR(50) DEFAULT 'error'",
            'message' => 'LONGTEXT',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ],
        'foreign_keys' => [
            'import_id' => ['table' => 'imports', 'column' => 'id']
        ]
    ],
    
    // Registrations table
    'registrations' => [
        'fields' => [
            'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
            'event_id' => 'INT',
            'event_category_id' => 'INT',
            'athlete_name' => 'VARCHAR(255)',
            'athlete_email' => 'VARCHAR(255)',
            'athlete_phone' => 'VARCHAR(20)',
            'athlete_dob' => 'DATE',
            'club_id' => 'INT',
            'region_id' => 'INT',
            'bib_number' => 'VARCHAR(50)',
            'status' => "VARCHAR(50) DEFAULT 'pending'",
            'notes' => 'LONGTEXT',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP',
            'updated_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP'
        ],
        'foreign_keys' => [
            'event_id' => ['table' => 'events', 'column' => 'id']
        ]
    ],
    
    // Regions table
    'regions' => [
        'fields' => [
            'id' => 'INT PRIMARY KEY AUTO_INCREMENT',
            'name' => 'VARCHAR(255)',
            'code' => 'VARCHAR(50)',
            'description' => 'LONGTEXT',
            'created_at' => 'TIMESTAMP DEFAULT CURRENT_TIMESTAMP'
        ]
    ]
```

---

## Step 4: Create Storage Directory

### Via FTP/File Manager
```
Create directory:
/storage/uploads/

Set permissions to 755 (writable):
Right-click on /storage/uploads/
Properties/Permissions → 755
```

### Via SSH (if available)
```bash
ssh user@your-domain.com
cd public_html/RMS/RMS_New/storage
mkdir uploads
chmod 755 uploads
```

---

## Step 5: Initialize Database

### Option A: Using Installer
```
1. Visit: http://your-domain.com/RMS/RMS_New/installer
2. Follow 6-step installation wizard
3. Database tables will be created automatically
```

### Option B: Manual Database Update
```sql
-- Create tables using your database client (phpMyAdmin)

CREATE TABLE imports (
  id INT PRIMARY KEY AUTO_INCREMENT,
  type VARCHAR(50),
  filename VARCHAR(255),
  filepath VARCHAR(255),
  original_filename VARCHAR(255),
  file_size INT,
  row_count INT,
  success_count INT,
  error_count INT,
  status VARCHAR(50) DEFAULT 'pending',
  notes LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);

CREATE TABLE import_logs (
  id INT PRIMARY KEY AUTO_INCREMENT,
  import_id INT,
  row_number INT,
  status VARCHAR(50) DEFAULT 'error',
  message LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  FOREIGN KEY (import_id) REFERENCES imports(id)
);

CREATE TABLE registrations (
  id INT PRIMARY KEY AUTO_INCREMENT,
  event_id INT,
  event_category_id INT,
  athlete_name VARCHAR(255),
  athlete_email VARCHAR(255),
  athlete_phone VARCHAR(20),
  athlete_dob DATE,
  club_id INT,
  region_id INT,
  bib_number VARCHAR(50),
  status VARCHAR(50) DEFAULT 'pending',
  notes LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
  updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  FOREIGN KEY (event_id) REFERENCES events(id)
);

CREATE TABLE regions (
  id INT PRIMARY KEY AUTO_INCREMENT,
  name VARCHAR(255),
  code VARCHAR(50),
  description LONGTEXT,
  created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);
```

---

## Step 6: Verify Installation

### Test URLs (Visit in Browser)

```
✓ Public Access (No Login):
  http://your-domain.com/RMS/RMS_New/registrations/form?event_id=1
  → Should show beautiful registration form
  
✓ Admin Dashboard:
  http://your-domain.com/RMS/RMS_New/dashboard
  → Should show dashboard
  
✓ Import Form:
  http://your-domain.com/RMS/RMS_New/imports/form
  → Should show import form
  
✓ Registration List:
  http://your-domain.com/RMS/RMS_New/registrations/list
  → Should show admin registration list
```

---

## Checklist Before Go Live

```
□ All new PHP files uploaded
□ config/routes.php updated with 15 new routes
□ config/schema.php updated with 5 new tables
□ storage/uploads/ directory created (755 permissions)
□ Database tables created via installer or SQL
□ /registrations/form accessible
□ /imports/form accessible
□ File upload works
□ Registration submission works
□ Admin approve/reject works
□ CSV export works
□ No error messages in browser console
□ Mobile responsive design works
```

---

## Troubleshooting

### "File Not Found" Error
- ✓ Check file path is correct
- ✓ Verify files uploaded to right directory
- ✓ Check file permissions (644 for PHP files)

### "Database Connection Error"
- ✓ Run installer again
- ✓ Check config/database.php settings
- ✓ Verify database tables created

### "Registration form not showing"
- ✓ Check routes.php has registration routes
- ✓ Visit /registrations/form (not /registration/form)
- ✓ Check event_id parameter: ?event_id=1

### "Import not working"
- ✓ Check storage/uploads/ directory exists
- ✓ Verify directory is writable (755 permissions)
- ✓ Check config/routes.php has import routes

### "Admin list shows no registrations"
- ✓ Check registrations table exists in database
- ✓ Check registrations actually submitted
- ✓ Try accessing /registrations/form and submitting test

---

## File Upload Summary

**Total Files to Upload: 13 new files**

```
Controllers:        2 files (550 lines)
Models:            2 files (290 lines)
Services:          1 file (210 lines)
Templates:         8 files (2,700+ lines)
Config Updates:    2 files (routes.php + schema.php)

Total Size: ~150 KB
Upload Time: 1-2 minutes
Setup Time: 5-10 minutes
```

---

## Support

If issues occur:
1. Check DEPLOYMENT.md guide
2. Review IMPORT_REGISTRATION_GUIDE.md
3. Check browser console for errors
4. Review server error logs
5. Verify all files uploaded correctly

---

**Deployment Complete!** 🎉

Your system is now live and ready to use.

Access URLs:
- Public Registration: `/registrations/form?event_id=1`
- Admin Dashboard: `/dashboard`
- Import Form: `/imports/form`
- Admin Registrations: `/registrations/list`
