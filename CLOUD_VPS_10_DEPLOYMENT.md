# 🚀 Cloud VPS 10 + Coolify Deployment Guide

## ✅ Pre-Deployment Checklist

### Cloud VPS 10 Requirements Met
- [x] **CPU**: 2-4 vCores (Need: 1 vCore) ✅ **Excellent**
- [x] **RAM**: 4-8 GB (Need: 512MB) ✅ **Excellent** 
- [x] **Storage**: 80-160 GB SSD (Need: 5GB) ✅ **Excellent**
- [x] **OS**: Ubuntu 20.04+ ✅ **Perfect**
- [x] **Docker Support** ✅ **Required for Coolify**

### Files Prepared
- [x] `docker-compose.yml` - Container orchestration
- [x] `Dockerfile` - PHP 8.1 + Apache container
- [x] `.env.production` - Environment variables template
- [x] `package.json` - Project metadata

---

## 🔧 Step 1: Prepare Git Repository

```powershell
# In your local RMS_New directory
cd C:\wamp64\www\RMS_New

# Initialize Git (if not done)
git init
git add .
git commit -m "Initial RMS system ready for Cloud VPS 10"

# Push to GitHub/GitLab
git remote add origin https://github.com/yourusername/athletics-rms.git
git branch -M main
git push -u origin main
```

---

## 🌐 Step 2: Coolify Setup on Cloud VPS 10

### Install Coolify on Your VPS

```bash
# SSH into your Cloud VPS 10
ssh root@your-vps-ip

# Install Coolify (one command)
curl -fsSL https://cdn.coollabs.io/coolify/install.sh | bash

# Access Coolify dashboard
# http://your-vps-ip:8000
```

### Configure Domain (Optional but Recommended)
```bash
# Point your domain to VPS IP
# Update DNS A record: your-domain.com → your-vps-ip
# SSL will be automatically configured by Coolify
```

---

## 📦 Step 3: Deploy RMS in Coolify

### Create New Application

1. **Login to Coolify Dashboard**
   ```
   URL: http://your-vps-ip:8000
   Create admin account
   ```

2. **Add New Project**
   ```
   Name: Athletics RMS
   Description: Results Management System
   ```

3. **Create Application**
   ```
   Type: Docker Compose
   Name: rms-system
   Git Repository: https://github.com/yourusername/athletics-rms.git
   Branch: main
   ```

### Configure Environment Variables

```env
# Set these in Coolify Environment tab:
APP_URL=https://your-domain.com
DB_PASSWORD=your_secure_password_123
DB_ROOT_PASSWORD=your_root_password_456
APP_KEY=base64:$(openssl rand -base64 32)
```

### Set Build Configuration

```yaml
# Coolify will auto-detect docker-compose.yml
# Ports: 80 (HTTP), 443 (HTTPS), 8080 (phpMyAdmin)
# Domain: your-domain.com
# SSL: Enable Let's Encrypt
```

---

## 🗃️ Step 4: Database Setup

### Automatic MySQL Container
```
Coolify creates MySQL 8.0 container automatically
Database: rms_db
User: rms_user
Password: (set in environment)
```

### Initialize Database
```bash
# After deployment, run installer
https://your-domain.com/installer/

# Or manually via phpMyAdmin
https://your-domain.com:8080/
```

---

## 🚀 Step 5: Deploy & Verify

### Deploy Application
```
1. Click "Deploy" in Coolify
2. Watch build logs
3. Deployment completes in 2-5 minutes
```

### Verification Checklist
```
□ Application loads: https://your-domain.com
□ Database connected: Check dashboard
□ File uploads work: Test import feature
□ SSL certificate: Green padlock
□ Performance: Check response times
□ Backups working: Check storage/backups/
```

---

## 📊 Performance Optimization for Cloud VPS 10

### Container Resource Limits
```yaml
# Already set in docker-compose.yml
services:
  app:
    deploy:
      resources:
        limits:
          memory: 2G        # Safe limit for 4-8GB VPS
          cpus: '1.5'       # Use 1.5 of 2-4 available cores
```

### MySQL Optimization
```env
# In .env file (already configured)
MYSQL_INNODB_BUFFER_POOL_SIZE=1G  # 1GB for good performance
MYSQL_MAX_CONNECTIONS=200         # Plenty for your needs
```

### Apache Optimization
```apache
# Already configured in Dockerfile
MaxRequestWorkers 200
ThreadsPerChild 25
ServerLimit 8
```

---

## 🔐 Security Configuration

### SSL/HTTPS (Automatic)
```
Coolify handles:
✅ Let's Encrypt SSL certificates
✅ Automatic renewal
✅ HTTP to HTTPS redirect
```

### Firewall (Recommended)
```bash
# On your Cloud VPS 10
ufw allow 22    # SSH
ufw allow 80    # HTTP (redirects to HTTPS)
ufw allow 443   # HTTPS
ufw allow 8000  # Coolify dashboard
ufw enable
```

---

## 📱 Monitoring & Maintenance

### Coolify Built-in Monitoring
```
✅ Resource usage graphs
✅ Container health checks  
✅ Log aggregation
✅ Backup scheduling
✅ Update notifications
```

### Manual Health Checks
```bash
# Check container status
docker ps

# Check resource usage
htop

# Check disk space (you have 80-160GB)
df -h
```

---

## 🎯 Expected Performance on Cloud VPS 10

### Response Times
```
Page Load: < 200ms       (Excellent)
Database Queries: < 50ms (Very Fast)
File Uploads: < 5s       (Fast)
Report Generation: < 10s (Good)
```

### Capacity
```
Concurrent Users: 100-200    (More than sufficient)
Events: 1000+               (No problem)
Athletes: 10,000+           (Easy)
Results: 100,000+           (Handled well)
File Storage: 80-160GB      (Massive headroom)
```

---

## 🚨 Troubleshooting

### Common Issues & Solutions

**Container won't start:**
```bash
# Check logs in Coolify
# Usually environment variables issue
```

**Database connection failed:**
```bash
# Verify environment variables
# Check MySQL container status
```

**SSL certificate issues:**
```bash
# Ensure domain points to VPS IP
# Check Coolify SSL configuration
```

**Performance issues:**
```bash
# Check resource usage
# Verify container limits
# Review MySQL configuration
```

---

## ✅ Success Indicators

You'll know deployment is successful when:

- [x] Website loads at https://your-domain.com
- [x] Dashboard shows all features working
- [x] File uploads process correctly
- [x] Database operations are fast
- [x] SSL certificate is valid
- [x] Backup system creates files
- [x] Reports generate successfully
- [x] System handles multiple users

## 📞 Need Help?

Your Cloud VPS 10 has more than enough resources for this system. The deployment should be smooth and performance excellent!

**Resources Available:**
- 2-4 vCores (using ~25%)
- 4-8 GB RAM (using ~512MB)  
- 80-160 GB storage (using ~5GB)
- High-speed SSD storage
- Unlimited bandwidth tier

**You're all set for success! 🚀**