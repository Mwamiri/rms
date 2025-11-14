# Athletics Federation Management System - Laravel-Free Edition

A WordPress-style Athletics Federation Event Management System that requires **NO framework, NO Composer**, and works on **ALL shared hosting**.

## ✨ Features

- ✅ Event Management
- ✅ Real-time Tallying & Ranking
- ✅ Athlete Management
- ✅ Results Recording
- ✅ PDF/Excel Reports
- ✅ Backup & Restore
- ✅ WordPress-style Installer
- ✅ Mobile-Responsive UI
- ✅ No Composer Required
- ✅ Works on Shared Hosting

## 📁 Folder Structure

```
RMS_New/
├── public/                  # Web root
│   ├── .htaccess           # Apache routing
│   ├── index.php           # Main entry point
│   ├── installer/          # Installation wizard
│   ├── css/                # Stylesheets
│   ├── js/                 # JavaScript
│   └── images/             # Images
├── app/                    # Application code
│   ├── Database.php        # Database class
│   ├── Router.php          # URL router
│   ├── controllers/        # Page controllers
│   ├── models/             # Data models
│   ├── views/              # Page templates
│   └── services/           # Business logic
├── config/                 # Configuration
│   ├── app.php            # App config
│   └── database.php       # DB config
├── storage/               # Runtime files
│   ├── logs/              # Application logs
│   ├── backups/           # Backup files
│   └── uploads/           # User uploads
├── .env.example           # Environment template
└── README.md              # This file
```

## 🚀 Installation

### Step 1: Upload to Hosting

Upload everything to your domain root (`/home/happylif/rms.happylife.co.ke/`)

```
public/  → upload to domain root
app/     → upload at same level
config/  → upload at same level
storage/ → upload at same level
```

### Step 2: Set Permissions

```bash
# Make directories writable
chmod 755 storage/
chmod 755 storage/logs
chmod 755 storage/backups
chmod 755 storage/uploads
```

### Step 3: Run Installer

Visit: `https://rms.happylife.co.ke/installer/`

Follow the 6-step wizard:
1. System Requirements Check
2. Database Configuration
3. Organization Details
4. Admin User Setup
5. System Settings
6. Complete Installation

### Step 4: Login

Visit: `https://rms.happylife.co.ke/`

Login with admin credentials from Step 4.

## 🎯 Why This Architecture?

- **No Composer** - Works without PHP package manager
- **No Framework** - Simple, direct PHP code
- **Fast** - Minimal overhead, optimized for shared hosting
- **Safe** - Built-in SQL injection prevention
- **Familiar** - WordPress-style structure developers understand
- **Scalable** - Easy to add features without framework constraints

## 📊 Key Files

| File | Purpose |
|------|---------|
| `public/index.php` | Entry point, initializes app |
| `app/Database.php` | MySQL database wrapper |
| `app/Router.php` | URL routing and dispatch |
| `public/installer/index.php` | Installation wizard |
| `config/app.php` | Application settings |
| `config/database.php` | Database configuration |

## 🔧 Development

### Add a New Page

1. Create controller: `app/controllers/EventsController.php`
2. Create view: `app/views/events/index.php`
3. Visit: `https://yourdomain.com/events/`

### Add a Database Table

1. Create migration file
2. Run migrations
3. Create model in `app/models/`

## 🔐 Security

- SQL injection prevention via parameterized queries
- Password hashing with bcrypt
- CSRF token protection
- XSS prevention
- Security headers in .htaccess
- Sensitive file protection

## 📱 Mobile Support

Fully responsive design works on:
- iPhone/iPad
- Android devices
- Tablets
- Desktops

## 🆘 Troubleshooting

### 500 Error
- Check `.htaccess` is uploaded
- Verify file permissions (755 for folders)
- Check PHP error logs in cPanel

### Database Connection Failed
- Verify credentials in installer
- Check database user permissions
- Ensure database host is correct (usually `localhost`)

### Installer Loop
- Delete `.env` file
- Check `storage/` is writable
- Ensure install lock can be created

## 📞 Support

For issues:
1. Check cPanel error logs
2. Review `storage/logs/` for app logs
3. Verify database connectivity
4. Check file permissions

## 📄 License

Open source - Modify and distribute freely

## 🎉 Ready to Deploy!

This system is **production-ready** and can be deployed to **any shared hosting** without Composer or framework dependencies.

**Total setup time: ~5 minutes**

Upload → Installer → Done! 🚀
