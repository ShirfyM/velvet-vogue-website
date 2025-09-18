# 🌐 InfinityFree Deployment Guide for Velvet Vogue

This guide will take you from your local XAMPP setup to a live website on InfinityFree. **Perfect for beginners!**

## 🌟 Why InfinityFree?

- **100% Free** hosting with PHP and MySQL
- **No ads** on your website
- **Easy cPanel** interface
- **Good performance** for small to medium sites
- **SSL certificate** included
- **No credit card** required

## 📋 What You'll Need

- Your Velvet Vogue project (in XAMPP)
- Internet connection
- Email address for InfinityFree account
- About 30-45 minutes

---

## 🚀 PART 1: PREPARING YOUR FILES

### Step 1: Export Your Database from XAMPP

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**
   - Both should show green "Running"

2. **Open phpMyAdmin**
   - Go to `http://localhost/phpmyadmin`
   - Click on `velvet_vogue_db` in the left sidebar

3. **Export Database**
   - Click the **"Export"** tab
   - Select **"Quick"** export method
   - Select **"SQL"** format
   - Click **"Go"**
   - Save as `velvet_vogue_database.sql` on your Desktop

### Step 2: Prepare Files for Upload

Create a folder on your Desktop called `VelvetVogue_Upload`:

1. **Copy All PHP Files**
   - Go to `C:\xampp\htdocs\Velvet_vogue_site`
   - Copy ALL `.php` files to `VelvetVogue_Upload`
   - **EXCEPT** `config.php` (we'll create a new one)

2. **Copy Folders**
   - Copy `assets` folder
   - Copy `uploads` folder
   - Copy `documentation` folder (optional)

3. **Copy Database File**
   - Copy `velvet_vogue_database.sql` to `VelvetVogue_Upload`

Your folder should look like:
```
VelvetVogue_Upload/
├── index.php
├── cart.php
├── checkout.php
├── collection.php
├── about.php
├── contact.php
├── login_register.php
├── account.php
├── wishlist.php
├── product.php
├── admin_dashboard.php
├── admin_order_details.php
├── assets/
├── uploads/
└── velvet_vogue_database.sql
```

---

## 🌐 PART 2: CREATING INFINITYFREE ACCOUNT

### Step 3: Sign Up for InfinityFree

1. **Go to InfinityFree**
   - Open browser
   - Go to `https://infinityfree.com`
   - Click **"Sign Up"** or **"Create Account"**

2. **Choose Your Domain**
   - Enter a subdomain name (e.g., `velvetvogue`)
   - Your site will be: `velvetvogue.infinityfreeapp.com`
   - Click **"Continue"**

3. **Complete Registration**
   - Enter your email address
   - Create a strong password
   - Complete any verification steps
   - **Save your login details!**

### Step 4: Access Your Control Panel

1. **Log In**
   - Go back to `https://infinityfree.com`
   - Click **"Client Area"** or **"Login"**
   - Enter your email and password

2. **Find Your Website**
   - You should see your website listed
   - Click **"Manage"** or **"Control Panel"**

---

## 📁 PART 3: UPLOADING YOUR FILES

### Step 5: Upload Files via File Manager

1. **Open File Manager**
   - In your control panel, find **"File Manager"**
   - Click on it
   - Navigate to the **"htdocs"** folder (this is your website root)

2. **Upload All Files**
   - Click **"Upload Files"** or drag and drop
   - Select ALL files from your `VelvetVogue_Upload` folder
   - Upload them to `htdocs`
   - **Important**: Upload folders as well

3. **Verify Upload**
   - Check that all files are in `htdocs`
   - Make sure folders are uploaded correctly

### Step 6: Create Database

1. **Go to MySQL Databases**
   - In control panel, find **"MySQL Databases"**
   - Click on it

2. **Create Database**
   - Database name: `velvet_vogue_db`
   - Click **"Create Database"**
   - **Note down the full database name** (it will have a prefix like `u1234567_velvet_vogue_db`)

3. **Create Database User**
   - Username: `velvet_user`
   - Password: Create a strong password
   - Click **"Create User"**
   - **Note down the full username** (it will have a prefix like `u1234567_velvet_user`)

4. **Add User to Database**
   - Select your user and database
   - Click **"Add User to Database"**
   - Give **"ALL PRIVILEGES"**
   - Click **"Make Changes"**

### Step 7: Create Config File

1. **Create New File**
   - In File Manager, right-click in `htdocs`
   - Select **"New File"**
   - Name it `config.php`

2. **Add Database Configuration**
   - Open the file for editing
   - Add this code (replace with your actual details):

```php
<?php
// InfinityFree Database Configuration
$host = 'localhost';
$user = 'your_full_username_here'; // Replace with your actual username
$password = 'your_password_here'; // Replace with your actual password
$database = 'your_full_database_name_here'; // Replace with your actual database name

mysqli_report(MYSQLI_REPORT_ERROR | MYSQLI_REPORT_STRICT);
$conn = new mysqli($host, $user, $password, $database);

if ($conn->connect_error) {
    error_log("Connection failed: " . $conn->connect_error);
    throw new Exception("Database connection error.");
}

// Set charset
$conn->set_charset("utf8mb4");

// Set timezone
date_default_timezone_set('UTC');
?>
```

3. **Save the File**
   - Click **"Save"** or **"Save Changes"**

---

## 🗄️ PART 4: IMPORTING YOUR DATABASE

### Step 8: Import Database via phpMyAdmin

1. **Open phpMyAdmin**
   - In control panel, find **"phpMyAdmin"**
   - Click on it

2. **Select Your Database**
   - Click on your database name in the left sidebar
   - It should be empty

3. **Import SQL File**
   - Click the **"Import"** tab
   - Click **"Choose File"**
   - Select your `velvet_vogue_database.sql` file
   - Click **"Go"**

4. **Verify Import**
   - Check that tables were created
   - Look for tables like `users`, `products`, `orders`

---

## 🔧 PART 5: CONFIGURING PERMISSIONS

### Step 9: Set File Permissions

1. **Set Uploads Folder Permissions**
   - In File Manager, right-click on `uploads` folder
   - Select **"Permissions"** or **"Change Permissions"**
   - Set to **"755"** or **"777"**
   - Click **"Change Permissions"**

2. **Apply to Subfolders**
   - Make sure to apply to all subfolders
   - This allows file uploads to work

---

## 🚀 PART 6: TESTING YOUR LIVE SITE

### Step 10: Test Your Website

1. **Visit Your Live Site**
   - Go to `https://yourdomain.infinityfreeapp.com`
   - Your site should load!

2. **Test Key Features**
   - ✅ Homepage loads
   - ✅ Products display
   - ✅ Can add items to cart
   - ✅ User registration works
   - ✅ Admin panel accessible

3. **Check for Errors**
   - Look for any error messages
   - Test all navigation links
   - Try uploading a product image

---

## 🎉 CONGRATULATIONS!

Your Velvet Vogue website is now live on InfinityFree! 

**Your website URL:** `https://yourdomain.infinityfreeapp.com`

---

## 🆘 TROUBLESHOOTING

### Common Issues and Solutions

**Issue: "Database connection error"**
- Check your `config.php` file
- Verify database credentials
- Make sure database user has all privileges

**Issue: "Images not loading"**
- Check file paths in your code
- Verify images are uploaded correctly
- Check file permissions

**Issue: "File upload not working"**
- Set uploads folder permissions to 755 or 777
- Check folder exists and is writable

**Issue: "500 Internal Server Error"**
- Check error logs in control panel
- Verify PHP syntax
- Check file permissions

### InfinityFree Specific Tips

1. **File Upload Limits**
   - Max file size: 10MB
   - Max execution time: 30 seconds

2. **Database Limits**
   - Max database size: 100MB
   - Max tables: 50

3. **Performance**
   - Site may be slower during peak hours
   - Consider upgrading for better performance

---

## 📈 NEXT STEPS

Once your site is working:

1. **Share Your Website**
   - Send the URL to friends and family
   - Test it on different devices

2. **Add More Content**
   - Upload more products
   - Add more pages
   - Customize the design

3. **Consider Upgrading**
   - InfinityFree is great for testing
   - For production, consider paid hosting
   - Better performance and support

---

## 🎯 QUICK REFERENCE

**Your Live Website:** `https://yourdomain.infinityfreeapp.com`
**Control Panel:** InfinityFree.com → Client Area → Manage
**File Manager:** Control Panel → File Manager
**Database:** Control Panel → MySQL Databases
**phpMyAdmin:** Control Panel → phpMyAdmin

---

## 🔒 SECURITY TIPS

1. **Change default passwords** immediately
2. **Use strong database passwords**
3. **Enable SSL** (usually automatic)
4. **Regular backups** of your database
5. **Keep files updated**

---

**Ready to start?** Let's begin with Part 1 - preparing your files!
