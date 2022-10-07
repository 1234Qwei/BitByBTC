-- phpMyAdmin SQL Dump
-- version 5.0.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 20, 2021 at 06:31 PM
-- Server version: 10.4.14-MariaDB
-- PHP Version: 7.4.10

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `learning_bit`
--

-- --------------------------------------------------------

--
-- Table structure for table `coins`
--

CREATE TABLE `coins` (
  `id` int(11) NOT NULL,
  `coin` varchar(150) NOT NULL,
  `symbol` varchar(10) NOT NULL,
  `coin_id` varchar(150) NOT NULL,
  `deposit_min` float NOT NULL,
  `withdraw_min` float NOT NULL,
  `withdraw_max` float NOT NULL,
  `withdraw_fee` float NOT NULL,
  `address` varchar(255) NOT NULL,
  `exchange_type` tinyint(4) NOT NULL,
  `status` tinyint(4) NOT NULL,
  `sort_order` tinyint(4) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `coin_order`
--

CREATE TABLE `coin_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `user_id` int(11) NOT NULL,
  `firstCurrency` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `secondCurrency` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Amount` decimal(32,8) NOT NULL,
  `Price` decimal(32,8) NOT NULL,
  `Type` varchar(10) COLLATE utf8mb4_unicode_ci NOT NULL,
  `Fee` decimal(32,8) NOT NULL,
  `Total` decimal(32,8) NOT NULL,
  `pair` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `ordertype` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `status` varchar(20) COLLATE utf8mb4_unicode_ci NOT NULL,
  `stopprice` decimal(32,8) DEFAULT NULL,
  `maker_fee_per` double NOT NULL,
  `taker_fee_per` double NOT NULL,
  `trader` varchar(20) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `order_token` varchar(30) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `remarks` varchar(200) COLLATE utf8mb4_unicode_ci NOT NULL,
  `isBinanceOrder` int(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `currency`
--

CREATE TABLE `currency` (
  `id` int(10) UNSIGNED NOT NULL,
  `order_id` int(11) NOT NULL,
  `token_id` int(11) NOT NULL,
  `name` varchar(50) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `symbol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `type` varchar(10) DEFAULT NULL,
  `status` tinyint(1) NOT NULL DEFAULT 1,
  `min_withdraw` decimal(32,8) DEFAULT NULL,
  `max_withdraw` decimal(32,8) DEFAULT NULL,
  `min_deposit` decimal(24,8) NOT NULL,
  `max_deposit` decimal(24,8) NOT NULL,
  `with_fee` decimal(24,4) NOT NULL DEFAULT 0.0000,
  `image` varchar(255) CHARACTER SET latin1 DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `deposit_status` tinyint(1) NOT NULL DEFAULT 1,
  `withdarw_status` tinyint(1) NOT NULL DEFAULT 1,
  `deposit_content` text NOT NULL,
  `withdarw_content` text NOT NULL,
  `alert_deposit` tinyint(1) NOT NULL DEFAULT 0,
  `alert_message` varchar(655) NOT NULL,
  `deposit_maintenance` varchar(300) NOT NULL,
  `withdraw_maintenance` varchar(300) NOT NULL,
  `alert_checkbox_content` varchar(100) NOT NULL,
  `inr_value` decimal(10,2) NOT NULL,
  `btc_value` decimal(40,8) DEFAULT NULL,
  `big_image` text NOT NULL,
  `ERC20` int(12) NOT NULL,
  `lastblock` varchar(200) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `currency`
--

INSERT INTO `currency` (`id`, `order_id`, `token_id`, `name`, `symbol`, `type`, `status`, `min_withdraw`, `max_withdraw`, `min_deposit`, `max_deposit`, `with_fee`, `image`, `created_at`, `updated_at`, `deposit_status`, `withdarw_status`, `deposit_content`, `withdarw_content`, `alert_deposit`, `alert_message`, `deposit_maintenance`, `withdraw_maintenance`, `alert_checkbox_content`, `inr_value`, `btc_value`, `big_image`, `ERC20`, `lastblock`) VALUES
(1, 1, 0, 'Bitcoin', 'BTC', 'crypto', 1, '0.00100000', '0.66660000', '1.00000000', '10.00000000', '0.2000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/PfoTpxSL0ItAupbOkQyGwyrUtyQSgByAGlNnXBpK.png', '2018-11-28 18:30:00', '2021-05-28 10:51:01', 1, 1, '<p>Need 4 confimarion</p>', '<p><span style=\"color: rgb(30, 40, 50); font-family: Montserrat, sans-serif; font-size: 14px; text-align: center;\">Withdraw process will be approved within 24 hours</span></p>', 0, '<p>test test image test test image test test image test test image</p>', '<p><span style=\"color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px;\">Deposit Maintenance</span></p>', '<p>Withdraw under Maintenance</p>', 'I understand', '5023442.73', '1.00000000', 'TychgNkoNbtcbig.png', 0, ''),
(2, 2, 0, 'Ethererum', 'ETH', 'crypto', 1, '0.00020000', '1.00000000', '1.00000000', '10.00000000', '0.2500', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/sfMo7iMXCJ0h1WPiyo4OV5RP21685XPHwEWmVoDu.png', '2018-11-28 18:30:00', '2021-05-28 10:51:26', 1, 1, '<p><span style=\"color: rgb(30, 40, 50); font-family: Montserrat, sans-serif; font-size: 14px; text-align: center;\">Withdraw process will be approved within 24 hours</span></p>', '', 0, '0', '<p>lorem s lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum lorem iposum</p>', '<p><label class=\"form-control-label\" style=\"padding: 0px 0px 7px; margin: 0px; box-sizing: border-box; display: inline-block; max-width: 100%; color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px; line-height: 18px;\">Withdraw Maintenance :</label></p>', '', '195060.28', '0.03888000', 'Tych6syGmethbig.png', 0, '10043373'),
(6, 6, 0, 'Tether', 'USDT', 'crypto', 1, '1.00000000', '5.00000000', '1.00000000', '10.00000000', '2.0000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/ngBlPrV9gSIHS7e8EV7UzOiFt6bEKm3zVT81Y3Dc.png', '2018-11-28 18:30:00', '2021-05-28 18:24:18', 1, 1, '', '', 0, '0', '<p>Deposit Maintenance</p>', '<p>Withdraw under Maintenance</p>', '', '80.84', '0.00001604', 'Tych4cQnKusdtbig.png', 0, ''),
(13, 13, 0, 'USD', 'USD', 'fiat', 0, '2.00000000', '5.00000000', '1.00000000', '10.00000000', '2.0000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/0SLLLZY1TrJAiNWa6kyDWe8oQAF1ehlt0iGPdy2q.png', '2019-03-29 10:22:48', '2021-05-28 18:22:54', 1, 1, '', '', 0, '', '<p><span style=\"color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px;\">Deposit Maintenancea</span></p>', '<p><span style=\"color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px;\">Withdraw Maintenancea</span></p>', '', '80.86', '0.00001604', 'TychiFKYSeurobig.png', 0, ''),
(42, 0, 17, 'BNB', 'BNB', 'crypto', 1, '0.01000000', '10000.00000000', '0.00000000', '0.00000000', '0.0000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/XJjfhXHrGo88VOiVMDoGkEnxMXNVYieUn86yAGrz.png', '2021-02-23 15:03:17', '2021-05-28 18:21:51', 1, 1, '', '', 0, '', '', '', '', '43628.60', '0.00870500', '', 0, ''),
(43, 13, 0, 'INR', 'INR', 'fiat', 1, '2.00000000', '50000.00000000', '1.00000000', '10.00000000', '2.0000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/Dy5AI7jgUSXUnfTbEZfnp34XhFmp3OpooEUnwlIf.png', '2019-03-29 10:22:48', '2021-05-28 20:01:16', 1, 1, '', '', 0, '', '<p><span style=\"color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px;\">Deposit Maintenancea</span></p>', '<p><span style=\"color: rgb(100, 100, 100); font-family: Roboto, sans-serif; font-size: 13.98px;\">Withdraw Maintenancea</span></p>', '', '1.00', '0.00000020', 'TychiFKYSeurobig.png', 0, ''),
(44, 0, 17, 'OWN', 'OWN', 'crypto', 1, '0.10000000', '10000.00000000', '0.00000000', '0.00000000', '0.0000', 'https://bit2atm.s3.us-east-2.amazonaws.com/bit2atm/JsH8X9jcldTxe5ftGLk4kspNPNpyFAFvrwGBzrsZ.png', '2021-02-23 15:03:17', '2021-05-28 18:25:10', 1, 1, '', '', 0, '', '', '', '', '0.03', '0.00000001', '', 1, '');

-- --------------------------------------------------------

--
-- Table structure for table `failed_jobs`
--

CREATE TABLE `failed_jobs` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `uuid` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `connection` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `payload` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `exception` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `failed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `loggedin_history`
--

CREATE TABLE `loggedin_history` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `ip` varchar(50) NOT NULL,
  `logged_in` datetime NOT NULL,
  `logged_out` datetime DEFAULT NULL,
  `browser` varchar(150) DEFAULT NULL,
  `browser_version` varchar(10) DEFAULT NULL,
  `city` varchar(150) DEFAULT NULL,
  `country` varchar(150) DEFAULT NULL,
  `is_browser` tinyint(1) NOT NULL DEFAULT 0,
  `is_mobile` tinyint(1) NOT NULL DEFAULT 0,
  `region` varchar(150) DEFAULT NULL,
  `collection_data` longtext CHARACTER SET utf8mb4 COLLATE utf8mb4_bin DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

--
-- Dumping data for table `loggedin_history`
--

INSERT INTO `loggedin_history` (`id`, `user_id`, `ip`, `logged_in`, `logged_out`, `browser`, `browser_version`, `city`, `country`, `is_browser`, `is_mobile`, `region`, `collection_data`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, '127.0.0.1', '2021-08-03 07:08:18', '2021-08-03 07:08:59', 'Chrome', '92.0.4515', NULL, NULL, 1, 0, NULL, '{\"ip\":\"127.0.0.1\",\"error\":true,\"reason\":\"Reserved IP Address\",\"reserved\":true,\"version\":\"IPv4\"}', '2021-08-03 14:11:18', '2021-08-03 14:11:59', NULL),
(2, 1, '127.0.0.1', '2021-08-03 08:08:13', '2021-08-03 08:08:29', 'Chrome', '92.0.4515', NULL, NULL, 1, 0, NULL, '{\"ip\":\"127.0.0.1\",\"error\":true,\"reason\":\"Reserved IP Address\",\"reserved\":true,\"version\":\"IPv4\"}', '2021-08-03 14:41:13', '2021-08-03 14:46:29', NULL),
(3, 1, '127.0.0.1', '2021-08-03 08:08:50', '2021-08-03 09:08:00', 'Chrome', '92.0.4515', NULL, NULL, 1, 0, NULL, '{\"ip\":\"127.0.0.1\",\"error\":true,\"reason\":\"Reserved IP Address\",\"reserved\":true,\"version\":\"IPv4\"}', '2021-08-03 14:47:50', '2021-08-03 15:49:00', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `referal_transaction`
--

CREATE TABLE `referal_transaction` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `refferer_id` int(11) NOT NULL,
  `deposit_id` int(11) NOT NULL,
  `coin_id` int(11) NOT NULL,
  `coin_value` decimal(27,18) NOT NULL,
  `is_credited` tinyint(1) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- --------------------------------------------------------

--
-- Table structure for table `tmp_order`
--

CREATE TABLE `tmp_order` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `sellorderId` int(11) NOT NULL,
  `sellerUserId` int(11) NOT NULL,
  `askAmount` double(24,8) NOT NULL,
  `askPrice` double(24,8) NOT NULL,
  `buyPrice` double(24,8) DEFAULT NULL,
  `sellPrice` double(24,8) DEFAULT NULL,
  `firstCurrency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `secondCurrency` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `filledAmount` double(24,8) NOT NULL,
  `buyorderId` int(11) NOT NULL,
  `buyerUserId` int(11) NOT NULL,
  `sellerStatus` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `buyerStatus` varchar(25) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `datetime` datetime DEFAULT NULL,
  `pair` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `cancel_id` int(11) DEFAULT NULL,
  `cancel_order` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `fee_per` double(24,8) NOT NULL DEFAULT 0.00000000,
  `buy_fee_per` double NOT NULL,
  `sell_fee_per` int(11) NOT NULL,
  `sell_fee` double NOT NULL,
  `buy_fee` double NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `isBinanceOrder` int(11) DEFAULT NULL,
  `orderid` text DEFAULT NULL,
  `clientorderid` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

-- --------------------------------------------------------

--
-- Table structure for table `trade_pair`
--

CREATE TABLE `trade_pair` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `token_id` int(11) NOT NULL,
  `from_symbol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `to_symbol` varchar(20) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci NOT NULL,
  `from_symbol_id` int(2) NOT NULL,
  `to_symbol_id` int(2) NOT NULL,
  `trade_fee` decimal(3,2) DEFAULT 0.00,
  `last_price` decimal(40,8) DEFAULT NULL,
  `min_amt` decimal(24,8) DEFAULT NULL,
  `min_price` decimal(24,8) DEFAULT NULL,
  `max_price` decimal(24,8) DEFAULT NULL,
  `status` int(11) NOT NULL DEFAULT 1,
  `convertedbtc` decimal(18,8) DEFAULT NULL,
  `convertedeur` decimal(18,8) DEFAULT NULL,
  `refer_fee` double DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `taker_trade_fee` decimal(3,2) NOT NULL,
  `show_home` tinyint(1) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8;

--
-- Dumping data for table `trade_pair`
--

INSERT INTO `trade_pair` (`id`, `token_id`, `from_symbol`, `to_symbol`, `from_symbol_id`, `to_symbol_id`, `trade_fee`, `last_price`, `min_amt`, `min_price`, `max_price`, `status`, `convertedbtc`, `convertedeur`, `refer_fee`, `created_at`, `updated_at`, `taker_trade_fee`, `show_home`) VALUES
(1, 0, 'INR', 'ETH', 43, 2, '9.99', '179218.71000000', '0.00000100', '0.20000000', '10.00000000', 1, NULL, '253.67196383', 10, '2018-09-10 18:30:00', '2021-06-07 17:13:43', '9.99', 1),
(3, 0, 'USD', 'BTC', 13, 1, '1.00', '35584.19000000', '0.50000000', '1.00000000', '10000.00000000', 0, NULL, '0.90004987', 20, '2018-09-10 18:30:00', '2021-05-31 12:49:50', '0.99', 1),
(16, 0, 'USD', 'BNB', 13, 42, '0.00', '335.17000000', '0.50000000', '0.20000000', '10.00000000', 0, NULL, '0.01444385', 20, '2019-03-29 08:08:24', '2021-05-30 17:43:33', '0.99', 1),
(17, 0, 'USD', 'OWN', 13, 44, '0.00', '5764.41000000', '0.50000000', '0.20000000', '10.00000000', 0, NULL, '3913.10723598', 20, '2019-03-29 10:22:48', '2021-05-10 05:28:29', '0.99', 1),
(19, 0, 'INR', 'BTC', 43, 1, '0.00', '2583950.30000000', '0.50000000', '0.20000000', '10.00000000', 1, '0.00000000', '10.00000000', 20, '2019-03-29 10:22:48', '2021-05-10 05:28:30', '0.99', 0),
(51, 17, 'INR', 'BNB', 43, 42, '0.01', '320.22000000', '0.00000000', '0.00000000', '0.00000000', 1, NULL, NULL, 0.1, '2021-02-23 15:03:17', '2021-05-30 20:57:01', '0.01', 1),
(52, 0, 'INR', 'OWN', 43, 44, '0.00', '71.45000000', '0.50000000', '0.20000000', '10.00000000', 1, NULL, '3913.10723598', 20, '2019-03-29 10:22:48', '2021-05-10 05:28:34', '0.99', 1),
(53, 0, 'USD', 'ETH', 13, 2, '0.00', '3643.05000000', '0.50000000', '0.20000000', '10.00000000', 0, NULL, '3913.10723598', 20, '2019-03-29 10:22:48', '2021-09-16 06:29:34', '0.99', 1),
(54, 0, 'USDT', 'ETH', 6, 2, '9.99', '3540.97000000', '0.00000100', '0.20000000', '10.00000000', 1, NULL, '253.67196383', 10, '2018-09-10 18:30:00', '2021-09-17 06:41:23', '9.99', 1),
(55, 0, 'USDT', 'BTC', 6, 1, '0.00', '48695.00000000', '0.50000000', '0.20000000', '10.00000000', 1, '0.00000000', '10.00000000', 20, '2019-03-29 10:22:48', '2021-09-18 08:14:15', '0.99', 0),
(56, 17, 'USDT', 'BNB', 6, 42, '0.01', '364.50000000', '0.00000000', '0.00000000', '0.00000000', 1, NULL, NULL, 0.1, '2021-02-23 15:03:17', '2021-09-20 12:45:52', '0.01', 1),
(57, 0, 'USDT', 'OWN', 6, 44, '0.00', '5767.80000000', '0.50000000', '0.20000000', '10.00000000', 1, NULL, '3913.10723598', 20, '2019-03-29 10:22:48', '2021-05-10 05:28:35', '0.99', 1),
(58, 17, 'BNB', 'USDT', 6, 42, '0.01', '351.98000000', '0.00000000', '0.00000000', '0.00000000', 1, NULL, NULL, 0.1, '2021-02-23 15:03:17', '2021-06-01 11:54:22', '0.01', 1),
(59, 0, 'BTC', 'USDT', 6, 1, '0.00', '48362.41000000', '0.50000000', '0.20000000', '10.00000000', 1, '0.00000000', '10.00000000', 20, '2019-03-29 10:22:48', '2021-09-16 07:27:53', '0.99', 0),
(60, 0, 'USDT', 'BTC', 6, 1, '0.00', '48362.41000000', '0.50000000', '0.20000000', '10.00000000', 1, '0.00000000', '10.00000000', 20, '2019-03-29 10:22:48', '2021-09-16 07:27:53', '0.99', 0);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `role_id` tinyint(4) NOT NULL DEFAULT 2,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `mobile` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `is_email_verified` tinyint(4) NOT NULL DEFAULT 0,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 1,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `referred_by` varchar(150) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `role_id`, `name`, `email`, `mobile`, `is_email_verified`, `email_verified_at`, `status`, `password`, `remember_token`, `referred_by`, `created_at`, `updated_at`) VALUES
(1, 2, NULL, 'satz@mail.com', '1234567890', 1, NULL, 2, '$2y$10$xVOjMikTYyLL1a/N06LCvuelYcaXbfVnZiU1Rbdmt0VYft6qKF6zu', NULL, NULL, '2021-08-03 13:54:51', '2021-08-03 14:11:05');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `coins`
--
ALTER TABLE `coins`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `coin_order`
--
ALTER TABLE `coin_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `created_at` (`created_at`),
  ADD KEY `Price` (`Price`),
  ADD KEY `fk_coin_id` (`user_id`);

--
-- Indexes for table `currency`
--
ALTER TABLE `currency`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Indexes for table `loggedin_history`
--
ALTER TABLE `loggedin_history`
  ADD PRIMARY KEY (`id`),
  ADD KEY `user_id` (`user_id`,`browser`,`city`,`country`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Indexes for table `referal_transaction`
--
ALTER TABLE `referal_transaction`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `tmp_order`
--
ALTER TABLE `tmp_order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id` (`id`);

--
-- Indexes for table `trade_pair`
--
ALTER TABLE `trade_pair`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD UNIQUE KEY `users_mobile_unique` (`mobile`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `coins`
--
ALTER TABLE `coins`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=17;

--
-- AUTO_INCREMENT for table `coin_order`
--
ALTER TABLE `coin_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `currency`
--
ALTER TABLE `currency`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=45;

--
-- AUTO_INCREMENT for table `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `loggedin_history`
--
ALTER TABLE `loggedin_history`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT for table `referal_transaction`
--
ALTER TABLE `referal_transaction`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `tmp_order`
--
ALTER TABLE `tmp_order`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `trade_pair`
--
ALTER TABLE `trade_pair`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=61;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
