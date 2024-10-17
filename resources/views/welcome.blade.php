-- phpMyAdmin SQL Dump
-- version 4.4.15.9




-- --------------------------------------------------------

--
-- Table structure for table `merchant_products`
--

DROP TABLE IF EXISTS `merchant_products`;
CREATE TABLE IF NOT EXISTS `merchant_products` (
`id` int(10) unsigned NOT NULL,
`merchant_id` int(10) unsigned NOT NULL,
`merchant_product_category_id` int(10) unsigned NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`description_ar` text COLLATE utf8mb4_unicode_ci NOT NULL,
`description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
`quantity` double NOT NULL,
`price` double NOT NULL,
`tax_ids` varchar(100) COLLATE utf8mb4_unicode_ci NOT NULL,
`creatable_type` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`creatable_id` int(11) DEFAULT NULL,
`approved_by_staff_id` int(11) DEFAULT NULL,
`approved_at` datetime DEFAULT NULL,
`status` enum('active','in-active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=37 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchant_products`
--

INSERT INTO `merchant_products` (`id`, `merchant_id`, `merchant_product_category_id`, `name_ar`, `name_en`, `description_ar`, `description_en`, `quantity`, `price`, `tax_ids`, `creatable_type`, `creatable_id`, `approved_by_staff_id`, `approved_at`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 1, 'sdfsd', 'fdsf', 'sdfsd', 'fsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 12:42:55', 'active', '2018-10-02 10:42:55', '2018-10-02 10:42:55', NULL),
(2, 3, 1, 'sdfsd', 'fdsf', 'sdfsd', 'fsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 12:44:56', 'active', '2018-10-02 10:44:56', '2018-10-02 10:44:56', NULL),
(3, 3, 1, 'sdfsd', 'fdsf', 'sdfsd', 'fsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 12:45:06', 'active', '2018-10-02 10:45:06', '2018-10-02 10:45:06', NULL),
(4, 3, 1, 'sdfsd', 'fdsf', 'sdfsd', 'fsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 12:45:18', 'active', '2018-10-02 10:45:18', '2018-10-02 10:45:18', NULL),
(5, 3, 2, 'rwer', 'ewrew', 'wer', 'ewr', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 13:31:48', 'active', '2018-10-02 11:31:48', '2018-10-02 11:31:48', NULL),
(6, 3, 2, 'rwer', 'ewrew', 'wer', 'ewr', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-02 14:03:35', 'active', '2018-10-02 12:03:36', '2018-10-02 12:03:36', NULL),
(7, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:44:56', 'active', '2018-10-03 12:44:56', '2018-10-03 12:44:56', NULL),
(8, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:45:41', 'active', '2018-10-03 12:45:41', '2018-10-03 12:45:41', NULL),
(9, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:46:38', 'active', '2018-10-03 12:46:38', '2018-10-03 12:46:38', NULL),
(10, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:48:43', 'active', '2018-10-03 12:48:43', '2018-10-03 12:48:43', NULL),
(11, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:49:38', 'active', '2018-10-03 12:49:38', '2018-10-03 12:49:38', NULL),
(12, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 2, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:50:07', 'active', '2018-10-03 12:50:07', '2018-10-03 12:50:07', NULL),
(13, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:50:44', 'active', '2018-10-03 12:50:44', '2018-10-03 12:50:44', NULL),
(14, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:51:21', 'active', '2018-10-03 12:51:21', '2018-10-03 12:51:21', NULL),
(15, 3, 1, 'عنوان', 'tite en', 'وصف', 'desc en', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-03 14:51:38', 'active', '2018-10-03 12:51:38', '2018-10-03 12:51:38', NULL),
(16, 3, 1, 'منتج جديد', 'new product', 'وصف منتج جديد', 'new product desc', 0, 2, '', 'App\\Models\\Staff', 1, NULL, NULL, 'active', '2018-10-03 13:01:24', '2018-10-03 13:01:24', NULL),
(17, 3, 1, 'منتج جديد', 'new product', 'وصف منتج جديد', 'new product desc', 0, 2, '', 'App\\Models\\Staff', 1, NULL, NULL, 'active', '2018-10-03 13:02:13', '2018-10-03 13:02:13', NULL),
(18, 3, 3, 'سالمان', 'salman', 'محمد سالمان', 'mohamed salman', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-04 16:39:44', 'active', '2018-10-04 14:39:44', '2018-10-04 14:39:44', NULL),
(19, 3, 2, 'fsdfsdf', 'dsfsd', 'sdfs', 'dfsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-07 15:47:50', 'active', '2018-10-07 13:47:50', '2018-10-07 13:47:50', NULL),
(20, 3, 2, 'fsdfsdf', 'dsfsd', 'sdfs', 'dfsdf', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-07 15:48:25', 'active', '2018-10-07 13:48:25', '2018-10-07 13:48:25', NULL),
(21, 3, 2, 'asdas', 'sadasd', 'dasdsa', 'das', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-07 15:49:39', 'active', '2018-10-07 13:49:39', '2018-10-07 13:49:39', NULL),
(22, 3, 2, 'fsdfs', 'fds', 'fdsf', 'fdsfds', 0, 1, '', 'App\\Models\\Staff', 1, 1, '2018-10-07 16:00:32', 'active', '2018-10-07 14:00:32', '2018-10-07 14:00:32', NULL),
(23, 3, 2, 'sdsad', 'sada', 'sad', 'sadsa', 0, 3333, '', 'App\\Models\\Staff', 1, 1, '2018-10-09 13:01:05', 'active', '2018-10-09 11:01:05', '2018-10-09 11:05:11', NULL),
(24, 3, 3, 'sdf', 'fsdf', 'sdf', 'dsfsdf', 0, 444, '', 'App\\Models\\Staff', 1, 1, '2018-10-11 12:22:30', 'active', '2018-10-11 10:22:30', '2018-10-11 10:22:30', NULL),
(25, 3, 3, 'sdf', 'fsdf', 'sdf', 'dsfsdf', 0, 444, '', 'App\\Models\\Staff', 1, 1, '2018-10-11 13:12:31', 'active', '2018-10-11 11:12:31', '2018-10-11 11:12:31', NULL),
(26, 3, 3, 'سيبسيب', 'يبسيب', 'سيب', 'يسب', 0, 333, '1,2', 'App\\Models\\Staff', 1, 1, '2018-10-11 13:19:03', 'active', '2018-10-11 11:19:03', '2018-10-13 11:29:18', NULL),
(27, 3, 3, 'asd', 'sadasd', 'asdsad', 'asd', 0, 333, '', 'App\\Models\\Staff', 1, 1, '2018-10-11 14:35:48', 'active', '2018-10-11 12:35:48', '2018-10-11 12:35:48', NULL),
(28, 3, 3, 'asd', 'sadasd', 'asdsad', 'asd', 0, 333, '', 'App\\Models\\Staff', 1, 1, '2018-10-11 14:36:52', 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52', NULL),
(29, 3, 3, 'dsad', 'sdsa', 'sad', 'sad', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 14:46:18', 'active', '2018-10-11 12:46:18', '2018-10-11 12:46:18', NULL),
(30, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:28:37', 'active', '2018-10-11 14:28:37', '2018-10-11 14:28:37', NULL),
(31, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:29:12', 'active', '2018-10-11 14:29:12', '2018-10-11 14:29:12', NULL),
(32, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:29:30', 'active', '2018-10-11 14:29:30', '2018-10-11 14:29:30', NULL),
(33, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:29:54', 'active', '2018-10-11 14:29:54', '2018-10-11 14:29:54', NULL),
(34, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:30:09', 'active', '2018-10-11 14:30:09', '2018-10-11 14:30:09', NULL),
(35, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:35:53', 'active', '2018-10-11 14:35:53', '2018-10-11 14:35:53', NULL),
(36, 3, 3, 'fdg', 'ffsg', 'dgfd', 'dfgf', 0, 33, '1', 'App\\Models\\Staff', 1, 1, '2018-10-11 16:36:30', 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_product_attribute_value`
--

DROP TABLE IF EXISTS `merchant_product_attribute_value`;
CREATE TABLE IF NOT EXISTS `merchant_product_attribute_value` (
`id` int(10) NOT NULL,
`merchant_product_id` int(10) NOT NULL,
`product_attribute_id` int(10) NOT NULL,
`product_attribute_value_id` int(10) DEFAULT NULL,
`value` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`status` enum('active','in-active','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=24 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchant_product_attribute_value`
--

INSERT INTO `merchant_product_attribute_value` (`id`, `merchant_product_id`, `product_attribute_id`, `product_attribute_value_id`, `value`, `status`, `created_at`, `updated_at`) VALUES
(1, 25, 1, 1, '', 'active', '2018-10-11 11:12:31', '2018-10-11 11:12:31'),
(7, 27, 1, 1, '', 'active', '2018-10-11 12:35:49', '2018-10-11 12:35:49'),
(8, 27, 2, 4, '', 'active', '2018-10-11 12:35:49', '2018-10-11 12:35:49'),
(9, 27, 2, 5, '', 'active', '2018-10-11 12:35:49', '2018-10-11 12:35:49'),
(10, 28, 1, 1, '', 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52'),
(11, 28, 2, 4, '', 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52'),
(12, 28, 2, 5, '', 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52'),
(13, 28, 3, 0, NULL, 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52'),
(14, 28, 4, 0, NULL, 'active', '2018-10-11 12:36:52', '2018-10-11 12:36:52'),
(15, 29, 1, 2, '', 'active', '2018-10-11 12:46:18', '2018-10-11 12:46:18'),
(16, 29, 2, 3, '', 'active', '2018-10-11 12:46:18', '2018-10-11 12:46:18'),
(17, 29, 3, 0, NULL, 'active', '2018-10-11 12:46:18', '2018-10-11 12:46:18'),
(18, 29, 4, 0, 'dsdsd', 'active', '2018-10-11 12:46:18', '2018-10-11 12:46:18'),
(19, 36, 1, 1, '', 'active', '2018-10-11 14:36:31', '2018-10-11 14:36:31'),
(20, 36, 2, 3, '', 'active', '2018-10-11 14:36:31', '2018-10-11 14:36:31'),
(21, 36, 2, 5, '', 'active', '2018-10-11 14:36:31', '2018-10-11 14:36:31'),
(22, 36, 3, 0, 'fdf', 'active', '2018-10-11 14:36:31', '2018-10-11 14:36:31'),
(23, 36, 4, 0, 'dfds', 'active', '2018-10-11 14:36:31', '2018-10-11 14:36:31');

-- --------------------------------------------------------

--
-- Table structure for table `merchant_product_categories`
--

DROP TABLE IF EXISTS `merchant_product_categories`;
CREATE TABLE IF NOT EXISTS `merchant_product_categories` (
`id` int(10) unsigned NOT NULL,
`merchant_category_id` int(10) unsigned NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`description_ar` text COLLATE utf8mb4_unicode_ci NOT NULL,
`description_en` text COLLATE utf8mb4_unicode_ci NOT NULL,
`icon` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
`status` enum('pending','active','in-active') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'pending',
`suggested_by_merchant_staff_id` int(11) DEFAULT NULL,
`staff_id` int(10) unsigned DEFAULT NULL COMMENT 'Created By staff || approved by staff ',
`approved_at` datetime DEFAULT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=4 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchant_product_categories`
--

INSERT INTO `merchant_product_categories` (`id`, `merchant_category_id`, `name_ar`, `name_en`, `description_ar`, `description_en`, `icon`, `status`, `suggested_by_merchant_staff_id`, `staff_id`, `approved_at`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 1, 'Cat AR', 'Cat EN', 'Cat AR\r\nCat AR\r\nCat AR\r\nCat AR', 'Cat EN\r\nCat EN\r\nCat EN\r\nCat EN', NULL, 'active', 1, 1, NULL, NULL, NULL, NULL),
(2, 1, '3ddddddddd', '1d', '4dddddddddd', '2dddddddddddddddddd', 'merchant-category/17/09/HWh3JPTdHfiN9DsedGoC03cQu970hvPKlP97Oddf.png', 'active', 1, 1, NULL, '2017-09-10 05:52:41', '2017-09-10 06:24:11', NULL),
(3, 1, 'ييييي', 'ييييي', 's', 'يسبيسب', NULL, 'active', NULL, 1, NULL, '2018-09-30 13:29:39', '2018-09-30 13:44:27', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_product_options`
--

DROP TABLE IF EXISTS `merchant_product_options`;
CREATE TABLE IF NOT EXISTS `merchant_product_options` (
`id` bigint(20) NOT NULL,
`merchant_product_id` int(10) NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`min_select` int(5) NOT NULL,
`max_select` int(5) NOT NULL,
`type` enum('text','textarea','select','radio','checkbox','file') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
`is_required` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
`sort` int(5) DEFAULT NULL,
`status` enum('active','in-active','deleted') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'active',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=61 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchant_product_options`
--

INSERT INTO `merchant_product_options` (`id`, `merchant_product_id`, `name_ar`, `name_en`, `min_select`, `max_select`, `type`, `is_required`, `sort`, `status`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 4, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-02 10:45:18', '2018-10-02 10:45:18', NULL),
(2, 4, 'gdsfs', 'fgfd', 3, 5, 'text', '', 0, 'active', '2018-10-02 10:45:19', '2018-10-02 10:45:19', NULL),
(3, 4, 'dfgfd', 'fdgdf', 3, 7, 'select', '', 0, 'active', '2018-10-02 10:45:19', '2018-10-02 10:45:19', NULL),
(4, 5, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-02 11:31:48', '2018-10-02 11:31:48', NULL),
(5, 5, 'rewr', 'ewr', 3, 6, 'text', '', 0, 'active', '2018-10-02 11:31:48', '2018-10-02 11:31:48', NULL),
(6, 5, 'dffsdf', 'erewr', 3, 6, 'select', '', 0, 'active', '2018-10-02 11:31:48', '2018-10-02 11:31:48', NULL),
(7, 6, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-02 12:03:36', '2018-10-02 12:03:36', NULL),
(8, 7, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:44:56', '2018-10-03 12:44:56', NULL),
(9, 7, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:44:56', '2018-10-03 12:44:56', NULL),
(10, 7, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:44:56', '2018-10-03 12:44:56', NULL),
(11, 8, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:45:41', '2018-10-03 12:45:41', NULL),
(12, 8, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:45:41', '2018-10-03 12:45:41', NULL),
(13, 8, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:45:41', '2018-10-03 12:45:41', NULL),
(14, 9, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:46:38', '2018-10-03 12:46:38', NULL),
(15, 9, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:46:38', '2018-10-03 12:46:38', NULL),
(16, 9, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:46:39', '2018-10-03 12:46:39', NULL),
(17, 10, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:48:43', '2018-10-03 12:48:43', NULL),
(18, 10, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:48:43', '2018-10-03 12:48:43', NULL),
(19, 10, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:48:43', '2018-10-03 12:48:43', NULL),
(20, 11, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:49:38', '2018-10-03 12:49:38', NULL),
(21, 11, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:49:38', '2018-10-03 12:49:38', NULL),
(22, 11, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:49:38', '2018-10-03 12:49:38', NULL),
(23, 12, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:50:07', '2018-10-03 12:50:07', NULL),
(24, 12, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:50:07', '2018-10-03 12:50:07', NULL),
(25, 12, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:50:08', '2018-10-03 12:50:08', NULL),
(26, 13, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:50:45', '2018-10-03 12:50:45', NULL),
(27, 13, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:50:45', '2018-10-03 12:50:45', NULL),
(28, 13, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:50:45', '2018-10-03 12:50:45', NULL),
(29, 14, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:51:21', '2018-10-03 12:51:21', NULL),
(30, 14, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:51:21', '2018-10-03 12:51:21', NULL),
(31, 14, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:51:22', '2018-10-03 12:51:22', NULL),
(32, 15, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 12:51:39', '2018-10-04 13:37:03', '2018-10-04 13:37:03'),
(33, 15, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-03 12:51:39', '2018-10-04 13:37:03', '2018-10-04 13:37:03'),
(34, 15, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-03 12:51:39', '2018-10-04 13:37:03', '2018-10-04 13:37:03'),
(35, 16, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, 'active', '2018-10-03 13:01:25', '2018-10-03 13:01:25', NULL),
(36, 17, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', NULL, 'active', '2018-10-03 13:02:13', '2018-10-03 13:02:13', NULL),
(37, 17, 'فطيرة', 'pie ?', 1, 3, 'select', '', NULL, 'active', '2018-10-03 13:02:13', '2018-10-03 13:02:13', NULL),
(38, 15, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', '', 0, 'active', '2018-10-04 13:37:03', '2018-10-04 13:37:03', NULL),
(39, 15, 'فطيرة ؟', 'pie ?', 1, 3, 'select', '', 3, 'active', '2018-10-04 13:37:03', '2018-10-04 13:37:03', NULL),
(40, 15, 'مناديل ؟', 'tissues ?', 2, 6, 'text', '', 2, 'active', '2018-10-04 13:37:04', '2018-10-04 13:37:04', NULL),
(41, 15, 'ييبسي', 'بيسبيس', 3, 3, 'select', '', NULL, 'active', '2018-10-04 13:37:04', '2018-10-04 13:37:04', NULL),
(42, 18, 'نوع جديد', 'rr', 10, 11, 'text', '', NULL, 'active', '2018-10-04 14:39:44', '2018-10-04 14:39:44', NULL),
(43, 31, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:29:12', '2018-10-11 14:29:12', NULL),
(44, 32, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:29:30', '2018-10-11 14:29:30', NULL),
(45, 33, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:29:54', '2018-10-11 14:29:54', NULL),
(46, 34, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:30:09', '2018-10-11 14:30:09', NULL),
(47, 34, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', NULL, 'active', '2018-10-11 14:30:09', '2018-10-11 14:30:09', NULL),
(48, 35, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:35:53', '2018-10-11 14:35:53', NULL),
(49, 36, 'fdsf', 'sdf', 33, 333, 'text', '', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(50, 36, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(51, 36, 'sdfsd', 'fsd', 3, 33, 'textarea', '', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(52, 36, 'fds', 'dsfsd', 3, 55, 'select', '', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(53, 36, 'fdsf', 'sdf', 3, 5, 'radio', '', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(54, 36, 'dsfsdf', 'sdfsd', 3, 5, '', '', NULL, 'active', '2018-10-11 14:36:30', '2018-10-11 14:36:30', NULL),
(55, 26, 'fdsfsd', 'fs', 4, 5, 'select', '', NULL, 'active', '2018-10-13 12:10:22', '2018-10-13 12:10:26', '2018-10-13 12:10:26'),
(56, 26, 'fdsfsd', 'fs', 4, 5, 'select', '', NULL, 'active', '2018-10-13 12:10:26', '2018-10-13 12:10:50', '2018-10-13 12:10:50'),
(57, 26, 'fdsfsd', 'fs', 4, 5, 'select', '', NULL, 'active', '2018-10-13 12:10:50', '2018-10-13 13:09:33', '2018-10-13 13:09:33'),
(58, 26, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', NULL, 'active', '2018-10-13 12:10:50', '2018-10-13 13:09:33', '2018-10-13 13:09:33'),
(59, 26, 'fdsfsd', 'fs', 4, 5, 'select', '', NULL, 'active', '2018-10-13 13:09:33', '2018-10-13 13:09:33', NULL),
(60, 26, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', NULL, 'active', '2018-10-13 13:09:33', '2018-10-13 13:09:33', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `merchant_product_option_values`
--

DROP TABLE IF EXISTS `merchant_product_option_values`;
CREATE TABLE IF NOT EXISTS `merchant_product_option_values` (
`id` int(11) NOT NULL,
`merchant_product_option_id` int(11) NOT NULL,
`name_ar` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
`price_prefix` enum('+','-') COLLATE utf8_unicode_ci NOT NULL,
`price` double NOT NULL,
`status` enum('active','in-active','deleted') COLLATE utf8_unicode_ci NOT NULL DEFAULT 'active',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `merchant_product_template_options`
--

DROP TABLE IF EXISTS `merchant_product_template_options`;
CREATE TABLE IF NOT EXISTS `merchant_product_template_options` (
`id` bigint(20) NOT NULL,
`merchant_id` int(10) NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`min_select` int(5) NOT NULL,
`max_select` int(5) NOT NULL,
`type` enum('text','textarea','select','radio','checkbox') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'text',
`is_required` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL DEFAULT 'yes',
`sort` int(5) NOT NULL,
`values` text COLLATE utf8mb4_unicode_ci COMMENT 'serialize[name_ar,name_en,price_prefix_price]',
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=2 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `merchant_product_template_options`
--

INSERT INTO `merchant_product_template_options` (`id`, `merchant_id`, `name_ar`, `name_en`, `min_select`, `max_select`, `type`, `is_required`, `sort`, `values`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'هل تريد كاتشب ؟', 'Do You want katchap ?', 2, 6, 'text', 'yes', 0, NULL, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_attributes`
--

DROP TABLE IF EXISTS `product_attributes`;
CREATE TABLE IF NOT EXISTS `product_attributes` (
`id` int(10) NOT NULL,
`merchant_product_category_id` int(10) NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`type` enum('text','textarea','select','multi-select') COLLATE utf8mb4_unicode_ci NOT NULL,
`is_required` enum('yes','no') COLLATE utf8mb4_unicode_ci NOT NULL,
`sort` int(10) NOT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL,
`deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=5 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attributes`
--

INSERT INTO `product_attributes` (`id`, `merchant_product_category_id`, `name_ar`, `name_en`, `type`, `is_required`, `sort`, `created_at`, `updated_at`, `deleted_at`) VALUES
(1, 3, 'رام', 'RAM', 'select', 'yes', 1, NULL, NULL, NULL),
(2, 3, 'بروسيسور', 'CPU', 'multi-select', 'no', 2, NULL, NULL, NULL),
(3, 3, 'شاشه', 'screen', 'text', 'no', 3, NULL, NULL, NULL),
(4, 3, 'ماوس', 'mouse', 'textarea', 'yes', 5, NULL, NULL, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `product_attribute_values`
--

DROP TABLE IF EXISTS `product_attribute_values`;
CREATE TABLE IF NOT EXISTS `product_attribute_values` (
`id` int(10) NOT NULL,
`product_attribute_id` int(10) NOT NULL,
`name_ar` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`name_en` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
`created_at` timestamp NULL DEFAULT NULL,
`updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB AUTO_INCREMENT=7 DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `product_attribute_values`
--

INSERT INTO `product_attribute_values` (`id`, `product_attribute_id`, `name_ar`, `name_en`, `created_at`, `updated_at`) VALUES
(1, 1, 'رام 2 جيجا', '2 GIGA', NULL, NULL),
(2, 1, '3 جيجا', '3 GIGA', NULL, NULL),
(3, 2, '2 جيجا', '2 GIGA', NULL, NULL),
(4, 2, '4 جيجا', '4 GIGA', NULL, NULL),
(5, 2, '6 جيجا', '6 GIGA', NULL, NULL),
(6, 2, '8 جيجا', '8 GIGA', NULL, NULL);

--
-- Indexes for dumped tables
--

--
-- Indexes for table `merchants`
--
ALTER TABLE `merchants`
ADD PRIMARY KEY (`id`),
ADD KEY `merchants_merchant_category_id_foreign` (`merchant_category_id`),
ADD KEY `merchants_area_id_foreign` (`area_id`),
ADD KEY `merchants_merchant_contract_id_foreign` (`merchant_contract_id`),
ADD KEY `merchants_staff_id_foreign` (`staff_id`),
ADD KEY `merchants_parent_id_foreign` (`parent_id`);

--
-- Indexes for table `merchant_categories`
--
ALTER TABLE `merchant_categories`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_products`
--
ALTER TABLE `merchant_products`
ADD PRIMARY KEY (`id`),
ADD KEY `merchant_products_merchant_id_foreign` (`merchant_id`),
ADD KEY `merchant_products_merchant_product_category_id_foreign` (`merchant_product_category_id`);

--
-- Indexes for table `merchant_product_attribute_value`
--
ALTER TABLE `merchant_product_attribute_value`
ADD PRIMARY KEY (`id`),
ADD KEY `merchant_product_id` (`merchant_product_id`),
ADD KEY `product_attribute_id` (`product_attribute_id`),
ADD KEY `product_attribute_value_id` (`product_attribute_value_id`);

--
-- Indexes for table `merchant_product_categories`
--
ALTER TABLE `merchant_product_categories`
ADD PRIMARY KEY (`id`),
ADD KEY `merchant_product_categories_merchant_id_foreign` (`merchant_category_id`),
ADD KEY `merchant_product_categories_approved_by_staff_id_foreign` (`staff_id`);

--
-- Indexes for table `merchant_product_options`
--
ALTER TABLE `merchant_product_options`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_product_option_values`
--
ALTER TABLE `merchant_product_option_values`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `merchant_product_template_options`
--
ALTER TABLE `merchant_product_template_options`
ADD PRIMARY KEY (`id`);

--
-- Indexes for table `product_attributes`
--
ALTER TABLE `product_attributes`
ADD PRIMARY KEY (`id`),
ADD KEY `merchant_product_category_id` (`merchant_product_category_id`);

--
-- Indexes for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
ADD PRIMARY KEY (`id`),
ADD KEY `merchant_product_attribute_id` (`product_attribute_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `merchants`
--
ALTER TABLE `merchants`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=968;
--
-- AUTO_INCREMENT for table `merchant_categories`
--
ALTER TABLE `merchant_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `merchant_products`
--
ALTER TABLE `merchant_products`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=37;
--
-- AUTO_INCREMENT for table `merchant_product_attribute_value`
--
ALTER TABLE `merchant_product_attribute_value`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=24;
--
-- AUTO_INCREMENT for table `merchant_product_categories`
--
ALTER TABLE `merchant_product_categories`
MODIFY `id` int(10) unsigned NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=4;
--
-- AUTO_INCREMENT for table `merchant_product_options`
--
ALTER TABLE `merchant_product_options`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=61;
--
-- AUTO_INCREMENT for table `merchant_product_option_values`
--
ALTER TABLE `merchant_product_option_values`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
--
-- AUTO_INCREMENT for table `merchant_product_template_options`
--
ALTER TABLE `merchant_product_template_options`
MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=2;
--
-- AUTO_INCREMENT for table `product_attributes`
--
ALTER TABLE `product_attributes`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=5;
--
-- AUTO_INCREMENT for table `product_attribute_values`
--
ALTER TABLE `product_attribute_values`
MODIFY `id` int(10) NOT NULL AUTO_INCREMENT,AUTO_INCREMENT=7;
--
-- Constraints for dumped tables
--

--
-- Constraints for table `merchants`
--
ALTER TABLE `merchants`
ADD CONSTRAINT `merchants_area_id_foreign` FOREIGN KEY (`area_id`) REFERENCES `areas` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchants_merchant_category_id_foreign` FOREIGN KEY (`merchant_category_id`) REFERENCES `merchant_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchants_merchant_contract_id_foreign` FOREIGN KEY (`merchant_contract_id`) REFERENCES `merchant_contracts` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchants_parent_id_foreign` FOREIGN KEY (`parent_id`) REFERENCES `merchants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchants_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `merchant_products`
--
ALTER TABLE `merchant_products`
ADD CONSTRAINT `merchant_products_merchant_id_foreign` FOREIGN KEY (`merchant_id`) REFERENCES `merchants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchant_products_merchant_product_category_id_foreign` FOREIGN KEY (`merchant_product_category_id`) REFERENCES `merchant_product_categories` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

--
-- Constraints for table `merchant_product_categories`
--
ALTER TABLE `merchant_product_categories`
ADD CONSTRAINT `merchant_product_categories_approved_by_staff_id_foreign` FOREIGN KEY (`staff_id`) REFERENCES `staff` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION,
ADD CONSTRAINT `merchant_product_categories_merchant_id_foreign` FOREIGN KEY (`merchant_category_id`) REFERENCES `merchants` (`id`) ON DELETE NO ACTION ON UPDATE NO ACTION;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
