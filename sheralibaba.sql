-- phpMyAdmin SQL Dump
-- version 4.6.4
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Dec 23, 2017 at 08:30 AM
-- Server version: 5.7.14
-- PHP Version: 7.0.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `sheralibaba`
--

-- --------------------------------------------------------

--
-- Table structure for table `address`
--

CREATE TABLE `address` (
  `id` int(11) UNSIGNED NOT NULL,
  `street` varchar(255) NOT NULL,
  `city` varchar(255) NOT NULL,
  `state` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `zip` varchar(10) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `address`
--

INSERT INTO `address` (`id`, `street`, `city`, `state`, `country`, `zip`, `created_date`, `modified_date`) VALUES
(1, 'Haroon Street # 4', 'Lahore', 'Punjab', 'Pakistan', '54780', '2017-07-09 22:02:36', '2017-07-11 15:05:04'),
(2, '65 Main Blvd Gulberg', 'Lahore', 'Punjab', 'Pakistan', '12345', '2017-07-09 22:17:17', '2017-07-09 22:17:17'),
(3, 'Dolmen Mall Clifton, Block-4, Clifton', 'Karachi', 'Sindh', 'Pakistan', '54321', '2017-07-09 23:04:05', '2017-07-09 23:04:05'),
(4, '65 Z, DHA, St 22', 'Lahore', 'Punjab', 'Pakistan', '54000', '2017-07-09 23:15:26', '2017-07-09 23:15:26'),
(5, '10th Avenue, M.M Alam Road, Gulberg III', 'Lahore', 'Punjab', 'Pakistan', '54000', '2017-07-09 23:37:24', '2017-07-09 23:37:24'),
(6, 'Sector Y, DHA 3', 'Lahore', 'Punjab', 'Pakistan', '54810', '2017-07-09 23:52:38', '2017-07-09 23:52:38'),
(7, 'Haroon Street # 4', 'Lahore', 'Punjab', 'Pakistan', '54780', '2017-07-10 23:03:08', '2017-07-10 23:03:08'),
(8, 'Street # 17', 'Lahore', 'Punjab', 'Pakistan', '54123', '2017-07-10 23:04:53', '2017-07-10 23:04:53'),
(9, 'Street # 3, Green Town', 'Lahore', 'Punjab', 'Pakistan', '54321', '2017-07-10 23:06:34', '2017-07-10 23:06:34'),
(10, 'Street # 13, Model Town', 'Lahore', 'Punjab', 'Pakistan', '54789', '2017-07-10 23:08:52', '2017-07-10 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `admin`
--

CREATE TABLE `admin` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `admin`
--

INSERT INTO `admin` (`id`, `first_name`, `last_name`, `contact_no`, `email`, `created_date`, `modified_date`) VALUES
(1, 'Sher', 'Ali', '515461651', 'ali4friends80@gmail.com', '2017-07-09 22:02:37', '2017-07-11 15:05:04');

-- --------------------------------------------------------

--
-- Table structure for table `buyer`
--

CREATE TABLE `buyer` (
  `id` int(11) UNSIGNED NOT NULL,
  `first_name` varchar(25) NOT NULL,
  `last_name` varchar(25) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buyer`
--

INSERT INTO `buyer` (`id`, `first_name`, `last_name`, `contact_no`, `email`, `created_date`, `modified_date`) VALUES
(1, 'Ahmad', 'Javaid', '03215675052', 'ahmadjavaid@gmail.com', '2017-07-10 23:03:08', '2017-07-10 23:03:08'),
(2, 'Muneeb', 'Ullah', '03244392357', 'muneebullah@gmail.com', '2017-07-10 23:04:53', '2017-07-10 23:04:53'),
(3, 'Muhammad', 'Usman', '03314930952', 'muhammadusman@gmail.com', '2017-07-10 23:06:34', '2017-07-10 23:06:34'),
(4, 'Arslan', 'Sleem', '03231442990', 'arslansleem@gmail.com', '2017-07-10 23:08:52', '2017-07-10 23:08:52');

-- --------------------------------------------------------

--
-- Table structure for table `buyer_order`
--

CREATE TABLE `buyer_order` (
  `id` int(11) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL,
  `buyer_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `buyer_order`
--

INSERT INTO `buyer_order` (`id`, `created_date`, `buyer_id`) VALUES
(1, '2017-07-10 23:12:56', 1),
(2, '2017-07-11 00:12:09', 2),
(3, '2017-07-11 00:14:25', 3),
(4, '2017-07-11 11:20:17', 1),
(5, '2017-07-11 11:42:45', 1),
(6, '2017-07-11 15:13:29', 1),
(7, '2017-07-13 14:58:23', 1);

-- --------------------------------------------------------

--
-- Table structure for table `category`
--

CREATE TABLE `category` (
  `id` mediumint(8) UNSIGNED NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `admin_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `category`
--

INSERT INTO `category` (`id`, `category_name`, `created_date`, `modified_date`, `admin_id`) VALUES
(1, 'Agriculture & Food', '2017-07-09 22:02:37', '2017-07-10 10:14:36', 1),
(2, 'Apparel,Textiles & Accessories', '2017-07-09 22:02:37', '2017-07-09 22:02:37', 1),
(3, 'Auto & Transportation', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(4, 'Bags, Shoes & Accessories', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(5, 'Electronics', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(6, 'Electrical Equipment, Components & Telecoms', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(7, 'Gifts, Sports & Toys', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(8, 'Health & Beauty', '2017-07-09 22:02:38', '2017-07-09 22:02:38', 1),
(9, 'Home, Lights & Construction', '2017-07-09 22:02:39', '2017-07-09 22:02:39', 1),
(10, 'Machinery, Industrial Parts & Tools', '2017-07-09 22:02:39', '2017-07-09 22:02:39', 1),
(11, 'Metallurgy, Chemicals, Rubber & Plastics', '2017-07-09 22:02:39', '2017-07-09 22:02:39', 1),
(12, 'Packaging, Advertising & Office', '2017-07-09 22:02:39', '2017-07-09 22:02:39', 1),
(13, '61616lljnjl', '2017-07-11 15:05:20', '2017-07-11 15:05:20', 1);

-- --------------------------------------------------------

--
-- Table structure for table `image`
--

CREATE TABLE `image` (
  `id` int(11) UNSIGNED NOT NULL,
  `image_name` varchar(100) NOT NULL,
  `image_type` varchar(100) NOT NULL,
  `size` mediumint(8) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `image`
--

INSERT INTO `image` (`id`, `image_name`, `image_type`, `size`, `created_date`, `modified_date`) VALUES
(1, 'air-zoom-mariah-flyknit-racer-mens-shoe.jpg', 'image/jpeg', 39498, '2017-07-09 22:20:35', '2017-07-09 22:20:35'),
(2, 'duel-racer-mens-shoe.jpg', 'image/jpeg', 35194, '2017-07-09 22:22:28', '2017-07-09 22:22:28'),
(3, 'free-train-virtue-hustle-hart-night-mens-training-shoe.jpg', 'image/jpeg', 29682, '2017-07-09 22:24:00', '2017-07-09 22:24:00'),
(4, 'jordan-trainer-2-flyknit-mens-training-shoe.jpg', 'image/jpeg', 27366, '2017-07-09 22:24:55', '2017-07-09 22:24:55'),
(5, 'air-force-1-07-mens-shoe.jpg', 'image/jpeg', 20519, '2017-07-09 22:25:47', '2017-07-09 22:25:47'),
(6, 'metcon-dsx-flyknit-mens-training-shoe.jpg', 'image/jpeg', 40966, '2017-07-09 22:27:37', '2017-07-09 22:27:37'),
(7, 'converse-chuck-taylor-all-star-pride-geostar-high-top-unisex-shoe.jpg', 'image/jpeg', 40387, '2017-07-09 22:28:30', '2017-07-09 22:28:30'),
(8, 'converse-chuck-taylor-all-star-pride-mesh-low-top-unisex-shoe.jpg', 'image/jpeg', 45865, '2017-07-09 22:29:57', '2017-07-09 22:29:57'),
(9, 'converse-one-star-premium-suede-low-top-unisex-shoe.jpg', 'image/jpeg', 29481, '2017-07-09 22:50:37', '2017-07-09 22:50:37'),
(10, 'converse-chuck-taylor-all-star-low-top-unisex-shoe.jpg', 'image/jpeg', 21817, '2017-07-09 22:52:52', '2017-07-09 22:52:52'),
(11, 'free-focus-flyknit-2-womens-training-shoe.jpg', 'image/jpeg', 46898, '2017-07-09 22:57:57', '2017-07-09 22:57:57'),
(12, 'BY9915_01_standard.jpg', 'image/jpeg', 19010, '2017-07-09 23:07:28', '2017-07-09 23:07:28'),
(13, 'BZ0515_01_standard.jpg', 'image/jpeg', 21754, '2017-07-09 23:08:31', '2017-07-09 23:08:31'),
(14, 'BY9410_01_standard.jpg', 'image/jpeg', 18495, '2017-07-09 23:09:21', '2017-07-09 23:09:21'),
(15, 'S80613_01_standard.jpg', 'image/jpeg', 23633, '2017-07-09 23:10:16', '2017-07-09 23:10:16'),
(16, 'korpon-portable-charcoal-grill-black__0400646_PE565966_S4.JPG', 'image/jpeg', 33773, '2017-07-09 23:17:36', '2017-07-09 23:17:36'),
(17, 'applaro-klasen-charcoal-grill-with-cart__0322991_PE517044_S4.JPG', 'image/jpeg', 40003, '2017-07-09 23:18:28', '2017-07-09 23:18:28'),
(18, 'klasen-serving-cart-outdoor-black__0187923_PE340875_S4.JPG', 'image/jpeg', 29913, '2017-07-09 23:19:23', '2017-07-09 23:19:23'),
(19, 'borja-training-cup__0155149_PE313399_S4.JPG', 'image/jpeg', 21568, '2017-07-09 23:20:32', '2017-07-09 23:20:32'),
(20, 'smaska-bowl__0092906_PE229648_S4.JPG', 'image/jpeg', 7084, '2017-07-09 23:21:40', '2017-07-09 23:21:40'),
(21, 'volym-steel-vacuum-flask__25654_PE094765_S4.JPG', 'image/jpeg', 9507, '2017-07-09 23:23:30', '2017-07-09 23:23:30'),
(22, 'upphetta-coffee-tea-maker__0328984_PE520135_S4.JPG', 'image/jpeg', 26676, '2017-07-09 23:24:28', '2017-07-09 23:24:28'),
(23, 'vardagen-teapot-white__0417325_PE583682_S4.JPG', 'image/jpeg', 16359, '2017-07-09 23:25:14', '2017-07-09 23:25:14'),
(24, 'lattad-place-mat-black__0252318_PE391150_S4.JPG', 'image/jpeg', 27529, '2017-07-09 23:31:57', '2017-07-09 23:31:57'),
(25, 'finstilt-table-runner-assorted-colors__0486364_PE622043_S4.JPG', 'image/jpeg', 38564, '2017-07-09 23:32:35', '2017-07-09 23:32:35'),
(26, 'dsc_4270_4.jpg', 'image/jpeg', 38762, '2017-07-09 23:43:09', '2017-07-09 23:43:09'),
(27, 'dsc_3254.jpg', 'image/jpeg', 14227, '2017-07-09 23:44:52', '2017-07-09 23:44:52'),
(28, 'dsc_3031.jpg', 'image/jpeg', 18367, '2017-07-09 23:46:22', '2017-07-09 23:46:22'),
(29, 'dsc_3125.jpg', 'image/jpeg', 14606, '2017-07-09 23:47:57', '2017-07-09 23:47:57'),
(30, 'cw585_uniworth_check_shirts_1.jpg', 'image/jpeg', 15561, '2017-07-09 23:56:37', '2017-07-09 23:56:37'),
(31, 'cw524_uniworth_check_shirt.jpg', 'image/jpeg', 16936, '2017-07-09 23:57:59', '2017-07-09 23:57:59'),
(32, 'cw586_uniworth_check_shirt.jpg', 'image/jpeg', 14396, '2017-07-09 23:59:07', '2017-07-09 23:59:07'),
(33, 'uniworth_formal_trouser_wutr4036p.jpg', 'image/jpeg', 6660, '2017-07-10 00:00:22', '2017-07-10 00:00:22'),
(34, 'uniworth_formal_trouser_wutr4035p.jpg', 'image/jpeg', 6609, '2017-07-10 00:01:47', '2017-07-10 00:01:47'),
(35, 'uniworth_formal_trouser_wuts2964p.jpg', 'image/jpeg', 4888, '2017-07-10 00:02:37', '2017-07-10 00:02:37'),
(36, 'k7150b.jpg', 'image/jpeg', 6791, '2017-07-10 00:03:55', '2017-07-10 00:03:55'),
(37, 'k7149b.jpg', 'image/jpeg', 6381, '2017-07-10 00:05:37', '2017-07-10 00:05:37'),
(38, 'images.jpg', 'image/jpeg', 4053, '2017-07-11 11:39:46', '2017-07-11 11:39:46');

-- --------------------------------------------------------

--
-- Table structure for table `manufacturer`
--

CREATE TABLE `manufacturer` (
  `id` int(11) UNSIGNED NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `contact_no` varchar(20) NOT NULL,
  `email` varchar(255) NOT NULL,
  `url` varchar(255) DEFAULT NULL,
  `description` tinytext,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `manufacturer`
--

INSERT INTO `manufacturer` (`id`, `business_name`, `contact_no`, `email`, `url`, `description`, `created_date`, `modified_date`) VALUES
(1, 'NIKE', '03005756547', 'nike@gmail.com', 'https://www.nike.com', 'NIKE, Inc. engages in the design, development, marketing, sale of sports and lifestyle footwear, apparel, and equipment, accessories and services. Its athletic footwear products are designed primarily for specific athletic use, although a large percent.', '2017-07-09 22:17:17', '2017-07-09 22:17:17'),
(2, 'Adidas', '03215291416', 'adidas@gmail.com', 'https://www.adidas.com', 'Adidas is a German multinational corporation, headquartered in Herzogenaurach, Germany, that designs and manufactures shoes, clothing and accessories. It is the largest sportswear manufactures shoes, clothing and accessories.', '2017-07-09 23:04:05', '2017-07-09 23:04:05'),
(3, 'IKEA', '03225892756', 'ikea@gmail.com', 'https://www.ikea.com', 'Ingvar Kamprad founded IKEA in 1943 as a mostly mail-order sales business. It began to sell furniture five years later. The first MÃ¶bel-IKÃ‰A store was opened in Ã„lmhult, SmÃ¥land, in 1958 (MÃ¶bel means "furniture" in Swedish).', '2017-07-09 23:15:26', '2017-07-09 23:15:26'),
(4, 'Lime Light', '03005778900', 'limelight@gmail.com', 'https://limelight.pk/', 'Limelight is a ready to wear brand for trendy girls and ladies. It offers eastern shirts, suits, tops, lowers & accessories. Limelight also offer interesting collection of handbags and wallets.', '2017-07-09 23:37:24', '2017-07-09 23:37:24'),
(5, 'UNIWORTH', '03454037778', 'uniworth@gmail.com', 'http://www.uniworthshop.com/', 'Established in 1971 by the visionary founder Mr Khawaja Iftikhar Ahmad Uniworth has been in the clothing business for over 40 years. Starting off as a clothing brand for women and kids.', '2017-07-09 23:52:38', '2017-07-09 23:52:38');

-- --------------------------------------------------------

--
-- Table structure for table `order_detail`
--

CREATE TABLE `order_detail` (
  `id` int(11) UNSIGNED NOT NULL,
  `quantity` mediumint(8) UNSIGNED NOT NULL,
  `status` tinyint(3) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL,
  `manufacturer_id` int(11) UNSIGNED NOT NULL,
  `buyer_order_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `order_detail`
--

INSERT INTO `order_detail` (`id`, `quantity`, `status`, `product_id`, `manufacturer_id`, `buyer_order_id`) VALUES
(1, 1, 4, 5, 1, 1),
(2, 1, 4, 9, 1, 1),
(3, 2, 1, 21, 3, 1),
(4, 2, 2, 30, 5, 1),
(5, 1, 2, 31, 5, 1),
(6, 1, 3, 36, 5, 1),
(7, 1, 4, 1, 1, 2),
(8, 1, 4, 15, 2, 2),
(9, 3, 1, 22, 3, 2),
(10, 1, 4, 1, 1, 3),
(11, 1, 4, 5, 1, 3),
(12, 1, 2, 31, 5, 3),
(13, 1, 3, 36, 5, 3),
(14, 1, 1, 1, 1, 4),
(15, 2, 1, 31, 5, 4),
(16, 2, 1, 34, 5, 4),
(17, 3, 4, 1, 1, 5),
(18, 2, 1, 2, 1, 6),
(19, 1, 1, 6, 1, 6),
(20, 1, 1, 1, 1, 7);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `id` int(11) UNSIGNED NOT NULL,
  `product_name` varchar(20) NOT NULL,
  `description` tinytext NOT NULL,
  `price` decimal(8,2) UNSIGNED NOT NULL,
  `quantity` mediumint(8) UNSIGNED NOT NULL,
  `visibility` tinyint(3) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `category_id` mediumint(8) UNSIGNED NOT NULL,
  `image_id` int(11) UNSIGNED NOT NULL,
  `manufacturer_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `product`
--

INSERT INTO `product` (`id`, `product_name`, `description`, `price`, `quantity`, `visibility`, `created_date`, `modified_date`, `category_id`, `image_id`, `manufacturer_id`) VALUES
(1, 'AIR ZOOM SHOE', 'A FAVORITE, REDISCOVERED.\r\nThe \'80s racer makes a triumphant return as a lifestyle sneaker in the Nike Air Zoom Mariah Flyknit Racer Men\'s Shoe. Foot-hugging Flyknit material and a quick-lace system that lets you throw it on, cinch down and go.', '150.00', 50, 1, '2017-07-09 22:20:35', '2017-07-10 10:30:36', 4, 1, 1),
(2, 'DUEL RACER SHOE', 'Inspired by the fast road racer known as the Duelist, the Nike Duel Racer Men\'s Shoe brings back the profile as a sleek lifestyle sneaker. Its dual-density cushioning for provides all-day, everyday comfort, even on marathon-long days.', '120.00', 30, 1, '2017-07-09 22:22:28', '2017-07-10 10:30:52', 4, 2, 1),
(3, 'TRAIN VIRTUE SHOE', 'The Nike Free Train Virtue Hustle Hart Night Men\'s Training Shoe locks down your foot during your workout with multiple stretch bands, while a Nike Free sole allows movement in all directions.', '130.00', 20, 1, '2017-07-09 22:24:00', '2017-07-10 10:31:12', 4, 3, 1),
(4, 'JORDAN TRAINER SHOE', 'With soft cushioning, breathable material and a strap that wraps your foot for support, the Jordan Trainer 2 Flyknit Men\'s Training Shoe takes you from the street to the gym and back in total comfort.', '140.00', 30, 1, '2017-07-09 22:24:55', '2017-07-10 10:31:24', 4, 4, 1),
(5, 'AIR FORCE SHOE', 'The legend lives on in the Nike Air Force 1 \'07 Men\'s Shoe, a modern take on the icon that blends classic style and fresh, crisp details.', '90.00', 50, 1, '2017-07-09 22:25:47', '2017-07-10 10:31:36', 4, 5, 1),
(6, 'METCON DSX SHOE', 'The lightweight Nike Metcon DSX Flyknit Men\'s Training Shoe is ready for your most demanding workoutsâ€”from wall exercises and rope climbs to sprinting and lifting.', '160.00', 20, 1, '2017-07-09 22:27:37', '2017-07-10 10:31:48', 4, 6, 1),
(7, 'CHUCK TAYLOR SHOE', 'The Converse Chuck Taylor All Star Pride Geostar is designed to help show your pride with bright details for standout style. The colors of the rainbow don\'t just adorn the upper, they\'re molded into the rubber outsole. ', '65.00', 10, 1, '2017-07-09 22:28:30', '2017-07-10 10:32:01', 4, 7, 1),
(8, 'CHUCK TAYLOR SHOE', 'The Converse Chuck Taylor All Star Pride Mesh is designed to help show your pride with vivid details and a textured look. The colors of the rainbow are molded into the rubber outsole, making each step unforgettable.', '90.00', 20, 1, '2017-07-09 22:29:57', '2017-07-10 10:32:17', 4, 8, 1),
(9, 'ONE STAR SHOE', 'The Converse One Star Premium Suede features a premium upper and a cushioned footbed for comfort.', '85.00', 40, 1, '2017-07-09 22:50:37', '2017-07-10 10:32:23', 4, 9, 1),
(10, 'CHUCK TAYLOR SHOE', 'The Converse Chuck Taylor All Star is the one that started it all for Converse. When it comes to sneakers, thereâ€™s nothing more pure than a canvas upper and a vulcanized rubber sole.', '50.00', 40, 1, '2017-07-09 22:52:52', '2017-07-10 10:32:27', 4, 10, 1),
(11, 'FOCUS FLYKNIT SHOE', 'The Nike Free Focus Flyknit 2 Women\'s Training Shoe features an innovative sole that expands and contracts with every stepâ€”plus Flyknit construction for snug comfort and lightweight breathability.', '120.00', 30, 1, '2017-07-09 22:57:57', '2017-07-10 10:32:32', 4, 11, 1),
(12, 'NMD_R2 SHOE', 'NMD is the next step in street style innovation. Sleek and modern, these shoes subtly blend heritage looks and breakthrough design.', '65.00', 20, 1, '2017-07-09 23:07:28', '2017-07-10 10:33:20', 4, 12, 2),
(13, 'NMD_CS2 SHOE', 'A bold new chapter in adidas Originals history, the NMD links archival style with modern comfort and design.', '60.00', 30, 1, '2017-07-09 23:08:31', '2017-07-10 10:33:26', 4, 13, 2),
(14, 'NMD_R2 SHOE', 'A breakthrough design that bridges the past with the future, the adidas NMD represents the next step in trainer innovation.', '95.00', 40, 1, '2017-07-09 23:09:21', '2017-07-10 10:33:31', 4, 14, 2),
(15, 'ULTRABOOST SHOE', 'On your best running days, everything falls into place. These men\'s shoes help you find the freedom of that best-ever run.', '120.00', 50, 1, '2017-07-09 23:10:16', '2017-07-10 10:33:35', 4, 15, 2),
(16, 'Charcoal Grill', 'KORPÃ–N portable charcoal grill is perfect for bringing to the beach, the park or on a camping trip.', '16.99', 100, 1, '2017-07-09 23:17:36', '2017-07-09 23:27:37', 9, 16, 3),
(17, 'Grill with Cart', 'With Ã„PPLARÃ–/KLASEN charcoal barbeque and Ã„PPLARÃ–/KLASEN trolley you get a cooking area combined with a practical area to put both serving plates and barbeque accessories.', '198.00', 70, 1, '2017-07-09 23:18:28', '2017-07-09 23:27:07', 9, 17, 3),
(18, 'Serving cart', 'The KLASEN cart provides an extra storage area which can be moved easily.', '99.00', 60, 1, '2017-07-09 23:19:23', '2017-07-09 23:28:03', 9, 18, 3),
(19, 'Training cup', 'This cup makes it easier for your baby to hold and drink by themselves since it has a spout and two large handles.', '2.00', 100, 1, '2017-07-09 23:20:32', '2017-07-09 23:28:40', 9, 19, 3),
(20, 'Bowl', 'Bowl.', '3.99', 100, 1, '2017-07-09 23:21:40', '2017-07-09 23:29:30', 9, 20, 3),
(21, 'Steel Flask', 'The insert is made of metal and is impact resistant.', '14.99', 70, 1, '2017-07-09 23:23:30', '2017-07-09 23:29:11', 9, 21, 3),
(22, 'Tea Maker', 'Pour hot water over ground coffee beans or tea leaves, push down the strainer and serve straight from the coffee/tea maker.', '7.99', 50, 1, '2017-07-09 23:24:28', '2017-07-09 23:24:28', 9, 22, 3),
(23, 'Teapot', 'Simple yet timeless tableware with a traditional style and soft round shapes with attention to details.', '9.99', 50, 1, '2017-07-09 23:25:14', '2017-07-09 23:25:14', 9, 23, 3),
(24, 'Place mat', 'Protects the table top surface and reduces noise from plates and flatware.', '3.99', 45, 1, '2017-07-09 23:31:57', '2017-07-09 23:31:57', 9, 24, 3),
(25, 'Table Mat', 'The runner protects the table and creates a decorative table setting.', '3.99', 35, 1, '2017-07-09 23:32:35', '2017-07-10 10:34:39', 9, 25, 3),
(26, 'Emboider Lawn Shirt', 'Lawn shirt with embroidered placket. (Long Length 38.5\\", Short Shirt 36\\")', '17.90', 20, 1, '2017-07-09 23:43:09', '2017-07-10 10:35:42', 2, 26, 4),
(27, 'Embroider Shirt', 'Printed shirt completed with embroidered neckline.', '28.90', 20, 1, '2017-07-09 23:44:52', '2017-07-10 10:36:00', 2, 27, 4),
(28, 'Cotton Top', 'Printed top with buttoned neck.', '11.90', 20, 1, '2017-07-09 23:46:22', '2017-07-09 23:46:22', 2, 28, 4),
(29, 'Embroider lawn Shirt', 'Summer\'s lawn printed shirt completed with embroidered placket.', '19.00', 20, 1, '2017-07-09 23:47:57', '2017-07-10 10:36:12', 2, 29, 4),
(30, 'NAVY BLUE Shirt', 'Cotton rich fabric composition i.e. 60% cotton and 40% polyester is used for the construction of this shirt to keep you comfortable without compromising on the elegance and style.', '23.95', 20, 1, '2017-07-09 23:56:37', '2017-07-10 10:36:57', 2, 30, 5),
(31, 'MULTI CHECK Shirt', 'Cotton rich fabric composition i.e. 60% cotton and 40% polyester is used for the construction of this shirt to keep you comfortable without compromising on the elegance and style.', '23.95', 15, 1, '2017-07-09 23:57:59', '2017-07-10 10:37:03', 2, 31, 5),
(32, 'BLUE AND WHITE Shirt', 'Cotton rich fabric composition i.e. 60% cotton and 40% polyester is used for the construction of this shirt to keep you comfortable without compromising on the elegance and style.', '23.95', 15, 1, '2017-07-09 23:59:07', '2017-07-10 10:37:10', 2, 32, 5),
(33, 'KHAKI PLAIN Trouser', 'Have a sharp and decent look from office to evening out with this appealing formal trouser. This well-made trouser is best to wear for smart occasions. ', '27.50', 20, 1, '2017-07-10 00:00:22', '2017-07-10 10:37:53', 2, 33, 5),
(34, 'BROWN PLAIN Trouser', 'Have a sharp and decent look from office to evening out with this appealing formal trouser. This well-made trouser is best to wear for smart occasions. ', '27.50', 25, 1, '2017-07-10 00:01:47', '2017-07-10 10:38:01', 2, 34, 5),
(35, 'BEIGE PLAIN Trouser', 'Have a sharp and decent look from office to evening out with this appealing formal trouser. This well-made trouser is best to wear for smart occasions. ', '27.50', 15, 1, '2017-07-10 00:02:37', '2017-07-10 10:38:07', 2, 35, 5),
(36, 'CURVE RUST Kurta', 'Wash & wear fabric is used for the construction of this wear that makes it crumple resistant and gives your suit a glossy look.', '28.95', 15, 1, '2017-07-10 00:03:55', '2017-07-10 10:38:19', 2, 36, 5),
(37, 'CURVE PURPLE Kurta', 'Wash & wear fabric is used for the construction of this wear that makes it crumple resistant and gives your suit a glossy look.', '28.95', 15, 1, '2017-07-10 00:05:37', '2017-07-10 10:38:24', 2, 37, 5),
(38, 'superstar shoe', 'this new', '90.00', 10, 1, '2017-07-11 11:39:46', '2017-07-11 11:39:46', 4, 38, 1);

-- --------------------------------------------------------

--
-- Stand-in structure for view `product_order`
-- (See below for the actual view)
--
CREATE TABLE `product_order` (
`product_id` int(11) unsigned
,`orders` bigint(21)
);

-- --------------------------------------------------------

--
-- Table structure for table `review`
--

CREATE TABLE `review` (
  `id` int(11) UNSIGNED NOT NULL,
  `message` tinytext NOT NULL,
  `rating` tinyint(3) UNSIGNED NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `buyer_id` int(11) UNSIGNED NOT NULL,
  `product_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `review`
--

INSERT INTO `review` (`id`, `message`, `rating`, `created_date`, `modified_date`, `buyer_id`, `product_id`) VALUES
(1, 'ONE OF THE BEST SNEAKERS THAT WILL MAKE YOUR OUTFIT LOOK GREAT', 4, '2017-07-11 00:10:53', '2017-07-11 00:10:53', 1, 5),
(2, 'HAS BEEN BASICALLY A RELIABLE SHOE, AND I APPRECIATE THE FULL WHITE LEATHER', 5, '2017-07-11 00:17:21', '2017-07-11 00:17:21', 3, 5),
(3, 'I love this very much. Always feel comfortable and stronger for all my trainings.', 4, '2017-07-11 00:18:23', '2017-07-11 00:18:23', 3, 1),
(4, 'SLIGHTLY BETTER THAN THE RACER', 3, '2017-07-11 00:19:57', '2017-07-11 00:19:57', 2, 1),
(5, 'Just a perfect shoe man I can\'t complain about anything', 4, '2017-07-11 00:22:21', '2017-07-11 00:22:21', 2, 15),
(6, 'These shoes seem very well made. They are very comfortable. The cushioning inside is a very nice touch. Nice looking, and very comfortable shoe.', 5, '2017-07-11 00:24:31', '2017-07-11 00:24:31', 1, 9);

-- --------------------------------------------------------

--
-- Table structure for table `user`
--

CREATE TABLE `user` (
  `id` int(11) UNSIGNED NOT NULL,
  `username` varchar(15) NOT NULL,
  `pwd` varchar(255) NOT NULL,
  `created_date` datetime NOT NULL,
  `modified_date` datetime NOT NULL,
  `role` tinyint(3) UNSIGNED NOT NULL,
  `role_id` int(11) UNSIGNED NOT NULL,
  `address_id` int(11) UNSIGNED NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `username`, `pwd`, `created_date`, `modified_date`, `role`, `role_id`, `address_id`) VALUES
(1, 'sherali', '$2y$10$WUYfnF.Zz3JRhK4nT93wleab0SY1SSeMH4UM4UN6IYL.5aZNTgRnS', '2017-07-09 22:02:37', '2017-07-11 15:05:04', 1, 1, 1),
(2, 'nike', '$2y$10$Nu50bNym1rqhqL9mTURZruGGAC/QJ0K9j2pAbRLr/hhhEu4evk7p2', '2017-07-09 22:17:17', '2017-07-09 22:17:17', 2, 1, 2),
(3, 'adidas', '$2y$10$WoH2/71cRk1c3q4WyruZI.b.cy9abQVu81AkDL9AJFHYBTQ2QHmzG', '2017-07-09 23:04:05', '2017-07-09 23:04:05', 2, 2, 3),
(4, 'ikea', '$2y$10$CSLkdoinmSnWhlZmzVLzxuarsKv1SALgSu57KQzZcgyvGCtNBvRVK', '2017-07-09 23:15:26', '2017-07-09 23:15:26', 2, 3, 4),
(5, 'limelight', '$2y$10$ytkaSfmRWrFHFs1hqUZUZOT3oKPcawApI2tdpld9ghXGlY/ddYGIC', '2017-07-09 23:37:24', '2017-07-09 23:37:24', 2, 4, 5),
(6, 'uniworth', '$2y$10$i8THLZl1uiJKBWbpSmyFhORaQNo2relyUsnjph6PLEDyzdZZ5ocqa', '2017-07-09 23:52:38', '2017-07-09 23:52:38', 2, 5, 6),
(7, 'ahmad', '$2y$10$WVh3W5tT0KEdrsLB0rFRNuNji15CVLjE.kBDxzoN6rTsRdM2.J6Bu', '2017-07-10 23:03:08', '2017-07-10 23:03:08', 3, 1, 7),
(8, 'muneeb', '$2y$10$uOhcu04vljTiNpXsatwRUu62XZDeZBKlKp11MZmNoxeTXa8NhxcaS', '2017-07-10 23:04:53', '2017-07-10 23:04:53', 3, 2, 8),
(9, 'usman', '$2y$10$fzvnakQmHkPJHhXzxmU6WOO2YjJC9WmTLRIj2Qr0GtMl3lIe2uP6i', '2017-07-10 23:06:34', '2017-07-10 23:06:34', 3, 3, 9),
(10, 'arslan', '$2y$10$tFh1l9FlM4ZQBy.Jv7h.zOvA5w4Eg3vkn5DaYz3ZnDkTKwpCcENoe', '2017-07-10 23:08:52', '2017-07-10 23:08:52', 3, 4, 10);

-- --------------------------------------------------------

--
-- Structure for view `product_order`
--
DROP TABLE IF EXISTS `product_order`;

CREATE ALGORITHM=UNDEFINED DEFINER=`sherali`@`localhost` SQL SECURITY DEFINER VIEW `product_order`  AS  (select `p`.`id` AS `product_id`,coalesce(`po`.`no_of_orders`,0) AS `orders` from (`product` `p` left join (select `od`.`product_id` AS `product_id`,count(`od`.`product_id`) AS `no_of_orders` from `order_detail` `od` group by `od`.`product_id`) `po` on((`p`.`id` = `po`.`product_id`)))) ;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `address`
--
ALTER TABLE `address`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer`
--
ALTER TABLE `buyer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `buyer_order`
--
ALTER TABLE `buyer_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`);

--
-- Indexes for table `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `image`
--
ALTER TABLE `image`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `manufacturer`
--
ALTER TABLE `manufacturer`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD PRIMARY KEY (`id`),
  ADD KEY `product_id` (`product_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`),
  ADD KEY `buyer_order_id` (`buyer_order_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `image_id` (`image_id`),
  ADD KEY `manufacturer_id` (`manufacturer_id`);

--
-- Indexes for table `review`
--
ALTER TABLE `review`
  ADD PRIMARY KEY (`id`),
  ADD KEY `buyer_id` (`buyer_id`),
  ADD KEY `product_id` (`product_id`);

--
-- Indexes for table `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `username` (`username`),
  ADD KEY `address_id` (`address_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `address`
--
ALTER TABLE `address`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- AUTO_INCREMENT for table `admin`
--
ALTER TABLE `admin`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `buyer`
--
ALTER TABLE `buyer`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `buyer_order`
--
ALTER TABLE `buyer_order`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;
--
-- AUTO_INCREMENT for table `category`
--
ALTER TABLE `category`
  MODIFY `id` mediumint(8) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;
--
-- AUTO_INCREMENT for table `image`
--
ALTER TABLE `image`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `manufacturer`
--
ALTER TABLE `manufacturer`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;
--
-- AUTO_INCREMENT for table `order_detail`
--
ALTER TABLE `order_detail`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=21;
--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=39;
--
-- AUTO_INCREMENT for table `review`
--
ALTER TABLE `review`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;
--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `buyer_order`
--
ALTER TABLE `buyer_order`
  ADD CONSTRAINT `buyer_order_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`);

--
-- Constraints for table `order_detail`
--
ALTER TABLE `order_detail`
  ADD CONSTRAINT `order_detail_ibfk_1` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_2` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`),
  ADD CONSTRAINT `order_detail_ibfk_3` FOREIGN KEY (`buyer_order_id`) REFERENCES `buyer_order` (`id`);

--
-- Constraints for table `product`
--
ALTER TABLE `product`
  ADD CONSTRAINT `product_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `product_ibfk_2` FOREIGN KEY (`image_id`) REFERENCES `image` (`id`),
  ADD CONSTRAINT `product_ibfk_3` FOREIGN KEY (`manufacturer_id`) REFERENCES `manufacturer` (`id`);

--
-- Constraints for table `review`
--
ALTER TABLE `review`
  ADD CONSTRAINT `review_ibfk_1` FOREIGN KEY (`buyer_id`) REFERENCES `buyer` (`id`),
  ADD CONSTRAINT `review_ibfk_2` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`);

--
-- Constraints for table `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `user_ibfk_1` FOREIGN KEY (`address_id`) REFERENCES `address` (`id`);

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
