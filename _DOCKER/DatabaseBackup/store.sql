-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Host: db:3306
-- Erstellungszeit: 06. Okt 2022 um 17:16
-- Server-Version: 5.7.36
-- PHP-Version: 7.4.20

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Datenbank: `store`
--

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `cart_items`
--

CREATE TABLE `cart_items` (
  `id` int(11) NOT NULL,
  `product_id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `quantity` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `cart_items`
--

INSERT INTO `cart_items` (`id`, `product_id`, `user_id`, `quantity`, `created_at`, `updated_at`) VALUES
(1, 99, 1, 2, '2022-10-06 10:52:19', '2022-10-06 10:52:19'),
(2, 97, 1, 2, '2022-10-06 10:52:19', '2022-10-06 10:52:19'),
(3, 100, 1, 5, '2022-10-06 10:52:19', '2022-10-06 10:52:19');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category`
--

CREATE TABLE `category` (
  `id` int(11) NOT NULL,
  `sort` int(11) NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `parent_id` int(11) DEFAULT NULL,
  `active` tinyint(1) NOT NULL,
  `path` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `category`
--

INSERT INTO `category` (`id`, `sort`, `created_at`, `updated_at`, `parent_id`, `active`, `path`, `img`) VALUES
(24, 1, '2022-09-22 15:38:03', '2022-09-30 17:27:40', NULL, 1, NULL, 'Category/21e260dba12ef856b6fa'),
(26, 2, '2022-09-22 15:45:49', '2022-09-30 17:23:36', NULL, 1, NULL, 'Category/470b8b46c08497f7a152'),
(29, 1, '2022-09-23 14:06:18', '2022-09-30 17:26:24', 26, 1, '|26|', 'Category/c251c770979c6d9805da'),
(34, 2, '2022-09-30 17:34:40', '2022-09-30 17:54:42', 26, 1, '|26|', 'Category/3d7892c783df15054caf');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `category_translation`
--

CREATE TABLE `category_translation` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `category_translation`
--

INSERT INTO `category_translation` (`id`, `language_id`, `category_id`, `name`, `description`) VALUES
(3, 1, 24, 'Autos', 'Autos für Leute die nicht fahren können'),
(4, 2, 24, 'Cars', 'Cars for drivers who cant drive'),
(23, 1, 26, 'Handys', 'Handys vom besten Shop für den besten Kunden!'),
(24, 2, 26, 'Mobile Phones', 'Mobile Phones from the best store for the best Customer'),
(25, 1, 29, 'Apple', 'parent of test222 DEparent of test222 DEparent of test222 DE'),
(26, 2, 29, 'Apple', 'Apple Handys'),
(29, 1, 34, 'Android', 'Android'),
(30, 2, 34, 'Android', 'Android');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `doctrine_migration_versions`
--

CREATE TABLE `doctrine_migration_versions` (
  `version` varchar(191) COLLATE utf8_unicode_ci NOT NULL,
  `executed_at` datetime DEFAULT NULL,
  `execution_time` int(11) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Daten für Tabelle `doctrine_migration_versions`
--

INSERT INTO `doctrine_migration_versions` (`version`, `executed_at`, `execution_time`) VALUES
('DoctrineMigrations\\Version20220920145848', '2022-09-20 14:59:24', 173),
('DoctrineMigrations\\Version20220920150526', '2022-09-20 15:05:49', 84),
('DoctrineMigrations\\Version20220921101355', '2022-09-21 10:14:47', 63),
('DoctrineMigrations\\Version20220921103924', '2022-09-21 10:39:38', 17),
('DoctrineMigrations\\Version20220921135856', '2022-09-21 13:59:00', 17),
('DoctrineMigrations\\Version20220921144810', '2022-09-21 14:48:14', 47),
('DoctrineMigrations\\Version20220922111900', '2022-09-22 11:19:22', 33),
('DoctrineMigrations\\Version20220923142804', '2022-09-23 14:28:17', 19),
('DoctrineMigrations\\Version20220923160017', '2022-09-23 16:00:26', 26),
('DoctrineMigrations\\Version20220928144822', '2022-09-28 14:48:29', 23),
('DoctrineMigrations\\Version20220929165915', '2022-09-29 16:59:27', 50),
('DoctrineMigrations\\Version20220929171118', '2022-09-29 17:11:21', 78),
('DoctrineMigrations\\Version20221004164826', '2022-10-04 16:51:37', 16);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `language`
--

CREATE TABLE `language` (
  `id` int(11) NOT NULL,
  `iso` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `display_name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `language`
--

INSERT INTO `language` (`id`, `iso`, `display_name`) VALUES
(1, 'de-DE', 'Deutsch'),
(2, 'en-EN', 'English');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `messenger_messages`
--

CREATE TABLE `messenger_messages` (
  `id` bigint(20) NOT NULL,
  `body` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `headers` longtext COLLATE utf8mb4_unicode_ci NOT NULL,
  `queue_name` varchar(190) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL,
  `available_at` datetime NOT NULL,
  `delivered_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `order`
--

CREATE TABLE `order` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `payload` json NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `price` double NOT NULL,
  `vat` int(11) NOT NULL,
  `shipping_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `payment_method` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `order`
--

INSERT INTO `order` (`id`, `user_id`, `payload`, `created_at`, `updated_at`, `price`, `vat`, `shipping_method`, `payment_method`) VALUES
(12, 1, '{\"products\": [{\"id\": 94, \"img\": \"Product/b2c07153e78077a77186\", \"name\": \"My Product-43\", \"sort\": 43, \"price\": 19.99, \"stock\": 499, \"active\": true, \"quantity\": 1, \"createdAt\": \"2022-09-30T14:33:45+00:00\", \"updatedAt\": \"2022-10-05T12:55:23+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-43\", \"translation\": [{\"id\": 187, \"name\": \"Mein Produkt-43\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-43\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-43\"}, {\"id\": 188, \"name\": \"My Product-43\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-43\", \"shortDescription\": \"My Product Short-Description-43\"}], \"productNumber\": \"P-10243\", \"calculatedImages\": [{\"img\": \"Product/b2c07153e78077a77186/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/b2c07153e78077a77186/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/b2c07153e78077a77186/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/b2c07153e78077a77186/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/b2c07153e78077a77186/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/b2c07153e78077a77186/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/b2c07153e78077a77186/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/b2c07153e78077a77186/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-43\"}, {\"id\": 93, \"img\": \"Product/f747f1596efec17620e0\", \"name\": \"My Product-42\", \"sort\": 42, \"price\": 19.99, \"stock\": 499, \"active\": true, \"quantity\": 1, \"createdAt\": \"2022-09-30T14:33:44+00:00\", \"updatedAt\": \"2022-10-05T12:55:23+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-42\", \"translation\": [{\"id\": 185, \"name\": \"Mein Produkt-42\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-42\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-42\"}, {\"id\": 186, \"name\": \"My Product-42\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-42\", \"shortDescription\": \"My Product Short-Description-42\"}], \"productNumber\": \"P-10242\", \"calculatedImages\": [{\"img\": \"Product/f747f1596efec17620e0/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/f747f1596efec17620e0/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/f747f1596efec17620e0/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/f747f1596efec17620e0/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/f747f1596efec17620e0/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/f747f1596efec17620e0/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/f747f1596efec17620e0/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/f747f1596efec17620e0/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-42\"}, {\"id\": 92, \"img\": \"Product/38dcea068ff010db6608\", \"name\": \"My Product-41\", \"sort\": 41, \"price\": 19.99, \"stock\": 487, \"active\": true, \"quantity\": 13, \"createdAt\": \"2022-09-30T14:33:44+00:00\", \"updatedAt\": \"2022-10-05T12:55:23+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-41\", \"translation\": [{\"id\": 183, \"name\": \"Mein Produkt-41\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-41\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-41\"}, {\"id\": 184, \"name\": \"My Product-41\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-41\", \"shortDescription\": \"My Product Short-Description-41\"}], \"productNumber\": \"P-10241\", \"calculatedImages\": [{\"img\": \"Product/38dcea068ff010db6608/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/38dcea068ff010db6608/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/38dcea068ff010db6608/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/38dcea068ff010db6608/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/38dcea068ff010db6608/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/38dcea068ff010db6608/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/38dcea068ff010db6608/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/38dcea068ff010db6608/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-41\"}, {\"id\": 95, \"img\": \"Product/eaf6c670734cc54ec43a\", \"name\": \"My Product-44\", \"sort\": 44, \"price\": 19.99, \"stock\": 495, \"active\": true, \"quantity\": 5, \"createdAt\": \"2022-09-30T14:33:46+00:00\", \"updatedAt\": \"2022-10-05T12:55:23+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-44\", \"translation\": [{\"id\": 189, \"name\": \"Mein Produkt-44\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-44\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-44\"}, {\"id\": 190, \"name\": \"My Product-44\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-44\", \"shortDescription\": \"My Product Short-Description-44\"}], \"productNumber\": \"P-10244\", \"calculatedImages\": [{\"img\": \"Product/eaf6c670734cc54ec43a/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-44\"}, {\"id\": 96, \"img\": \"Product/fbb16a082050f34fa691\", \"name\": \"My Product-45\", \"sort\": 45, \"price\": 19.99, \"stock\": 487, \"active\": true, \"quantity\": 7, \"createdAt\": \"2022-09-30T14:33:46+00:00\", \"updatedAt\": \"2022-10-05T12:55:23+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-45\", \"translation\": [{\"id\": 191, \"name\": \"Mein Produkt-45\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-45\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-45\"}, {\"id\": 192, \"name\": \"My Product-45\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-45\", \"shortDescription\": \"My Product Short-Description-45\"}], \"productNumber\": \"P-10245\", \"calculatedImages\": [{\"img\": \"Product/fbb16a082050f34fa691/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-45\"}]}', '2022-10-05 12:55:51', '2022-10-05 12:55:51', 539.73, 19, 'd', 'c'),
(13, 1, '{\"products\": [{\"id\": 93, \"img\": \"Product/f747f1596efec17620e0\", \"name\": \"My Product-42\", \"sort\": 42, \"price\": 19.99, \"stock\": 498, \"active\": true, \"quantity\": 1, \"createdAt\": \"2022-09-30T14:33:44+00:00\", \"updatedAt\": \"2022-10-05T12:55:51+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-42\", \"translation\": [{\"id\": 185, \"name\": \"Mein Produkt-42\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-42\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-42\"}, {\"id\": 186, \"name\": \"My Product-42\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-42\", \"shortDescription\": \"My Product Short-Description-42\"}], \"productNumber\": \"P-10242\", \"calculatedImages\": [{\"img\": \"Product/f747f1596efec17620e0/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/f747f1596efec17620e0/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/f747f1596efec17620e0/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/f747f1596efec17620e0/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/f747f1596efec17620e0/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/f747f1596efec17620e0/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/f747f1596efec17620e0/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/f747f1596efec17620e0/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-42\"}, {\"id\": 92, \"img\": \"Product/38dcea068ff010db6608\", \"name\": \"My Product-41\", \"sort\": 41, \"price\": 19.99, \"stock\": 474, \"active\": true, \"quantity\": 13, \"createdAt\": \"2022-09-30T14:33:44+00:00\", \"updatedAt\": \"2022-10-05T12:55:51+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-41\", \"translation\": [{\"id\": 183, \"name\": \"Mein Produkt-41\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-41\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-41\"}, {\"id\": 184, \"name\": \"My Product-41\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-41\", \"shortDescription\": \"My Product Short-Description-41\"}], \"productNumber\": \"P-10241\", \"calculatedImages\": [{\"img\": \"Product/38dcea068ff010db6608/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/38dcea068ff010db6608/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/38dcea068ff010db6608/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/38dcea068ff010db6608/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/38dcea068ff010db6608/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/38dcea068ff010db6608/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/38dcea068ff010db6608/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/38dcea068ff010db6608/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-41\"}, {\"id\": 95, \"img\": \"Product/eaf6c670734cc54ec43a\", \"name\": \"My Product-44\", \"sort\": 44, \"price\": 19.99, \"stock\": 490, \"active\": true, \"quantity\": 5, \"createdAt\": \"2022-09-30T14:33:46+00:00\", \"updatedAt\": \"2022-10-05T12:55:51+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-44\", \"translation\": [{\"id\": 189, \"name\": \"Mein Produkt-44\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-44\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-44\"}, {\"id\": 190, \"name\": \"My Product-44\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-44\", \"shortDescription\": \"My Product Short-Description-44\"}], \"productNumber\": \"P-10244\", \"calculatedImages\": [{\"img\": \"Product/eaf6c670734cc54ec43a/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/eaf6c670734cc54ec43a/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-44\"}, {\"id\": 96, \"img\": \"Product/fbb16a082050f34fa691\", \"name\": \"My Product-45\", \"sort\": 45, \"price\": 19.99, \"stock\": 480, \"active\": true, \"quantity\": 7, \"createdAt\": \"2022-09-30T14:33:46+00:00\", \"updatedAt\": \"2022-10-05T12:55:51+00:00\", \"categories\": [[]], \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-45\", \"translation\": [{\"id\": 191, \"name\": \"Mein Produkt-45\", \"language\": {\"id\": 1, \"iso\": \"de-DE\", \"displayName\": \"Deutsch\"}, \"languageId\": 1, \"description\": \"<h2>Mein Produkt <span style=\\\"background-color:hsl(120, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Beschreibung</h2>-45\", \"shortDescription\": \"Meine Produkt Kurzbeschreibung-45\"}, {\"id\": 192, \"name\": \"My Product-45\", \"language\": {\"id\": 2, \"iso\": \"en-EN\", \"displayName\": \"English\"}, \"languageId\": 2, \"description\": \"<h2>My Product Long-Description <span style=\\\"color:hsl(0, 75%, 60%);\\\"><mark class=\\\"marker-yellow\\\">HTML</mark></span> Description</h2>-45\", \"shortDescription\": \"My Product Short-Description-45\"}], \"productNumber\": \"P-10245\", \"calculatedImages\": [{\"img\": \"Product/fbb16a082050f34fa691/1-1140.jpg\", \"size\": \"1140\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-1440.jpg\", \"size\": \"1440\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-1920.jpg\", \"size\": \"1920\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-400.jpg\", \"size\": \"400\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-540.jpg\", \"size\": \"540\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-720.jpg\", \"size\": \"720\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-960.jpg\", \"size\": \"960\"}, {\"img\": \"Product/fbb16a082050f34fa691/1-org.jpg\", \"size\": \"org\"}], \"shortDescription\": \"My Product Short-Description-45\"}]}', '2022-10-05 12:59:05', '2022-10-05 12:59:05', 519.74, 19, 'd', 'c');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product`
--

CREATE TABLE `product` (
  `id` int(11) NOT NULL,
  `stock` int(11) NOT NULL,
  `price` double NOT NULL,
  `active` tinyint(1) NOT NULL,
  `img` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `sort` int(11) NOT NULL,
  `product_number` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `product`
--

INSERT INTO `product` (`id`, `stock`, `price`, `active`, `img`, `sort`, `product_number`, `created_at`, `updated_at`) VALUES
(52, 500, 19.99, 1, 'Product/109a93417eb8532ae7ad', 1, 'P-1021', '2022-09-30 14:33:20', '2022-09-30 14:33:20'),
(53, 500, 19.99, 1, 'Product/49a2d13357fca4e22d47', 2, 'P-1022', '2022-09-30 14:33:21', '2022-09-30 14:33:21'),
(54, 500, 19.99, 1, 'Product/446663d36edc61cc22f2', 3, 'P-1023', '2022-09-30 14:33:22', '2022-09-30 14:33:22'),
(55, 500, 19.99, 1, 'Product/ffc5555af2365176b67a', 4, 'P-1024', '2022-09-30 14:33:22', '2022-09-30 14:33:22'),
(56, 500, 19.99, 1, 'Product/2b77a66379fe667a6df6', 5, 'P-1025', '2022-09-30 14:33:23', '2022-09-30 14:33:23'),
(57, 500, 19.99, 1, 'Product/3eb172bfddb7d20900e3', 6, 'P-1026', '2022-09-30 14:33:23', '2022-09-30 14:33:23'),
(58, 500, 19.99, 1, 'Product/a90caae7c6b7edf76230', 7, 'P-1027', '2022-09-30 14:33:24', '2022-09-30 14:33:24'),
(59, 500, 19.99, 1, 'Product/9eab05a4d8348c643b75', 8, 'P-1028', '2022-09-30 14:33:25', '2022-09-30 14:33:25'),
(60, 500, 19.99, 1, 'Product/4fc89b36e41a5ee73cf1', 9, 'P-1029', '2022-09-30 14:33:25', '2022-09-30 14:33:25'),
(61, 500, 19.99, 1, 'Product/2c9d864cc4f29db51623', 10, 'P-10210', '2022-09-30 14:33:26', '2022-09-30 14:33:26'),
(62, 500, 19.99, 1, 'Product/32d3930f6d0eb3d1be6b', 11, 'P-10211', '2022-09-30 14:33:26', '2022-09-30 14:33:26'),
(63, 500, 19.99, 1, 'Product/3d50f2b774e8a30d1780', 12, 'P-10212', '2022-09-30 14:33:27', '2022-09-30 14:33:27'),
(64, 500, 19.99, 1, 'Product/c7549352762d765ffae5', 13, 'P-10213', '2022-09-30 14:33:27', '2022-09-30 14:33:27'),
(65, 500, 19.99, 1, 'Product/794304da24224f7544f8', 14, 'P-10214', '2022-09-30 14:33:28', '2022-09-30 14:33:28'),
(66, 500, 19.99, 1, 'Product/b263316f987da60c4982', 15, 'P-10215', '2022-09-30 14:33:29', '2022-09-30 14:33:29'),
(67, 500, 19.99, 1, 'Product/803c37e01b854b3205c5', 16, 'P-10216', '2022-09-30 14:33:29', '2022-09-30 14:33:29'),
(68, 500, 19.99, 1, 'Product/bbd2aa68ab6dd4ad6d30', 17, 'P-10217', '2022-09-30 14:33:30', '2022-09-30 14:33:30'),
(69, 500, 19.99, 1, 'Product/6ba9165de4e5dba1935c', 18, 'P-10218', '2022-09-30 14:33:30', '2022-09-30 14:33:30'),
(70, 500, 19.99, 1, 'Product/ef1ae8ad82c756c96251', 19, 'P-10219', '2022-09-30 14:33:31', '2022-09-30 14:33:31'),
(71, 500, 19.99, 1, 'Product/ec039e103ff1976e1309', 20, 'P-10220', '2022-09-30 14:33:32', '2022-09-30 14:33:32'),
(72, 500, 19.99, 1, 'Product/20b38350de49f33cf387', 21, 'P-10221', '2022-09-30 14:33:32', '2022-09-30 14:33:32'),
(73, 500, 19.99, 1, 'Product/1cf11b389f501fc8c7a2', 22, 'P-10222', '2022-09-30 14:33:33', '2022-09-30 14:33:33'),
(74, 500, 19.99, 1, 'Product/b44f7aa60f565d2b23d4', 23, 'P-10223', '2022-09-30 14:33:33', '2022-09-30 14:33:33'),
(75, 500, 19.99, 1, 'Product/14e33afd7fb416a51306', 24, 'P-10224', '2022-09-30 14:33:34', '2022-09-30 14:33:34'),
(76, 500, 19.99, 1, 'Product/1d518ad40206724ec69c', 25, 'P-10225', '2022-09-30 14:33:35', '2022-09-30 14:33:35'),
(77, 500, 19.99, 1, 'Product/41d65f3b37a770b2d3b8', 26, 'P-10226', '2022-09-30 14:33:35', '2022-09-30 14:33:35'),
(78, 500, 19.99, 1, 'Product/26bacc085d00883f0bd3', 27, 'P-10227', '2022-09-30 14:33:36', '2022-09-30 14:33:36'),
(79, 500, 19.99, 1, 'Product/93c2f1bd175c70c9c65c', 28, 'P-10228', '2022-09-30 14:33:36', '2022-09-30 14:33:36'),
(80, 500, 19.99, 1, 'Product/558c08c1d20024d1da8a', 29, 'P-10229', '2022-09-30 14:33:37', '2022-09-30 14:33:37'),
(81, 500, 19.99, 1, 'Product/d2101671b6cd1db7c8ac', 30, 'P-10230', '2022-09-30 14:33:37', '2022-09-30 14:33:37'),
(82, 500, 19.99, 1, 'Product/b84184190f6cb133d3c2', 31, 'P-10231', '2022-09-30 14:33:38', '2022-09-30 14:33:38'),
(83, 500, 19.99, 1, 'Product/c01e380b381821a1bdc8', 32, 'P-10232', '2022-09-30 14:33:39', '2022-09-30 14:33:39'),
(84, 500, 19.99, 1, 'Product/35002c1c5f4a20c0e924', 33, 'P-10233', '2022-09-30 14:33:39', '2022-09-30 14:33:39'),
(85, 500, 19.99, 1, 'Product/d708af87ec54550ec9fd', 34, 'P-10234', '2022-09-30 14:33:40', '2022-09-30 14:33:40'),
(86, 500, 19.99, 1, 'Product/45d362acbd695c623143', 35, 'P-10235', '2022-09-30 14:33:40', '2022-09-30 14:33:40'),
(87, 500, 19.99, 1, 'Product/c949fd97d8b1f5fed4eb', 36, 'P-10236', '2022-09-30 14:33:41', '2022-09-30 14:33:41'),
(88, 500, 19.99, 1, 'Product/a850b3ac057f2cff937f', 37, 'P-10237', '2022-09-30 14:33:42', '2022-09-30 14:33:42'),
(89, 500, 19.99, 1, 'Product/a10399bf8fc76b08d741', 38, 'P-10238', '2022-09-30 14:33:42', '2022-09-30 14:33:42'),
(90, 500, 19.99, 1, 'Product/9486d91fede4f4a197f7', 39, 'P-10239', '2022-09-30 14:33:43', '2022-09-30 14:33:43'),
(91, 500, 19.99, 1, 'Product/86dcc0f0a2bc069c39d9', 40, 'P-10240', '2022-09-30 14:33:43', '2022-09-30 14:33:43'),
(92, 461, 19.99, 1, 'Product/38dcea068ff010db6608', 41, 'P-10241', '2022-09-30 14:33:44', '2022-10-05 12:59:05'),
(93, 497, 19.99, 1, 'Product/f747f1596efec17620e0', 42, 'P-10242', '2022-09-30 14:33:44', '2022-10-05 12:59:05'),
(94, 498, 19.99, 1, 'Product/b2c07153e78077a77186', 43, 'P-10243', '2022-09-30 14:33:45', '2022-10-05 12:55:51'),
(95, 485, 19.99, 1, 'Product/eaf6c670734cc54ec43a', 44, 'P-10244', '2022-09-30 14:33:46', '2022-10-05 12:59:05'),
(96, 473, 19.99, 1, 'Product/fbb16a082050f34fa691', 45, 'P-10245', '2022-09-30 14:33:46', '2022-10-05 12:59:05'),
(97, 498, 19.99, 1, 'Product/5861a8e3700e54e60d30', 46, 'P-10246', '2022-09-30 14:33:47', '2022-10-05 12:51:13'),
(98, 469, 19.99, 1, 'Product/0e6f8487e72c159523f6', 47, 'P-10247', '2022-09-30 14:33:47', '2022-10-05 12:51:13'),
(99, 488, 19.99, 1, 'Product/16a61a4e1cde213e47ff', 48, 'P-10248', '2022-09-30 14:33:48', '2022-10-05 12:51:13'),
(100, 488, 19.99, 1, 'Product/ede623836b8d623cfb78', 49, 'P-10249', '2022-09-30 14:33:49', '2022-10-05 12:51:13'),
(101, 1, 200, 1, 'Product/b34fa4e7fe733479ebae', 1, 'P-201', '2022-10-06 16:47:24', '2022-10-06 16:47:24'),
(102, 5, 250, 1, 'Product/9afc312b66eb86d1c63f', 2, 'P-201', '2022-10-06 16:51:02', '2022-10-06 16:51:02'),
(103, 5, 100, 1, 'Product/7b4406d293cfa1d6a6c1', 1, 'P-203', '2022-10-06 16:57:28', '2022-10-06 16:57:28');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_category`
--

CREATE TABLE `product_category` (
  `product_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `product_category`
--

INSERT INTO `product_category` (`product_id`, `category_id`) VALUES
(52, 24),
(53, 24),
(54, 24),
(55, 24),
(56, 24),
(57, 24),
(58, 24),
(59, 24),
(60, 24),
(61, 24),
(62, 24),
(63, 24),
(64, 24),
(65, 24),
(66, 24),
(67, 24),
(68, 24),
(69, 24),
(70, 24),
(71, 24),
(72, 24),
(73, 24),
(74, 24),
(75, 24),
(76, 24),
(77, 24),
(78, 24),
(79, 24),
(80, 24),
(81, 24),
(82, 24),
(83, 24),
(84, 24),
(85, 24),
(86, 24),
(87, 24),
(88, 24),
(89, 24),
(90, 24),
(91, 24),
(92, 24),
(93, 24),
(94, 24),
(95, 24),
(96, 24),
(97, 24),
(98, 24),
(99, 24),
(100, 24),
(101, 29),
(102, 29),
(103, 34);

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `product_translation`
--

CREATE TABLE `product_translation` (
  `id` int(11) NOT NULL,
  `language_id` int(11) NOT NULL,
  `product_id` int(11) DEFAULT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `description` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `short_description` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `product_translation`
--

INSERT INTO `product_translation` (`id`, `language_id`, `product_id`, `name`, `description`, `short_description`) VALUES
(103, 1, 52, 'Mein Produkt-1', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-1', 'Meine Produkt Kurzbeschreibung-1'),
(104, 2, 52, 'My Product-1', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-1', 'My Product Short-Description-1'),
(105, 1, 53, 'Mein Produkt-2', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-2', 'Meine Produkt Kurzbeschreibung-2'),
(106, 2, 53, 'My Product-2', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-2', 'My Product Short-Description-2'),
(107, 1, 54, 'Mein Produkt-3', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-3', 'Meine Produkt Kurzbeschreibung-3'),
(108, 2, 54, 'My Product-3', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-3', 'My Product Short-Description-3'),
(109, 1, 55, 'Mein Produkt-4', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-4', 'Meine Produkt Kurzbeschreibung-4'),
(110, 2, 55, 'My Product-4', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-4', 'My Product Short-Description-4'),
(111, 1, 56, 'Mein Produkt-5', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-5', 'Meine Produkt Kurzbeschreibung-5'),
(112, 2, 56, 'My Product-5', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-5', 'My Product Short-Description-5'),
(113, 1, 57, 'Mein Produkt-6', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-6', 'Meine Produkt Kurzbeschreibung-6'),
(114, 2, 57, 'My Product-6', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-6', 'My Product Short-Description-6'),
(115, 1, 58, 'Mein Produkt-7', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-7', 'Meine Produkt Kurzbeschreibung-7'),
(116, 2, 58, 'My Product-7', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-7', 'My Product Short-Description-7'),
(117, 1, 59, 'Mein Produkt-8', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-8', 'Meine Produkt Kurzbeschreibung-8'),
(118, 2, 59, 'My Product-8', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-8', 'My Product Short-Description-8'),
(119, 1, 60, 'Mein Produkt-9', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-9', 'Meine Produkt Kurzbeschreibung-9'),
(120, 2, 60, 'My Product-9', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-9', 'My Product Short-Description-9'),
(121, 1, 61, 'Mein Produkt-10', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-10', 'Meine Produkt Kurzbeschreibung-10'),
(122, 2, 61, 'My Product-10', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-10', 'My Product Short-Description-10'),
(123, 1, 62, 'Mein Produkt-11', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-11', 'Meine Produkt Kurzbeschreibung-11'),
(124, 2, 62, 'My Product-11', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-11', 'My Product Short-Description-11'),
(125, 1, 63, 'Mein Produkt-12', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-12', 'Meine Produkt Kurzbeschreibung-12'),
(126, 2, 63, 'My Product-12', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-12', 'My Product Short-Description-12'),
(127, 1, 64, 'Mein Produkt-13', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-13', 'Meine Produkt Kurzbeschreibung-13'),
(128, 2, 64, 'My Product-13', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-13', 'My Product Short-Description-13'),
(129, 1, 65, 'Mein Produkt-14', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-14', 'Meine Produkt Kurzbeschreibung-14'),
(130, 2, 65, 'My Product-14', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-14', 'My Product Short-Description-14'),
(131, 1, 66, 'Mein Produkt-15', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-15', 'Meine Produkt Kurzbeschreibung-15'),
(132, 2, 66, 'My Product-15', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-15', 'My Product Short-Description-15'),
(133, 1, 67, 'Mein Produkt-16', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-16', 'Meine Produkt Kurzbeschreibung-16'),
(134, 2, 67, 'My Product-16', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-16', 'My Product Short-Description-16'),
(135, 1, 68, 'Mein Produkt-17', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-17', 'Meine Produkt Kurzbeschreibung-17'),
(136, 2, 68, 'My Product-17', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-17', 'My Product Short-Description-17'),
(137, 1, 69, 'Mein Produkt-18', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-18', 'Meine Produkt Kurzbeschreibung-18'),
(138, 2, 69, 'My Product-18', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-18', 'My Product Short-Description-18'),
(139, 1, 70, 'Mein Produkt-19', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-19', 'Meine Produkt Kurzbeschreibung-19'),
(140, 2, 70, 'My Product-19', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-19', 'My Product Short-Description-19'),
(141, 1, 71, 'Mein Produkt-20', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-20', 'Meine Produkt Kurzbeschreibung-20'),
(142, 2, 71, 'My Product-20', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-20', 'My Product Short-Description-20'),
(143, 1, 72, 'Mein Produkt-21', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-21', 'Meine Produkt Kurzbeschreibung-21'),
(144, 2, 72, 'My Product-21', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-21', 'My Product Short-Description-21'),
(145, 1, 73, 'Mein Produkt-22', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-22', 'Meine Produkt Kurzbeschreibung-22'),
(146, 2, 73, 'My Product-22', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-22', 'My Product Short-Description-22'),
(147, 1, 74, 'Mein Produkt-23', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-23', 'Meine Produkt Kurzbeschreibung-23'),
(148, 2, 74, 'My Product-23', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-23', 'My Product Short-Description-23'),
(149, 1, 75, 'Mein Produkt-24', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-24', 'Meine Produkt Kurzbeschreibung-24'),
(150, 2, 75, 'My Product-24', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-24', 'My Product Short-Description-24'),
(151, 1, 76, 'Mein Produkt-25', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-25', 'Meine Produkt Kurzbeschreibung-25'),
(152, 2, 76, 'My Product-25', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-25', 'My Product Short-Description-25'),
(153, 1, 77, 'Mein Produkt-26', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-26', 'Meine Produkt Kurzbeschreibung-26'),
(154, 2, 77, 'My Product-26', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-26', 'My Product Short-Description-26'),
(155, 1, 78, 'Mein Produkt-27', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-27', 'Meine Produkt Kurzbeschreibung-27'),
(156, 2, 78, 'My Product-27', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-27', 'My Product Short-Description-27'),
(157, 1, 79, 'Mein Produkt-28', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-28', 'Meine Produkt Kurzbeschreibung-28'),
(158, 2, 79, 'My Product-28', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-28', 'My Product Short-Description-28'),
(159, 1, 80, 'Mein Produkt-29', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-29', 'Meine Produkt Kurzbeschreibung-29'),
(160, 2, 80, 'My Product-29', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-29', 'My Product Short-Description-29'),
(161, 1, 81, 'Mein Produkt-30', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-30', 'Meine Produkt Kurzbeschreibung-30'),
(162, 2, 81, 'My Product-30', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-30', 'My Product Short-Description-30'),
(163, 1, 82, 'Mein Produkt-31', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-31', 'Meine Produkt Kurzbeschreibung-31'),
(164, 2, 82, 'My Product-31', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-31', 'My Product Short-Description-31'),
(165, 1, 83, 'Mein Produkt-32', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-32', 'Meine Produkt Kurzbeschreibung-32'),
(166, 2, 83, 'My Product-32', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-32', 'My Product Short-Description-32'),
(167, 1, 84, 'Mein Produkt-33', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-33', 'Meine Produkt Kurzbeschreibung-33'),
(168, 2, 84, 'My Product-33', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-33', 'My Product Short-Description-33'),
(169, 1, 85, 'Mein Produkt-34', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-34', 'Meine Produkt Kurzbeschreibung-34'),
(170, 2, 85, 'My Product-34', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-34', 'My Product Short-Description-34'),
(171, 1, 86, 'Mein Produkt-35', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-35', 'Meine Produkt Kurzbeschreibung-35'),
(172, 2, 86, 'My Product-35', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-35', 'My Product Short-Description-35'),
(173, 1, 87, 'Mein Produkt-36', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-36', 'Meine Produkt Kurzbeschreibung-36'),
(174, 2, 87, 'My Product-36', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-36', 'My Product Short-Description-36'),
(175, 1, 88, 'Mein Produkt-37', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-37', 'Meine Produkt Kurzbeschreibung-37'),
(176, 2, 88, 'My Product-37', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-37', 'My Product Short-Description-37'),
(177, 1, 89, 'Mein Produkt-38', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-38', 'Meine Produkt Kurzbeschreibung-38'),
(178, 2, 89, 'My Product-38', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-38', 'My Product Short-Description-38'),
(179, 1, 90, 'Mein Produkt-39', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-39', 'Meine Produkt Kurzbeschreibung-39'),
(180, 2, 90, 'My Product-39', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-39', 'My Product Short-Description-39'),
(181, 1, 91, 'Mein Produkt-40', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-40', 'Meine Produkt Kurzbeschreibung-40'),
(182, 2, 91, 'My Product-40', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-40', 'My Product Short-Description-40'),
(183, 1, 92, 'Mein Produkt-41', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-41', 'Meine Produkt Kurzbeschreibung-41'),
(184, 2, 92, 'My Product-41', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-41', 'My Product Short-Description-41'),
(185, 1, 93, 'Mein Produkt-42', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-42', 'Meine Produkt Kurzbeschreibung-42'),
(186, 2, 93, 'My Product-42', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-42', 'My Product Short-Description-42'),
(187, 1, 94, 'Mein Produkt-43', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-43', 'Meine Produkt Kurzbeschreibung-43'),
(188, 2, 94, 'My Product-43', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-43', 'My Product Short-Description-43'),
(189, 1, 95, 'Mein Produkt-44', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-44', 'Meine Produkt Kurzbeschreibung-44'),
(190, 2, 95, 'My Product-44', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-44', 'My Product Short-Description-44'),
(191, 1, 96, 'Mein Produkt-45', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-45', 'Meine Produkt Kurzbeschreibung-45'),
(192, 2, 96, 'My Product-45', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-45', 'My Product Short-Description-45'),
(193, 1, 97, 'Mein Produkt-46', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-46', 'Meine Produkt Kurzbeschreibung-46'),
(194, 2, 97, 'My Product-46', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-46', 'My Product Short-Description-46'),
(195, 1, 98, 'Mein Produkt-47', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-47', 'Meine Produkt Kurzbeschreibung-47'),
(196, 2, 98, 'My Product-47', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-47', 'My Product Short-Description-47'),
(197, 1, 99, 'Mein Produkt-48', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-48', 'Meine Produkt Kurzbeschreibung-48'),
(198, 2, 99, 'My Product-48', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-48', 'My Product Short-Description-48'),
(199, 1, 100, 'Mein Produkt-49', '<h2>Mein Produkt <span style=\"background-color:hsl(120, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Beschreibung</h2>-49', 'Meine Produkt Kurzbeschreibung-49'),
(200, 2, 100, 'My Product-49', '<h2>My Product Long-Description <span style=\"color:hsl(0, 75%, 60%);\"><mark class=\"marker-yellow\">HTML</mark></span> Description</h2>-49', 'My Product Short-Description-49'),
(201, 1, 101, 'IPhone', '<p><strong>Beschreibung long irgendwas mit </strong><span style=\"background-color:hsl(0, 75%, 60%);\"><s><strong>HTML&nbsp;</strong></s></span></p>', 'Apfel'),
(202, 2, 101, 'IPhone', '<h2>Description</h2><p>Somthing with HTML&nbsp;</p>', 'IPhone nor YourPhone'),
(203, 1, 102, 'IPhone 2', '<p>IPhone2 Beschreibung&nbsp;</p>', 'IPhone 2 Handy'),
(204, 2, 102, 'IPhone2', '<p>IPhone 2 Long Description</p>', 'IPhone 2 Mobile'),
(205, 1, 103, 'Android', '<p>Besser als Apfel<br><img src=\"http://localhost:82/img/uploads/Product/1af1f02451b7c18057e5/1-org.jpg\" srcset=\"http://localhost:82/img/uploads/Product/1af1f02451b7c18057e5/1-400.jpg 400w\" sizes=\"100vw\" width=\"400\"></p>', 'Besser als Apple'),
(206, 2, 103, 'Android', '<p>Besser then Appel<br><img src=\"http://localhost:82/img/uploads/Product/1af1f02451b7c18057e5/1-org.jpg\" srcset=\"http://localhost:82/img/uploads/Product/1af1f02451b7c18057e5/1-400.jpg 400w\" sizes=\"100vw\" width=\"400\"></p>', 'Just the better one');

-- --------------------------------------------------------

--
-- Tabellenstruktur für Tabelle `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `language_id` int(11) DEFAULT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `is_admin` tinyint(1) NOT NULL,
  `firstname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `lastname` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `street` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `city` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `country` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `postcode` int(11) NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `access_token` varchar(255) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `activation_token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `updated_at` datetime NOT NULL COMMENT '(DC2Type:datetime_immutable)',
  `active` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Daten für Tabelle `user`
--

INSERT INTO `user` (`id`, `language_id`, `email`, `password`, `is_admin`, `firstname`, `lastname`, `street`, `city`, `country`, `postcode`, `gender`, `access_token`, `activation_token`, `created_at`, `updated_at`, `active`) VALUES
(1, 1, 'chris.w.1999@web.de', '$2y$08$NeoZqS..frMEJ39GGJZpFudxkv3sM.hbqQItjI4NlHscWA8RBLAIe', 1, 'Chris', 'Wagner', 'Mahlower Str 26', 'Berlin', 'Deutschland', 12557, 'd', 'f591336821d0c0607c01b266a912fcd8', 'b23810a2', '2022-09-21 14:18:13', '2022-10-06 11:06:54', 1),
(2, 2, 'user@user.user', '$2y$08$y75XNXyka1fEY70Rp2eHHeKwTzBN3EqzzaKatcb.aBW0OT5XIDxHK', 0, 'user', 'user', 'userStr', 'Berlin', 'Germany', 12222, 'm', 'd45e95ff7b6e008a33f43fe4c2af4a1d', '349957b9', '2022-10-06 16:27:52', '2022-10-06 16:35:20', 1),
(3, 2, 'admin@admin.admin', '$2y$08$.z3SMshswTP/LVk4oizHQuY4mueWSV4OJWDCvmPTU95mmrN5J06MS', 1, 'Admin', 'Admin', 'AdminStr', 'Berlin', 'Germany', 12333, 'd', 'a3bc42e6fc9d7b2a79f8', '60a94b96', '2022-10-06 16:35:49', '2022-10-06 16:36:22', 1);

--
-- Indizes der exportierten Tabellen
--

--
-- Indizes für die Tabelle `cart_items`
--
ALTER TABLE `cart_items`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_BEF484454584665A` (`product_id`),
  ADD KEY `IDX_BEF48445A76ED395` (`user_id`);

--
-- Indizes für die Tabelle `category`
--
ALTER TABLE `category`
  ADD PRIMARY KEY (`id`),
  ADD KEY `FK_64C19C1727ACA70` (`parent_id`);

--
-- Indizes für die Tabelle `category_translation`
--
ALTER TABLE `category_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_3F2070412469DE2` (`category_id`),
  ADD KEY `IDX_3F2070482F1BAF4` (`language_id`);

--
-- Indizes für die Tabelle `doctrine_migration_versions`
--
ALTER TABLE `doctrine_migration_versions`
  ADD PRIMARY KEY (`version`);

--
-- Indizes für die Tabelle `language`
--
ALTER TABLE `language`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `messenger_messages`
--
ALTER TABLE `messenger_messages`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_75EA56E0FB7336F0` (`queue_name`),
  ADD KEY `IDX_75EA56E0E3BD61CE` (`available_at`),
  ADD KEY `IDX_75EA56E016BA31DB` (`delivered_at`);

--
-- Indizes für die Tabelle `order`
--
ALTER TABLE `order`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_F5299398A76ED395` (`user_id`);

--
-- Indizes für die Tabelle `product`
--
ALTER TABLE `product`
  ADD PRIMARY KEY (`id`);

--
-- Indizes für die Tabelle `product_category`
--
ALTER TABLE `product_category`
  ADD PRIMARY KEY (`product_id`,`category_id`),
  ADD KEY `IDX_CDFC73564584665A` (`product_id`),
  ADD KEY `IDX_CDFC735612469DE2` (`category_id`);

--
-- Indizes für die Tabelle `product_translation`
--
ALTER TABLE `product_translation`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_1846DB704584665A` (`product_id`),
  ADD KEY `IDX_1846DB7082F1BAF4` (`language_id`);

--
-- Indizes für die Tabelle `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD KEY `IDX_8D93D64982F1BAF4` (`language_id`);

--
-- AUTO_INCREMENT für exportierte Tabellen
--

--
-- AUTO_INCREMENT für Tabelle `cart_items`
--
ALTER TABLE `cart_items`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT für Tabelle `category`
--
ALTER TABLE `category`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- AUTO_INCREMENT für Tabelle `category_translation`
--
ALTER TABLE `category_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT für Tabelle `language`
--
ALTER TABLE `language`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT für Tabelle `messenger_messages`
--
ALTER TABLE `messenger_messages`
  MODIFY `id` bigint(20) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT für Tabelle `order`
--
ALTER TABLE `order`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=14;

--
-- AUTO_INCREMENT für Tabelle `product`
--
ALTER TABLE `product`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=104;

--
-- AUTO_INCREMENT für Tabelle `product_translation`
--
ALTER TABLE `product_translation`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=207;

--
-- AUTO_INCREMENT für Tabelle `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- Constraints der exportierten Tabellen
--

--
-- Constraints der Tabelle `cart_items`
--
ALTER TABLE `cart_items`
  ADD CONSTRAINT `FK_BEF484454584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_BEF48445A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `category`
--
ALTER TABLE `category`
  ADD CONSTRAINT `FK_64C19C1727ACA70` FOREIGN KEY (`parent_id`) REFERENCES `category` (`id`);

--
-- Constraints der Tabelle `category_translation`
--
ALTER TABLE `category_translation`
  ADD CONSTRAINT `FK_3F2070412469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`),
  ADD CONSTRAINT `FK_3F2070482F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints der Tabelle `order`
--
ALTER TABLE `order`
  ADD CONSTRAINT `FK_F5299398A76ED395` FOREIGN KEY (`user_id`) REFERENCES `user` (`id`);

--
-- Constraints der Tabelle `product_category`
--
ALTER TABLE `product_category`
  ADD CONSTRAINT `FK_CDFC735612469DE2` FOREIGN KEY (`category_id`) REFERENCES `category` (`id`) ON DELETE CASCADE,
  ADD CONSTRAINT `FK_CDFC73564584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`) ON DELETE CASCADE;

--
-- Constraints der Tabelle `product_translation`
--
ALTER TABLE `product_translation`
  ADD CONSTRAINT `FK_1846DB704584665A` FOREIGN KEY (`product_id`) REFERENCES `product` (`id`),
  ADD CONSTRAINT `FK_1846DB7082F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);

--
-- Constraints der Tabelle `user`
--
ALTER TABLE `user`
  ADD CONSTRAINT `FK_8D93D64982F1BAF4` FOREIGN KEY (`language_id`) REFERENCES `language` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
