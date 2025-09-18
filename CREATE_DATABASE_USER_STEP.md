# 👤 How to Create Database User in InfinityFree

## 🎯 **Step 3: Create Database User**

This is a crucial step for your website to work. Here's exactly how to do it:

### **Step 3a: Find the User Creation Section**

1. **In your MySQL Database area**, look for:
   - **"Add New User"** button
   - **"Create User"** button
   - **"Add User"** button
   - Or a **"+"** icon next to users

2. **Click on it** to open the user creation form

### **Step 3b: Fill in User Details**

You'll see a form with these fields:

#### **Username Field:**
- **Enter:** `velvet_user`
- **Important:** The system will add a prefix automatically
- **Final username will be:** `u1234567_velvet_user` (numbers will be different)

#### **Password Field:**
- **Create a strong password** (at least 8 characters)
- **Use a mix of:** letters, numbers, and symbols
- **Example:** `MyVelvet2024!` or `VelvetVogue123#`
- **Write it down** - you'll need it for config.php

#### **Confirm Password Field:**
- **Enter the same password** again

### **Step 3c: Submit the Form**

1. **Click "Create User"** or **"Add User"**
2. **Wait for confirmation** that the user was created
3. **Note down the full username** (with prefix)
4. **Note down the password** you created

## 📝 **What You Should See**

After creating the user, you should see:
- ✅ **Success message** confirming user creation
- ✅ **Full username** displayed (with prefix)
- ✅ **User listed** in your users section

## 🔍 **Example of What It Looks Like**

**Before creation:**
- Username: `velvet_user`
- Password: `MyVelvet2024!`

**After creation:**
- Full Username: `u1234567_velvet_user`
- Password: `MyVelvet2024!` (same as you entered)

## ⚠️ **Important Notes**

1. **The prefix is automatic** - don't try to add it yourself
2. **Write down both** the full username and password
3. **You'll need these** for your config.php file
4. **Don't lose the password** - you can't recover it easily

## 🆘 **If You Can't Find the User Creation Button**

### **Look for these alternatives:**
- **"Add New User"** link
- **"Create User"** link
- **"+"** icon next to "Users"
- **"New User"** button

### **If you still can't find it:**
- Look for **"phpMyAdmin"** in your control panel
- You can create users from there too
- Or contact InfinityFree support

## 📋 **Quick Checklist**

- [ ] Found the user creation form
- [ ] Entered username: `velvet_user`
- [ ] Created a strong password
- [ ] Confirmed the password
- [ ] Clicked "Create User"
- [ ] Noted down the full username (with prefix)
- [ ] Noted down the password

## 🎯 **Next Step**

After creating the user, you'll need to:
1. **Add the user to your database** (Step 4)
2. **Give the user all privileges**
3. **Use these credentials** in your config.php file

---

**Need help with the next step?** Let me know when you've created the user and I'll guide you through adding it to your database!
