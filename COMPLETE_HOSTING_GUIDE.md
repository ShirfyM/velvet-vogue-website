# 🌐 Complete Hosting Guide: From XAMPP to Live Website

This guide will take you from your local XAMPP development environment to a live website on InfinityFree. **No prior hosting experience required!**

## 🎯 **UPDATED FOR INFINITYFREE HOSTING**

Since you chose InfinityFree, I've created a specific guide: `INFINITYFREE_DEPLOYMENT_GUIDE.md` - follow that instead of this general guide for the best experience!

## 📋 What You'll Need

- Your Velvet Vogue project (already in XAMPP)
- Internet connection
- Email address for 000webhost account
- About 30-45 minutes

## 🎯 What We'll Accomplish

By the end of this guide, you'll have:
- ✅ A live website accessible to anyone on the internet
- ✅ Your database working on the live server
- ✅ All your products and functionality working
- ✅ A professional web address

---

## 📚 PART 1: PREPARING YOUR FILES

### Step 1: Check Your Current Setup

First, let's make sure everything is working locally:

1. **Start XAMPP**
   - Open XAMPP Control Panel
   - Start **Apache** and **MySQL**
   - Both should show green "Running" status

2. **Test Your Local Site**
   - Open browser
   - Go to `http://localhost/Velvet_vogue_site`
   - Make sure your site loads correctly
   - Test a few features (add to cart, etc.)

### Step 2: Export Your Database

This is the most important step - we need to save all your data:

1. **Open phpMyAdmin**
   - Go to `http://localhost/phpmyadmin`
   - Click on `velvet_vogue_db` in the left sidebar

2. **Export Database**
   - Click the **"Export"** tab at the top
   - Select **"Quick"** export method
   - Select **"SQL"** format
   - Click **"Go"** button
   - Save the file as `velvet_vogue_database.sql` on your Desktop

3. **Verify Export**
   - Check that the file was saved (should be several KB in size)
   - Open it in Notepad to make sure it contains data

### Step 3: Prepare Files for Upload

Create a new folder on your Desktop called `Website_Files`:

1. **Copy All PHP Files**
   - Go to `C:\xampp\htdocs\Velvet_vogue_site`
   - Copy ALL `.php` files to `Website_Files`
   - **EXCEPT** `config.php` (we'll create a new one)

2. **Copy Assets Folder**
   - Copy the entire `assets` folder to `Website_Files`

3. **Copy Uploads Folder**
   - Copy the entire `uploads` folder to `Website_Files`

4. **Copy Database File**
   - Copy `velvet_vogue_database.sql` to `Website_Files`

Your `Website_Files` folder should look like this:
```
Website_Files/
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
├── assets/
│   ├── css/
│   ├── js/
│   └── images/
├── uploads/
│   └── products/
└── velvet_vogue_database.sql
```

---
## 🌐 PART 2: CREATING INFINITYFREE ACCOUNT

### Step 4: Sign Up for InfinityFree

1. **Go to InfinityFree**
   - Open your browser
   - Go to [https://infinityfree.com](https://infinityfree.com)
   - Click **"Sign Up Now"**

2. **Create Your Account**
   - Enter your email address and create a password
   - Complete the registration and verify your email if required
   - **Important**: Save your login details!

### Step 5: Create a New Hosting Account

1. **Log In**
   - Go to [https://app.infinityfree.net/login](https://app.infinityfree.net/login)
   - Enter your email and password

2. **Create a Hosting Account**
   - Click **"Create Account"**
   - Choose a free subdomain (e.g., `velvetvogue.epizy.com`)
   - Follow the prompts to set up your hosting account

3. **Access Your Control Panel**
   - Once your account is created, click **"Manage"** next to your new account
   - Click **"Control Panel"** to open the cPanel (VistaPanel)

---

## 🌐 PART 2: CREATING 000WEBHOST ACCOUNT

### Step 4: Sign Up for 000webhost

1. **Go to 000webhost**
   - Open browser
   - Go to `https://000webhost.com`
   - Click **"Create Free Website"**

2. **Choose Your Domain**
   - Enter a subdomain name (e.g., `velvetvogue`)
   - Your site will be: `velvetvogue.000webhostapp.com`
   - Click **"Create"**

3. **Complete Registration**
   - Enter your email address
   - Create a password
   - Complete any verification steps
   - **Important**: Save your login details!

### Step 5: Access Your Control Panel

1. **Log In**
   - Go back to 000webhost.com
   - Click **"Login"**
   - Enter your email and password

2. **Find Your Website**
   - You should see your website listed
   - Click **"Manage Website"**
   - Click **"Open Control Panel"**

---

## 📁 PART 3: UPLOADING YOUR FILES

### Step 6: Upload Files via File Manager

1. **Open File Manager**
   - In your control panel, find **"File Manager"**
   - Click on it
   - Navigate to the **"public_html"** folder

2. **Upload All Files**
   - Click **"Upload Files"** button
   - Select ALL files from your `Website_Files` folder
   - Upload them to `public_html`
   - **Important**: Upload folders as well (assets, uploads)

3. **Verify Upload**
   - Check that all files are in `public_html`
   - Make sure folders are uploaded correctly

### Step 7: Create Database

1. **Go to MySQL Databases**
   - In control panel, find **"MySQL Databases"**
   - Click on it

2. **Create Database**
   - Database name: `velvet_vogue_db`
   - Click **"Create Database"**
   - **Note down the full database name** (it will have a prefix)

3. **Create Database User**
   - Username: `velvet_user`
   - Password: Create a strong password
   - Click **"Create User"**
   - **Note down the full username** (it will have a prefix)

4. **Add User to Database**
   - Select your user and database
   - Click **"Add"**
   - Give **"ALL PRIVILEGES"**
   - Click **"Make Changes"**

### Step 8: Create Config File

1. **Create New File**
   - In File Manager, right-click in `public_html`
   - Select **"New File"**
   - Name it `config.php`

2. **Add Database Configuration**
   - Open the file for editing
   - Add this code (replace with your actual details):

```php
<?php
// 000webhost Database Configuration
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

### Step 9: Import Database via phpMyAdmin

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

### Step 10: Set File Permissions

1. **Set Uploads Folder Permissions**
   - In File Manager, right-click on `uploads` folder
   - Select **"Permissions"**
   - Set to **"755"** or **"777"**
   - Click **"Change Permissions"**

2. **Apply to Subfolders**
   - Make sure to apply to all subfolders
   - This allows file uploads to work

---

## 🚀 PART 6: TESTING YOUR LIVE SITE

### Step 11: Test Your Website

1. **Visit Your Live Site**
   - Go to `https://yourdomain.000webhostapp.com`
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

Your Velvet Vogue website is now live on the internet! 

**Your website URL:** `https://yourdomain.000webhostapp.com`

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
- Set uploads folder permissions to 777
- Check folder exists and is writable

**Issue: "500 Internal Server Error"**
- Check error logs in control panel
- Verify PHP syntax
- Check file permissions

### Getting Help

1. **Check Error Logs**
   - In control panel, look for "Error Logs"
   - This shows what's going wrong

2. **000webhost Support**
   - Use their support system
   - Check their knowledge base

3. **Double-Check Everything**
   - Verify all steps were followed
   - Check file uploads
   - Verify database import

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
   - 000webhost is great for testing
   - For production, consider paid hosting
   - Better performance and support

---

## 🎯 QUICK REFERENCE

**Your Live Website:** `https://yourdomain.000webhostapp.com`
**Control Panel:** 000webhost.com → Login → Manage Website
**File Manager:** Control Panel → File Manager
**Database:** Control Panel → MySQL Databases
**phpMyAdmin:** Control Panel → phpMyAdmin

---

**Ready to start?** Let's begin with Part 1 - preparing your files!
