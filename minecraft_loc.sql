-- phpMyAdmin SQL Dump
-- version 5.1.2
-- https://www.phpmyadmin.net/
--
-- Хост: localhost
-- Час створення: Трв 09 2022 р., 17:04
-- Версія сервера: 8.0.28
-- Версія PHP: 8.1.2

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- База даних: `minecraft.loc`
--

-- --------------------------------------------------------

--
-- Структура таблиці `apis`
--

CREATE TABLE `apis` (
  `id` int UNSIGNED NOT NULL,
  `shortcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `img` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT ''
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `apis`
--

INSERT INTO `apis` (`id`, `shortcode`, `name`, `sort`, `created_at`, `updated_at`, `img`) VALUES
(2, 'gta5', 'GTA 5', 2, '2022-01-25 07:58:08', '2022-05-09 11:04:12', 'y61RS9R3NkTVJ7BZJCKtcGWwNIMeXe.jpg'),
(8, 'gta4', 'GTA 4', 3, '2022-02-02 12:12:58', '2022-02-02 12:12:58', ''),
(9, 'sanandreas', 'GTA San Andreas', 4, '2022-02-08 06:57:37', '2022-02-08 06:57:37', ''),
(10, 'vicecity', 'GTA Vice City', 5, '2022-02-08 06:58:11', '2022-02-08 06:58:11', ''),
(11, 'gta3', 'GTA 3', 6, '2022-02-08 06:58:58', '2022-05-09 11:04:23', '0VgUch6aRBasxBSwOGKycP5VI7IITM.jpg');

-- --------------------------------------------------------

--
-- Структура таблиці `groups`
--

CREATE TABLE `groups` (
  `id` int UNSIGNED NOT NULL,
  `shortcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `group` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `groups`
--

INSERT INTO `groups` (`id`, `shortcode`, `group`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'player_skins', 'Player skins', 1, '2022-01-25 11:08:44', '2022-02-02 12:15:10'),
(2, 'weapon', 'Weapon', 2, '2022-01-26 06:32:22', '2022-02-02 12:15:17'),
(3, 'transport', 'Transport', 3, '2022-02-02 10:09:57', '2022-02-02 12:15:22'),
(4, 'world', 'World', 4, '2022-02-02 10:11:43', '2022-02-02 12:15:28'),
(5, 'other', 'Other', 5, '2022-02-02 10:12:28', '2022-02-02 12:15:33');

-- --------------------------------------------------------

--
-- Структура таблиці `helps`
--

CREATE TABLE `helps` (
  `id` int UNSIGNED NOT NULL,
  `title` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `api_id` int UNSIGNED DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `language_id` int UNSIGNED DEFAULT NULL,
  `group_id` int UNSIGNED DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `helps`
--

INSERT INTO `helps` (`id`, `title`, `description`, `api_id`, `category_id`, `language_id`, `group_id`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'GTA5 PS3, PS4', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 1, NULL, 1, '2022-01-25 11:04:22', '2022-02-02 10:17:45'),
(6, 'GTA5 Xbox', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 3, 4, NULL, 2, '2022-02-02 10:18:22', '2022-02-11 10:00:43'),
(7, 'GTA5 PC', 'Open smartphone and enter phone number, or open console and enter word', 2, 6, 1, NULL, 3, '2022-02-02 10:20:28', '2022-02-02 10:21:46'),
(14, 'GTA5 PS3, PS4 [copy1]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 4, NULL, 4, '2022-02-11 07:53:18', '2022-02-11 07:53:18'),
(15, 'GTA5 PS3, PS4 [copy2]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 6, NULL, 5, '2022-02-11 07:53:18', '2022-02-11 07:53:18'),
(16, 'GTA5 PS3, PS4 [copy3]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 6, 4, NULL, 6, '2022-02-11 07:53:18', '2022-02-11 11:21:48'),
(17, 'GTA5 PS3, PS4 [copy4]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 6, 1, NULL, 7, '2022-02-11 07:53:18', '2022-02-11 07:53:18'),
(19, 'GTA5 PS3, PS4 [copy5]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 7, 1, NULL, 8, '2022-02-11 11:12:26', '2022-02-11 11:12:26'),
(20, 'GTA5 PS3, PS4 [copy6]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 8, 2, 4, NULL, 9, '2022-02-11 11:12:41', '2022-02-11 11:22:52'),
(21, 'GTA5 PS3, PS4 [copy7]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 1, NULL, 10, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(22, 'GTA5 PS3, PS4 [copy8]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 4, NULL, 11, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(23, 'GTA5 PS3, PS4 [copy9]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 5, NULL, 12, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(24, 'GTA5 PS3, PS4 [copy10]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 6, NULL, 13, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(25, 'GTA5 PS3, PS4 [copy11]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 2, 1, NULL, 14, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(26, 'GTA5 PS3, PS4 [copy12]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 3, 1, NULL, 15, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(27, 'GTA5 PS3, PS4 [copy13]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 6, 1, NULL, 16, '2022-04-01 06:56:41', '2022-04-01 06:56:41'),
(28, 'GTA5 PS3, PS4 [copy14]', 'Just start enter from your joystic, or open smartphone and enter phone number.', 2, 7, 1, NULL, 17, '2022-04-01 06:56:41', '2022-04-01 06:56:41');

-- --------------------------------------------------------

--
-- Структура таблиці `languages`
--

CREATE TABLE `languages` (
  `id` int UNSIGNED NOT NULL,
  `shortcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `language` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `flag` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `languages`
--

INSERT INTO `languages` (`id`, `shortcode`, `language`, `flag`, `description`, `sort`, `created_at`, `updated_at`) VALUES
(1, 'en', 'English', 'VNbH0pkv1dVMwjhHJBmLYR7F4B5Ebl.png', 'English', 3, '2022-01-25 11:08:32', '2022-02-07 09:03:11'),
(4, 'ru', 'Russian', 'g3GuOuiNDVVZB60mCp1S8VMTQrxpd5.png', 'Русский', 4, '2022-01-26 07:05:55', '2022-02-07 09:03:23'),
(5, 'de', 'Deutsch', 'X3KzjDqa62lOg8oc45soIlbbNPOjBk.png', 'Deutsch', 5, '2022-02-10 11:44:53', '2022-02-10 11:44:53'),
(6, 'es', 'Español', 'lvnA2Nxtxkdl3CXZ9kkiDSnpC4tacY.png', 'Español', 6, '2022-02-10 11:45:39', '2022-02-10 11:45:39');

-- --------------------------------------------------------

--
-- Структура таблиці `categories`
--

CREATE TABLE `categories` (
  `id` int UNSIGNED NOT NULL,
  `shortcode` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `category` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `categories`
--

INSERT INTO `categories` (`id`, `shortcode`, `category`, `sort`, `created_at`, `updated_at`) VALUES
(2, 'ps3_5', 'PS3, PS4, PS5', 1, '2022-01-25 11:06:18', '2022-02-09 05:55:56'),
(3, 'xb', 'XBOX', 3, '2022-01-26 05:58:18', '2022-02-02 09:57:49'),
(6, 'pc', 'PC', 4, '2022-02-02 09:58:00', '2022-02-02 09:58:00'),
(7, 'all', 'All', 5, '2022-02-09 05:22:14', '2022-02-10 12:12:59');

-- --------------------------------------------------------

--
-- Структура таблиці `settings`
--

CREATE TABLE `settings` (
  `id` int UNSIGNED NOT NULL,
  `key` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL,
  `value` text CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `settings`
--

INSERT INTO `settings` (`id`, `key`, `value`) VALUES
(1, 'app_api_auth:', 'false'),
(2, 'app_id:', '\"5554fdggdfg54534267dfgdfgf43frvd\"'),
(3, 'list_update:', '3'),
(4, 'ads_d:', '2'),
(5, 'adspro_c:', '2'),
(6, 'pro_c:', '3'),
(8, 'pro_sd:', '3');

-- --------------------------------------------------------

--
-- Структура таблиці `skins`
--

CREATE TABLE `skins` (
  `id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `description` text CHARACTER SET utf8 COLLATE utf8_unicode_ci,
  `code` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sound` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `video` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `screenshot` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type` enum('free','pro') CHARACTER SET utf8 COLLATE utf8_unicode_ci NOT NULL DEFAULT 'free',
  `api_id` int UNSIGNED DEFAULT NULL,
  `category_id` int UNSIGNED DEFAULT NULL,
  `language_id` int UNSIGNED DEFAULT NULL,
  `group_id` int UNSIGNED DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `sortapi` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `phone` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `console` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `type_name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

--
-- Дамп даних таблиці `skins`
--

INSERT INTO `skins` (`id`, `name`, `description`, `code`, `sound`, `video`, `screenshot`, `type`, `api_id`, `category_id`, `language_id`, `group_id`, `sort`, `sortapi`, `created_at`, `updated_at`, `phone`, `console`, `type_name`) VALUES
(23, 'Ohrimenko Охріменко [copy6]', 'sfs dfsdf', '1234', NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'pro', 2, 7, 4, 2, 12, 12, '2022-02-11 11:39:31', '2022-04-04 11:06:17', '0637525609', 'dfgsdfsdf', 'gta5_ohrimenko_ohrimenko'),
(28, 'Ohrimenko Охріменко [copy8]', NULL, NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'free', 2, 2, 4, 2, 17, 17, '2022-04-01 06:42:26', '2022-04-04 11:23:49', NULL, NULL, 'gta5_ohrimenko_ohrimenko'),
(32, 'Ohrimenko Охріменко [copy12]', NULL, NULL, NULL, NULL, NULL, 'free', 11, 2, 4, 2, 21, 21, '2022-04-01 06:42:26', '2022-04-04 10:35:24', NULL, NULL, 'gta3_ohrimenko_ohrimenko_copy12'),
(41, 'Ohrimenko Охріменко [copy6] [copy1]', NULL, NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'free', 2, 7, 1, 2, 22, 22, '2022-04-04 07:14:02', '2022-04-04 10:35:24', '0637525609', NULL, 'gta5_ohrimenko_ohrimenko_copy6_copy1'),
(42, 'Ohrimenko Охріменко [copy6] [copy2]', NULL, NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'free', 2, 7, 5, 2, 23, 23, '2022-04-04 07:18:07', '2022-04-04 10:35:24', '0637525609', NULL, 'gta5_ohrimenko_ohrimenko_copy6_copy2'),
(43, 'Ohrimenko Охріменко [copy8] [copy1]', NULL, NULL, NULL, NULL, NULL, 'free', 2, 2, 6, 2, 24, 24, '2022-04-04 07:18:31', '2022-04-04 10:35:24', NULL, NULL, 'gta5_ohrimenko_ohrimenko_copy8_copy1'),
(44, 'Ohrimenko Охріменко [copy6] [copy3]', 'sfs dfsdf', NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'pro', 2, 7, 1, 2, 25, 25, '2022-04-04 07:18:50', '2022-04-04 11:06:24', '0637525609', NULL, 'gta5_ohrimenko_ohrimenko'),
(45, 'Ohrimenko Охріменко [copy8] [copy2]', NULL, NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'free', 2, 2, 1, 2, 26, 26, '2022-04-04 07:23:17', '2022-04-04 12:12:27', NULL, NULL, 'gta5_ohrimenko_ohrimenko'),
(46, 'Ohrimenko Охріменко [copy6] [copy4]', NULL, NULL, NULL, NULL, NULL, 'free', 2, 7, 4, 2, 27, 27, '2022-04-04 07:23:29', '2022-04-04 10:35:24', NULL, NULL, 'gta5_ohrimenko_ohrimenko_copy6_copy4'),
(47, 'Ohrimenko Охріменко [copy6] [copy5]', NULL, NULL, NULL, NULL, NULL, 'free', 8, 7, 4, 2, 28, 28, '2022-04-04 07:23:40', '2022-04-04 10:35:24', NULL, NULL, 'gta4_ohrimenko_ohrimenko_copy6_copy5'),
(48, 'Ohrimenko Охріменко [copy6] [copy6]', 'sfs dfsdf', NULL, NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'pro', 2, 7, 4, 2, 29, 29, '2022-05-02 10:03:28', '2022-05-02 10:03:28', '0637525609', NULL, NULL),
(49, 'Ohrimenko Охріменко [copy6] [copy7]', NULL, '1234', NULL, NULL, NULL, 'free', 2, 7, 4, 2, 30, 30, '2022-05-02 10:11:54', '2022-05-02 10:11:54', NULL, NULL, NULL),
(50, 'Ohrimenko Охріменко [copy6] [copy8]', 'sfs dfsdf', '1234', NULL, 'https://laravel.su/docs/8.x/queries#upserts', '', 'pro', 2, 7, 1, 2, 31, 31, '2022-05-02 10:15:29', '2022-05-02 10:15:29', '0637525609', NULL, NULL);

--
-- Індекси збережених таблиць
--

--
-- Індекси таблиці `apis`
--
ALTER TABLE `apis`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `apis_shortcode_unique` (`shortcode`),
  ADD KEY `apis_shortcode_index` (`shortcode`),
  ADD KEY `apis_sort_index` (`sort`);

--
-- Індекси таблиці `groups`
--
ALTER TABLE `groups`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `groups_shortcode_unique` (`shortcode`),
  ADD KEY `groups_shortcode_index` (`shortcode`),
  ADD KEY `groups_sort_index` (`sort`);

--
-- Індекси таблиці `helps`
--
ALTER TABLE `helps`
  ADD PRIMARY KEY (`id`),
  ADD KEY `helps_sort_index` (`sort`),
  ADD KEY `helps_api_id_index` (`api_id`),
  ADD KEY `helps_category_id_index` (`category_id`),
  ADD KEY `helps_language_id_index` (`language_id`),
  ADD KEY `helps_group_id_index` (`group_id`);

--
-- Індекси таблиці `languages`
--
ALTER TABLE `languages`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `languages_shortcode_unique` (`shortcode`),
  ADD KEY `languages_shortcode_index` (`shortcode`),
  ADD KEY `languages_sort_index` (`sort`);

--
-- Індекси таблиці `categories`
--
ALTER TABLE `categories`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `categories_shortcode_unique` (`shortcode`),
  ADD KEY `categories_shortcode_index` (`shortcode`),
  ADD KEY `categories_sort_index` (`sort`);

--
-- Індекси таблиці `settings`
--
ALTER TABLE `settings`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `settings_key_unique` (`key`);

--
-- Індекси таблиці `skins`
--
ALTER TABLE `skins`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skins_sort_index` (`sort`),
  ADD KEY `skins_api_id_index` (`api_id`),
  ADD KEY `skins_category_id_index` (`category_id`),
  ADD KEY `skins_language_id_index` (`language_id`),
  ADD KEY `skins_group_id_index` (`group_id`),
  ADD KEY `skins_sort2_index` (`sortapi`);

--
-- AUTO_INCREMENT для збережених таблиць
--

--
-- AUTO_INCREMENT для таблиці `apis`
--
ALTER TABLE `apis`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=12;

--
-- AUTO_INCREMENT для таблиці `groups`
--
ALTER TABLE `groups`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- AUTO_INCREMENT для таблиці `helps`
--
ALTER TABLE `helps`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=29;

--
-- AUTO_INCREMENT для таблиці `languages`
--
ALTER TABLE `languages`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT для таблиці `categories`
--
ALTER TABLE `categories`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT для таблиці `settings`
--
ALTER TABLE `settings`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT для таблиці `skins`
--
ALTER TABLE `skins`
  MODIFY `id` int UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- Обмеження зовнішнього ключа збережених таблиць
--

--
-- Обмеження зовнішнього ключа таблиці `helps`
--
ALTER TABLE `helps`
  ADD CONSTRAINT `helps_api_id_foreign` FOREIGN KEY (`api_id`) REFERENCES `apis` (`id`),
  ADD CONSTRAINT `helps_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `helps_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `helps_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);

--
-- Обмеження зовнішнього ключа таблиці `skins`
--
ALTER TABLE `skins`
  ADD CONSTRAINT `skins_api_id_foreign` FOREIGN KEY (`api_id`) REFERENCES `apis` (`id`),
  ADD CONSTRAINT `skins_group_id_foreign` FOREIGN KEY (`group_id`) REFERENCES `groups` (`id`),
  ADD CONSTRAINT `skins_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`),
  ADD CONSTRAINT `skins_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;

CREATE TABLE `category_languages` (
  `id` int UNSIGNED NOT NULL,
  `category_id` int UNSIGNED NOT NULL,
  `language_id` int UNSIGNED NOT NULL,
  `name` varchar(255) CHARACTER SET utf8 COLLATE utf8_unicode_ci DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

ALTER TABLE `category_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `category_languages_category_id_index` (`category_id`),
  ADD KEY `category_languages_language_id_index` (`language_id`),
  ADD KEY `category_languages_sort_index` (`sort`);

ALTER TABLE `category_languages`
  ADD UNIQUE KEY `category_category_id_language_id_unique` (`category_id`, `language_id`);
  
ALTER TABLE `category_languages`
  ADD CONSTRAINT `category_languages_category_id_foreign` FOREIGN KEY (`category_id`) REFERENCES `categories` (`id`),
  ADD CONSTRAINT `category_languages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

CREATE TABLE `skin_languages` (
  `id` int UNSIGNED NOT NULL,
  `skin_id` int UNSIGNED NOT NULL,
  `language_id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `description` text DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

ALTER TABLE `skin_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `skin_languages_skin_id_index` (`skin_id`),
  ADD KEY `skin_languages_language_id_index` (`language_id`),
  ADD KEY `skin_languages_sort_index` (`sort`);

ALTER TABLE `skin_languages`
  ADD UNIQUE KEY `category_skin_id_language_id_unique` (`skin_id`, `language_id`);
  
ALTER TABLE `skin_languages`
  ADD CONSTRAINT `skin_languages_skin_id_foreign` FOREIGN KEY (`skin_id`) REFERENCES `skins` (`id`),
  ADD CONSTRAINT `skin_languages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

CREATE TABLE `help_languages` (
  `id` int UNSIGNED NOT NULL,
  `help_id` int UNSIGNED NOT NULL,
  `language_id` int UNSIGNED NOT NULL,
  `name` varchar(255) DEFAULT NULL,
  `text` text DEFAULT NULL,
  `sort` float NOT NULL DEFAULT '1',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb3 COLLATE=utf8_unicode_ci;

ALTER TABLE `help_languages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `help_languages_help_id_index` (`help_id`),
  ADD KEY `help_languages_language_id_index` (`language_id`),
  ADD KEY `help_languages_sort_index` (`sort`);

ALTER TABLE `help_languages`
  ADD UNIQUE KEY `category_help_id_language_id_unique` (`help_id`, `language_id`);
  
ALTER TABLE `help_languages`
  ADD CONSTRAINT `help_languages_help_id_foreign` FOREIGN KEY (`help_id`) REFERENCES `helps` (`id`),
  ADD CONSTRAINT `help_languages_language_id_foreign` FOREIGN KEY (`language_id`) REFERENCES `languages` (`id`);

