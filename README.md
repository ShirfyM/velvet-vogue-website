# Velvet Vogue - E-commerce Fashion Website

A modern, responsive e-commerce website built with PHP and MySQL for a fashion retail business. Velvet Vogue offers a complete online shopping experience with user authentication, product management, shopping cart, wishlist, and admin dashboard.

## 🌟 Features

### Customer Features
- **User Authentication**: Secure login/registration system with CSRF protection
- **Product Catalog**: Browse products with filtering by category and gender
- **Product Details**: Detailed product pages with images and descriptions
- **Shopping Cart**: Add/remove items, update quantities, and view cart total
- **Wishlist**: Save favorite products for later purchase
- **Order Management**: Place orders and track order history
- **Responsive Design**: Mobile-friendly interface using Tailwind CSS
- **Search & Filter**: Find products by name, category, or gender

### Admin Features
- **Admin Dashboard**: Complete admin panel for managing the store
- **Product Management**: Add, edit, and delete products with image uploads
- **Order Management**: View and manage customer orders
- **User Management**: Monitor user accounts and activities
- **Inventory Control**: Track product stock and availability
- **Secure File Upload**: Validated image uploads with security checks

### Technical Features
- **CSRF Protection**: Cross-site request forgery protection
- **Session Management**: Secure user session handling
- **Database Security**: Prepared statements and input validation
- **File Upload Security**: MIME type validation and size limits
- **Responsive UI**: Modern design with Tailwind CSS
- **Error Handling**: Comprehensive error handling and user feedback

## 🛠️ Technology Stack

- **Backend**: PHP 8.2+
- **Database**: MySQL/MariaDB
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Tailwind CSS
- **Server**: Apache (XAMPP)
- **Security**: CSRF tokens, input validation, secure file uploads

## 📋 Prerequisites

- XAMPP (Apache, MySQL, PHP)
- PHP 8.2 or higher
- MySQL 5.7 or higher
- Web browser with JavaScript enabled

## 🚀 Installation

1. **Clone the repository**
   ```bash
   git clone https://github.com/yourusername/velvet-vogue-site.git
   cd velvet-vogue-site
   ```

2. **Setup XAMPP**
   - Download and install [XAMPP](https://www.apachefriends.org/)
   - Start Apache and MySQL services

3. **Database Setup**
   - Open phpMyAdmin (http://localhost/phpmyadmin)
   - Create a new database named `velvet_vogue_db`
   - Import the `velvet_vogue_database.sql` file

4. **Configure Database**
   - Update `config.php` with your database credentials if needed:
   ```php
   $host = 'localhost';
   $user = 'root';
   $password = '';
   $database = 'velvet_vogue_db';
   ```

5. **File Permissions**
   - Ensure the `uploads/products/` directory has write permissions
   - Set proper permissions for the project folder

6. **Access the Website**
   - Place the project folder in `C:\xampp\htdocs\`
   - Open your browser and navigate to `http://localhost/Velvet_vogue_site/`

## 📁 Project Structure

```
velvet_vogue_site/
├── assets/
│   ├── css/
│   │   └── style.css          # Custom CSS styles
│   ├── images/                # Static images
│   ├── js/
│   │   └── script.js          # JavaScript functionality
│   └── uploads/
│       └── products/          # Product images
├── admin_dashboard.php        # Admin panel
├── cart.php                   # Shopping cart page
├── checkout.php              # Checkout process
├── collection.php            # Product catalog
├── config.php                # Database configuration
├── contact.php               # Contact page
├── index.php                 # Homepage
├── login_register.php        # Authentication
├── product.php               # Product details
├── wishlist.php              # User wishlist
├── velvet_vogue_database.sql # Database schema
└── README.md                 # This file
```

## 🗄️ Database Schema

The database includes the following tables:
- **users**: User accounts and authentication
- **products**: Product catalog with images and details
- **orders**: Customer orders and order status
- **order_items**: Individual items within orders
- **message**: Contact form submissions

## 🔐 Security Features

- **CSRF Protection**: All forms protected with CSRF tokens
- **Input Validation**: Server-side validation for all user inputs
- **SQL Injection Prevention**: Prepared statements for database queries
- **File Upload Security**: MIME type validation and file size limits
- **Session Security**: Secure session management
- **Password Hashing**: Secure password storage

## 🎨 Design Features

- **Modern UI**: Clean, professional design with Tailwind CSS
- **Responsive Layout**: Mobile-first responsive design
- **Interactive Elements**: Smooth animations and hover effects
- **Product Gallery**: High-quality product images with zoom functionality
- **User Experience**: Intuitive navigation and user-friendly interface

## 📱 Pages Overview

- **Homepage** (`index.php`): Featured products and site introduction
- **Collection** (`collection.php`): Complete product catalog with filters
- **Product Details** (`product.php`): Individual product pages
- **Cart** (`cart.php`): Shopping cart management
- **Checkout** (`checkout.php`): Order placement process
- **Wishlist** (`wishlist.php`): Saved products
- **Account** (`account.php`): User account management
- **Admin Dashboard** (`admin_dashboard.php`): Store management panel

## 🚀 Usage

### For Customers
1. Browse products on the homepage or collection page
2. Click on products to view details
3. Add items to cart or wishlist
4. Proceed to checkout to place orders
5. Create an account to track orders

### For Administrators
1. Access admin dashboard with admin credentials
2. Manage products (add, edit, delete)
3. View and process orders
4. Monitor user activities
5. Upload product images securely

## 🔧 Configuration

### Database Configuration
Edit `config.php` to match your database setup:
```php
$host = 'your_host';
$user = 'your_username';
$password = 'your_password';
$database = 'velvet_vogue_db';
```

### File Upload Settings
Modify upload settings in `admin_dashboard.php`:
- Maximum file size: 2MB
- Allowed formats: JPG, PNG, GIF, WebP
- Upload directory: `uploads/products/`

## 🤝 Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📝 License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 Support

For support, email support@velvetvogue.com or create an issue in the repository.

## 🙏 Acknowledgments

- Tailwind CSS for the styling framework
- Inter font family for typography
- PHP community for excellent documentation
- MySQL for reliable database management

---

**Velvet Vogue** - Where fashion meets technology! 👗✨
