-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 18, 2025 at 07:09 AM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `velvet_vogue_db`
--

-- --------------------------------------------------------

--
-- Table structure for table `message`
--

CREATE TABLE `message` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `message`
--

INSERT INTO `message` (`id`, `name`, `email`, `message`, `created_at`) VALUES
(1, 'moahmed', 'mohamed@gmail.com', 'the site is greate', '2025-09-10 13:14:17'),
(2, 'Shirfy Mohamed', 'shirfy@gmail.com', 'I wanted to say this is the greatest website i ever visit.', '2025-09-10 13:32:02');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `email` varchar(255) NOT NULL,
  `full_name` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `city` varchar(120) NOT NULL,
  `zip_code` varchar(20) NOT NULL,
  `contact_number` varchar(40) NOT NULL,
  `payment_method` enum('COD','Card','BankTransfer') NOT NULL,
  `shipping_method` enum('Standard','Express') NOT NULL,
  `subtotal` decimal(10,2) NOT NULL DEFAULT 0.00,
  `shipping_fee` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_amount` decimal(10,2) NOT NULL DEFAULT 0.00,
  `status` enum('Pending','Processing','Shipped','Delivered','Cancelled') NOT NULL DEFAULT 'Pending',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `orders`
--

INSERT INTO `orders` (`id`, `user_id`, `email`, `full_name`, `address`, `city`, `zip_code`, `contact_number`, `payment_method`, `shipping_method`, `subtotal`, `shipping_fee`, `total_amount`, `status`, `created_at`) VALUES
(1, 1, 'shirfy@gmail.com', 'shirfym', '104/c', 'kalmunai', '32300', '0761234590', 'COD', 'Express', 0.00, 800.00, 800.00, 'Pending', '2025-09-10 12:29:57'),
(2, NULL, 'shirfy@gmail.com', 'shirfy', '90/1, town hall road', 'kalmunai', '32300', '0761234560', 'Card', 'Standard', 3290.00, 400.00, 3690.00, 'Delivered', '2025-09-10 12:49:46'),
(3, NULL, 'shirfy@gmail.com', 'asdsad', 'asdasd', 'asdsada', '32131121231', '123131312312', 'COD', 'Express', 5800.00, 800.00, 6600.00, 'Processing', '2025-09-10 12:53:52'),
(4, NULL, 'shirfy@gmail.com', 'asdsadsa', 'sadsadad', '21313', '32112213', '2131232121312', 'COD', 'Standard', 5290.00, 400.00, 5690.00, 'Delivered', '2025-09-10 12:58:11'),
(5, NULL, 'shirfy@gmail.com', 'asdsadsa', 'sadsadad', '21313', '32112213', '2131232121312', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-10 13:00:36'),
(6, NULL, 'sadssadfs@gmail.com', 'dfdsfdsf', 'sdfdsfds', '2432342', '234242', '2343243243242', 'COD', 'Standard', 3250.00, 400.00, 3650.00, 'Delivered', '2025-09-10 13:05:03'),
(7, 1, 'shirfy@gmail.com', 'mohamed shirfy', '98', 'kalmunai', '32300', '0761234568', 'BankTransfer', 'Standard', 33180.00, 400.00, 33580.00, 'Shipped', '2025-09-10 14:33:47'),
(8, NULL, 'sadas@gmail.com', 'sdoasjdo', 'djdfiosjadoi', 'jiodsjfosaj', '329424`', '0294023940', 'COD', 'Standard', 5350.00, 400.00, 5750.00, 'Delivered', '2025-09-11 12:11:21'),
(9, NULL, 'aasik@gmail.com', 'mohamed', '45, colombo - 07', 'colombo', '43920', '0768403231', 'Card', 'Standard', 13980.00, 400.00, 14380.00, 'Cancelled', '2025-09-11 12:34:15'),
(10, 8, 'shirfy@gmail.com', 'shirfy mohamed', 'sailan road', 'kalmunai', '32300', '0767337598', 'Card', 'Standard', 18570.00, 400.00, 18970.00, 'Shipped', '2025-09-11 12:46:17'),
(11, 7, 'shirfy@gmail.com', 'Shirfy', 'kalumai', 'Kalmunai', '32300', '0761234569', 'Card', 'Standard', 3990.00, 400.00, 4390.00, 'Processing', '2025-09-11 13:57:42'),
(13, 7, 'shirfy@gmail.com', 'shirfy', '90, Sahibu Road', 'Kalmunai', '32300', '0767337590', 'BankTransfer', 'Standard', 11600.00, 400.00, 12000.00, 'Pending', '2025-09-12 05:11:13'),
(14, 7, 'sadsa@gmail.com', 'qsdsadad', 'asjiosajdoias10`', 'sdakd;askd', '324324', '4903243232', 'COD', 'Standard', 8990.00, 400.00, 9390.00, 'Pending', '2025-09-12 06:11:45'),
(15, 8, 'shirfy505@gmail.com', 'shirfy', '83, Sailan Road', 'Kalmunai', '32300', '0767337598', 'COD', 'Standard', 3250.00, 400.00, 3650.00, 'Pending', '2025-09-12 10:13:58'),
(16, 8, 'asdsad@gmail.com', 'dfkas', 'sdsasdads', 'sjdsaj', '128908120', '211232112', 'COD', 'Standard', 4690.00, 400.00, 5090.00, 'Pending', '2025-09-12 10:25:55'),
(17, 7, 'shirfym@gmail.com', 'mohamed', 'sahibu', 'kalmunai', '32300', '077342343827', 'COD', 'Standard', 10980.00, 400.00, 11380.00, 'Pending', '2025-09-13 05:26:21'),
(18, 7, 'mohamed@gmail.om', 'moahmed', 'central road', 'sainthamaruthu', '32300', '078490238', 'COD', 'Standard', 17560.00, 400.00, 17960.00, 'Pending', '2025-09-13 05:30:53'),
(19, 7, 'mohamed@gmail.om', 'moahmed', 'central road', 'sainthamaruthu', '32300', '078490238', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:31:11'),
(20, 7, 'aasik@gmail.com', 'aasik', 'colombo', 'colombo', '40000', '0342434903', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:33:38'),
(21, 7, 'aasik@gmail.com', 'aasik', 'colombo', 'colombo', '40000', '0342434903', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:34:09'),
(22, 7, 'aasik@gmail.com', 'aasik', 'colombo', 'colombo', '40000', '0342434903', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:34:46'),
(23, 7, 'aasik@gmail.com', 'aasik', 'colombo', 'colombo', '40000', '0342434903', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:36:59'),
(24, 7, 'aasik@gmail.com', 'aasik', 'colombo', 'colombo', '40000', '0342434903', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:38:56'),
(25, 7, 'shirfy@gmail.com', 'shirfy', '94,kal', 'kalmunai', '32300', '09382302', 'Card', 'Standard', 8090.00, 400.00, 8490.00, 'Pending', '2025-09-13 05:41:05'),
(26, 7, 'shirfy@gmail.com', 'shirfy', '94,kal', 'kalmunai', '32300', '09382302', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:43:38'),
(27, 7, 'shirfy@gmail.com', 'shirfy', '94,kal', 'kalmunai', '32300', '09382302', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:43:45'),
(28, 7, 'shirfy@gmail.com', 'shirfy', '94,kal', 'kalmunai', '32300', '09382302', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:43:54'),
(29, 7, 'shirfy@gmail.com', 'shirfy', '94,kal', 'kalmunai', '32300', '09382302', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:43:57'),
(30, 7, 'shirfy@gamil.com', 'mohamed', 'sadj', 'djas', '2309128', '39349032', 'COD', 'Standard', 2290.00, 400.00, 2690.00, 'Pending', '2025-09-13 05:47:30'),
(31, 7, 'shirfy@gamil.com', 'mohamed', 'sadj', 'djas', '2309128', '39349032', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:48:05'),
(32, 7, 'shirfy@gamil.com', 'mohamed', 'sadj', 'djas', '2309128', '39349032', 'COD', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:48:13'),
(33, 7, 'shirfy@505.com', 'sdsadsa', 'dasdsad', 'asdasdad', '2131231', '3123213', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Pending', '2025-09-13 05:48:32'),
(34, 7, 'shirfy@505.com', 'sdsadsa', 'dasdsad', 'asdasdad', '2131231', '3123213', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Delivered', '2025-09-13 05:49:57'),
(35, 7, 'shirfy@505.com', 'sdsadsa', 'dasdsad', 'asdasdad', '2131231', '3123213', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Shipped', '2025-09-13 05:49:59'),
(36, 7, 'shirfy@505.com', 'dsad', 'asdasdsa', '23e2', 'q31221132', '12312321', 'Card', 'Standard', 3250.00, 400.00, 3650.00, 'Delivered', '2025-09-13 05:50:17'),
(37, 7, 'shirfy@505.com', 'dsad', 'asdasdsa', '23e2', 'q31221132', '12312321', 'Card', 'Standard', 0.00, 400.00, 400.00, 'Processing', '2025-09-13 05:50:30');

-- --------------------------------------------------------

--
-- Table structure for table `order_items`
--

CREATE TABLE `order_items` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `product_name` varchar(255) NOT NULL,
  `unit_price` decimal(10,2) NOT NULL,
  `quantity` int(11) NOT NULL DEFAULT 1,
  `size` varchar(10) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `order_items`
--

INSERT INTO `order_items` (`id`, `order_id`, `product_id`, `product_name`, `unit_price`, `quantity`, `size`, `created_at`) VALUES
(1, 2, 16, 'Ribbed zip-up crewneck', 3290.00, 1, 'S', '2025-09-10 12:49:46'),
(2, 3, 6, 'Linen Shirt', 5800.00, 1, 'L', '2025-09-10 12:53:52'),
(3, 4, 18, 'Casual Wear Self Collar L/S T-Shirt', 5290.00, 1, 'S', '2025-09-10 12:58:11'),
(4, 6, 12, 'Acid wash t shirt', 3250.00, 1, 'XL', '2025-09-10 13:05:03'),
(5, 7, 10, 'Casual Wear L/S Shirt with Detailed Pkt', 6700.00, 3, 'L', '2025-09-10 14:33:47'),
(6, 7, 26, 'Men\'s Slim Fit Cutaway Collar Formal Shirt Black', 3550.00, 2, 'L', '2025-09-10 14:33:47'),
(7, 7, 25, 'Men\'s Slim Fit Check Cotton Formal Shirt Navy', 2990.00, 2, 'S', '2025-09-10 14:33:47'),
(8, 8, 8, 'Sri Lankan Cap', 900.00, 2, 'M', '2025-09-11 12:11:21'),
(9, 8, 26, 'Men\'s Slim Fit Cutaway Collar Formal Shirt Black', 3550.00, 1, 'L', '2025-09-11 12:11:21'),
(10, 9, 19, 'Men\'s Black Slim Fit Denim Trouser', 6990.00, 2, 'XL', '2025-09-11 12:34:15'),
(11, 10, 20, 'Men\'s Embroidery Cap Ash', 2790.00, 2, 'M', '2025-09-11 12:46:17'),
(12, 10, 22, 'DEEDAT Men\'s Sports Running Shoe Grey', 12990.00, 1, 'M', '2025-09-11 12:46:17'),
(13, 11, 33, 'HUF & DEE Women\'s High Build Print Oversized T-Shirt Off White', 3990.00, 1, 'L', '2025-09-11 13:57:42'),
(15, 13, 6, 'Linen Shirt', 5800.00, 2, 'L', '2025-09-12 05:11:13'),
(16, 14, 35, 'Women\'s Shirt Collar Printed Formal Blouse Black', 2290.00, 1, 'M', '2025-09-12 06:11:45'),
(17, 14, 10, 'Casual Wear L/S Shirt with Detailed Pkt', 6700.00, 1, 'M', '2025-09-12 06:11:45'),
(18, 15, 12, 'Acid wash t shirt', 3250.00, 1, 'M', '2025-09-12 10:13:58'),
(19, 16, 14, 'Crew Neck L/S Shirt', 4690.00, 1, 'M', '2025-09-12 10:25:55'),
(20, 17, 18, 'Casual Wear Self Collar L/S T-Shirt', 5290.00, 1, 'XL', '2025-09-13 05:26:21'),
(21, 17, 9, 'Mandarine collar shirt', 5690.00, 1, 'M', '2025-09-13 05:26:21'),
(22, 18, 34, 'HUF & DEE Women\'s Ribbon Frill Oversized T-Shirt Black', 3490.00, 2, 'M', '2025-09-13 05:30:53'),
(23, 18, 18, 'Casual Wear Self Collar L/S T-Shirt', 5290.00, 2, 'M', '2025-09-13 05:30:53'),
(24, 25, 36, 'Women\'s Printed Formal Blouse Cream', 2290.00, 1, 'M', '2025-09-13 05:41:05'),
(25, 25, 6, 'Linen Shirt', 5800.00, 1, 'M', '2025-09-13 05:41:05'),
(26, 30, 35, 'Women\'s Shirt Collar Printed Formal Blouse Black', 2290.00, 1, 'M', '2025-09-13 05:47:30'),
(27, 36, 12, 'Acid wash t shirt', 3250.00, 1, 'M', '2025-09-13 05:50:17');

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `price` decimal(10,2) NOT NULL,
  `description` text DEFAULT NULL,
  `category` varchar(100) DEFAULT NULL,
  `gender` varchar(20) NOT NULL DEFAULT 'Unisex',
  `image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `products`
--

INSERT INTO `products` (`id`, `name`, `price`, `description`, `category`, `gender`, `image`, `created_at`) VALUES
(6, 'Linen Shirt', 5800.00, 'Discover everyday sophistication with the all-new Casual Wear Regular Long-Sleeve Shirt from CONCEPT — crafted for modern minimalists who value effortless style. Featuring a sleek Mandarin collar, this shirt adds a contemporary touch to a timeless design.\r\n\r\nMade from a premium blend of cotton and linen, it offers breathable comfort with a soft, structured feel—perfect for daily wear or layering. With a regular fit and long sleeves, it embodies CONCEPT\'s philosophy of functional, stylish essentials.', 'shirts', 'Men', 'uploads/products/img_68c4fdf6af08a9.04359541.jpeg', '2025-09-09 13:56:03'),
(7, 'America Black Cap', 800.00, 'Cap with the American flag.', 'accessories', 'Unisex', 'uploads/products/img_68c4fec106e7d4.68801833.jpg', '2025-09-09 13:56:52'),
(8, 'Sri Lankan Cap', 900.00, 'Cap with the Sri Lanka flag.', 'accessories', 'Unisex', 'uploads/products/img_68c4fec87c5046.84674103.jpg', '2025-09-09 13:57:57'),
(9, 'Mandarine collar shirt', 5690.00, 'Casual Wear Regular L/S Shirt – Mandarin Collar\r\n\r\nDiscover everyday sophistication with the all-new Casual Wear Regular Long-Sleeve Shirt from CONCEPT — crafted for modern minimalists who value effortless style. Featuring a sleek Mandarin collar, this shirt adds a contemporary touch to a timeless design.\r\n\r\nMade from a premium blend of cotton and linen, it offers breathable comfort with a soft, structured feel—perfect for daily wear or layering. With a regular fit and long sleeves, it embodies CONCEPT\'s philosophy of functional, stylish essentials.\r\n\r\nKey Features:\r\n\r\n✔ Cotton & Linen Blend – Soft, breathable fabric for all-day comfort\r\n✔ Mandarin Collar – Modern, minimalist touch for versatile styling\r\n✔ Timeless Structure – Regular fit with long sleeves for a polished yet relaxed silhouette\r\n✔ Made to Layer – Wear buttoned up, over a tee, or your own way\r\n✔ Model Info – Wearing size M | Height: 6ft\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences\r\n\r\nTagline:\r\nCONCEPT — Built for those who value simplicity, quality, and modern design.', 'shirts', 'Men', 'uploads/products/img_68c50003e3ac99.31366144.webp', '2025-09-10 11:49:52'),
(10, 'Casual Wear L/S Shirt with Detailed Pkt', 6700.00, 'Casual Wear L/S Shirt with Detailed Pkt.\r\n\r\nModel wearing - L\r\nModel height - 6ft.\r\nMade of Blends Of Cotton & Linen\r\nColors may slightly vary from the picture.', 'shirts', 'Men', 'uploads/products/img_68c4fff4677885.48510239.webp', '2025-09-10 11:50:36'),
(11, '5 PKT Denim Trouser (Black)', 7000.00, '5 PKT Denim Trouser\r\n\r\nModel wearing - 32\r\nModel height - 6ft.\r\nMade of 100% Cotton Denim\r\nColors may slightly vary from the picture.', 'jeans', 'Men', 'uploads/products/img_68c4ffe0e7f710.93680009.webp', '2025-09-10 11:51:13'),
(12, 'Acid wash t shirt', 3250.00, 'Casual Wear Acid Wash T-Shirt\r\n\r\nAdd a contemporary edge to your everyday wardrobe with the all-new Casual Wear Acid Wash T-Shirt from CONCEPT. Designed for modern minimalists who value style and comfort, this T-shirt features a unique acid-wash finish that sets it apart.\r\n\r\nCrafted from 100% cotton single jersey, it delivers soft, breathable comfort with a lightweight feel—ideal for casual wear or layering. With a relaxed fit, it embodies CONCEPT’s philosophy of timeless, functional fashion.\r\n\r\nKey Features:\r\n\r\n✔ 100% Cotton Single Jersey – Soft, breathable fabric for all-day comfort\r\n✔ Acid Wash Finish – Unique texture for a modern, stylish look\r\n✔ Relaxed Fit – Comfortable and versatile for everyday wear\r\n✔ Made to Layer – Pair with jackets, overshirts, or wear solo\r\n✔ Model Info – Wearing size M | Height: 6ft\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences\r\n\r\nTagline:\r\nCONCEPT — Built for those who value simplicity, quality, and modern design.', 't-shirts', 'Men', 'uploads/products/img_68c4ffda37ba68.23002660.jpg', '2025-09-10 11:52:06'),
(13, 'Sleeveless knit vest', 2690.00, 'Casual Wear Sleeveless Knit Vest\r\n\r\nElevate your layering game with the all-new Casual Wear Sleeveless Knit Vest from CONCEPT, designed for modern minimalists who value comfort and style. Its clean silhouette makes it a versatile piece for both casual and smart-casual looks.\r\n\r\nCrafted from 100% cotton single jersey, this vest offers soft, breathable comfort with a lightweight feel—perfect for layering over shirts, tees, or under jackets. Designed with a relaxed fit, it embodies CONCEPT’s philosophy of timeless, functional fashion.\r\n\r\nKey Features:\r\n\r\n✔ 100% Cotton Single Jersey – Soft, breathable fabric for all-day comfort\r\n✔ Sleeveless Design – Easy to layer over shirts or tees\r\n✔ Versatile Fit – Relaxed, comfortable silhouette for effortless styling\r\n✔ Made to Layer – Perfect for casual or smart-casual outfits\r\n✔ Model Info – Wearing size M | Height: 6ft\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences\r\n\r\nTagline:\r\nCONCEPT — Built for those who value simplicity, quality, and modern design.', 't-shirts', 'Men', 'uploads/products/img_68c4ffd1296933.07528118.webp', '2025-09-10 11:52:40'),
(14, 'Crew Neck L/S Shirt', 4690.00, 'Crew Neck Long Sleeve Shirt\r\n\r\nStay comfortable and effortlessly stylish with the Crew Neck Long Sleeve Shirt, crafted from 100% cotton single jersey fabric. Soft, breathable, and lightweight, it’s designed for all-day comfort while offering a clean, timeless look.\r\n\r\nPerfect for layering or wearing on its own, this long sleeve tee is a versatile staple for year-round wear.\r\n\r\nKey Features:\r\n✔ 100% Cotton Single Jersey – Natural, soft, and breathable\r\n✔ Classic Crew Neckline – Simple and timeless design\r\n✔ Long Sleeve Style – Ideal for layering and added coverage\r\n✔ Lightweight Comfort – Suitable for everyday wear\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences', 't-shirts', 'Men', 'uploads/products/img_68c4ffb92c5558.88586457.webp', '2025-09-10 11:53:33'),
(15, 'Graphic Printed Crew Neck T-shirt', 3890.00, 'Graphic Printed Crew Neck T-Shirt\r\n\r\nEasygoing comfort meets bold expression in the Graphic Printed Crew Neck T-Shirt, made from soft and breathable 100% cotton single jersey. With its smooth finish and eye-catching graphic print, this tee adds personality to your everyday lineup while keeping things cool and comfortable.\r\n\r\nPerfect for laid-back days or statement layering.\r\n\r\nKey Features:\r\n✔ 100% Cotton Single Jersey – Lightweight, soft, and breathable for all-day ease\r\n✔ Graphic Print – Bold artwork adds standout style\r\n✔ Crew Neck Design – Classic neckline for a timeless fit\r\n✔ Short Sleeves – Ideal for warm weather or layering under outerwear\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences\r\n\r\nA must-have tee that brings comfort and character together effortlessly.', 't-shirts', 'Men', 'uploads/products/img_68c4ff97e61e67.54502581.webp', '2025-09-10 11:54:14'),
(16, 'Ribbed zip-up crewneck', 3290.00, 'Ribbed Zip-Up Crewneck\r\n\r\nModern, minimal, and made for comfort — the **Ribbed Zip-Up Crewneck** from **CONCEPT** is crafted from premium **cotton rib fabric** for a soft, flexible fit. Designed with a clean zip-up front and subtle texture, this piece blends everyday ease with elevated style, perfect for layering or solo wear.\r\n\r\nWith its refined crew neckline and ribbed structure, it’s a staple that speaks to **CONCEPT**’s signature approach: essentials with intention.\r\n\r\n### **Key Features:**\r\n\r\n✔ **Cotton Rib Fabric** – Soft, breathable, and stretch-friendly for everyday comfort\r\n✔ **Zip-Up Design** – Clean front closure for versatile wear and styling\r\n✔ **Crewneck Cut** – A classic neckline with a modern update\r\n✔ **Textured Finish** – Ribbed detailing adds structure and depth\r\n✔ **Color Disclaimer** – Shades may slightly vary due to lighting & display differences\r\n\r\n**CONCEPT** — Minimal design. Maximum comfort.', 't-shirts', 'Men', 'uploads/products/img_68c4ff8488e410.10552745.webp', '2025-09-10 11:54:48'),
(17, 'Mens Textured Sleeveless Hoodie', 4390.00, 'Mens Textured Sleeveless Hoodie\r\n\r\nGet the perfect combination of comfort and style with our Men’s Textured Sleeveless Hoodie. Crafted from 96% polyester and 4% spandex, this hoodie offers a soft, lightweight feel with just the right amount of stretch. Whether you\'re working out, running errands, or layering for a trendy look, this hoodie delivers on both performance and style.\r\n\r\n✔ Textured Fabric: Stylish, modern design for a bold look\r\n✔ Breathable & Lightweight: Keeps you cool and comfortable all day\r\n✔ Flexible Fit: Stretchable material for ease of movement\r\n✔ Perfect for Any Occasion: Ideal for gym, casual wear, or layering\r\n✔ Model Info: Wearing size L | Height: 6ft\r\n✔ Color Disclaimer: Actual shades may slightly vary due to lighting & display differences', 't-shirts', 'Men', 'uploads/products/img_68c4ff64603a65.47779292.webp', '2025-09-10 11:55:16'),
(18, 'Casual Wear Self Collar L/S T-Shirt', 5290.00, 'Casual Wear Self Collar L/S T-Shirt\r\n\r\nElevate your everyday look with our Casual Wear Self Collar Long-Sleeve T-Shirt, crafted for ultimate comfort and effortless style. Made from a premium blend of 96% Polyester and 4% Spandex, this soft, stretchable fabric ensures a perfect fit while keeping you comfortable all day. Designed with a sleek self-collar and a modern silhouette, it’s ideal for casual outings or layering for a smart, refined look.\r\n\r\nKey Features:\r\n✔ Soft & Stretchable: High-quality Polyester-Spandex blend for a comfortable fit\r\n✔ Classic Design: Self-collar and long sleeves for a versatile, polished look\r\n✔ Perfect for Any Occasion: Great for casual wear, layering, or a smart-casual outfit\r\n✔ Model Info: Wearing size L | Height: 6ft\r\n✔ Color Disclaimer: Shades may slightly vary due to lighting & display differences', 't-shirts', 'Men', 'uploads/products/img_68c4ff47b50951.41330318.webp', '2025-09-10 11:56:12'),
(19, 'Men\'s Black Slim Fit Denim Trouser', 6990.00, 'Men\'s Black Slim Fit Denim Trouser\r\nCrafted from high-quality denim, the Men\'s Black Slim Fit Denim Trouser offers a sleek, modern silhouette with a comfortable fit. Perfect for casual or smart-casual wear, these trousers are a versatile addition to any wardrobe.\r\n\r\nKey Features:\r\n✔ Denim Fabric – For a durable, soft feel with a timeless look\r\n✔ Slim Fit – Offers a modern, tailored fit for a sharp appearance\r\n✔ Classic Black Color – Easy to pair with any top for a polished look\r\n✔ Versatile Styling – Ideal for casual outings or smart-casual wear\r\n✔ Color Disclaimer – Shades may slightly vary due to lighting & display differences\r\n\r\nA wardrobe staple for modern, sleek style—elevate your look with the Men\'s Black Slim Fit Denim Trouser!', 'jeans', 'Men', 'uploads/products/img_68c5063d3f8078.63124549.webp', '2025-09-10 11:56:49'),
(20, 'Men\'s Embroidery Cap Ash', 2790.00, 'Experience the ultimate in style and comfort with our texture plain type fashion product, featuring a unique base ball embellishment that adds a touch of sophistication. Elevated by intricate embroidery, this piece is perfect for making a statement.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'accessories', 'Unisex', 'uploads/products/img_68c4fef57ab817.58769449.webp', '2025-09-10 11:58:58'),
(21, 'DEEDAT Men\'s Casual Belt Black & Gray', 1990.00, 'This casual piece features a pin‑fastening detail with a clean cut design, crafted from a durable yet soft material for everyday wear.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'accessories', 'Unisex', 'uploads/products/img_68c4feeaa6f428.35560062.webp', '2025-09-10 11:59:40'),
(22, 'DEEDAT Men\'s Sports Running Shoe Grey', 12990.00, 'These DEEDAT Sports Running Shoes in Beige are the perfect combination of fashion and function. Designed specifically for runners, these shoes will provide the support and comfort you need to maximize your performance. With the latest technology in running shoes, you can trust that these will be a game-changer for your workouts. Elevate your training with DEEDAT\r\n\r\nThe color of the product may vary slightly due to different lighting conditions during photography and individual monitor settings', 'shoes', 'Women', 'uploads/products/img_68c4feb57f6398.82495244.webp', '2025-09-10 12:00:25'),
(23, 'DEEDAT Men\'s Sports Running Shoe Beige', 12990.00, 'These DEEDAT Sports Running Shoes in Beige are the perfect combination of fashion and function. Designed specifically for runners, these shoes will provide the support and comfort you need to maximize your performance. With the latest technology in running shoes, you can trust that these will be a game-changer for your workouts. Elevate your training with DEEDAT\r\n\r\nThe color of the product may vary slightly due to different lighting conditions during photography and individual monitor settings', 'shoes', 'Women', 'uploads/products/img_68c4feae9eced7.13916326.webp', '2025-09-10 12:01:04'),
(24, 'DEEDAT Men\'s Sandals Black', 1990.00, 'Black sandals\r\n\r\nNote: *Product color may vary slightly due to different lighting conditions during photography and individual monitor settings*', 'shoes', 'Women', 'uploads/products/img_68c4fea3d42471.04028726.webp', '2025-09-10 12:01:36'),
(25, 'Men\'s Slim Fit Check Cotton Formal Shirt Navy', 2990.00, 'A slim-fit, long-sleeve cotton fashion shirt featuring a button-down collar, striped texture, and a curved hem.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'shirts', 'Men', 'uploads/products/img_68c4fe96b47bb4.87884261.webp', '2025-09-10 12:03:51'),
(26, 'Men\'s Slim Fit Cutaway Collar Formal Shirt Black', 3550.00, 'This basic shirt features a slim fit with long sleeves, a cutaway collar, a curved hem, and a plain twill texture.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'shirts', 'Men', 'uploads/products/img_68c4fe6e0f0845.91171256.webp', '2025-09-10 12:04:29'),
(28, 'Mens Slim Fit Formal Trouser Ash', 3995.00, 'Elevate your formalwear with the NoLimit Slim Fit Formal Trouser — tailored for the modern man who values style, comfort, and confidence. Crafted from premium-quality fabric, this trouser offers a sharp silhouette with a slim fit that complements your frame without compromising ease of movement.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'jeans', 'Men', 'uploads/products/img_68c4fe60eb1882.83996473.webp', '2025-09-10 13:28:26'),
(31, 'Men\'s Tapered Fit Jeans Mid Blue', 6990.00, 'This denim piece features a tapered fit, plain texture, and a fashion-forward design.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'jeans', 'Men', 'uploads/products/img_68c4fe4a58c786.16863967.webp', '2025-09-10 13:51:09'),
(32, 'Women\'s Stripe Sweater Apricot', 4290.00, 'This semi-winter masterpiece boasts a unique texture that will elevate your style game. The crew neck and long sleeve design provides a comfortable fit, perfect for the chilly days of the season. Made from high-quality viscoe material, this stripe patterned piece is not only visually appealing but also soft to the touch. Stay warm and stylish with this must-have addition to your winter wardrobe. Note: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 't-shirts', 'Women', 'uploads/products/img_68c4198e4dce40.98211661.webp', '2025-09-11 13:52:46'),
(33, 'HUF & DEE Women\'s High Build Print Oversized T-Shirt Off White', 3990.00, 'Upgrade your casual wardrobe with this oversized fashion tee, designed for comfort and style. Featuring a classic crew neck and short sleeves, this piece is crafted from a soft and stretchy cotton-spandex blend for a perfect relaxed fit. The plain texture keeps it minimal and versatile, while the high-build print embellishment adds a bold, tactile detail that stands out. Ideal for everyday wear, this t-shirt is your go-to for effortless street-style vibes.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 't-shirts', 'Women', 'uploads/products/img_68c4197d026a97.35823798.webp', '2025-09-11 13:54:05'),
(34, 'HUF & DEE Women\'s Ribbon Frill Oversized T-Shirt Black', 3490.00, 'Make a statement with this oversized cropped fashion tee, blending comfort with a playful twist. Designed with a classic crew neck and short sleeves, it’s made from a soft, breathable cotton-spandex blend for a relaxed yet flattering fit. The plain texture keeps it versatile, while the ribbon frill embellishment adds a touch of feminine charm and unique style. Perfect for casual outings or dressing up your everyday look with a hint of flair.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 't-shirts', 'Women', 'uploads/products/img_68c419694fdc96.25554884.webp', '2025-09-11 13:54:34'),
(35, 'Women\'s Shirt Collar Printed Formal Blouse Black', 2290.00, 'Make a bold statement with this fashion-forward long sleeve shirt, designed for those who appreciate standout style. Featuring a classic shirt collar and eye-catching printed texture, this shirt blends timeless structure with modern flair. Crafted from durable, lightweight polyester, it offers all-day comfort and easy maintenance. Perfect for turning heads at casual outings or trendy events, this shirt adds personality to any wardrobe.\r\n\r\nNote: Product color may vary slightly due to different lighting conditions during photography and individual monitor settings.', 'shirts', 'Women', 'uploads/products/img_68c4fe2857f0b7.70043819.webp', '2025-09-11 13:55:11'),
(36, 'Women\'s Printed Formal Blouse Cream', 2290.00, 'Make a bold style statement with this trendy women\'s fashion shirt. Designed with a classic shirt collar and long sleeves, it offers a timeless silhouette enhanced by an eye-catching printed texture. Made from durable and lightweight polyester, this shirt ensures all-day comfort while keeping you effortlessly chic.', 'shirts', 'Women', 'uploads/products/img_68c4fe177d6241.12355741.webp', '2025-09-11 13:55:48'),
(37, 'Women\'s Shirt Collar Long Sleeve Formal Blouse Gray', 3490.00, 'Add a touch of elegance to your everyday style with this versatile women\'s fashion shirt. Designed with a classic shirt collar and long sleeves, this piece offers a polished yet comfortable look, perfect for both work and casual settings. Made from high-quality polyester, the shirt features a smooth plain texture that gives it a clean and sophisticated appeal.\r\n\r\nThis fashion piece is a long‑sleeve polyester shirt featuring a classic shirt collar and eye‑catching printed texture.', 'shirts', 'Women', 'uploads/products/img_68c4fe07c418f1.74171242.webp', '2025-09-11 13:56:34');

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `role` varchar(20) NOT NULL DEFAULT 'user'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `password`, `role`) VALUES
(1, 'sadadsad', 'asdsad@gmail.com', '$2y$10$zj9rgTf6GFhjZg1u5csEHeFzx5zb9B1eYsBp7kPyt/Q7JtzhbOfFS', 'user'),
(2, 'dsadasd', 'sdsad@gmail.com', '$2y$10$s154aabR7VYXA71uk1U3.uJtJ08w8jN9yKNunXCI4qAcUjfy2VgjS', 'user'),
(3, 'adsfasd', 'asdcas@mfdskf.com', '$2y$10$a.cjokqbDTL7Cd/yiChrvuZCDEsJgIPPrcoqtpEFndEVj/.xb.Uei', 'user'),
(7, 'Admin', 'admin@gmail.com', '$2y$10$IVhlZVSMReJW2e057WsU1.302K6OKDSV2hLkIee57muMXGR9gLqI2', 'admin'),
(8, 'Shirfy Mohamed', 'shirfy@gmail.com', '$2y$10$N5O7TIZjY.I8wycKAFoZmebGQSC7TNymjdJ8yLq1Gdvm6rYw3pEg.', 'user');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `message`
--
ALTER TABLE `message`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_orders_user_id` (`user_id`);

--
-- Indexes for table `order_items`
--
ALTER TABLE `order_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fk_order_items_order_id` (`order_id`),
  ADD KEY `fk_order_items_product_id` (`product_id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email_unique` (`email`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `message`
--
ALTER TABLE `message`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_items`
--
ALTER TABLE `order_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `orders`
--
ALTER TABLE `orders`
  ADD CONSTRAINT `fk_orders_user_id` FOREIGN KEY (`user_id`) REFERENCES `users` (`id`) ON DELETE SET NULL;

--
-- Constraints for table `order_items`
--
ALTER TABLE `order_items`
  ADD CONSTRAINT `fk_order_items_order_id` FOREIGN KEY (`order_id`) REFERENCES `orders` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `fk_order_items_product_id` FOREIGN KEY (`product_id`) REFERENCES `products` (`id`) ON DELETE SET NULL;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
