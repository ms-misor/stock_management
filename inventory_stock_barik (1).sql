-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 25, 2022 at 11:33 AM
-- Server version: 10.4.13-MariaDB
-- PHP Version: 7.4.8

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `inventory_stock_barik`
--

-- --------------------------------------------------------

--
-- Table structure for table `activity_logs`
--

CREATE TABLE `activity_logs` (
  `activity_id` int(11) NOT NULL,
  `user_id` varchar(11) CHARACTER SET utf8mb4 NOT NULL,
  `type` text CHARACTER SET utf8mb4 NOT NULL,
  `action` varchar(15) CHARACTER SET utf8mb4 NOT NULL COMMENT 'create, update, delete',
  `action_id` varchar(15) CHARACTER SET utf8mb4 NOT NULL,
  `table_name` varchar(30) CHARACTER SET utf8mb4 DEFAULT NULL,
  `slug` varchar(150) CHARACTER SET utf8mb4 NOT NULL,
  `form_data` text CHARACTER SET utf8mb4 DEFAULT NULL,
  `create_date` datetime NOT NULL DEFAULT current_timestamp(),
  `status` tinyint(1) NOT NULL DEFAULT 0 COMMENT '1=created, 2=updated, 3=deleted	'
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `activity_logs`
--

INSERT INTO `activity_logs` (`activity_id`, `user_id`, `type`, `action`, `action_id`, `table_name`, `slug`, `form_data`, `create_date`, `status`) VALUES
(1, '1', 'products stocks', 'created', '1', 'products', 'products/create', '{\"name\":\"Pran juice\",\"price\":\"20\",\"qty\":\"0\",\"unit\":\"4\",\"attribute_value_id\":\"null\",\"brand_id\":\"[\\\"5\\\"]\",\"category_id\":\"[\\\"5\\\"]\",\"availability\":\"1\",\"create_date\":\"2022-02-25 04:21:52\"}', '2022-02-25 16:21:52', 1),
(2, '1', 'products stocks', 'created', '2', 'products', 'products/create', '{\"name\":\"test\",\"price\":\"100\",\"qty\":\"0\",\"unit\":\"5\",\"attribute_value_id\":\"null\",\"brand_id\":\"[\\\"4\\\"]\",\"category_id\":\"[\\\"4\\\"]\",\"availability\":\"1\",\"create_date\":\"2022-02-25 04:22:14\"}', '2022-02-25 16:22:14', 1),
(3, '1', 'products stocks', 'updated', '2', 'products', 'products/update/2', '{\"name\":\"test\",\"price\":\"100\",\"unit\":\"5\",\"attribute_value_id\":\"null\",\"brand_id\":\"[\\\"4\\\"]\",\"category_id\":\"[\\\"4\\\"]\",\"availability\":\"2\",\"update_date\":\"2022-02-25 04:22:35\"}', '2022-02-25 16:22:35', 2),
(4, '1', 'products stocks', 'deleted', '2', 'products', 'products/remove', '{\"product_id\":\"2\"}', '2022-02-25 16:22:40', 3),
(5, '1', 'products stocks', 'created', '3', 'products', 'products/create', '{\"name\":\"test pro\",\"price\":\"100\",\"qty\":\"0\",\"unit\":\"4\",\"attribute_value_id\":\"null\",\"brand_id\":\"[\\\"4\\\"]\",\"category_id\":\"[\\\"4\\\"]\",\"availability\":\"1\",\"create_date\":\"2022-02-25 04:23:13\"}', '2022-02-25 16:23:13', 1),
(6, '1', 'purchase items', 'create', '1', 'products_items', 'products/item_create', '{\"product_id\":\"1\",\"price\":\"25\",\"total_price\":\"1250.00\",\"qty\":\"50\",\"big_unit\":\"1\",\"big_unit_qty\":\"2\",\"small_unit\":\"4\",\"small_unit_qty\":\"10\",\"purchase_date\":\"2022-02-25\",\"create_date\":\"2022-02-25 04:24:14\"}', '2022-02-25 16:24:14', 1),
(7, '1', 'products qty updated from purchase', 'updated', '1', 'products', 'products/item_create', '{\"price\":\"25\",\"qty\":50}', '2022-02-25 16:24:14', 2),
(8, '1', 'products stocks', 'updated', '3', 'products', 'products/update/3', '{\"name\":\"test pro\",\"price\":\"100\",\"unit\":\"5\",\"attribute_value_id\":\"null\",\"brand_id\":\"[\\\"4\\\"]\",\"category_id\":\"[\\\"4\\\"]\",\"availability\":\"1\",\"update_date\":\"2022-02-25 04:25:11\"}', '2022-02-25 16:25:11', 2),
(9, '1', 'purchase items', 'create', '2', 'products_items', 'products/item_create', '{\"product_id\":\"3\",\"price\":\"110\",\"total_price\":\"2970.00\",\"qty\":\"27\",\"big_unit\":\"4\",\"big_unit_qty\":\"1\",\"small_unit\":\"5\",\"small_unit_qty\":\"2\",\"purchase_date\":\"2022-02-24\",\"create_date\":\"2022-02-25 04:26:25\"}', '2022-02-25 16:26:25', 1),
(10, '1', 'products qty updated from purchase', 'updated', '3', 'products', 'products/item_create', '{\"price\":\"110\",\"qty\":27}', '2022-02-25 16:26:26', 2);

-- --------------------------------------------------------

--
-- Table structure for table `attributes`
--

CREATE TABLE `attributes` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `attribute_value`
--

CREATE TABLE `attribute_value` (
  `id` int(11) NOT NULL,
  `value` varchar(255) NOT NULL,
  `attribute_parent_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `attribute_value`
--

INSERT INTO `attribute_value` (`id`, `value`, `attribute_parent_id`) VALUES
(5, 'Blue', 2),
(6, 'White', 2),
(7, 'M', 3),
(8, 'L', 3),
(9, 'Green', 2),
(10, 'Black', 2),
(12, 'Grey', 2),
(13, 'S', 3);

-- --------------------------------------------------------

--
-- Table structure for table `big_units`
--

CREATE TABLE `big_units` (
  `big_unit_id` int(11) NOT NULL,
  `name` text NOT NULL COMMENT 'big unit name',
  `unit_id` varchar(11) NOT NULL COMMENT 'small unit from units table',
  `qty` varchar(11) NOT NULL COMMENT 'small unit qty',
  `active` tinyint(4) NOT NULL DEFAULT 1,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `big_units`
--

INSERT INTO `big_units` (`big_unit_id`, `name`, `unit_id`, `qty`, `active`, `create_date`, `update_date`) VALUES
(1, 'Jar Cartoon', '4', '20', 1, '2022-01-28 11:47:41', NULL),
(4, 'Bag Cartoon', '5', '25', 1, '2022-01-29 10:31:52', NULL),
(5, 'Poly Cartoon', '7', '30', 1, '2022-01-29 10:32:14', NULL),
(6, 'poly cartonn 2', '7', '40', 1, '2022-02-06 05:13:38', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `brands`
--

INSERT INTO `brands` (`id`, `name`, `active`) VALUES
(4, 'ABC Inc.', 1),
(5, 'pran', 1);

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `categories`
--

INSERT INTO `categories` (`id`, `name`, `active`) VALUES
(4, 'Microscope', 1),
(5, 'juices', 1);

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(11) NOT NULL,
  `company_name` varchar(255) NOT NULL,
  `service_charge_value` varchar(255) NOT NULL,
  `vat_charge_value` varchar(255) NOT NULL,
  `address` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `country` varchar(255) NOT NULL,
  `message` text NOT NULL,
  `currency` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `company_name`, `service_charge_value`, `vat_charge_value`, `address`, `phone`, `country`, `message`, `currency`) VALUES
(1, 'ABC Inc.', '', '', '1234 Main St. Los Angeles, CA 98765 U.S.A.', '(123) 456-7890', 'United States of America', 'Sample message<br>', 'USD');

-- --------------------------------------------------------

--
-- Table structure for table `groups`
--

CREATE TABLE `groups` (
  `id` int(11) NOT NULL,
  `group_name` varchar(255) NOT NULL,
  `permission` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `groups`
--

INSERT INTO `groups` (`id`, `group_name`, `permission`) VALUES
(1, 'Administrator', 'a:36:{i:0;s:10:\"createUser\";i:1;s:10:\"updateUser\";i:2;s:8:\"viewUser\";i:3;s:10:\"deleteUser\";i:4;s:11:\"createGroup\";i:5;s:11:\"updateGroup\";i:6;s:9:\"viewGroup\";i:7;s:11:\"deleteGroup\";i:8;s:11:\"createBrand\";i:9;s:11:\"updateBrand\";i:10;s:9:\"viewBrand\";i:11;s:11:\"deleteBrand\";i:12;s:14:\"createCategory\";i:13;s:14:\"updateCategory\";i:14;s:12:\"viewCategory\";i:15;s:14:\"deleteCategory\";i:16;s:11:\"createStore\";i:17;s:11:\"updateStore\";i:18;s:9:\"viewStore\";i:19;s:11:\"deleteStore\";i:20;s:15:\"createAttribute\";i:21;s:15:\"updateAttribute\";i:22;s:13:\"viewAttribute\";i:23;s:15:\"deleteAttribute\";i:24;s:13:\"createProduct\";i:25;s:13:\"updateProduct\";i:26;s:11:\"viewProduct\";i:27;s:13:\"deleteProduct\";i:28;s:11:\"createOrder\";i:29;s:11:\"updateOrder\";i:30;s:9:\"viewOrder\";i:31;s:11:\"deleteOrder\";i:32;s:11:\"viewReports\";i:33;s:13:\"updateCompany\";i:34;s:11:\"viewProfile\";i:35;s:13:\"updateSetting\";}'),
(4, 'Owners', 'a:36:{i:0;s:10:\"createUser\";i:1;s:10:\"updateUser\";i:2;s:8:\"viewUser\";i:3;s:10:\"deleteUser\";i:4;s:11:\"createGroup\";i:5;s:11:\"updateGroup\";i:6;s:9:\"viewGroup\";i:7;s:11:\"deleteGroup\";i:8;s:11:\"createBrand\";i:9;s:11:\"updateBrand\";i:10;s:9:\"viewBrand\";i:11;s:11:\"deleteBrand\";i:12;s:14:\"createCategory\";i:13;s:14:\"updateCategory\";i:14;s:12:\"viewCategory\";i:15;s:14:\"deleteCategory\";i:16;s:11:\"createStore\";i:17;s:11:\"updateStore\";i:18;s:9:\"viewStore\";i:19;s:11:\"deleteStore\";i:20;s:15:\"createAttribute\";i:21;s:15:\"updateAttribute\";i:22;s:13:\"viewAttribute\";i:23;s:15:\"deleteAttribute\";i:24;s:13:\"createProduct\";i:25;s:13:\"updateProduct\";i:26;s:11:\"viewProduct\";i:27;s:13:\"deleteProduct\";i:28;s:11:\"createOrder\";i:29;s:11:\"updateOrder\";i:30;s:9:\"viewOrder\";i:31;s:11:\"deleteOrder\";i:32;s:11:\"viewReports\";i:33;s:13:\"updateCompany\";i:34;s:11:\"viewProfile\";i:35;s:13:\"updateSetting\";}');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `id` int(11) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `customer_name` varchar(255) NOT NULL,
  `customer_address` varchar(255) NOT NULL,
  `customer_phone` varchar(255) NOT NULL,
  `date_time` varchar(255) NOT NULL,
  `gross_amount` varchar(255) NOT NULL,
  `service_charge_rate` varchar(255) NOT NULL,
  `service_charge` varchar(255) NOT NULL,
  `vat_charge_rate` varchar(255) NOT NULL,
  `vat_charge` varchar(255) NOT NULL,
  `net_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `paid_status` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `status` varchar(50) DEFAULT 'draft' COMMENT 'draft or approved',
  `return_qty_print` int(1) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `orders_item`
--

CREATE TABLE `orders_item` (
  `id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `return_qty` varchar(11) NOT NULL DEFAULT '0' COMMENT 'return qty',
  `rate` varchar(255) NOT NULL,
  `sale_rate` varchar(255) NOT NULL COMMENT 'item will be sold in this price',
  `amount` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products`
--

CREATE TABLE `products` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `sku` varchar(255) DEFAULT NULL,
  `price` varchar(255) NOT NULL,
  `unit` varchar(50) NOT NULL,
  `qty` varchar(255) NOT NULL,
  `image` text DEFAULT NULL,
  `description` text DEFAULT NULL,
  `attribute_value_id` text DEFAULT NULL,
  `brand_id` text NOT NULL,
  `category_id` text NOT NULL,
  `store_id` int(11) DEFAULT NULL,
  `availability` int(11) NOT NULL,
  `create_date` datetime DEFAULT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `products_items`
--

CREATE TABLE `products_items` (
  `id` int(11) NOT NULL,
  `product_id` varchar(11) NOT NULL,
  `big_unit` varchar(11) DEFAULT NULL,
  `big_unit_qty` varchar(11) DEFAULT NULL,
  `small_unit` varchar(11) NOT NULL,
  `small_unit_qty` varchar(11) NOT NULL,
  `price` varchar(11) NOT NULL,
  `total_price` varchar(11) NOT NULL,
  `qty` varchar(11) NOT NULL COMMENT 'Total qty',
  `description` text DEFAULT NULL,
  `purchase_date` date NOT NULL,
  `no_update_delete` tinyint(4) DEFAULT 0 COMMENT 'if similar stock product based item created, then not allow to delete previous items',
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `stores`
--

CREATE TABLE `stores` (
  `id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `active` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `stores`
--

INSERT INTO `stores` (`id`, `name`, `active`) VALUES
(3, 'Test Store', 1),
(4, 'Lion Battery', 1),
(5, 'store 1', 1);

-- --------------------------------------------------------

--
-- Table structure for table `units`
--

CREATE TABLE `units` (
  `id` int(11) NOT NULL,
  `name` varchar(50) NOT NULL,
  `active` int(11) NOT NULL,
  `create_date` datetime NOT NULL,
  `update_date` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `units`
--

INSERT INTO `units` (`id`, `name`, `active`, `create_date`, `update_date`) VALUES
(4, 'Jar', 1, '0000-00-00 00:00:00', NULL),
(5, 'bag', 1, '0000-00-00 00:00:00', '2022-01-28 04:51:04'),
(7, 'poly', 1, '2022-01-22 05:39:16', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` int(11) NOT NULL,
  `username` varchar(255) NOT NULL,
  `password` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `firstname` varchar(255) NOT NULL,
  `lastname` varchar(255) NOT NULL,
  `phone` varchar(255) NOT NULL,
  `gender` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `username`, `password`, `email`, `firstname`, `lastname`, `phone`, `gender`) VALUES
(1, 'admin', 'e10adc3949ba59abbe56e057f20f883e', 'admin@example.com', 'admin', 'a', '12345678910', 1);

-- --------------------------------------------------------

--
-- Table structure for table `user_group`
--

CREATE TABLE `user_group` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `group_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `user_group`
--

INSERT INTO `user_group` (`id`, `user_id`, `group_id`) VALUES
(1, 1, 1);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `activity_logs`
--
ALTER TABLE `activity_logs`
  ADD PRIMARY KEY (`activity_id`);

--
-- Indexes for table `attributes`
--
ALTER TABLE `attributes`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `attribute_value`
--
ALTER TABLE `attribute_value`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `big_units`
--
ALTER TABLE `big_units`
  ADD PRIMARY KEY (`big_unit_id`);

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders_item`
--
ALTER TABLE `orders_item`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products`
--
ALTER TABLE `products`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `products_items`
--
ALTER TABLE `products_items`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `stores`
--
ALTER TABLE `stores`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `units`
--
ALTER TABLE `units`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `user_group`
--
ALTER TABLE `user_group`
  ADD PRIMARY KEY (`id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `activity_logs`
--
ALTER TABLE `activity_logs`
  MODIFY `activity_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=11;

--
-- AUTO_INCREMENT for table `attributes`
--
ALTER TABLE `attributes`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `attribute_value`
--
ALTER TABLE `attribute_value`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT for table `big_units`
--
ALTER TABLE `big_units`
  MODIFY `big_unit_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `orders_item`
--
ALTER TABLE `orders_item`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products`
--
ALTER TABLE `products`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `products_items`
--
ALTER TABLE `products_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stores`
--
ALTER TABLE `stores`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `units`
--
ALTER TABLE `units`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT for table `user_group`
--
ALTER TABLE `user_group`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
