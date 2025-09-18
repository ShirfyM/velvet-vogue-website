# 🌟 Velvet Vogue - E-commerce Fashion Website

A modern, responsive e-commerce website built with PHP and MySQL for a fashion retail business.

## 🌐 **Live Website**
**Visit:** https://velvetvogue.infinityfree.me/

## ✨ **Features**

- **🛍️ Product Catalog**: Browse and search through fashion items
- **🛒 Shopping Cart**: Add, remove, and manage items in cart
- **❤️ Wishlist**: Save favorite items for later
- **👤 User Authentication**: Login/Register system
- **📦 Order Management**: Track orders and order history
- **⚙️ Admin Dashboard**: Manage products, orders, and users
- **📱 Responsive Design**: Mobile-first approach with Tailwind CSS
- **🔍 Search Functionality**: Real-time product search
- **🔒 Security**: CSRF protection and secure authentication

## 🛠️ **Tech Stack**

- **Backend**: PHP 7.4+
- **Database**: MySQL 5.7+
- **Frontend**: HTML5, CSS3, JavaScript
- **Styling**: Tailwind CSS
- **Icons**: Heroicons (SVG)
- **Fonts**: Google Fonts (Inter)

## 🚀 **Quick Start**

### **Local Development**
1. Clone this repository
2. Set up XAMPP/WAMP/LAMP
3. Import the database schema
4. Update `config.php` with your database credentials
5. Access via `http://localhost/Velvet_vogue_site`

### **Live Hosting**
- **Hosted on**: InfinityFree
- **URL**: https://velvetvogue.infinityfree.me/
- **Database**: MySQL on InfinityFree

## 📁 **Project Structure**

```
velvet_vogue_site/
├── assets/
│   ├── css/           # Stylesheets
│   ├── js/            # JavaScript files
│   └── images/        # Static images
├── uploads/           # User uploaded content
├── *.php             # Main application files
├── config.php        # Database configuration (not in repo)
└── README.md         # This file
```

## 🗄️ **Database Schema**

### **Main Tables:**
- `users` - User accounts and authentication
- `products` - Product catalog with images and details
- `orders` - Order information and customer details
- `order_items` - Individual items in each order
- `message` - Contact form submissions

## 🔧 **Configuration**

### **Database Setup:**
1. Create MySQL database
2. Import the provided SQL schema
3. Update `config.php` with your credentials:

```php
$host = 'your_host';
$user = 'your_username';
$password = 'your_password';
$database = 'velvet_vogue_db';
```

## 🌐 **Hosting Information**

- **Current Host**: InfinityFree (Free PHP/MySQL hosting)
- **Domain**: velvetvogue.infinityfree.me
- **SSL**: Enabled
- **PHP Version**: 8.0+
- **MySQL Version**: 5.7+

## 📱 **Responsive Design**

The website is fully responsive and optimized for:
- **Desktop** (1200px+)
- **Tablet** (768px - 1199px)
- **Mobile** (320px - 767px)

## 🔒 **Security Features**

- ✅ CSRF token protection
- ✅ SQL injection prevention (prepared statements)
- ✅ XSS protection (htmlspecialchars)
- ✅ Secure session management
- ✅ Input validation and sanitization

## 🎨 **Design Features**

- Modern, clean interface
- Tailwind CSS for styling
- Responsive grid layouts
- Interactive elements
- Professional color scheme
- Mobile-optimized navigation

## 📊 **Admin Features**

- Product management (add, edit, delete)
- Order management and status updates
- User management
- Contact message handling
- Sales analytics and reporting

## 🚀 **Deployment**

### **For InfinityFree:**
1. Upload all files to `htdocs` directory
2. Create MySQL database
3. Import database schema
4. Update `config.php` with production credentials
5. Set proper file permissions

### **For Other Hosts:**
- Ensure PHP 7.4+ and MySQL 5.7+ support
- Upload files to web root directory
- Configure database connection
- Set up SSL certificate

## 🤝 **Contributing**

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## 📄 **License**

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## 📞 **Support**

- **Website**: https://velvetvogue.infinityfree.me/
- **Email**: support@velvetvogue.com
- **Issues**: Use GitHub Issues for bug reports

## 🎯 **Future Enhancements**

- [ ] Payment gateway integration
- [ ] Email notifications
- [ ] Advanced search filters
- [ ] Product reviews and ratings
- [ ] Multi-language support
- [ ] Mobile app

---

**Built with ❤️ for the fashion community**

*Velvet Vogue - Where Style Meets Technology*
