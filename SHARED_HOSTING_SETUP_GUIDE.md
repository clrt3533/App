# Billar Application - Shared Hosting Setup Guide (cPanel)

## 🚀 Quick Overview
This Laravel 8 Billar application has been successfully built and is ready for shared hosting deployment via cPanel.

**Key Features:**
- ✅ Invoicing & Payment Management
- ✅ MSG91 SMS Integration
- ✅ Multiple Payment Gateways (Stripe, PayPal, Razorpay)
- ✅ Google Calendar Integration
- ✅ Vue.js 2 Frontend (Pre-compiled)

---

## 📋 Pre-Deployment Checklist

### 1. **Files Already Compiled ✅**
- Vue.js frontend compiled via `npm run prod`
- CSS/JS assets minified and ready
- Production-ready build completed

### 2. **MSG91 SMS Integration Details ✅**
**Current Configuration:**
- **Service**: MSG91 (control.msg91.com)
- **Auth Key**: `238708A7BIRMsept5ba3c275` ⚠️ *Move to environment*
- **Sender ID**: `SAIPKR`
- **Templates**: Onboarding, Booking Confirmation, Reminders, Estimates

---

## 🔧 Step-by-Step Deployment Guide

### **Step 1: Prepare Files for Upload**

#### A. Create Deployment Structure
```
your-domain.com/
├── public_html/           # ← This becomes your Laravel public folder
│   ├── index.php
│   ├── .htaccess
│   ├── css/
│   ├── js/
│   ├── fonts/
│   └── images/
└── app/                   # ← Laravel app files (outside public_html)
    ├── src/
    ├── vendor/
    ├── .env
    └── storage/ (with proper permissions)
```

#### B. Modify Entry Point for Shared Hosting
You need to update the `index.php` file to point to the correct paths:

**Current index.php** (needs modification):
```php
require __DIR__.'/src/vendor/autoload.php';
$app = require_once __DIR__.'/src/bootstrap/app.php';
```

**Updated for Shared Hosting:**
```php
require __DIR__.'/../app/src/vendor/autoload.php';
$app = require_once __DIR__.'/../app/src/bootstrap/app.php';
```

### **Step 2: cPanel Setup Process**

#### A. **Create Database**
1. **cPanel → MySQL Databases**
2. **Create Database**: `yourusername_billar`
3. **Create User**: `yourusername_billar`
4. **Add User to Database** with ALL PRIVILEGES

#### B. **Upload Files via File Manager**
1. **Upload Structure**:
   ```
   File Manager:
   ├── public_html/
   │   ├── index.php (modified)
   │   ├── .htaccess
   │   ├── css/ (from compiled assets)
   │   ├── js/ (from compiled assets)
   │   ├── fonts/
   │   └── mix-manifest.json
   └── app/
       └── src/ (entire Laravel src folder)
   ```

2. **Extract ZIP Files**: Upload and extract in correct locations

#### C. **Set Folder Permissions**
```bash
# Via cPanel File Manager or SSH
chmod 755 app/src/bootstrap/cache/
chmod 755 app/src/storage/
chmod -R 775 app/src/storage/
chmod -R 775 app/src/bootstrap/cache/
```

### **Step 3: Environment Configuration**

#### A. Create `.env` File
Create `app/src/.env` with your hosting details:

```env
APP_NAME="Billar Apps"
APP_ENV=production
APP_KEY=base64:YOUR_APP_KEY_HERE
APP_DEBUG=false
APP_URL=https://yourdomain.com

LOG_CHANNEL=stack
LOG_LEVEL=error

# Database Configuration
DB_CONNECTION=mysql
DB_HOST=localhost
DB_PORT=3306
DB_DATABASE=yourusername_billar
DB_USERNAME=yourusername_billar
DB_PASSWORD=your_database_password

# Mail Configuration (Choose your provider)
MAIL_MAILER=smtp
MAIL_HOST=mail.yourdomain.com
MAIL_PORT=587
MAIL_USERNAME=your_email@yourdomain.com
MAIL_PASSWORD=your_email_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"

# MSG91 SMS Configuration
MSG91_AUTH_KEY=238708A7BIRMsept5ba3c275
MSG91_SENDER_ID=SAIPKR

# Payment Gateway Configuration
PAYMENT_METHOD=stripe

# Stripe
STRIPE_KEY=pk_test_your_stripe_key
STRIPE_SECRET=sk_test_your_stripe_secret

# PayPal
PAYPAL_CLIENT_ID=your_paypal_client_id
PAYPAL_CLIENT_SECRET=your_paypal_secret
PAYMENT_MODE=sandbox

# Razorpay
RAZORPAY_KEY=your_razorpay_key
RAZORPAY_SECRET=your_razorpay_secret

# Google reCAPTCHA
CAPTCHA_KEY=your_recaptcha_site_key
CAPTCHA_SECRET=your_recaptcha_secret_key

# Session & Cache
SESSION_DRIVER=file
SESSION_LIFETIME=120
CACHE_DRIVER=file
QUEUE_CONNECTION=sync

# File Storage
FILESYSTEM_DRIVER=local

# Google Calendar (Optional)
GOOGLE_CALENDAR_ID=your_calendar_id
```

#### B. Generate Application Key
**Via SSH** (if available):
```bash
cd app/src
php artisan key:generate
```

**OR manually**: Generate a base64 encoded 32-character string for APP_KEY

### **Step 4: Database Setup**

#### A. **Run Migrations** (via SSH or cPanel Terminal)
```bash
cd app/src
php artisan migrate
php artisan db:seed
```

#### B. **If No SSH Access**: Import SQL manually
1. Export database from your local environment
2. Import via cPanel → phpMyAdmin

### **Step 5: Configure .htaccess**

#### A. **Root .htaccess** (`public_html/.htaccess`):
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    
    # Handle Laravel Routes
    RewriteCond %{REQUEST_FILENAME} !-f
    RewriteCond %{REQUEST_FILENAME} !-d
    RewriteRule ^(.*)$ index.php [QSA,L]
</IfModule>

# Security Headers
<IfModule mod_headers.c>
    Header always set X-Content-Type-Options nosniff
    Header always set X-Frame-Options DENY
    Header always set X-XSS-Protection "1; mode=block"
</IfModule>

# Gzip Compression
<IfModule mod_deflate.c>
    AddOutputFilterByType DEFLATE text/css
    AddOutputFilterByType DEFLATE application/javascript
    AddOutputFilterByType DEFLATE text/html
</IfModule>
```

#### B. **Laravel App .htaccess** (`app/src/.htaccess`):
```apache
<Files .env>
    Order allow,deny
    Deny from all
</Files>

<Files composer.json>
    Order allow,deny
    Deny from all
</Files>
```

### **Step 6: Storage Symlink**

#### A. **Create Storage Symlink**
```bash
# Via SSH
cd app/src
php artisan storage:link
```

#### B. **Manual Method** (if no SSH):
1. **cPanel File Manager**
2. **Create Symbolic Link**:
   - **From**: `app/src/storage/app/public`
   - **To**: `public_html/storage`

### **Step 7: Final Configuration**

#### A. **Update File Paths in Configuration**
Edit `app/src/config/filesystems.php` if needed to ensure correct paths.

#### B. **Clear Caches** (if SSH available):
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

#### C. **Test Email Configuration**
Update `app/src/config/mail.php` with your hosting provider's SMTP settings.

---

## 🔐 Security Enhancements for Production

### 1. **Environment Security**
```env
APP_ENV=production
APP_DEBUG=false
LOG_LEVEL=error
```

### 2. **Move Sensitive Data to Environment**
Update `src/app/Providers/AppServiceProvider.php`:

```php
// Replace hardcoded MSG91 key with environment variable
'authkey' => env('MSG91_AUTH_KEY', '238708A7BIRMsept5ba3c275'),
```

### 3. **File Permissions**
```bash
# Secure permissions
find app/src -type f -exec chmod 644 {} \;
find app/src -type d -exec chmod 755 {} \;
chmod -R 775 app/src/storage/
chmod -R 775 app/src/bootstrap/cache/
```

---

## 📱 MSG91 SMS Setup Guide

### **Current Implementation Status: ✅ READY**

**Features Available:**
- ✅ **Onboarding SMS**: Welcome messages for new users
- ✅ **Booking Confirmation**: Invoice/order confirmations
- ✅ **Payment Reminders**: Automated reminder system
- ✅ **Estimate Notifications**: Quote generation alerts

### **To Update MSG91 Configuration:**

1. **Update Environment Variables**:
```env
MSG91_AUTH_KEY=your_new_auth_key
MSG91_SENDER_ID=your_sender_id
```

2. **Update Template IDs** in `src/app/Services/App/SmsSetting/Message91Services.php`:
```php
// Update template IDs for your MSG91 account
"template_id" => "your_template_id_here"
```

3. **Test SMS Functionality**:
```bash
# Via application settings or API endpoint
POST /admin/test-sms
{
    "phone": "+1234567890"
}
```

---

## 🚀 Post-Deployment Testing

### 1. **Basic Functionality Test**
- [ ] Application loads correctly
- [ ] Login system works
- [ ] Database connection successful
- [ ] File uploads work

### 2. **Payment Gateway Tests**
- [ ] Stripe test transactions
- [ ] PayPal sandbox mode
- [ ] Razorpay test mode

### 3. **SMS Integration Test**
- [ ] Test MSG91 connectivity
- [ ] Send test SMS via application

### 4. **Email Functionality**
- [ ] Test email sending
- [ ] Invoice email delivery
- [ ] User registration emails

---

## 🔧 Troubleshooting Common Issues

### **Issue 1: 500 Internal Server Error**
**Solution:**
1. Check `.env` file exists and is properly configured
2. Verify file permissions on `storage/` and `bootstrap/cache/`
3. Check error logs in cPanel

### **Issue 2: CSS/JS Not Loading**
**Solution:**
1. Verify `mix-manifest.json` is in `public_html/`
2. Check asset paths in compiled files
3. Ensure all static files uploaded correctly

### **Issue 3: Database Connection Failed**
**Solution:**
1. Verify database credentials in `.env`
2. Check database hostname (usually `localhost`)
3. Ensure database user has proper privileges

### **Issue 4: MSG91 SMS Not Sending**
**Solution:**
1. Verify MSG91 auth key and sender ID
2. Check phone number formatting
3. Test API connectivity via curl

---

## 📞 Support & Resources

### **Application Features:**
- **Invoicing**: Create, send, track invoices
- **Payments**: Multiple gateway support
- **Reporting**: Comprehensive business reports
- **Client Management**: Complete CRM functionality
- **SMS Notifications**: Automated MSG91 integration

### **Technical Stack:**
- **Backend**: Laravel 8 + PHP 7.4+
- **Frontend**: Vue.js 2 + Bootstrap 4
- **Database**: MySQL
- **SMS**: MSG91 API
- **Payments**: Stripe, PayPal, Razorpay

### **Quick Commands (SSH):**
```bash
# Clear application cache
php artisan cache:clear

# Update application
php artisan config:cache
php artisan route:cache

# Check application status
php artisan about
```

---

**🎉 Your Billar application is now ready for shared hosting deployment!**

*Remember to backup your database and files regularly, and keep your environment variables secure.*