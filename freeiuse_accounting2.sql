-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: May 07, 2025 at 07:01 PM
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
-- Database: `freeiuse_accounting2`
--

-- --------------------------------------------------------

--
-- Table structure for table `brands`
--

CREATE TABLE `brands` (
  `brand_id` int(11) NOT NULL,
  `brand_name` varchar(255) NOT NULL,
  `brand_active` int(11) NOT NULL DEFAULT 0,
  `brand_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `brokers`
--

CREATE TABLE `brokers` (
  `broker_id` int(255) NOT NULL,
  `broker_name` text NOT NULL,
  `broker_phone` text NOT NULL,
  `broker_email` text NOT NULL,
  `broker_address` text NOT NULL,
  `broker_status` int(11) NOT NULL,
  `adddatetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget`
--

CREATE TABLE `budget` (
  `budget_id` int(11) NOT NULL,
  `budget_name` text NOT NULL,
  `budget_amount` double NOT NULL,
  `budget_type` varchar(300) NOT NULL,
  `voucher_id` int(11) DEFAULT NULL,
  `voucher_type` int(11) DEFAULT NULL,
  `budget_date` date NOT NULL,
  `budget_add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `budget_category`
--

CREATE TABLE `budget_category` (
  `budget_category_id` int(11) NOT NULL,
  `budget_category_name` text NOT NULL,
  `budget_category_type` varchar(400) NOT NULL,
  `budget_category_add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `categories`
--

CREATE TABLE `categories` (
  `categories_id` int(11) NOT NULL,
  `categories_name` varchar(255) NOT NULL,
  `category_price` varchar(100) NOT NULL DEFAULT '1',
  `category_purchase` varchar(100) NOT NULL,
  `categories_active` int(11) NOT NULL DEFAULT 0,
  `categories_status` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `checks`
--

CREATE TABLE `checks` (
  `check_id` int(11) NOT NULL,
  `check_no` varchar(250) DEFAULT NULL,
  `check_bank_name` varchar(250) DEFAULT NULL,
  `check_expiry_date` varchar(100) DEFAULT NULL,
  `check_type` varchar(100) DEFAULT NULL,
  `check_amount` varchar(100) DEFAULT NULL,
  `voucher_id` int(11) NOT NULL,
  `customer_id` int(11) NOT NULL DEFAULT 0,
  `check_status` int(11) NOT NULL DEFAULT 0,
  `check_location` text DEFAULT NULL,
  `check_timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `company`
--

CREATE TABLE `company` (
  `id` int(10) NOT NULL,
  `name` varchar(100) NOT NULL,
  `logo` text NOT NULL,
  `address` text DEFAULT NULL,
  `company_phone` varchar(100) NOT NULL,
  `personal_phone` varchar(100) NOT NULL,
  `email` text DEFAULT NULL,
  `stock_manage` int(11) NOT NULL,
  `sale_interface` varchar(50) DEFAULT NULL,
  `print_url` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `company`
--

INSERT INTO `company` (`id`, `name`, `logo`, `address`, `company_phone`, `personal_phone`, `email`, `stock_manage`, `sale_interface`, `print_url`) VALUES
(5, 'Lasani Bedsheets', '1627588470675982bc641fe.png', 'Head Office : P#10 Central Mill Road , Ayub Colony Jhang road Faisalabad Pakistan  ', '123456897', '231546897', 'https://lasanibedsheets.com/', 1, '', 'print_order.php');

-- --------------------------------------------------------

--
-- Table structure for table `customers`
--

CREATE TABLE `customers` (
  `customer_id` int(11) NOT NULL,
  `customer_name` varchar(2000) NOT NULL,
  `customer_email` varchar(200) NOT NULL,
  `customer_phone` varchar(13) NOT NULL,
  `customer_address` text NOT NULL,
  `customer_status` int(255) NOT NULL,
  `customer_type` varchar(250) DEFAULT NULL,
  `customer_limit` varchar(10) NOT NULL DEFAULT '0',
  `customer_add_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `expenses`
--

CREATE TABLE `expenses` (
  `expense_id` int(11) NOT NULL,
  `expense_name` varchar(100) DEFAULT NULL,
  `expense_status` int(11) DEFAULT NULL,
  `timestamp` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `id` int(11) NOT NULL,
  `title` text DEFAULT NULL,
  `page` text DEFAULT NULL,
  `parent_id` int(11) DEFAULT NULL,
  `icon` text DEFAULT NULL,
  `sort_order` int(11) DEFAULT NULL,
  `nav_edit` int(11) NOT NULL DEFAULT 0,
  `nav_delete` int(11) NOT NULL DEFAULT 0,
  `nav_add` int(11) NOT NULL DEFAULT 0,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`id`, `title`, `page`, `parent_id`, `icon`, `sort_order`, `nav_edit`, `nav_delete`, `nav_add`, `timestamp`) VALUES
(97, 'accounts', '#', 0, 'fa fa-edit', 4, 1, 1, 1, '2024-08-06 06:28:41'),
(98, 'customers', 'customers.php?type=customer', 97, 'fa fa-edit', 4, 1, 1, 1, '2021-04-13 20:03:33'),
(99, 'banks', 'customers.php?type=bank', 97, 'fa fa-edit', 2, 1, 1, 1, '2021-04-13 20:03:33'),
(100, 'users', 'users.php', 97, 'fa fa-edit', 3, 1, 1, 1, '2021-04-13 20:03:33'),
(101, 'vouchers', '#', 0, 'fa fa-clipboard-list', 3, 0, 0, 0, '2024-08-06 06:29:01'),
(103, 'view vouchers', 'voucher.php?act=list', 101, 'fas fa-clipboard-list', 7, 1, 1, 1, '2021-04-13 20:03:33'),
(104, 'order', '#', 0, 'fas fa-cart-plus', 2, 0, 0, 0, '2024-08-06 06:29:01'),
(105, 'Cash Sale', 'cash_sale.php', 104, 'fas fa-cart-plus', 9, 1, 0, 1, '2021-04-13 20:03:33'),
(107, 'others', '#', 0, 'fa fa-edit', 8, 0, 0, 0, '2021-09-19 13:04:12'),
(108, 'Add Products', 'product.php?act=add', 148, 'fa fa-edit', 12, 1, 1, 1, '2021-09-19 13:01:26'),
(109, 'view products', 'product.php?act=list', 148, 'fa fa-edit', 13, 1, 1, 1, '2021-09-19 13:03:25'),
(110, 'brands', 'brands.php#', 107, 'fa fa-edit', 14, 1, 1, 1, '2021-04-13 20:03:34'),
(111, 'Credit Sale', 'credit_sale.php?credit_type=15days', 104, 'fa fa-edit', 15, 1, 1, 1, '2022-03-07 09:52:12'),
(112, 'purchase', '#', 0, 'fa fa-edit', 1, 0, 0, 0, '2024-08-06 06:29:01'),
(113, 'Cash Purchase', 'cash_purchase.php', 112, 'fa fa-edit', NULL, 1, 1, 1, '2021-04-13 22:33:37'),
(114, 'credit purchase', 'credit_purchase.php', 112, 'fa fa-edit', NULL, 1, 1, 1, '2021-04-13 22:34:31'),
(115, 'Reports', '#', 0, 'fa fa-edit', 6, 0, 0, 0, '2024-08-06 06:28:41'),
(116, 'bank ledger', 'reports.php?type=bank', 115, 'fa fa-edit', NULL, 1, 1, 1, '2021-04-14 21:03:11'),
(117, 'supplier ledger', 'reports.php?type=supplier', 115, 'fa fa-edit', NULL, 1, 0, 0, '2021-04-14 21:03:52'),
(118, 'customer ledger', 'reports.php?type=customer ', 115, 'fa-edit', NULL, 0, 0, 0, '2021-04-14 21:04:27'),
(119, 'view purchases', 'view_purchases.php', 112, 'add_to_queue', NULL, 1, 1, 1, '2021-04-15 21:17:07'),
(120, 'categories', 'categories.php', 107, 'fa fa-edit', NULL, 1, 1, 1, '2021-08-30 09:59:57'),
(121, 'supplier', 'customers.php?type=supplier', 97, 'fa fa-edit', NULL, 1, 1, 1, '2021-04-17 20:36:01'),
(122, 'expense ', 'customers.php?type=expense', 97, 'fa fa-edit', NULL, 1, 1, 1, '2021-04-17 20:41:42'),
(123, 'product purchase report', 'product_purchase_report.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-20 18:07:34'),
(125, 'product sale report', 'product_sale_report.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-21 19:48:47'),
(127, 'expense report', 'expence_report.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-21 20:11:51'),
(128, 'income report', 'income_report.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-21 20:12:23'),
(129, 'profit and loss', 'profit_loss.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-21 20:12:38'),
(130, 'profit summary', 'profit_summary.php', 115, 'fa fa-edit', NULL, 0, 0, 0, '2021-04-21 20:12:58'),
(131, 'trail balance', 'trail_balance.php#', 115, 'fa fa-edit', 6, 0, 0, 0, '2021-06-02 23:19:37'),
(133, 'expense type', 'expense_type.php', 107, 'local_shipping', NULL, 0, 0, 0, '2021-06-10 19:04:02'),
(134, 'analytics', 'analytics.php', 0, 'local_shipping', 7, 0, 0, 0, '2024-08-06 06:28:41'),
(135, 'Cash orders', 'view_orders.php#', 104, 'local_shipping', NULL, 0, 0, 0, '2021-06-12 16:43:17'),
(136, 'credit orders', 'credit_orders.php', 104, 'local_shipping', NULL, 0, 0, 0, '2021-06-12 16:44:41'),
(137, 'analytics', 'analytics.php', 115, 'local_shipping', NULL, 0, 0, 0, '2021-08-29 17:56:15'),
(138, 'sale reports', 'sale_report.php', 107, 'local_shipping', NULL, 0, 0, 0, '2021-06-15 17:40:17'),
(139, 'purchase reports', 'purchase_report.php', 107, 'local_shipping', NULL, 0, 0, 0, '2021-06-15 17:41:00'),
(140, 'general voucher', 'voucher.php?act=general_voucher', 101, 'local_shipping', NULL, 1, 0, 1, '2021-06-21 19:42:27'),
(141, 'expense voucher', 'voucher.php?act=expense_voucher', 101, 'local_shipping', NULL, 1, 0, 1, '2021-06-21 19:43:15'),
(142, 'single voucher', 'voucher.php?act=single_voucher#', 101, 'local_shipping', NULL, 1, 0, 1, '2021-06-21 19:44:55'),
(143, 'backup & restore', 'backup.php', 107, 'local_shipping', NULL, 1, 0, 1, '2021-06-26 20:36:25'),
(144, 'pending cash bills ', 'pending_bills.php?search_it=all', 115, '', NULL, 0, 0, 0, '2021-08-20 19:43:20'),
(145, 'pending report', 'customerpendingreport.php', 107, '', NULL, 0, 0, 0, '2021-08-20 19:58:40'),
(147, 'check list ', 'check_list.php', 101, '', NULL, 1, 1, 1, '2021-09-19 12:53:19'),
(148, 'products', '#', 0, '', 5, 0, 0, 0, '2024-08-06 06:28:41'),
(149, 'inventory products', 'inventory.php?act=add', 148, '', NULL, 1, 1, 1, '2021-09-19 12:58:26'),
(150, 'inventory p list ', 'inventory.php?act=list', 148, '', NULL, 1, 1, 1, '2021-09-19 12:59:06'),
(151, 'customer due report', 'reports3.php?type=customer#', 107, '', NULL, 0, 0, 0, '2021-11-15 11:47:04'),
(152, 'pos sale', 'cash_salegui.php', 104, '', NULL, 0, 0, 0, '2023-01-15 09:40:32');

-- --------------------------------------------------------

--
-- Table structure for table `orders`
--

CREATE TABLE `orders` (
  `order_id` int(11) NOT NULL,
  `transaction_paid_id` int(11) DEFAULT NULL,
  `order_date` date NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `cod` varchar(200) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` varchar(30) NOT NULL,
  `payment_status` int(11) NOT NULL,
  `customer_account` int(11) DEFAULT NULL,
  `payment_account` int(11) DEFAULT NULL,
  `order_status` varchar(20) NOT NULL DEFAULT '0',
  `address` varchar(500) NOT NULL,
  `charges` varchar(200) NOT NULL,
  `note` varchar(1000) NOT NULL,
  `pending_order` varchar(1000) NOT NULL,
  `tracking` varchar(200) NOT NULL,
  `customer_profit` varchar(255) NOT NULL,
  `transaction_id` int(11) NOT NULL DEFAULT 0,
  `broker_id` int(11) DEFAULT NULL,
  `type` text DEFAULT NULL,
  `delaytime` text DEFAULT NULL,
  `freight` text DEFAULT NULL,
  `order_narration` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `credit_sale_type` varchar(20) NOT NULL DEFAULT 'none',
  `vehicle_no` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `order_item`
--

CREATE TABLE `order_item` (
  `order_item_id` int(11) NOT NULL,
  `order_id` int(11) NOT NULL DEFAULT 0,
  `product_id` int(11) NOT NULL DEFAULT 0,
  `product_detail` text DEFAULT NULL,
  `quantity` double NOT NULL,
  `rate` double NOT NULL,
  `total` double NOT NULL,
  `order_item_status` int(11) NOT NULL DEFAULT 0,
  `discount` varchar(255) DEFAULT NULL,
  `gauge` text DEFAULT NULL,
  `width` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `privileges`
--

CREATE TABLE `privileges` (
  `privileges_id` int(255) NOT NULL,
  `user_id` int(11) NOT NULL,
  `nav_id` int(11) NOT NULL,
  `nav_url` text NOT NULL,
  `addby` text NOT NULL,
  `date_time` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `nav_add` int(11) NOT NULL DEFAULT 0,
  `nav_edit` int(11) NOT NULL DEFAULT 0,
  `nav_delete` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `privileges`
--

INSERT INTO `privileges` (`privileges_id`, `user_id`, `nav_id`, `nav_url`, `addby`, `date_time`, `nav_add`, `nav_edit`, `nav_delete`) VALUES
(332, 1, 98, 'customers.php?type=customer', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(333, 1, 99, 'customers.php?type=bank', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(334, 1, 100, 'users.php', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(335, 1, 102, 'voucher.php?act=add', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 0),
(336, 1, 103, 'voucher.php?act=list', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(337, 1, 105, 'cash_sale.php', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 0),
(338, 1, 106, 'view_orders.php', 'Added By: admin', '2021-04-15 21:26:49', 0, 0, 0),
(339, 1, 108, 'product.php?act=add#', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 0),
(340, 1, 109, 'product.php?act=list', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(341, 1, 110, 'brands.php#', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(342, 1, 111, 'credit_sale.php', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 0),
(343, 1, 113, 'cash_purchase.php', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(344, 1, 114, 'credit_purchase.php', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(345, 1, 116, 'reports.php?type=bank', 'Added By: admin', '2021-04-15 21:26:49', 1, 0, 1),
(346, 1, 117, 'reports.php?type=supplier', 'Added By: admin', '2021-04-15 21:26:49', 0, 0, 0),
(347, 1, 118, 'reports.php?type=customer ', 'Added By: admin', '2021-04-15 21:26:49', 0, 0, 0),
(348, 1, 119, 'view_purchases.php', 'Added By: admin', '2021-04-15 21:26:49', 0, 0, 0),
(349, 1, 0, '', 'Added By: admin', '2021-04-15 21:26:49', 0, 0, 0),
(386, 2, 98, 'customers.php?type=customer', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(387, 2, 99, 'customers.php?type=bank', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(388, 2, 100, 'users.php', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(389, 2, 102, 'voucher.php?act=add', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 0),
(390, 2, 103, 'voucher.php?act=list', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(391, 2, 105, 'cash_sale.php', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 0),
(392, 2, 106, 'view_orders.php', 'Added By: admin', '2021-06-04 21:43:16', 0, 0, 0),
(393, 2, 108, 'product.php?act=add#', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 0),
(394, 2, 109, 'product.php?act=list', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(395, 2, 110, 'brands.php#', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(396, 2, 111, 'credit_sale.php?credit_type=30days', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(397, 2, 113, 'cash_purchase.php', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 1),
(398, 2, 114, 'credit_purchase.php', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 0),
(399, 2, 116, 'reports.php?type=bank', 'Added By: admin', '2021-06-04 21:43:16', 0, 0, 0),
(400, 2, 117, 'reports.php?type=supplier', 'Added By: admin', '2021-06-04 21:43:16', 0, 0, 1),
(401, 2, 118, 'reports.php?type=customer ', 'Added By: admin', '2021-06-04 21:43:16', 1, 0, 0),
(402, 2, 119, 'view_purchases.php', 'Added By: admin', '2021-06-04 21:43:16', 0, 0, 0),
(403, 5, 0, 'voucher.php?act=general_voucher', 'Added By: admin', '2022-01-24 06:43:14', 1, 0, 0),
(1323, 5, 98, 'customers.php?type=customer', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 1),
(1324, 5, 99, 'customers.php?type=bank', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 1),
(1325, 5, 103, 'users.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1326, 5, 105, 'voucher.php?act=list', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1327, 5, 108, 'cash_sale.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1328, 5, 109, 'product.php?act=add', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1329, 5, 110, 'product.php?act=list', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1330, 5, 111, 'brands.php#', 'Added By: admin', '2022-01-23 13:40:10', 1, 1, 0),
(1331, 5, 117, 'credit_sale.php?credit_type=15days', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1332, 5, 118, 'cash_purchase.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1333, 5, 119, 'credit_purchase.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1334, 5, 120, 'reports.php?type=bank', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1335, 5, 121, 'reports.php?type=supplier', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1336, 5, 122, 'reports.php?type=customer ', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1337, 5, 123, 'view_purchases.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1338, 5, 125, 'categories.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1339, 5, 127, 'customers.php?type=supplier', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1340, 5, 132, 'customers.php?type=expense', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1341, 5, 133, 'product_purchase_report.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1342, 5, 135, 'product_sale_report.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1343, 5, 136, 'expence_report.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1344, 5, 138, 'income_report.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1345, 5, 139, 'profit_loss.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1346, 5, 140, 'profit_summary.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1347, 5, 141, 'trail_balance.php#', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1348, 5, 143, 'credit_sale.php?credit_type=30days', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1349, 5, 144, 'expense_type.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1350, 5, 146, 'analytics.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1351, 5, 147, 'view_orders.php#', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1352, 5, 149, 'credit_orders.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1353, 5, 150, 'analytics.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1354, 5, 151, 'sale_report.php', 'Added By: admin', '2022-01-23 13:40:10', 1, 0, 0),
(1355, 5, 0, 'purchase_report.php', 'Added By: admin', '2022-01-23 13:40:10', 0, 0, 0),
(1400, 6, 98, 'customers.php?type=customer', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1401, 6, 99, 'customers.php?type=bank', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1402, 6, 103, 'users.php', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1403, 6, 105, 'voucher.php?act=list', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1404, 6, 108, 'cash_sale.php', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1405, 6, 109, 'product.php?act=add', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1406, 6, 110, 'product.php?act=list', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1407, 6, 111, 'brands.php#', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0),
(1408, 6, 0, 'credit_sale.php?credit_type=15days', 'Added By: admin', '2024-12-11 12:09:26', 1, 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `product`
--

CREATE TABLE `product` (
  `product_id` int(200) NOT NULL,
  `product_name` varchar(255) NOT NULL,
  `product_code` varchar(250) DEFAULT NULL,
  `product_image` text NOT NULL,
  `brand_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `quantity_instock` double NOT NULL,
  `purchased` double NOT NULL,
  `current_rate` double NOT NULL,
  `f_days` text DEFAULT NULL,
  `t_days` text DEFAULT NULL,
  `purchase_rate` double NOT NULL,
  `status` int(11) NOT NULL DEFAULT 0,
  `availability` int(11) DEFAULT 0,
  `alert_at` double DEFAULT 5,
  `weight` varchar(200) NOT NULL,
  `actual_rate` varchar(250) DEFAULT NULL,
  `product_description` text DEFAULT NULL,
  `product_mm` varchar(100) NOT NULL DEFAULT '0',
  `product_inch` varchar(100) DEFAULT '0',
  `product_meter` varchar(100) NOT NULL DEFAULT '0',
  `adddatetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `inventory` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase`
--

CREATE TABLE `purchase` (
  `purchase_id` int(11) NOT NULL,
  `purchase_date` date NOT NULL,
  `client_name` varchar(255) NOT NULL,
  `bill_no` varchar(255) NOT NULL,
  `client_contact` varchar(255) NOT NULL,
  `sub_total` varchar(255) NOT NULL,
  `vat` varchar(255) NOT NULL,
  `total_amount` varchar(255) NOT NULL,
  `discount` varchar(255) NOT NULL,
  `grand_total` varchar(255) NOT NULL,
  `paid` varchar(255) NOT NULL,
  `due` varchar(255) NOT NULL,
  `payment_type` varchar(30) DEFAULT NULL,
  `payment_account` int(11) DEFAULT NULL,
  `customer_account` int(11) DEFAULT NULL,
  `payment_status` int(11) NOT NULL,
  `transaction_id` int(11) NOT NULL,
  `transaction_paid_id` int(11) NOT NULL,
  `purchase_narration` text DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `purchase_item`
--

CREATE TABLE `purchase_item` (
  `purchase_item_id` int(255) NOT NULL,
  `purchase_id` int(255) NOT NULL,
  `product_id` int(255) NOT NULL,
  `product_detail` text DEFAULT NULL,
  `quantity` varchar(255) NOT NULL,
  `rate` varchar(255) NOT NULL,
  `sale_rate` varchar(255) NOT NULL,
  `total` varchar(255) NOT NULL,
  `purchase_item_status` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `transactions`
--

CREATE TABLE `transactions` (
  `transaction_id` int(11) NOT NULL,
  `debit` varchar(100) NOT NULL,
  `credit` varchar(100) NOT NULL,
  `balance` varchar(100) NOT NULL,
  `customer_id` int(11) NOT NULL,
  `transaction_remarks` text NOT NULL,
  `transaction_add_date` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `transaction_date` text DEFAULT NULL,
  `transaction_type` text DEFAULT NULL,
  `transaction_from` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `user_id` int(11) NOT NULL,
  `username` text NOT NULL,
  `fullname` varchar(50) DEFAULT NULL,
  `email` text NOT NULL,
  `password` text NOT NULL,
  `address` text NOT NULL,
  `phone` text NOT NULL,
  `user_role` text NOT NULL,
  `status` text NOT NULL,
  `adddatetime` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`user_id`, `username`, `fullname`, `email`, `password`, `address`, `phone`, `user_role`, `status`, `adddatetime`) VALUES
(1, 'admin', 'Ahsan Malik ', 'a.ttraders909@gmail.com', '21232f297a57a5a743894a0e4a801fc3', 'fsd', '1234567', 'admin', '1', '2022-12-08 13:38:04'),
(5, 'test', '', 'ali@polybags.com', '81dc9bdb52d04dc20036dbd8313ed055', 'fsd', '03057442934', 'manager', '1', '2022-06-27 19:21:18'),
(6, 'arhamwaheed', '', 'info.arham.org@gmail.com', 'd41d8cd98f00b204e9800998ecf8427e', 'old summumndri road dijkot', '03035672559', 'manager', '1', '2024-12-11 12:08:29');

-- --------------------------------------------------------

--
-- Table structure for table `vouchers`
--

CREATE TABLE `vouchers` (
  `voucher_id` int(11) NOT NULL,
  `customer_id1` varchar(250) DEFAULT NULL,
  `customer_id2` varchar(250) DEFAULT NULL,
  `addby_user_id` int(11) DEFAULT NULL,
  `editby_user_id` int(11) DEFAULT NULL,
  `voucher_amount` varchar(250) NOT NULL,
  `transaction_id1` varchar(250) DEFAULT NULL,
  `transaction_id2` varchar(250) DEFAULT NULL,
  `voucher_hint` text DEFAULT NULL,
  `voucher_date` varchar(100) DEFAULT NULL,
  `timestamp` timestamp NOT NULL DEFAULT current_timestamp(),
  `voucher_type` varchar(100) DEFAULT NULL,
  `voucher_group` varchar(100) DEFAULT NULL,
  `td_check_no` text DEFAULT NULL,
  `voucher_bank_name` varchar(255) DEFAULT NULL,
  `td_check_date` varchar(100) DEFAULT NULL,
  `check_type` varchar(150) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=latin1 COLLATE=latin1_swedish_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `brands`
--
ALTER TABLE `brands`
  ADD PRIMARY KEY (`brand_id`);

--
-- Indexes for table `brokers`
--
ALTER TABLE `brokers`
  ADD PRIMARY KEY (`broker_id`);

--
-- Indexes for table `budget`
--
ALTER TABLE `budget`
  ADD PRIMARY KEY (`budget_id`);

--
-- Indexes for table `budget_category`
--
ALTER TABLE `budget_category`
  ADD PRIMARY KEY (`budget_category_id`);

--
-- Indexes for table `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`categories_id`);

--
-- Indexes for table `checks`
--
ALTER TABLE `checks`
  ADD PRIMARY KEY (`check_id`);

--
-- Indexes for table `company`
--
ALTER TABLE `company`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `customers`
--
ALTER TABLE `customers`
  ADD PRIMARY KEY (`customer_id`);

--
-- Indexes for table `expenses`
--
ALTER TABLE `expenses`
  ADD PRIMARY KEY (`expense_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `orders`
--
ALTER TABLE `orders`
  ADD PRIMARY KEY (`order_id`);

--
-- Indexes for table `order_item`
--
ALTER TABLE `order_item`
  ADD PRIMARY KEY (`order_item_id`);

--
-- Indexes for table `privileges`
--
ALTER TABLE `privileges`
  ADD PRIMARY KEY (`privileges_id`);

--
-- Indexes for table `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`product_id`);

--
-- Indexes for table `purchase`
--
ALTER TABLE `purchase`
  ADD PRIMARY KEY (`purchase_id`);

--
-- Indexes for table `purchase_item`
--
ALTER TABLE `purchase_item`
  ADD PRIMARY KEY (`purchase_item_id`);

--
-- Indexes for table `transactions`
--
ALTER TABLE `transactions`
  ADD PRIMARY KEY (`transaction_id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `vouchers`
--
ALTER TABLE `vouchers`
  ADD PRIMARY KEY (`voucher_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `brands`
--
ALTER TABLE `brands`
  MODIFY `brand_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `brokers`
--
ALTER TABLE `brokers`
  MODIFY `broker_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget`
--
ALTER TABLE `budget`
  MODIFY `budget_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `budget_category`
--
ALTER TABLE `budget_category`
  MODIFY `budget_category_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `categories`
--
ALTER TABLE `categories`
  MODIFY `categories_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `checks`
--
ALTER TABLE `checks`
  MODIFY `check_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `company`
--
ALTER TABLE `company`
  MODIFY `id` int(10) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT for table `customers`
--
ALTER TABLE `customers`
  MODIFY `customer_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `expenses`
--
ALTER TABLE `expenses`
  MODIFY `expense_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=153;

--
-- AUTO_INCREMENT for table `orders`
--
ALTER TABLE `orders`
  MODIFY `order_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `order_item`
--
ALTER TABLE `order_item`
  MODIFY `order_item_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `privileges`
--
ALTER TABLE `privileges`
  MODIFY `privileges_id` int(255) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1409;

--
-- AUTO_INCREMENT for table `product`
--
ALTER TABLE `product`
  MODIFY `product_id` int(200) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase`
--
ALTER TABLE `purchase`
  MODIFY `purchase_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `purchase_item`
--
ALTER TABLE `purchase_item`
  MODIFY `purchase_item_id` int(255) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `transactions`
--
ALTER TABLE `transactions`
  MODIFY `transaction_id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `vouchers`
--
ALTER TABLE `vouchers`
  MODIFY `voucher_id` int(11) NOT NULL AUTO_INCREMENT;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
