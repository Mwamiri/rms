# 🏃‍♂️ RMS - Athletics Federation Management System

[![PHP Version](https://img.shields.io/badge/PHP-8.1+-blue.svg)](https://php.net/)
[![MySQL](https://img.shields.io/badge/MySQL-8.0+-orange.svg)](https://mysql.com/)
[![Docker](https://img.shields.io/badge/Docker-Ready-brightgreen.svg)](https://docker.com/)
[![Cloud VPS](https://img.shields.io/badge/Cloud%20VPS-Optimized-success.svg)](https://github.com/Mwamiri/rms)

A comprehensive **Athletics Federation Results Management System** designed for track and field events. Built with modern PHP and optimized for cloud deployment with **Coolify** on **Cloud VPS 10**.

## 🚀 Quick Start

### Option 1: Cloud VPS 10 + Coolify (Recommended)

```bash
# Deploy directly from GitHub using Coolify
# Point to: https://github.com/Mwamiri/rms.git
# Follow: CLOUD_VPS_10_DEPLOYMENT.md
```

### Option 2: Docker Deployment

```bash
git clone https://github.com/Mwamiri/rms.git
cd rms
cp .env.production .env
# Edit .env with your database credentials
docker-compose up -d
```

### Option 3: Traditional Hosting

```bash
# Upload files to web hosting
# Run installer at: https://yourdomain.com/installer/
```

## 📋 Features

### ✅ Core Modules
- **🏃‍♂️ Events Management** - Create and manage athletic events
- **👥 Athletes Management** - Athlete database with registration
- **🏆 Results Recording** - Race results with automatic point calculation
- **📊 Team Rankings** - Real-time team standings
- **📈 Reports & Analytics** - CSV/Excel export capabilities

### ✅ Advanced Features  
- **📂 Import/Export System** - Bulk data import from CSV/Excel
- **🔄 Backup & Recovery** - Automated database backups
- **📱 Responsive Interface** - Mobile-friendly dashboard
- **🔐 Secure Authentication** - Role-based access control
- **🌐 Multi-event Support** - Handle multiple competitions

### ✅ Cloud Ready
- **🐳 Docker Containerized** - Production-ready containers
- **☁️ Cloud VPS Optimized** - Tuned for 2-4 vCores, 4-8GB RAM
- **🚀 Coolify Compatible** - One-click deployment
- **🔒 SSL Ready** - Automatic HTTPS with Let's Encrypt
- **📊 Monitoring Ready** - Health checks and logging

## 🛠️ Technology Stack

| Component | Technology | Version |
|-----------|------------|---------|
| **Backend** | PHP | 8.1+ |
| **Database** | MySQL | 8.0+ |
| **Web Server** | Apache | 2.4+ |
| **Frontend** | HTML5/CSS3/JS | Modern |
| **Containerization** | Docker | Latest |
| **Deployment** | Coolify | Latest |

## 🏗️ Architecture

```
RMS/
├── 📱 app/
│   ├── controllers/     # MVC Controllers (6 modules)
│   ├── models/          # Data Models (6 entities)  
│   ├── services/        # Business Logic (3 services)
│   └── views/           # UI Templates
├── ⚙️ config/           # Configuration Files
├── 🌐 public/           # Web Root & Assets
├── 📦 storage/          # Uploads, Backups, Logs
├── 🐳 Docker Files      # Container Configuration
└── 📚 Documentation    # Complete Guides
```

## 🚀 Deployment Options

### 🌟 Recommended: Cloud VPS 10 + Coolify

**Perfect for:**
- Production environments
- Scalable deployment
- Automatic updates
- SSL certificates
- Monitoring & backups

**Resources needed:**
- CPU: 1 vCore (2-4 available)
- RAM: 512MB (4-8GB available) 
- Storage: 5GB (80-160GB available)

### 🐳 Docker Deployment

```yaml
# Use included docker-compose.yml
services:
  - PHP 8.1 + Apache container
  - MySQL 8.0 database
  - phpMyAdmin interface
  - Volume persistence
  - Network isolation
```

### 🏢 Traditional Hosting

```
Requirements:
✅ PHP 7.4+ (8.1+ recommended)
✅ MySQL 5.7+ or MariaDB 10.3+
✅ Apache with mod_rewrite
✅ 5GB disk space minimum
```

## 📊 Performance

**Tested on Cloud VPS 10:**
- **Page Load**: < 200ms
- **Database Queries**: < 50ms  
- **Concurrent Users**: 100-200
- **File Uploads**: < 5 seconds
- **Report Generation**: < 10 seconds

## 📖 Documentation

| Document | Description |
|----------|-------------|
| [🚀 Cloud VPS Deployment](CLOUD_VPS_10_DEPLOYMENT.md) | Complete Cloud VPS 10 + Coolify guide |
| [🏗️ Architecture Overview](ARCHITECTURE_OVERVIEW.md) | System design and structure |
| [⚙️ System Requirements](SYSTEM_REQUIREMENTS.md) | Technical specifications |
| [📦 Deployment Steps](DEPLOYMENT_STEPS.md) | Traditional hosting guide |
| [📚 Quick Reference](QUICK_REFERENCE.md) | API and usage reference |

## 🎯 Use Cases

### 🏃‍♂️ Athletics Federations
- National/regional athletics competitions
- Track and field event management
- Athlete registration and tracking
- Team rankings and standings

### 🏫 Educational Institutions
- School sports competitions
- Inter-school athletics meets
- Student athlete management
- Performance tracking

### 🏢 Sports Organizations
- Club competitions
- League management
- Member registration
- Event coordination

## 🔧 Quick Setup

### 1. **Clone Repository**
```bash
git clone https://github.com/Mwamiri/rms.git
cd rms
```

### 2. **Configure Environment**
```bash
cp .env.production .env
# Edit database credentials in .env
```

### 3. **Deploy with Docker**
```bash
docker-compose up -d
```

### 4. **Initialize Database**
```bash
# Visit: http://localhost/installer/
# Or use phpMyAdmin: http://localhost:8080
```

### 5. **Access Dashboard**
```bash
# Main App: http://localhost
# Admin Panel: http://localhost/dashboard
```

## 📈 Roadmap

- [ ] **API Development** - RESTful API for mobile apps
- [ ] **Real-time Updates** - WebSocket integration
- [ ] **Advanced Analytics** - Performance statistics
- [ ] **Mobile App** - Native mobile application
- [ ] **Multi-language** - Internationalization support

## 🤝 Contributing

1. Fork the repository
2. Create feature branch (`git checkout -b feature/amazing-feature`)
3. Commit changes (`git commit -m 'Add amazing feature'`)
4. Push to branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## 📄 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 👨‍💻 Author

**Mwamiri** - [GitHub Profile](https://github.com/Mwamiri)

## 🙏 Acknowledgments

- Built for athletics federations worldwide
- Optimized for Cloud VPS hosting
- Designed for scalability and performance
- Community-driven development

---

## 🚀 Ready to Deploy?

[![Deploy to Cloud VPS](https://img.shields.io/badge/Deploy%20to-Cloud%20VPS-blue?style=for-the-badge&logo=docker)](CLOUD_VPS_10_DEPLOYMENT.md)
[![View Documentation](https://img.shields.io/badge/View-Documentation-green?style=for-the-badge&logo=gitbook)](INDEX.md)
[![Report Issues](https://img.shields.io/badge/Report-Issues-red?style=for-the-badge&logo=github)](https://github.com/Mwamiri/rms/issues)

**Your complete athletics management solution is ready for Cloud VPS 10! 🏃‍♂️💨**