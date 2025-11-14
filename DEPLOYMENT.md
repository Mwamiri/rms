# RMS (Athletics Federation Management System) - Deployment Guide

## System Architecture

The RMS is a **WordPress-style standalone PHP application** built without framework dependencies, specifically optimized for shared hosting environments.

### Key Features
- ✅ **Zero Composer Dependency** - Works on any shared hosting
- ✅ **Database-Driven** - Uses native MySQL (mysqli)
- ✅ **MVC Architecture** - Models, Controllers, Views, Services
- ✅ **Event Management** - Create, manage, and track athletic events
- ✅ **Athlete Management** - Maintain athlete database
- ✅ **Results Recording** - Record race results with automatic point calculation
- ✅ **Team Rankings** - Calculate and display team standings
- ✅ **Reports** - Generate CSV/Excel reports
- ✅ **Backup System** - Automated database backups
- ✅ **Responsive Dashboard** - Web-based management interface

## Technology Stack

| Component | Requirement | Version |
|-----------|-------------|---------|
| PHP | Minimum | 7.4+ |
| MySQL | Minimum | 5.7 |
| Server | Apache/LiteSpeed | Any |
| Disk Space | Minimum | ~5MB |
| Dependencies | Framework | None |

## Pre-Deployment Checklist

Before deploying to Hostafrica, ensure:

- [ ] PHP 7.4+ available on hosting (verify in cPanel)
- [ ] MySQL database created (via cPanel)
- [ ] Domain pointed to document root
- [ ] File upload permissions enabled (644 for files, 755 for directories)
- [ ] `.htaccess` support enabled (or PHP routing fallback)
- [ ] SSL certificate installed (optional but recommended)

## Deployment Steps

### Step 1: Prepare Files Locally

```powershell
# All files are in: C:\wamp64\www\RMS\RMS_New\
# Structure:
# RMS_New/
#   ├── app/
#   │   ├── controllers/     (5 controllers)
#   │   ├── models/          (4 models)
#   │   ├── services/        (2 services)
#   │   ├── helpers/         (helpers)
#   │   ├── views/           (templates - to be created)
#   │   ├── Database.php     (core class)
#   │   └── Router.php       (core class)
#   ├── config/
#   │   ├── app.php          (settings)
#   │   ├── database.php     (db config)
#   │   ├── schema.php       (db schema)
#   │   └── routes.php       (route definitions)
#   ├── public/
#   │   ├── index.php        (entry point)
#   │   ├── installer/       (installation wizard)
#   │   ├── .htaccess        (rewrite rules)
#   │   ├── css/
#   │   ├── js/
#   │   └── images/
#   ├── storage/
#   │   ├── backups/         (backup files)
#   │   ├── logs/            (log files)
#   │   └── uploads/         (user uploads)
#   ├── .env.example         (environment template)
#   └── README.md
```

### Step 2: Upload to Hostafrica

1. **Connect to Hosting via FTP/SFTP**
   - Host: Your Hostafrica FTP server
   - Username: Your hosting account username
   - Password: Your hosting account password
   - Port: 21 (FTP) or 22 (SFTP)

2. **Upload All Files to Document Root**
   - Navigate to: `/home/username/yourdomain.co.ke/`
   - Upload entire `RMS_New/` folder contents
   - **Important**: Files go directly in domain root, NOT in a subfolder

3. **Verify Upload**
   ```
   Correct Structure:
   /home/username/yourdomain.co.ke/
   ├── app/              ✓
   ├── config/           ✓
   ├── public/           ✓
   ├── storage/          ✓
   ├── .env.example      ✓
   └── README.md         ✓
   
   Incorrect Structure:
   /home/username/yourdomain.co.ke/
   └── RMS_New/
       ├── app/          ✗ (nested too deep)
       ├── config/
       └── ...
   ```

### Step 3: Set File Permissions

Via cPanel File Manager or FTP client:

```
Files:       644 (rw-r--r--)
Directories: 755 (rwxr-xr-x)
storage/:    777 (rwxrwxrwx)  - writable for backups/logs
```

Commands (via SSH if available):
```bash
chmod 755 app config public storage
chmod 755 storage/backups storage/logs storage/uploads
chmod 644 public/index.php public/.htaccess
```

### Step 4: Run Installation Wizard

1. **Visit Installer**
   - Open: `https://yourdomain.co.ke/installer/`
   - Or: `https://yourdomain.co.ke/public/installer/`

2. **Complete 6-Step Installation**

   **Step 1: Requirements Check**
   - ✓ PHP 7.4+ (LiteSpeed)
   - ✓ MySQLi extension
   - ✓ File permissions

   **Step 2: Database Configuration**
   - Database Host: `localhost` (or provided by Hostafrica)
   - Database Name: (from cPanel MySQL)
   - Database User: (from cPanel MySQL)
   - Database Password: (from cPanel MySQL)
   - Click: "Test Connection"

   **Step 3: Organization Details**
   - Organization Name: Your Athletics Federation Name
   - Organization Email: Your email
   - Timezone: Your timezone (e.g., Africa/Nairobi)

   **Step 4: Admin User**
   - Admin Name: Your name
   - Admin Email: Your email
   - Admin Password: Strong password
   - Confirm Password: Repeat password

   **Step 5: System Settings**
   - System Name: (auto-filled)
   - Default Currency: KES (Kenya Shilling)
   - Email Notifications: Yes/No
   - Enable Reports: Yes

   **Step 6: Complete Installation**
   - Creates `.env` file
   - Creates `.install.lock` file
   - Initializes database tables
   - Ready to use!

3. **Login to Dashboard**
   - URL: `https://yourdomain.co.ke/dashboard`
   - Username: Your admin email
   - Password: Your admin password (from Step 4)

## Troubleshooting Deployment

### Issue: Installer Not Loading
**Symptom**: Blank page or 404 error on `/installer/`

**Solution**:
1. Check .htaccess is uploaded to `public/`
2. Verify mod_rewrite enabled (cPanel → Apache Modules)
3. Try: `https://yourdomain.co.ke/public/installer/index.php`
4. Check file permissions: `chmod 644 public/index.php`

### Issue: Database Connection Failed
**Symptom**: "Cannot connect to database" error on Step 2

**Solution**:
1. Verify database credentials in cPanel
2. Check database host (usually `localhost` for shared hosting)
3. Ensure database user has all permissions
4. Try hostname variations:
   - `localhost`
   - `127.0.0.1`
   - `mysql.yourdomain.co.ke`

### Issue: 404 or Blank Page After Login
**Symptom**: Routes not working, white screen

**Solution**:
1. Check .htaccess upload location: must be in `public/`
2. Content of `public/.htaccess`:
   ```apache
   <IfModule mod_rewrite.c>
       RewriteEngine On
       RewriteBase /
       RewriteRule ^index\.php$ - [L]
       RewriteCond %{REQUEST_FILENAME} !-f
       RewriteCond %{REQUEST_FILENAME} !-d
       RewriteRule . /index.php [L]
   </IfModule>
   ```
3. If LiteSpeed server: .htaccess ignored, PHP routing used automatically
4. Clear browser cache (Ctrl+Shift+Del)

### Issue: "Permission Denied" on Storage
**Symptom**: Cannot create backups or upload files

**Solution**:
```bash
chmod 777 storage/
chmod 777 storage/backups
chmod 777 storage/logs
chmod 777 storage/uploads
```

### Issue: PHP Version Error
**Symptom**: "Requires PHP 7.4+" but running older version

**Solution**:
1. Check Hostafrica's available PHP versions
2. Switch in cPanel: Multi PHP Manager → Select 7.4+ or 8.3
3. Restart Apache

## Post-Deployment Configuration

### 1. Create Additional Users

```sql
-- Create user account in database (optional)
INSERT INTO users (name, email, password, role, is_active)
VALUES ('Coach Name', 'coach@example.com', PASSWORD('password'), 'coordinator', 1);
```

### 2. Add Teams

**Via Dashboard**:
1. Navigate: Teams → Add Team
2. Enter:
   - Team Name
   - Team Code (e.g., "NK01")
   - Region
   - Contact details
3. Click: Save

**Via CSV Import**:
```csv
name,code,region
Nairobi School 1,NS01,Nairobi
Mombasa School 1,MS01,Mombasa
Kisumu School 1,KS01,Kisumu
```

### 3. Import Athletes

**Via Dashboard**:
1. Navigate: Athletes → Import
2. Upload CSV file with format:
```csv
name,bib_number,gender,district,team_id
John Kipchoge,101,M,Eldoret,1
Jane Kiplagat,102,F,Eldoret,1
```

### 4. Create Event

**Via Dashboard**:
1. Navigate: Events → Create Event
2. Enter:
   - Event Name
   - Event Date
   - Location
   - Event Type
3. Add Event Categories (Races)
4. Click: Save

### 5. Record Results

**Via Dashboard**:
1. Navigate: Results → Record Results
2. Select Event Category
3. Enter each athlete's result
4. Click: Save Result

### 6. Calculate Rankings

**Automatic**:
- Rankings calculate when results finalized
- System uses scoring: 1st=10, 2nd=8, 3rd=6, 4th=5, 5th=4, 6th=3, 7th=2, 8th=1

**Manual**:
1. Navigate: Rankings → Calculate
2. Select Event
3. Click: Calculate Rankings

## Performance Optimization

### 1. Enable Caching
Edit `public/.htaccess`:
```apache
# Cache static assets for 1 year
<FilesMatch "\.(jpg|jpeg|png|gif|ico|css|js)$">
    Header set Cache-Control "max-age=31536000, public"
</FilesMatch>
```

### 2. Database Optimization
```sql
-- Add indexes for faster queries
ALTER TABLE athletes ADD INDEX idx_team_id (team_id);
ALTER TABLE athletes ADD INDEX idx_active (is_active);
ALTER TABLE results ADD INDEX idx_athlete (athlete_id);
ALTER TABLE results ADD INDEX idx_category (event_category_id);
```

### 3. Regular Backups
- Automatic backup: Daily (if auto-backup enabled)
- Manual backup: Via Dashboard → System → Backups
- Download and store externally

## Security Best Practices

### 1. Change Admin Password
- First login: Change password immediately
- Use strong password (12+ chars, mixed case, numbers, symbols)

### 2. Restrict Uploads
- Only CSV files for athlete import
- Validate file types in `/app/controllers/AthletesController.php`

### 3. Backup Sensitive Data
- Regular backups stored in `storage/backups/`
- Download and store securely
- Test restore procedures

### 4. Monitor Logs
- Check `storage/logs/` for errors
- Review periodically for security issues

### 5. SSL Certificate
- Ensure HTTPS enabled on domain
- Hostafrica provides free SSL (AutoSSL in cPanel)
- Update config: `APP_URL = https://yourdomain.co.ke`

## Maintenance Tasks

### Weekly
- [ ] Review recent results and rankings
- [ ] Verify all athletes properly registered
- [ ] Check for any error messages in logs

### Monthly
- [ ] Download and verify backup
- [ ] Review athlete performance trends
- [ ] Update team information as needed

### Quarterly
- [ ] Full system audit
- [ ] Database optimization
- [ ] Security updates to PHP (if available)

## File Structure Reference

```
RMS_New/
├── app/
│   ├── controllers/
│   │   ├── DashboardController.php     (Dashboard statistics)
│   │   ├── EventsController.php        (Event management)
│   │   ├── AthletesController.php      (Athlete management)
│   │   ├── ResultsController.php       (Results recording)
│   │   └── RankingsController.php      (Rankings calculation)
│   ├── models/
│   │   ├── Event.php                   (Event operations)
│   │   ├── Athlete.php                 (Athlete operations)
│   │   ├── Result.php                  (Result operations & scoring)
│   │   └── TeamRanking.php             (Ranking calculations)
│   ├── services/
│   │   ├── ReportService.php           (Report generation)
│   │   └── BackupService.php           (Database backups)
│   ├── helpers/
│   │   └── ViewHelper.php              (Formatting utilities)
│   ├── views/
│   │   ├── dashboard/                  (Dashboard templates)
│   │   ├── events/                     (Event templates)
│   │   ├── athletes/                   (Athlete templates)
│   │   ├── results/                    (Results templates)
│   │   └── rankings/                   (Rankings templates)
│   ├── Database.php                    (Core MySQL wrapper)
│   └── Router.php                      (Core URL router)
├── config/
│   ├── app.php                         (Application settings)
│   ├── database.php                    (Database configuration)
│   ├── schema.php                      (Database schema creation)
│   └── routes.php                      (Route definitions)
├── public/
│   ├── index.php                       (Application entry point)
│   ├── .htaccess                       (Apache rewrite rules)
│   ├── installer/
│   │   └── index.php                   (Installation wizard)
│   ├── css/                            (Stylesheets)
│   ├── js/                             (JavaScript)
│   └── images/                         (Images)
├── storage/
│   ├── backups/                        (Database backups)
│   ├── logs/                           (Application logs)
│   └── uploads/                        (Imported files)
├── .env.example                        (Environment template)
├── README.md                           (Quick start guide)
├── DEPLOYMENT.md                       (This file)
└── BUILD_SUMMARY.md                    (Architecture overview)
```

## Support & Documentation

- **Quick Start**: See `README.md`
- **Architecture**: See `BUILD_SUMMARY.md`
- **Database Schema**: See `config/schema.php`
- **API Routes**: See `config/routes.php`

## Redeployment (If Needed)

If you need to redeploy or reinstall:

1. **Remove Installation Lock**:
   ```bash
   rm storage/.install.lock
   ```

2. **Delete .env File**:
   ```bash
   rm .env
   ```

3. **Visit Installer Again**:
   - `https://yourdomain.co.ke/installer/`
   - Run installation wizard again
   - New database tables will be created

4. **Restore from Backup** (if needed):
   - Dashboard → System → Backups
   - Select backup file
   - Click: Restore

## Conclusion

Your Athletics Federation Management System is now deployed and ready to use! 

**Next Steps**:
1. Login to dashboard
2. Configure organization details
3. Add teams
4. Import athletes
5. Create first event
6. Record results
7. Generate rankings

For detailed usage, see `README.md`.
