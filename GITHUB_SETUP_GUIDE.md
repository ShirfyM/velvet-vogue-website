# 🚀 GitHub Setup Guide for Velvet Vogue

## 📋 **Prerequisites**

1. **Install Git** (if not already installed)
   - Download from: https://git-scm.com/download/win
   - Install with default settings
   - Restart your computer

2. **Create GitHub Account** (if you don't have one)
   - Go to: https://github.com
   - Sign up for a free account

## 🔧 **Step 1: Initialize Git Repository**

### **Open Command Prompt/PowerShell in your project folder:**
```bash
cd C:\xampp\htdocs\Velvet_vogue_site
git init
```

### **Configure Git (first time only):**
```bash
git config --global user.name "Your Name"
git config --global user.email "your.email@example.com"
```

## 📁 **Step 2: Add Files to Git**

### **Add all files:**
```bash
git add .
```

### **Make your first commit:**
```bash
git commit -m "Initial commit: Velvet Vogue e-commerce website"
```

## 🌐 **Step 3: Create GitHub Repository**

1. **Go to GitHub.com**
2. **Click "New Repository"** (green button)
3. **Repository name:** `velvet-vogue-website`
4. **Description:** `E-commerce fashion website built with PHP and MySQL`
5. **Make it Public** (so others can see it)
6. **DON'T** initialize with README (we already have one)
7. **Click "Create Repository"**

## 🔗 **Step 4: Connect Local Repository to GitHub**

### **Copy the commands from GitHub:**
After creating the repository, GitHub will show you commands like:
```bash
git remote add origin https://github.com/yourusername/velvet-vogue-website.git
git branch -M main
git push -u origin main
```

### **Run these commands in your project folder**

## ✅ **Step 5: Verify Upload**

1. **Refresh your GitHub repository page**
2. **You should see all your files**
3. **Check that README.md displays properly**

## 🔄 **Step 6: Future Updates**

### **When you make changes:**
```bash
git add .
git commit -m "Description of changes"
git push
```

## 📋 **What Gets Uploaded**

### **✅ Files that WILL be uploaded:**
- All PHP files
- Assets folder (CSS, JS, images)
- README.md
- .gitignore
- Documentation files

### **❌ Files that WON'T be uploaded:**
- config.php (contains sensitive database info)
- uploads/ folder (large files)
- Database files (.sql)
- Test files

## 🎯 **Benefits of GitHub**

1. **Version Control**: Track changes to your code
2. **Backup**: Your code is safely stored online
3. **Portfolio**: Show your work to potential employers
4. **Collaboration**: Others can contribute to your project
5. **Documentation**: Professional README and documentation

## 🚨 **Important Security Notes**

- **Never upload `config.php`** (contains database passwords)
- **Never upload database files** (contains sensitive data)
- **Use .gitignore** to exclude sensitive files
- **Keep your database credentials private**

## 📱 **Your Repository Will Look Like:**

```
velvet-vogue-website/
├── README.md
├── .gitignore
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
│   ├── css/
│   ├── js/
│   └── images/
└── documentation/
```

## 🎉 **You're Done!**

Your Velvet Vogue website is now on GitHub! You can:
- Share the repository link
- Show it in your portfolio
- Collaborate with others
- Track changes over time

**Repository URL will be:** `https://github.com/yourusername/velvet-vogue-website`
