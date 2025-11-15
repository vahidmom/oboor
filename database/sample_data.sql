-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Nov 15, 2025 at 02:46 PM
-- Server version: 10.4.32-MariaDB
-- PHP Version: 8.1.25

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `vahid_db`
--

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `username`, `password_hash`, `status`, `role`, `created_at`, `updated_at`) VALUES
(1, 'ادمین اصلی', 'admin@example.com', 'admin', '$2y$10$Q9lH0DqY7iFfvwQExxxxxxxb9JqFqC5FSTvCzH6dGZpHfZ.D2Hh2', 1, 2, '2025-11-10 11:25:39', '2025-11-10 11:25:39');

--
-- Dumping data for table `connection_files`
--

INSERT INTO `connection_files` (`id`, `tutorial_id`, `title`, `file_path`, `file_size`, `version`, `change_log`, `sort_order`, `is_active`, `download_count`, `created_at`, `updated_at`) VALUES
(1, 1, 'کانکشن هوشمند ویندوز - نسخه 1.0', 'windows/smart-connection-v1.0.exe', NULL, '1.0', 'اولین نسخه پایدار کانکشن هوشمند ویندوز.', 1, 1, 1, '2025-11-10 20:25:33', '2025-11-10 18:19:39'),
(2, 2, 'اپلیکیشن اندروید - نسخه 1.0', 'android/app-android-v1.0.apk', NULL, '1.0', 'اولین نسخه اپلیکیشن اندروید.', 1, 1, 0, '2025-11-10 20:45:52', '2025-11-10 20:45:52'),
(3, 3, 'فایل OpenVPN اندروید - سرور اصلی', 'android/openvpn-main.ovpn', NULL, '1.0', 'کانفیگ OpenVPN برای اندروید - سرور اصلی.', 1, 1, 0, '2025-11-10 20:45:52', '2025-11-10 20:45:52'),
(4, 4, 'کانفیگ iOS - پروفایل آماده', 'ios/ios-profile-1.conf', NULL, '1.0', 'پروفایل آماده اتصال برای iOS.', 1, 1, 0, '2025-11-10 20:45:52', '2025-11-10 20:45:52'),
(5, 5, 'کانکشن هوشمند macOS - نسخه 1.0', 'macos/macos-smart-connection-v1.0.dmg', NULL, '1.0', 'اولین نسخه کانکشن هوشمند macOS.', 1, 1, 0, '2025-11-10 20:45:52', '2025-11-10 20:45:52');

--
-- Dumping data for table `connection_groups`
--

INSERT INTO `connection_groups` (`id`, `platform_id`, `title`, `slug`, `description`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 1, 'کانکشن هوشمند', 'smart-connection', 'کانکشن هوشمند مخصوص ویندوز برای اتصال سریع و پایدار.', 1, 1, '2025-11-10 20:25:33', '2025-11-10 20:25:33'),
(2, 2, 'اپ اندروید', 'android-app', 'اپلیکیشن اختصاصی اندروید برای اتصال سریع و ساده.', 1, 1, '2025-11-10 20:45:19', '2025-11-10 20:45:19'),
(3, 2, 'فایل‌های OpenVPN اندروید', 'android-openvpn', 'فایل‌های آماده OpenVPN برای اندروید.', 2, 1, '2025-11-10 20:45:19', '2025-11-10 20:45:19'),
(4, 3, 'کانکشن iOS با اپ Stunnel / Shadowrocket', 'ios-app', 'ترفند اتصال روی iOS با استفاده از اپ‌های معرفی شده.', 1, 1, '2025-11-10 20:45:19', '2025-11-10 20:45:19'),
(5, 4, 'کانکشن هوشمند مک او اس', 'macos-smart', 'کانکشن هوشمند مخصوص سیستم‌عامل macOS.', 1, 1, '2025-11-10 20:45:19', '2025-11-10 20:45:19');

--
-- Dumping data for table `connection_platforms`
--

INSERT INTO `connection_platforms` (`id`, `name`, `slug`, `sort_order`, `is_active`, `created_at`, `updated_at`) VALUES
(1, 'ویندوز', 'windows', 2, 1, '2025-11-10 20:25:32', '2025-11-10 20:25:32'),
(2, 'اندروید', 'android', 1, 1, '2025-11-10 20:45:11', '2025-11-10 20:45:11'),
(3, 'آی‌اواس (iOS)', 'ios', 3, 1, '2025-11-10 20:45:11', '2025-11-10 20:45:11'),
(4, 'مک او اس', 'macos', 4, 1, '2025-11-10 20:45:11', '2025-11-10 20:45:11');

--
-- Dumping data for table `connection_tutorials`
--

INSERT INTO `connection_tutorials` (`id`, `group_id`, `title`, `slug`, `short_description`, `content`, `video_path`, `video_embed`, `sort_order`, `is_active`, `view_count`, `min_user_level`, `created_at`, `updated_at`) VALUES
(1, 1, 'آموزش نصب کانکشن هوشمند ویندوز', 'windows-smart-connection', 'آموزش مرحله به مرحله نصب و استفاده از کانکشن هوشمند روی ویندوز.', '<h3>مرحله ۱</h3><p>فایل کانکشن را از بخش پایین دانلود کنید.</p>\r\n  <h3>مرحله ۲</h3><p>روی فایل راست کلیک کرده و گزینه Run as administrator را بزنید.</p>\r\n  <h3>مرحله ۳</h3><p>مراحل نصب را تا انتها روی Next ادامه دهید و در پایان یوزرنیم و پسورد خود را وارد کنید.</p>', NULL, NULL, 1, 1, 4, 0, '2025-11-10 20:25:33', '2025-11-10 18:10:36'),
(2, 2, 'آموزش نصب اپ اندروید', 'android-app-install', 'نصب و ورود به اپلیکیشن اختصاصی اندروید.', '<p>۱. اپ را از بخش فایل‌های دانلود در پایین صفحه دریافت کنید.</p>\r\n  <p>۲. پس از نصب، وارد برنامه شده و یوزرنیم و پسورد خود را وارد کنید.</p>', NULL, NULL, 1, 1, 1, 0, '2025-11-10 20:45:32', '2025-11-12 06:26:54'),
(3, 3, 'آموزش اتصال OpenVPN در اندروید', 'android-openvpn-tutorial', 'اتصال با فایل‌های OpenVPN روی اندروید.', '<p>۱. اپ OpenVPN for Android را از گوگل‌پلی نصب کنید.</p>\r\n  <p>۲. فایل کانفیگ را از بخش دانلودها دریافت و در برنامه Import کنید.</p>', NULL, NULL, 1, 1, 0, 0, '2025-11-10 20:45:32', '2025-11-10 20:45:32'),
(4, 4, 'آموزش اتصال روی iOS', 'ios-connection-tutorial', 'آموزش استفاده از اپ‌های اتصال روی iOS.', '<p>۱. اپ معرفی‌شده (مثلاً Shadowrocket) را نصب کنید.</p>\r\n  <p>۲. کانفیگ را از بخش دانلودها دریافت و در برنامه اضافه کنید.</p>', NULL, NULL, 1, 1, 0, 0, '2025-11-10 20:45:32', '2025-11-10 20:45:32'),
(5, 5, 'آموزش نصب کانکشن هوشمند مک او اس', 'macos-smart-connection', 'نصب کانکشن هوشمند روی سیستم‌عامل macOS.', '<p>۱. فایل نصبی مک را از پایین صفحه دانلود کنید.</p>\r\n  <p>۲. پس از نصب، برنامه را اجرا کرده و به سرور متصل شوید.</p>', NULL, NULL, 1, 1, 0, 0, '2025-11-10 20:45:32', '2025-11-10 20:45:32');

--
-- Dumping data for table `servers`
--

INSERT INTO `servers` (`id`, `service_id`, `name`, `hostname`, `port`, `country`, `is_active`, `order_no`, `created_at`, `updated_at`) VALUES
(1, 1, 'Cisco Europe #1', 'cisco-eu1.example.com', 443, 'NL', 1, 4, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(2, 1, 'Cisco Europe #2', 'cisco-eu2.example.com', 443, 'DE', 1, 2, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(3, 1, 'Cisco Asia #1', 'cisco-asia1.example.com', 443, 'TR', 1, 3, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(4, 2, 'OpenVPN Europe #1', 'ovpn-eu1.example.com', 1194, 'NL', 1, 1, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(5, 2, 'OpenVPN Europe #2', 'ovpn-eu2.example.com', 1194, 'DE', 1, 2, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(6, 2, 'OpenVPN Asia #1', 'ovpn-asia1.example.com', 1194, 'TR', 1, 3, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(7, 3, 'L2TP Europe #1', 'l2tp-eu1.example.com', 1701, 'NL', 1, 1, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(8, 3, 'L2TP Europe #2', 'l2tp-eu2.example.com', 1701, 'DE', 1, 2, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(9, 3, 'L2TP Asia #1', 'l2tp-asia1.example.com', 1701, 'TR', 1, 3, '2025-11-11 12:45:55', '2025-11-11 12:45:55'),
(10, 4, 'PPTP Europe #1', 'pptp-eu1.example.com', 1723, 'NL', 1, 1, '2025-11-11 13:14:19', '2025-11-11 13:14:19'),
(11, 4, 'PPTP Europe #2', 'pptp-eu2.example.com', 1723, 'DE', 1, 2, '2025-11-11 13:14:19', '2025-11-11 13:14:19'),
(12, 4, 'PPTP Asia #1', 'pptp-asia1.example.com', 1723, 'TR', 1, 3, '2025-11-11 13:14:19', '2025-11-11 13:14:19');

--
-- Dumping data for table `server_meta`
--

INSERT INTO `server_meta` (`id`, `server_id`, `meta_key`, `meta_value`, `created_at`, `updated_at`) VALUES
(1, 4, 'config_file_name', 'ovpn-eu1.ovpn', '2025-11-11 12:46:29', '2025-11-11 12:46:29'),
(2, 5, 'config_file_name', 'ovpn-eu2.ovpn', '2025-11-11 12:46:29', '2025-11-11 12:46:29'),
(3, 6, 'config_file_name', 'ovpn-asia1.ovpn', '2025-11-11 12:46:29', '2025-11-11 12:46:29'),
(4, 7, 'secret_key', 'MyL2TPSecret_EU1', '2025-11-11 12:46:29', '2025-11-11 12:46:29'),
(5, 8, 'secret_key', 'MyL2TPSecret_EU2', '2025-11-11 12:46:29', '2025-11-11 12:46:29'),
(6, 9, 'secret_key', 'MyL2TPSecret_ASIA1', '2025-11-11 12:46:29', '2025-11-11 12:46:29');

--
-- Dumping data for table `services`
--

INSERT INTO `services` (`id`, `name`, `slug`, `description`, `is_active`, `order_no`, `last_update_at`, `last_update_reason`, `created_at`, `updated_at`) VALUES
(1, 'Cisco AnyConnect', 'cisco', 'Cisco VPN servers', 1, 1, '2025-01-05 10:20:00', 'افزودن سرور جدید در آلمان', NULL, NULL),
(2, 'OpenVPN', 'openvpn', 'OpenVPN servers', 1, 2, '2025-01-10 13:45:00', 'تغییر آدرس سرورهای اروپا', NULL, NULL),
(3, 'L2TP', 'l2tp', 'L2TP/IPSec servers', 1, 3, '2025-01-08 09:00:00', 'به‌روزرسانی Secret Key', NULL, NULL),
(4, 'PPTP VPN', 'pptp', 'سرورهای PPTP VPN', 1, 4, '2025-01-12 11:30:00', 'افزودن سرورهای PPTP برای کاربران ویندوز', '2025-11-11 13:14:10', '2025-11-11 13:14:10');

--
-- Dumping data for table `settings`
--

INSERT INTO `settings` (`id`, `name`, `data`) VALUES
(1, 'site_title', 'عبورینگ'),
(2, 'login_message', 'سلام خوش آمدید'),
(3, 'allow_user_register', '1'),
(4, 'shop_enabled', '1'),
(5, 'ibsng_enabled', '1'),
(6, 'level_0_shop_enabled', '1'),
(7, 'level_1_shop_enabled', '1'),
(8, 'level_2_shop_enabled', '1'),
(9, 'level_3_shop_enabled', '1'),
(10, 'level_4_shop_enabled', '1'),
(11, 'level_5_shop_enabled', '1'),
(12, 'level_6_shop_enabled', '0'),
(13, 'level_7_shop_enabled', '1'),
(14, 'ibsng_url', 'mypanel.fadns.biz'),
(15, 'ibsng_username', 'system'),
(16, 'ibsng_password', '09111306485');

--
-- Dumping data for table `support_attachments`
--

INSERT INTO `support_attachments` (`id`, `ticket_id`, `message_id`, `file_path`, `original_name`, `mime_type`, `file_size`, `created_at`, `updated_at`) VALUES
(1, 1, 5, 'support/1762764560_eab66569d40268ca9e33.png', 'Screenshot_12.png', 'image/png', 348851, '2025-11-10 08:49:20', '2025-11-10 08:49:20'),
(2, 1, 6, 'support/1762764844_d53ac13360f483072c23.png', 'Screenshot_10.png', 'image/png', 791382, '2025-11-10 08:54:04', '2025-11-10 08:54:04'),
(3, 2, 7, 'support/1762765692_0482978b91a35f4a2221.png', 'Screenshot_11.png', 'image/png', 163622, '2025-11-10 09:08:12', '2025-11-10 09:08:12');

--
-- Dumping data for table `support_messages`
--

INSERT INTO `support_messages` (`id`, `ticket_id`, `sender_type`, `user_id`, `admin_id`, `message`, `created_at`, `updated_at`) VALUES
(1, 1, 'user', 1, NULL, 'سلام. من نمی‌تونم با ایمیلم وارد پنل بشم، خطای \"کاربر یافت نشد\" می‌گیرم.', '2025-11-10 10:18:00', '2025-11-10 10:18:00'),
(2, 1, 'admin', NULL, 1, 'سلام وقت‌تون بخیر. ایمیل شما در سیستم با شماره موبایل ثبت شده بود. لطفاً به‌جای ایمیل، شماره موبایل‌تان را وارد کنید یا از بخش فراموشی رمز استفاده کنید.', '2025-11-10 10:22:00', '2025-11-10 10:22:00'),
(3, 1, 'user', 1, NULL, 'ممنون، با شماره موبایل تست کردم و وارد شدم. مشکل حل شد.', '2025-11-10 10:24:00', '2025-11-10 10:24:00'),
(4, 1, 'user', NULL, NULL, 'یبیبشسیب', '2025-11-10 12:18:12', '2025-11-10 12:18:12'),
(5, 1, 'user', NULL, NULL, 'بسبشسبشسب', '2025-11-10 12:19:20', '2025-11-10 12:19:20'),
(6, 1, 'user', NULL, NULL, 'تست بعدی', '2025-11-10 12:24:04', '2025-11-10 12:24:04'),
(7, 2, 'user', NULL, NULL, 'تیتازنزنااااراا\r\nتتالتنات', '2025-11-10 12:38:12', '2025-11-10 12:38:12'),
(8, 2, 'user', NULL, NULL, 'بشسیبسشبشسب', '2025-11-10 12:43:05', '2025-11-10 12:43:05');

--
-- Dumping data for table `support_tickets`
--

INSERT INTO `support_tickets` (`id`, `user_id`, `subject`, `category`, `status`, `created_at`, `updated_at`) VALUES
(1, 1, 'مشکل در ورود به حساب', 'فنی', 3, '2025-11-10 10:17:38', '2025-11-10 08:54:04'),
(2, 1, 'عدم ساخت دی ان اس برای دامنه', NULL, 2, '2025-11-10 09:08:12', '2025-11-10 09:13:05'),
(3, 1, 'تست تیکت شماره 1', 'عمومی', 3, '2025-11-10 09:01:00', '2025-11-10 09:01:00'),
(4, 1, 'تست تیکت شماره 2', 'عمومی', 3, '2025-11-10 09:02:00', '2025-11-10 09:02:00'),
(5, 1, 'تست تیکت شماره 3', 'عمومی', 3, '2025-11-10 09:03:00', '2025-11-10 09:03:00'),
(6, 1, 'تست تیکت شماره 4', 'عمومی', 3, '2025-11-10 09:04:00', '2025-11-10 09:04:00'),
(7, 1, 'تست تیکت شماره 5', 'عمومی', 3, '2025-11-10 09:05:00', '2025-11-10 09:05:00'),
(8, 1, 'تست تیکت شماره 6', 'عمومی', 3, '2025-11-10 09:06:00', '2025-11-10 09:06:00'),
(9, 1, 'تست تیکت شماره 7', 'عمومی', 3, '2025-11-10 09:07:00', '2025-11-10 09:07:00'),
(10, 1, 'تست تیکت شماره 8', 'عمومی', 3, '2025-11-10 09:08:00', '2025-11-10 09:08:00'),
(11, 1, 'تست تیکت شماره 9', 'عمومی', 3, '2025-11-10 09:09:00', '2025-11-10 09:09:00'),
(12, 1, 'تست تیکت شماره 10', 'عمومی', 3, '2025-11-10 09:10:00', '2025-11-10 09:10:00'),
(13, 1, 'تست تیکت شماره 11', 'عمومی', 3, '2025-11-10 09:11:00', '2025-11-10 09:11:00'),
(14, 1, 'تست تیکت شماره 12', 'عمومی', 3, '2025-11-10 09:12:00', '2025-11-10 09:12:00'),
(15, 1, 'تست تیکت شماره 13', 'عمومی', 3, '2025-11-10 09:13:00', '2025-11-10 09:13:00'),
(16, 1, 'تست تیکت شماره 14', 'عمومی', 3, '2025-11-10 09:14:00', '2025-11-10 09:14:00'),
(17, 1, 'تست تیکت شماره 15', 'عمومی', 3, '2025-11-10 09:15:00', '2025-11-10 09:15:00'),
(18, 1, 'تست تیکت شماره 16', 'عمومی', 3, '2025-11-10 09:16:00', '2025-11-10 09:16:00'),
(19, 1, 'تست تیکت شماره 17', 'عمومی', 3, '2025-11-10 09:17:00', '2025-11-10 09:17:00'),
(20, 1, 'تست تیکت شماره 18', 'عمومی', 3, '2025-11-10 09:18:00', '2025-11-10 09:18:00'),
(21, 1, 'تست تیکت شماره 19', 'عمومی', 3, '2025-11-10 09:19:00', '2025-11-10 09:19:00'),
(22, 1, 'تست تیکت شماره 20', 'عمومی', 3, '2025-11-10 09:20:00', '2025-11-10 09:20:00');

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `password_hash`, `avatar`, `last_login_at`, `last_login_ip`, `last_password_change_at`, `user_level`, `user_bio`, `email_verified`, `phone_verified`, `email`, `mobile`, `total_orders`, `total_spent`, `wallet_balance`, `discount_percent`, `notify_email`, `notify_sms`, `notify_email_newsletter`, `notify_sms_newsletter`, `created_at`, `updated_at`) VALUES
(1, '$2y$10$KISJEkAoRFs26EHbahqZz.1eVA1P8mEvNntFroLlFSyytSBuS/Egq', '1762884800_a6a735ab17bbf9280f40.jpg', '2025-11-11 16:58:44', '5.9.214.444', '2025-11-11 16:58:44', 1, NULL, 1, 1, 'ali@gmail.com', '09111306485', 5, 3500000, 2930000, 10, 0, 0, 1, 1, '2025-09-15 12:50:39', '2025-11-12 02:43:52'),
(2, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, 'sara@example.com', '09120000002', 0, 0, 0, 0, 1, 1, 1, 1, '2025-09-15 12:50:39', NULL),
(3, NULL, NULL, NULL, NULL, NULL, 0, NULL, 0, 0, 'reza@example.com', '09120000003', 0, 0, 0, 0, 1, 1, 1, 1, '2025-09-15 12:50:39', NULL),
(5, '$2y$10$W83sJzFRtLXsOvxJJLZ25uMOEkx3U5A2xlfItFPkfXi5/w5ehOIyi', NULL, NULL, NULL, NULL, 3, NULL, 0, 0, 'vahid.mom@gmail.com', '09357554409', 0, 0, 0, 0, 1, 1, 1, 1, '2025-10-21 15:29:42', NULL),
(6, '$2y$10$yPGDCjckfNEeH74f3dXFcuVVxfpDn5qEjldujt.bxMVuB1uHXjDj.', NULL, NULL, NULL, NULL, 3, NULL, 0, 0, 'info@parkdns.org', '09115669957', 0, 0, 0, 0, 1, 1, 1, 1, '2025-10-21 15:30:33', NULL);

--
-- Dumping data for table `user_change_logs`
--

INSERT INTO `user_change_logs` (`id`, `user_id`, `action_key`, `title`, `description`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'password_change', 'تغییر رمز عبور', 'کاربر رمز عبور خود را تغییر داد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 20:28:44'),
(2, 1, 'notification_settings_change', 'تغییر تنظیمات اعلان', 'کاربر تنظیمات اعلان خود را تغییر داد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:15:53'),
(3, 1, 'avatar_change', 'تغییر آواتار', 'کاربر آواتار پروفایل خود را بروزرسانی کرد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:16:24'),
(4, 1, 'avatar_change', 'تغییر آواتار', 'کاربر آواتار پروفایل خود را بروزرسانی کرد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:24:09'),
(5, 1, 'avatar_change', 'تغییر آواتار', 'کاربر آواتار پروفایل خود را بروزرسانی کرد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:26:12'),
(6, 1, 'avatar_remove', 'حذف آواتار', 'کاربر آواتار پروفایل خود را حذف کرد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:43:13'),
(7, 1, 'avatar_change', 'تغییر آواتار', 'کاربر آواتار پروفایل خود را بروزرسانی کرد.', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:43:20'),
(8, 1, 'phone_change', 'تغییر شماره موبایل', 'تغییر موبایل از 09111306485 به 09113586538', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 22:04:32'),
(9, 1, 'phone_change', 'تغییر شماره موبایل', 'تغییر موبایل از 09113586538 به 09111306485', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 22:05:09'),
(10, 1, 'email_change', 'تغییر ایمیل', 'تغییر ایمیل از vahidmom@gmail.com به ali@gmail.com', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-12 09:43:52');

--
-- Dumping data for table `user_email_otps`
--

INSERT INTO `user_email_otps` (`id`, `user_id`, `email`, `code`, `expires_at`, `used_at`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'ali@gmail.com', '537703', '2025-11-12 06:22:45', '2025-11-12 06:13:52', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-12 09:42:45');

--
-- Dumping data for table `user_mobile_otps`
--

INSERT INTO `user_mobile_otps` (`id`, `user_id`, `mobile`, `code`, `expires_at`, `used_at`, `ip_address`, `user_agent`, `created_at`) VALUES
(1, 1, 'سسب', '7346', '2025-11-11 18:29:18', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:54:18'),
(2, 1, '09111306485', '7619', '2025-11-11 18:29:50', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 21:54:50'),
(3, 1, '09113586538', '9591', '2025-11-11 18:38:56', '2025-11-11 18:34:32', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 22:03:56'),
(4, 1, '09111306485', '7453', '2025-11-11 18:39:50', '2025-11-11 18:35:09', '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:144.0) Gecko/20100101 Firefox/144.0', '2025-11-11 22:04:50');
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
