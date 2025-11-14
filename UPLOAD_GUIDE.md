# 📂 Complete File & Folder Structure to Upload

## Option 1: Upload Complete RMS_New Folder

**Simplest Method**: Upload the entire `RMS_New` folder to your server

```
Your Server Path: /public_html/
Upload This:     RMS_New/

Result on Server:
/public_html/RMS_New/
├── app/
├── config/
├── core/
├── storage/
├── public/
├── installer/
└── all other files
```

---

## Option 2: Upload Only New/Updated Files (Selective)

If you already have the system uploaded, only upload NEW files:

### New Files (13 Total)

```
UPLOAD THESE 13 FILES:

1. app/controllers/ImportController.php
2. app/controllers/RegistrationController.php
3. app/models/Import.php
4. app/models/Registration.php
5. app/services/FileImportService.php
6. app/views/imports/form.php
7. app/views/imports/preview.php
8. app/views/imports/result.php
9. app/views/imports/index.php
10. app/views/registrations/public_form.php
11. app/views/registrations/confirm.php
12. app/views/registrations/list.php
13. app/views/registrations/view.php

UPDATED FILES (Edit these):
1. config/routes.php
2. config/schema.php
```

### Folder Structure Needed

```
Make sure these folders exist on your server:

□ app/
  ├── controllers/
  ├── models/
  ├── services/
  └── views/
      ├── imports/          ← Create if missing
      └── registrations/    ← Create if missing

□ config/
□ core/
□ storage/
  ├── uploads/             ← Create if missing
  ├── backups/
  └── logs/

□ public/
□ installer/
```

---

## Complete Directory Tree

```
RMS_New/
│
├── app/
│   ├── controllers/
│   │   ├── DashboardController.php          (existing)
│   │   ├── EventsController.php             (existing)
│   │   ├── AthletesController.php           (existing)
│   │   ├── ResultsController.php            (existing)
│   │   ├── RankingsController.php           (existing)
│   │   ├── ImportController.php             ✓ NEW
│   │   └── RegistrationController.php       ✓ NEW
│   │
│   ├── models/
│   │   ├── Event.php                        (existing)
│   │   ├── Athlete.php                      (existing)
│   │   ├── Result.php                       (existing)
│   │   ├── TeamRanking.php                  (existing)
│   │   ├── Import.php                       ✓ NEW
│   │   └── Registration.php                 ✓ NEW
│   │
│   ├── services/
│   │   ├── ReportService.php                (existing)
│   │   ├── BackupService.php                (existing)
│   │   └── FileImportService.php            ✓ NEW
│   │
│   └── views/
│       ├── imports/                         ✓ NEW FOLDER
│       │   ├── form.php                     ✓ NEW
│       │   ├── preview.php                  ✓ NEW
│       │   ├── result.php                   ✓ NEW
│       │   └── index.php                    ✓ NEW
│       │
│       ├── registrations/                   ✓ NEW FOLDER
│       │   ├── public_form.php              ✓ NEW
│       │   ├── confirm.php                  ✓ NEW
│       │   ├── list.php                     ✓ NEW
│       │   └── view.php                     ✓ NEW
│       │
│       ├── events/
│       │   └── (existing views)
│       │
│       ├── athletes/
│       │   └── (existing views)
│       │
│       ├── results/
│       │   └── (existing views)
│       │
│       ├── dashboard/
│       │   └── (existing views)
│       │
│       └── rankings/
│           └── (existing views)
│
├── config/
│   ├── app.php                              (existing)
│   ├── database.php                         (existing)
│   ├── schema.php                           ⚠ UPDATE
│   └── routes.php                           ⚠ UPDATE
│
├── core/
│   ├── Database.php                         (existing)
│   └── Router.php                           (existing)
│
├── storage/
│   ├── uploads/                             ✓ CREATE IF MISSING
│   ├── backups/
│   └── logs/
│
├── public/
│   ├── index.php                            (existing)
│   └── .htaccess                            (existing)
│
├── installer/
│   └── index.php                            (existing)
│
├── .htaccess                                (existing)
├── .gitignore                               (existing)
├── README.md                                (existing)
├── DEPLOYMENT.md                            (existing)
├── MODULES_SUMMARY.md                       (existing)
├── QUICK_REFERENCE.md                       (existing)
├── INDEX.md                                 (existing)
│
└── DOCUMENTATION FILES (NEW):
    ├── PHASE_2_COMPLETION.md                ✓ NEW
    ├── IMPORT_REGISTRATION_GUIDE.md         ✓ NEW
    ├── SESSION_SUMMARY.md                   ✓ NEW
    ├── ARCHITECTURE_OVERVIEW.md             ✓ NEW
    ├── COMPLETION_CHECKLIST.md              ✓ NEW
    ├── README_PHASE2.md                     ✓ NEW
    └── DEPLOYMENT_STEPS.md                  ✓ NEW
```

---

## Upload Method 1: Complete Folder (Recommended)

### Using FTP (Filezilla)

1. **Download entire RMS_New folder** from your local machine
2. **Connect to your server** via FTP
3. **Navigate to**: `/public_html/` (or your hosting root)
4. **Delete old RMS_New** folder (if exists)
5. **Upload entire RMS_New** folder
6. **Wait for upload to complete** (5-10 minutes)
7. **Run installer** at `http://your-site.com/RMS/RMS_New/installer`

### Using cPanel

1. **Login to cPanel**: `https://your-domain.com:2083`
2. **File Manager**
3. **Navigate to**: `/public_html/RMS/`
4. **Right-click** → Delete old `RMS_New` folder
5. **Right-click** → Upload
6. **Select entire `RMS_New` folder** from your computer
7. **Wait for upload**
8. **Run installer**

---

## Upload Method 2: Selective Files Only

### If you already have the system, upload ONLY new files:

**Step 1: Create New Folders**
```
Create via FTP or cPanel:
□ /public_html/RMS/RMS_New/app/views/imports/
□ /public_html/RMS/RMS_New/app/views/registrations/
□ /public_html/RMS/RMS_New/storage/uploads/  (set 755 permissions)
```

**Step 2: Upload 13 New Files**
```
Controllers (2):
- app/controllers/ImportController.php
- app/controllers/RegistrationController.php

Models (2):
- app/models/Import.php
- app/models/Registration.php

Service (1):
- app/services/FileImportService.php

Views (8):
- app/views/imports/form.php
- app/views/imports/preview.php
- app/views/imports/result.php
- app/views/imports/index.php
- app/views/registrations/public_form.php
- app/views/registrations/confirm.php
- app/views/registrations/list.php
- app/views/registrations/view.php
```

**Step 3: Update 2 Config Files**
```
Download, edit, upload back:

1. config/routes.php
   → Add 15 new routes at the end

2. config/schema.php
   → Add 5 new table definitions
```

---

## Exact File Locations on Your Server

```
Your Server Should Look Like:

www.hostingserver.com/
├── public_html/
│   └── RMS/
│       └── RMS_New/              ← Your main folder
│           ├── app/
│           ├── config/
│           ├── core/
│           ├── storage/
│           ├── public/
│           ├── installer/
│           └── ... (all other files)
```

### Access URLs After Upload

```
http://your-domain.com/RMS/RMS_New/dashboard
http://your-domain.com/RMS/RMS_New/registrations/form
http://your-domain.com/RMS/RMS_New/imports/form
http://your-domain.com/RMS/RMS_New/installer
```

---

## Storage Permissions

After uploading, set these permissions via FTP (Right-click → Permissions):

```
Folders:
□ storage/          → 755
□ storage/uploads/  → 755 (must be writable!)
□ storage/backups/  → 755
□ storage/logs/     → 755

Files:
□ All .php files    → 644
□ .htaccess         → 644
□ config files      → 644
```

---

## Database Setup After Upload

**Option A: Auto Setup (Easiest)**
```
1. Visit: http://your-domain.com/RMS/RMS_New/installer
2. Follow 6-step wizard
3. Database tables created automatically
```

**Option B: Manual Setup**
```
1. Login to cPanel → phpMyAdmin
2. Select your database
3. Go to SQL tab
4. Copy SQL from DEPLOYMENT_STEPS.md
5. Paste and execute
```

---

## Quick Checklist

```
Before Go Live:
☐ Complete RMS_New folder uploaded (or all 13 new files)
☐ config/routes.php updated with 15 routes
☐ config/schema.php updated with 5 tables
☐ storage/uploads/ directory exists (755 permissions)
☐ Installer run successfully
☐ All 5 database tables created
☐ Public registration form accessible
☐ Admin dashboard accessible
☐ File upload works
☐ No error messages

Test URLs:
☐ http://your-site.com/RMS/RMS_New/registrations/form?event_id=1
☐ http://your-site.com/RMS/RMS_New/imports/form
☐ http://your-site.com/RMS/RMS_New/registrations/list
☐ http://your-site.com/RMS/RMS_New/dashboard
```

---

## Total Upload Size

```
Complete RMS_New folder:  ~500 KB
Just new files (13):      ~150 KB

Upload Time:
- Complete folder:        5-10 minutes
- New files only:         1-2 minutes
```

---

## Troubleshooting Upload

**If upload fails:**
- Break into smaller uploads
- Upload one folder at a time
- Use FTP instead of cPanel web interface
- Increase upload timeout in your FTP client

**If files seem corrupted:**
- Set FTP transfer mode to AUTO
- Try ASCII mode for .txt, BINARY for .php
- Re-download and compare file size

**If permissions issues:**
- Right-click file/folder in FTP
- Set permissions to 755 (folders) or 644 (files)
- Or use SSH: `chmod 755 storage/uploads/`

---

## Support Files

All documentation included in RMS_New folder:

1. **DEPLOYMENT_STEPS.md** - Detailed setup guide
2. **DEPLOYMENT.md** - Original deployment guide
3. **IMPORT_REGISTRATION_GUIDE.md** - Usage guide
4. **README_PHASE2.md** - Project overview
5. **README.md** - Original documentation

---

**Upload Now & You're Done!** 🚀

Just upload the complete `RMS_New` folder or the 13 new files + update 2 config files.

Questions? Check the documentation files!
