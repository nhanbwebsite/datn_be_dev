-- phpMyAdmin SQL Dump
-- version 5.1.1
-- https://www.phpmyadmin.net/
--
-- Máy chủ: 127.0.0.1
-- Thời gian đã tạo: Th10 20, 2022 lúc 06:10 PM
-- Phiên bản máy phục vụ: 10.4.22-MariaDB
-- Phiên bản PHP: 8.0.13

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Cơ sở dữ liệu: `education`
--

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `admin`
--

CREATE TABLE `admin` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_id` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `phone` varchar(10) COLLATE utf8_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `admin`
--

INSERT INTO `admin` (`id`, `id_user`, `name`, `name_id`, `phone`, `address`, `role`) VALUES
(1, 5, 'Nguyễn Nhật Trung', 'AD01001', '031321312', 'Cần Thơ', 0);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `case_score`
--

CREATE TABLE `case_score` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_subject` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8_unicode_ci NOT NULL,
  `name_field` varchar(255) COLLATE utf8_unicode_ci NOT NULL DEFAULT 'nameFiled',
  `percent` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8 COLLATE=utf8_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `case_score`
--

INSERT INTO `case_score` (`id`, `id_subject`, `title`, `name_field`, `percent`) VALUES
(3, 1, 'Đánh giá Assignment GĐ 2', 'nameFiled', 40),
(4, 1, 'Đánh giá Assignment GĐ 1', 'nameFiled', 5);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `class`
--

CREATE TABLE `class` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_course` bigint(20) UNSIGNED NOT NULL,
  `id_major` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `class`
--

INSERT INTO `class` (`id`, `id_course`, `id_major`, `name_id`, `created_at`, `updated_at`) VALUES
(5, 1, 1, 'WE16301', '2022-10-12 09:11:23', '2022-10-12 09:11:23'),
(6, 1, 1, 'WE16302', '2022-10-12 09:11:32', '2022-10-12 09:11:32'),
(7, 1, 2, 'UD16302', '2022-10-12 09:11:51', '2022-10-12 09:11:51');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `contact`
--

CREATE TABLE `contact` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `course`
--

CREATE TABLE `course` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `course`
--

INSERT INTO `course` (`id`, `name_id`, `created_at`, `updated_at`) VALUES
(1, '16.1', '2022-10-12 02:36:50', '2022-10-12 02:36:50'),
(2, '16.2', '2022-10-12 02:37:28', '2022-10-12 02:37:28');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `detail_scores`
--

CREATE TABLE `detail_scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_score` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `score` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `note` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `percent` int(11) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `detail_scores`
--

INSERT INTO `detail_scores` (`id`, `id_score`, `title`, `score`, `note`, `percent`, `created_at`, `updated_at`) VALUES
(4, 8, 'Đánh giá Assignment GĐ 1', '9', NULL, 10, '2022-10-15 11:52:13', '2022-10-15 11:52:13');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `failed_jobs`
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
-- Cấu trúc bảng cho bảng `field`
--

CREATE TABLE `field` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `field_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `field`
--

INSERT INTO `field` (`id`, `field_type`, `created_at`, `updated_at`) VALUES
(1, 'Công Nghệ Thông Tin', '2022-10-12 09:02:30', '2022-10-12 09:02:30'),
(2, 'Kinh Tế', '2022-10-12 09:02:43', '2022-10-12 09:02:43'),
(3, 'Đồ Họa', '2022-10-12 09:02:53', '2022-10-12 09:02:53'),
(4, 'Du Lịch', '2022-10-12 09:03:09', '2022-10-12 09:04:19');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `lecturers`
--

CREATE TABLE `lecturers` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `address` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `lecturers`
--

INSERT INTO `lecturers` (`id`, `id_user`, `name_id`, `name`, `phone`, `address`, `gender`, `created_at`, `updated_at`) VALUES
(2, 4, 'TC01012', 'Nguyễn Trung Hoàng', '01312312312', 'Cần Thơ', 'Male', '2022-10-19 11:00:35', '2022-10-19 11:00:35'),
(3, 3, 'TC01001', 'Trần Văn Thện', '312312431212', 'Cần Thơ', 'Male', '2022-10-19 11:02:13', '2022-10-19 11:19:32');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `majors`
--

CREATE TABLE `majors` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_field` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name_major` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `majors`
--

INSERT INTO `majors` (`id`, `id_field`, `name_id`, `name_major`, `created_at`, `updated_at`) VALUES
(1, 1, 'WE', 'Thiết Kế Website', '2022-10-12 09:09:10', '2022-10-12 09:09:10'),
(2, 1, 'UD', 'Ứng Dụng Phần Mềm', '2022-10-12 09:09:37', '2022-10-12 09:09:37');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `migrations`
--

INSERT INTO `migrations` (`id`, `migration`, `batch`) VALUES
(1, '2014_10_12_000000_create_users_table', 1),
(2, '2014_10_12_100000_create_password_resets_table', 1),
(3, '2019_08_19_000000_create_failed_jobs_table', 1),
(4, '2019_12_14_000001_create_personal_access_tokens_table', 1),
(5, '2022_10_11_123045_create_course_table', 2),
(7, '2022_10_11_123318_create_semester_table', 3),
(8, '2022_10_11_123708_create_majors_table', 4),
(9, '2022_10_11_123817_create_subject_type_table', 4),
(10, '2022_10_11_123954_create_class_table', 5),
(11, '2022_10_11_124322_create_subject_table', 6),
(12, '2022_10_11_125304_create_students_table', 7),
(16, '2022_10_11_125622_create_scores_table', 8),
(17, '2022_10_11_130301_create_detail_scores_table', 9),
(18, '2022_10_11_130720_create_contact_table', 10),
(19, '2022_10_11_130950_create_lecturers_table', 10),
(21, '2022_10_11_132155_create_detail_scores_table', 11),
(22, '2022_10_11_132321_create_notification_categories_table', 12),
(23, '2022_10_11_132420_create_notify_table', 13),
(24, '2022_10_11_132619_create_lecturers_table', 14),
(25, '2022_10_12_150222_create_field_table', 15);

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notification_categories`
--

CREATE TABLE `notification_categories` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `name_cate` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `notify`
--

CREATE TABLE `notify` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_cate` bigint(20) UNSIGNED NOT NULL,
  `title` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `date` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `content` text COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `password_resets`
--

CREATE TABLE `password_resets` (
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `personal_access_tokens`
--

CREATE TABLE `personal_access_tokens` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `tokenable_type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `tokenable_id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `token` varchar(64) COLLATE utf8mb4_unicode_ci NOT NULL,
  `abilities` text COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `last_used_at` timestamp NULL DEFAULT NULL,
  `expires_at` timestamp NULL DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `personal_access_tokens`
--

INSERT INTO `personal_access_tokens` (`id`, `tokenable_type`, `tokenable_id`, `name`, `token`, `abilities`, `last_used_at`, `expires_at`, `created_at`, `updated_at`) VALUES
(12, 'App\\Models\\User', 4, 'authToken', 'e7bc3d5ea0c96c4e758af53d77e26790ef5b18af3e64be558d6981dee0cb0cc7', '[\"*\"]', '2022-10-19 20:34:04', NULL, '2022-10-19 10:50:31', '2022-10-19 20:34:04'),
(14, 'App\\Models\\User', 5, 'authToken', '6eebfea89f155d935c3d8430d4eb96f8a24b8dc533b89ef9606814dbf465de19', '[\"*\"]', '2022-10-19 20:32:25', NULL, '2022-10-19 20:17:25', '2022-10-19 20:32:25'),
(15, 'App\\Models\\User', 5, 'authToken', '0fedaf0620998c4271c3b67516f5d32f9cfb7f2e93c4fd360b3819b4eeac53e7', '[\"*\"]', '2022-10-19 20:39:41', NULL, '2022-10-19 20:34:28', '2022-10-19 20:39:41');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `scores`
--

CREATE TABLE `scores` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_subject` bigint(20) UNSIGNED NOT NULL,
  `id_student` bigint(20) UNSIGNED NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `scores`
--

INSERT INTO `scores` (`id`, `id_subject`, `id_student`, `created_at`, `updated_at`) VALUES
(8, 1, 1, '2022-10-14 20:59:36', '2022-10-14 20:59:36');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `semester`
--

CREATE TABLE `semester` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `semester`
--

INSERT INTO `semester` (`id`, `name_id`, `created_at`, `updated_at`) VALUES
(1, 'FALL2021', '2022-10-12 02:45:55', '2022-10-12 02:45:55');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `students`
--

CREATE TABLE `students` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_user` bigint(20) UNSIGNED NOT NULL,
  `id_course` bigint(20) UNSIGNED NOT NULL,
  `id_class` bigint(20) UNSIGNED NOT NULL,
  `id_major` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `phone` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `gender` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `students`
--

INSERT INTO `students` (`id`, `id_user`, `id_course`, `id_class`, `id_major`, `name_id`, `name`, `phone`, `gender`, `created_at`, `updated_at`) VALUES
(1, 1, 1, 7, 2, 'PC02042', 'Trần Hoàng Khôi', '0974821032', 'Male', '2022-10-13 08:38:33', '2022-10-13 08:38:33'),
(2, 2, 2, 5, 1, 'PC02012', 'Phạm Hoàng Giang', '213123123123123', 'Female', '2022-10-19 10:24:59', '2022-10-19 10:38:17');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subject`
--

CREATE TABLE `subject` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `id_semester` bigint(20) UNSIGNED NOT NULL,
  `id_class` bigint(20) UNSIGNED NOT NULL,
  `id_major` bigint(20) UNSIGNED NOT NULL,
  `subject_type` bigint(20) UNSIGNED NOT NULL,
  `name_id` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `name` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `credit` int(11) NOT NULL COMMENT 'so Tin chi',
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `subject`
--

INSERT INTO `subject` (`id`, `id_semester`, `id_class`, `id_major`, `subject_type`, `name_id`, `name`, `credit`, `created_at`, `updated_at`) VALUES
(1, 1, 7, 2, 1, 'WEB1022', 'Xây dựng trang web', 1, '2022-10-13 20:02:44', '2022-10-13 20:02:44');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `subject_type`
--

CREATE TABLE `subject_type` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `type` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `subject_type`
--

INSERT INTO `subject_type` (`id`, `type`, `created_at`, `updated_at`) VALUES
(1, 'Môn Bổ Trợ', '2022-10-12 05:00:05', '2022-10-12 05:01:02'),
(2, 'Môn chuyên ngành', '2022-10-12 05:00:33', '2022-10-12 05:00:33');

-- --------------------------------------------------------

--
-- Cấu trúc bảng cho bảng `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `email` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) COLLATE utf8mb4_unicode_ci NOT NULL,
  `role` tinyint(4) NOT NULL DEFAULT 0,
  `remember_token` varchar(100) COLLATE utf8mb4_unicode_ci DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Đang đổ dữ liệu cho bảng `users`
--

INSERT INTO `users` (`id`, `email`, `email_verified_at`, `password`, `role`, `remember_token`, `created_at`, `updated_at`) VALUES
(1, 'khoithpc02042@fpt.edu.vn', NULL, '$2y$10$RRv4j6De18.0Fm9f8ne6OOuAglolHUCpb/lEuxw4xvKEOzMwryyR.', 0, NULL, '2022-10-17 10:22:46', '2022-10-17 10:22:46'),
(2, 'giangphpc02011@fpt.edu.vn', NULL, '$2y$10$S1IvM6r0U7m7m8YWOnLANOwIcpPNOkFCJ4C1CdW7RgfgT4poIuyHW', 0, NULL, '2022-10-19 10:19:00', '2022-10-19 10:19:00'),
(3, 'thentvtc0101@fpt.edu.vn', NULL, '$2y$10$CbhFKTAB4JvrZ8PVgn3Xfeq7UlEPCJt9i7/Yq7wjOWnrD.8epd9wy', 1, NULL, '2022-10-19 10:42:41', '2022-10-19 10:42:41'),
(4, 'hoangnt@fpt.edu.vn', NULL, '$2y$10$WwYbCZNrsn2Em6YR6i22QOgox.mbOpEA8hinrLS6T6n2l.kdNdE.a', 1, NULL, '2022-10-19 10:49:54', '2022-10-19 10:49:54'),
(5, 'trungnnad0011@fpt.edu.vn', NULL, '$2y$10$sgGrOeu3sRnn4V8cGlgctO.PFI5e8Sy0zndGS.JcML51tPPGNEbgC', 2, NULL, '2022-10-19 20:16:03', '2022-10-19 20:16:03');

--
-- Chỉ mục cho các bảng đã đổ
--

--
-- Chỉ mục cho bảng `admin`
--
ALTER TABLE `admin`
  ADD PRIMARY KEY (`id`),
  ADD KEY `admin_ibfk_1` (`id_user`);

--
-- Chỉ mục cho bảng `case_score`
--
ALTER TABLE `case_score`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_subject` (`id_subject`);

--
-- Chỉ mục cho bảng `class`
--
ALTER TABLE `class`
  ADD PRIMARY KEY (`id`),
  ADD KEY `class_id_course_foreign` (`id_course`),
  ADD KEY `class_id_major_foreign` (`id_major`);

--
-- Chỉ mục cho bảng `contact`
--
ALTER TABLE `contact`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `course`
--
ALTER TABLE `course`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `detail_scores`
--
ALTER TABLE `detail_scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `detail_scores_id_score_foreign` (`id_score`);

--
-- Chỉ mục cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `failed_jobs_uuid_unique` (`uuid`);

--
-- Chỉ mục cho bảng `field`
--
ALTER TABLE `field`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  ADD PRIMARY KEY (`id`),
  ADD KEY `lecturers_ibfk_1` (`id_user`);

--
-- Chỉ mục cho bảng `majors`
--
ALTER TABLE `majors`
  ADD PRIMARY KEY (`id`),
  ADD KEY `majors_id_field_foreign` (`id_field`);

--
-- Chỉ mục cho bảng `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notification_categories`
--
ALTER TABLE `notification_categories`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `notify`
--
ALTER TABLE `notify`
  ADD PRIMARY KEY (`id`),
  ADD KEY `notify_id_cate_foreign` (`id_cate`);

--
-- Chỉ mục cho bảng `password_resets`
--
ALTER TABLE `password_resets`
  ADD KEY `password_resets_email_index` (`email`);

--
-- Chỉ mục cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `personal_access_tokens_token_unique` (`token`),
  ADD KEY `personal_access_tokens_tokenable_type_tokenable_id_index` (`tokenable_type`,`tokenable_id`);

--
-- Chỉ mục cho bảng `scores`
--
ALTER TABLE `scores`
  ADD PRIMARY KEY (`id`),
  ADD KEY `scores_id_subject_foreign` (`id_subject`),
  ADD KEY `scores_id_student_foreign` (`id_student`);

--
-- Chỉ mục cho bảng `semester`
--
ALTER TABLE `semester`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `students`
--
ALTER TABLE `students`
  ADD PRIMARY KEY (`id`),
  ADD KEY `students_id_course_foreign` (`id_course`),
  ADD KEY `students_id_class_foreign` (`id_class`),
  ADD KEY `students_id_major_foreign` (`id_major`),
  ADD KEY `id_user` (`id_user`);

--
-- Chỉ mục cho bảng `subject`
--
ALTER TABLE `subject`
  ADD PRIMARY KEY (`id`),
  ADD KEY `subject_id_semester_foreign` (`id_semester`),
  ADD KEY `subject_id_class_foreign` (`id_class`),
  ADD KEY `subject_id_major_foreign` (`id_major`),
  ADD KEY `subject_subject_type_foreign` (`subject_type`);

--
-- Chỉ mục cho bảng `subject_type`
--
ALTER TABLE `subject_type`
  ADD PRIMARY KEY (`id`);

--
-- Chỉ mục cho bảng `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`);

--
-- AUTO_INCREMENT cho các bảng đã đổ
--

--
-- AUTO_INCREMENT cho bảng `admin`
--
ALTER TABLE `admin`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `case_score`
--
ALTER TABLE `case_score`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `class`
--
ALTER TABLE `class`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=8;

--
-- AUTO_INCREMENT cho bảng `contact`
--
ALTER TABLE `contact`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `course`
--
ALTER TABLE `course`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `detail_scores`
--
ALTER TABLE `detail_scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `failed_jobs`
--
ALTER TABLE `failed_jobs`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `field`
--
ALTER TABLE `field`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=5;

--
-- AUTO_INCREMENT cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `majors`
--
ALTER TABLE `majors`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=4;

--
-- AUTO_INCREMENT cho bảng `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT cho bảng `notification_categories`
--
ALTER TABLE `notification_categories`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `notify`
--
ALTER TABLE `notify`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT cho bảng `personal_access_tokens`
--
ALTER TABLE `personal_access_tokens`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

--
-- AUTO_INCREMENT cho bảng `scores`
--
ALTER TABLE `scores`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- AUTO_INCREMENT cho bảng `semester`
--
ALTER TABLE `semester`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `students`
--
ALTER TABLE `students`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `subject`
--
ALTER TABLE `subject`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT cho bảng `subject_type`
--
ALTER TABLE `subject_type`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT cho bảng `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Các ràng buộc cho các bảng đã đổ
--

--
-- Các ràng buộc cho bảng `admin`
--
ALTER TABLE `admin`
  ADD CONSTRAINT `admin_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `case_score`
--
ALTER TABLE `case_score`
  ADD CONSTRAINT `case_score_ibfk_1` FOREIGN KEY (`id_subject`) REFERENCES `subject` (`id`);

--
-- Các ràng buộc cho bảng `class`
--
ALTER TABLE `class`
  ADD CONSTRAINT `class_id_course_foreign` FOREIGN KEY (`id_course`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `class_id_major_foreign` FOREIGN KEY (`id_major`) REFERENCES `majors` (`id`);

--
-- Các ràng buộc cho bảng `detail_scores`
--
ALTER TABLE `detail_scores`
  ADD CONSTRAINT `detail_scores_id_score_foreign` FOREIGN KEY (`id_score`) REFERENCES `scores` (`id`);

--
-- Các ràng buộc cho bảng `lecturers`
--
ALTER TABLE `lecturers`
  ADD CONSTRAINT `lecturers_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Các ràng buộc cho bảng `majors`
--
ALTER TABLE `majors`
  ADD CONSTRAINT `majors_id_field_foreign` FOREIGN KEY (`id_field`) REFERENCES `field` (`id`);

--
-- Các ràng buộc cho bảng `notify`
--
ALTER TABLE `notify`
  ADD CONSTRAINT `notify_id_cate_foreign` FOREIGN KEY (`id_cate`) REFERENCES `notification_categories` (`id`);

--
-- Các ràng buộc cho bảng `scores`
--
ALTER TABLE `scores`
  ADD CONSTRAINT `scores_id_student_foreign` FOREIGN KEY (`id_student`) REFERENCES `students` (`id`),
  ADD CONSTRAINT `scores_id_subject_foreign` FOREIGN KEY (`id_subject`) REFERENCES `subject` (`id`);

--
-- Các ràng buộc cho bảng `students`
--
ALTER TABLE `students`
  ADD CONSTRAINT `students_ibfk_1` FOREIGN KEY (`id_user`) REFERENCES `users` (`id`),
  ADD CONSTRAINT `students_id_class_foreign` FOREIGN KEY (`id_class`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `students_id_course_foreign` FOREIGN KEY (`id_course`) REFERENCES `course` (`id`),
  ADD CONSTRAINT `students_id_major_foreign` FOREIGN KEY (`id_major`) REFERENCES `majors` (`id`);

--
-- Các ràng buộc cho bảng `subject`
--
ALTER TABLE `subject`
  ADD CONSTRAINT `subject_id_class_foreign` FOREIGN KEY (`id_class`) REFERENCES `class` (`id`),
  ADD CONSTRAINT `subject_id_major_foreign` FOREIGN KEY (`id_major`) REFERENCES `majors` (`id`),
  ADD CONSTRAINT `subject_id_semester_foreign` FOREIGN KEY (`id_semester`) REFERENCES `semester` (`id`),
  ADD CONSTRAINT `subject_subject_type_foreign` FOREIGN KEY (`subject_type`) REFERENCES `subject_type` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
