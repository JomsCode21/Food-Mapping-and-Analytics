-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Sep 16, 2025 at 03:14 PM
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
-- Database: `tastelibmanan`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int(11) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `recovery_code` varchar(255) NOT NULL,
  `recovery_code_expiry` timestamp NULL DEFAULT NULL,
  `user_favorites` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `name`, `email`, `phone_number`, `password`, `user_type`, `date_created`, `recovery_code`, `recovery_code_expiry`, `user_favorites`) VALUES
(27, 'BPLO Admin', 'bploadmin2025@gmail.com', '09077984234', '$2y$10$JRF.vJ9KFfxAU1z6UE.QYOkEPzN6IaRLjR8sGDMEmCInTGkmDiRl6', 'admin', '2025-07-30 11:27:44', '', '0000-00-00 00:00:00', ''),
(28, 'Roman Victor Delavega', 'romandelavegamgg@gmail.com', '09077984234', '$2y$10$i4N6JJl8UVVEVkXMe2a.qudFe/IEE2fe2gVdaDoWzZ8RHN9zETsvy', 'user', '2025-07-30 11:36:30', '', NULL, ''),
(29, 'Dale Mar Sandagon', 'dalemarsandagon12@gmail.com', '09077984234', '$2y$10$4zEGXmHuK4C8rB.jakfIL.IWkBtVZuIE1ftIKWDfGhuBGay0ipYvW', 'user', '2025-07-30 11:37:42', '', NULL, ''),
(38, 'Liter&#039;s Owner', 'liters@gmail.com', '09123456789', '$2y$10$ohnIuFFvn5IX5yUf8.FQ5.LHHAjouETUVwVgvTSylQMTRU.sAYyDK', 'fb_owner', '2025-08-02 04:47:04', '', NULL, ''),
(39, '8teatripcafe Owner', '8teatripcafe@gmail.com', '09123456789', '$2y$10$bkIUeGwsXe1Nbb79dJIpwOfjlyRVw3iccDFMcRYGUQpCN5YFZDbAu', 'fb_owner', '2025-08-02 04:51:39', '', NULL, ''),
(40, 'tastelibmanan', 'tastelibmanan@gmail.com', '09077984234', '$2y$10$Po2652X0MjUDYcyMwsyzCOegVqx9ii/mPjmvMW0RX2Obqp18stVR6', 'user', '2025-08-02 07:02:39', '', NULL, ''),
(41, 'Bigbrews Owner', 'bigbrew@gmail.com', '09123456789', '$2y$10$.1ii/vfeydVuYSqbl7/iZOMQ/yOed46EuM.c1O6TL4dc8z2f2ZpnK', 'fb_owner', '2025-08-02 07:25:53', '', NULL, ''),
(42, 'Tiama Owner', 'tiama@gmail.com', '09123456789', '$2y$10$0Vs6T18sxj7/xD/R881rreLiB8HOD/5ChCvaAlgQakjEjrPJhyaC.', 'fb_owner', '2025-08-02 10:03:40', '', NULL, ''),
(43, 'Rekados Owner', 'rekados@gmail.com', '09123456789', '$2y$10$SJ04C5mZExxZCbOsLGD4sO3saBymcAP431Gn2Icezcn2lT9cYTWM2', 'fb_owner', '2025-08-02 10:07:54', '', NULL, ''),
(44, 'Atlantic Owner', 'atlantic@gmail.com', '09123456789', '$2y$10$uGLN62KfVQnXGZFSj7UP5eyK.tocpWLaUma3VZDuj9.zTiM1pfeUu', 'fb_owner', '2025-08-05 09:13:34', '', NULL, ''),
(45, '3N Owner', '3n@gmail.com', '09123456789', '$2y$10$CWtWqK5qJ60XI8QKv8H7reqP7G97hKzSsbcp5/cyfFWrCrJqLg4Bq', 'fb_owner', '2025-08-05 09:40:21', '', NULL, ''),
(46, 'Bakers Plaza Owner', 'bakersplaza@gmail.com', '09123456789', '$2y$10$SJTY0vL8SbAZ9lpYG1/u3ei31a.nEJSfZv9xvgty.MZrJXGh2eWSm', 'fb_owner', '2025-08-05 09:46:22', '', NULL, ''),
(47, 'Miggys grill house owner', 'miggys@gmail.com', '09123456789', '$2y$10$Qq1dnDDqO/VH3JtjXnP65.O2l.PCpmGB9BqDajAtCeE.84Y7p9MV.', 'fb_owner', '2025-08-05 10:13:18', '', NULL, ''),
(48, 'Kap Onis Owner', 'kaponi@gmail.com', '09123456789', '$2y$10$4vkgN08AOhJ4wGbJANy18u9qLOZiTotNGEunZn1SUv5AboFoImvXu', 'fb_owner', '2025-08-05 11:21:17', '', NULL, ''),
(49, 'Jhumari Job Galos', 'iramuhjsolag@gmail.com', '09104171409', '$2y$10$ptyERutGMqtwoleSiT/Ng.kFlXTkuXeiT0.LjyQh73GF2a3qivMl2', 'user', '2025-09-04 11:47:06', '', NULL, ''),
(50, 'Mark Alvarado', 'alvaradomarkangelo28@gmail.com', '0945643217654', '$2y$10$m8PQVG.3yC0p0o7jpZNn4O36gXSlmnrKRtw/f2HveqdXzcNRiEbCa', 'user', '2025-09-08 04:59:17', '', NULL, '');

-- --------------------------------------------------------

--
-- Table structure for table `business_applications`
--

CREATE TABLE `business_applications` (
  `ba_id` int(11) NOT NULL,
  `payment_mode` varchar(255) NOT NULL,
  `application_type` varchar(255) NOT NULL,
  `application_date` varchar(255) NOT NULL,
  `tin_no` varchar(255) NOT NULL,
  `dti_reg_no` varchar(255) NOT NULL,
  `dti_reg_date` varchar(255) NOT NULL,
  `business_type` varchar(255) NOT NULL,
  `tax_incentive` varchar(255) NOT NULL,
  `tax_entity` varchar(255) NOT NULL,
  `last_name` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `middle_name` varchar(255) NOT NULL,
  `owner_name` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `trade_name` varchar(255) NOT NULL,
  `business_address` varchar(255) NOT NULL,
  `postal_code` varchar(255) NOT NULL,
  `telephone_no` varchar(255) NOT NULL,
  `email_address` varchar(255) NOT NULL,
  `mobile_no` varchar(255) NOT NULL,
  `owner_address` varchar(255) NOT NULL,
  `owner_postal_code` varchar(255) NOT NULL,
  `owner_telephone_no` varchar(255) NOT NULL,
  `owner_email_address` varchar(255) NOT NULL,
  `owner_mobile_no` varchar(255) NOT NULL,
  `emergency_contact_name` varchar(255) NOT NULL,
  `emergency_contact_phone` varchar(255) NOT NULL,
  `emergency_contact_email` varchar(255) NOT NULL,
  `business_area` varchar(255) NOT NULL,
  `total_employees` varchar(255) NOT NULL,
  `male_employees` varchar(255) NOT NULL,
  `female_employees` varchar(255) NOT NULL,
  `lgu_employees` varchar(255) NOT NULL,
  `lessor_full_name` varchar(255) NOT NULL,
  `lessor_address` varchar(255) NOT NULL,
  `lessor_phone` varchar(255) NOT NULL,
  `lessor_email` varchar(255) NOT NULL,
  `monthly_rental` varchar(255) NOT NULL,
  `line_of_business` varchar(255) NOT NULL,
  `no_of_units` varchar(255) NOT NULL,
  `capitalization` varchar(255) NOT NULL,
  `position_title` varchar(255) NOT NULL,
  `barangay_clearance_owner` varchar(255) NOT NULL,
  `barangay_business_clearance` varchar(255) NOT NULL,
  `police_clearance` varchar(255) NOT NULL,
  `cedula` varchar(255) NOT NULL,
  `lease_contract` varchar(255) NOT NULL,
  `business_permit_form` varchar(255) NOT NULL,
  `dti_registration` varchar(255) NOT NULL,
  `sec_registration` varchar(255) NOT NULL,
  `rhu_permit` varchar(255) NOT NULL,
  `meo_clearance` varchar(255) NOT NULL,
  `mpdc_clearance` varchar(255) NOT NULL,
  `menro_clearance` varchar(255) NOT NULL,
  `bfp_certificate` varchar(255) NOT NULL,
  `applicant_signature` varchar(255) NOT NULL,
  `status` varchar(255) DEFAULT NULL,
  `user_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_applications`
--

INSERT INTO `business_applications` (`ba_id`, `payment_mode`, `application_type`, `application_date`, `tin_no`, `dti_reg_no`, `dti_reg_date`, `business_type`, `tax_incentive`, `tax_entity`, `last_name`, `first_name`, `middle_name`, `owner_name`, `business_name`, `trade_name`, `business_address`, `postal_code`, `telephone_no`, `email_address`, `mobile_no`, `owner_address`, `owner_postal_code`, `owner_telephone_no`, `owner_email_address`, `owner_mobile_no`, `emergency_contact_name`, `emergency_contact_phone`, `emergency_contact_email`, `business_area`, `total_employees`, `male_employees`, `female_employees`, `lgu_employees`, `lessor_full_name`, `lessor_address`, `lessor_phone`, `lessor_email`, `monthly_rental`, `line_of_business`, `no_of_units`, `capitalization`, `position_title`, `barangay_clearance_owner`, `barangay_business_clearance`, `police_clearance`, `cedula`, `lease_contract`, `business_permit_form`, `dti_registration`, `sec_registration`, `rhu_permit`, `meo_clearance`, `mpdc_clearance`, `menro_clearance`, `bfp_certificate`, `applicant_signature`, `status`, `user_id`) VALUES
(26, '', '', '2025-08-02', '', '', '', '', '', '', '', '', '', 'Liters Owner', 'Liters Cafe', 'Cafe', 'Poblacion', '4407', '0912345678', 'liters@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 38),
(27, '', '', '2025-08-02', '', '', '', '', '', '', '', '', '', '8teatripcafes owner', '8teatripcafe', 'Cafe', 'Poblacion', '4407', '0912345678', '8teatripcafe@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 39),
(28, '', '', '2025-08-02', '', '', '', '', '', '', '', '', '', 'Bigbrews Owner', 'BigBrew Cafe', 'Cafe Brew', 'Poblacion', '4407', '0912345678', 'bigbrew@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 41),
(29, '', '', '2025-08-02', '', '', '', '', '', '', '', '', '', 'Tiama Owner ', 'Tata Tiama Restaurant', 'Tiama kakanan', 'Poblacion', '4407', '0912345678', 'tiama@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 42),
(31, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', 'Atlantic Owner', 'Atlantic Bakery', 'Bakery', 'Poblacion', '4407', '0912345678', 'atlantic@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 44),
(32, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', '3n Owner', '3N Bakery', 'Bakery', 'Poblacion', '4407', '0912345678', '3n@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 45),
(33, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', 'Bakers Plaza Owner', 'Bakers Plaza', 'bakers ', 'Libod 1 ', '4407', '0912345678', 'bakersplaza@gmail.com', '09123456789', 'Libod 1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 46),
(34, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', 'Rekados Owner', 'Rekados', 'rekados', 'Libod 1 ', '4407', '0912345678', 'rekados@gmail.com', '09123456789', 'Libod 1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 43),
(35, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', 'Miggys grill house Owner', 'Miggys Grill House', 'grill house', 'Poblacion', '4407', '0912345678', 'miggys@gmail.com', '09123456789', 'Poblacion', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 47),
(36, '', '', '2025-08-05', '', '', '', '', '', '', '', '', '', 'Kap Onis Owner', 'Kap Oni Samgyupsal', 'Samgyup', 'Libod 1 ', '4407', '0912345678', 'kaponi@gmail.com', '09123456789', 'Libod 1', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', 'approved', 48);

-- --------------------------------------------------------

--
-- Table structure for table `business_gallery`
--
-- Error reading structure for table tastelibmanan.business_gallery: #1030 - Got error 194 &quot;Tablespace is missing for a table&quot; from storage engine InnoDB
-- Error reading data for table tastelibmanan.business_gallery: #1064 - You have an error in your SQL syntax; check the manual that corresponds to your MariaDB server version for the right syntax to use near &#039;FROM `tastelibmanan`.`business_gallery`&#039; at line 1

-- --------------------------------------------------------

--
-- Table structure for table `fb_owner`
--

CREATE TABLE `fb_owner` (
  `fbowner_id` int(100) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `fb_name` varchar(255) NOT NULL,
  `fb_type` varchar(255) NOT NULL,
  `fb_description` text NOT NULL,
  `fb_phone_number` varchar(255) NOT NULL,
  `fb_email_address` varchar(255) NOT NULL,
  `fb_operating_hours` varchar(255) NOT NULL,
  `fb_address` text NOT NULL,
  `fb_photo` varchar(255) DEFAULT NULL,
  `fb_latitude` varchar(255) DEFAULT NULL,
  `fb_longitude` varchar(255) DEFAULT NULL,
  `fb_status` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fb_owner`
--

INSERT INTO `fb_owner` (`fbowner_id`, `user_id`, `fb_name`, `fb_type`, `fb_description`, `fb_phone_number`, `fb_email_address`, `fb_operating_hours`, `fb_address`, `fb_photo`, `fb_latitude`, `fb_longitude`, `fb_status`) VALUES
(36, 39, '8 tea trip cafe', 'Cafe', 'ugh siram', '09123456789', '8teatripcafe@gmail.com', '4pm-5am', 'Poblacion', '../uploads/business_photo/8_tea_trip_cafe/68c4f1e512d95_8teatripcafe.jpg', '13.692872', '123.060088', 'open'),
(37, 38, 'Liter\'s Cafe', 'Cafe', 'sarap', '09123456789', 'liters@gmail.com', '9am-9pm', 'Poblacion', '../uploads/business_photo/688dbc9075204_liters.jpg', NULL, NULL, 'open'),
(38, 41, 'Bigbrew', 'Cafe', 'Brewed coffee', '09123456789', 'bigbrew@gmail.com', '8am - 10pm', 'Poblacion', '../uploads/business_photo/68ba31eabe4b6_bigbrew.png', '13.6930549', '123.0612091', 'open'),
(39, 42, 'Tata Tiama Restaurant', 'Restaurant', 'Kakanan sa Centro kan Libmanan', '09123456789', 'tiama@gmail.com', '7:00 AM - 7:00 PM', 'Poblacion', '../uploads/business_photo/688de33f5a84e_tatatiama.jpg', NULL, NULL, NULL),
(47, 28, 'Miggys Grill House', 'Restaurant', 'Grill House', '09123456789', 'miggys@gmail.com', '9am-9pm', 'Poblacion', '../uploads/business_photo/6891dd9a6ed35_miggys.jpg', '13.6936579', '123.0601746', 'closed'),
(48, 45, '3N Bakery', 'Bakery', '3n no#1 Bakery in Libmanan', '09123456789', '3n@gmail.com', '7:00 AM - 7:00 PM', 'Poblacion', '../uploads/business_photo/6891d27526d7a_3n.jpg', '13.6793167', '123.0392057', 'open'),
(49, 46, 'Bakers Plaza', 'Bakery', 'Bakers', '09123456789', 'bakersplaza@gmail.com', '7:00 AM - 7:00 PM', 'Libod 1', '../uploads/business_photo/6891d3b00737e_bakersplaza.jpg', '13.6941162', '123.061928', NULL),
(51, 43, 'Rekados', 'Restaurant', 'rekados eatery', '09123456789', 'rekados@gmail.com', '7:00 AM - 10:00 PM', 'Libod 1', '../uploads/business_photo/6891d8e57021a_rekados.jpg', NULL, NULL, 'open'),
(53, 48, 'Kap Oni Samgyupsal House', 'Fastfood', 'Samgyupsalan', '09123456789', 'kaponis@gmail.com', '7:00 AM - 7:00 PM', 'Libod 1', '../uploads/business_photo/6891ea047ce6d_kaponi.jpg', NULL, NULL, 'closed'),
(54, 44, 'Atlantic Bakery', 'Bakery', 'Bakery', '09123456789', 'atlantic@gmail.com', '7:00 AM - 7:00 PM', 'Poblacion', '../uploads/business_photo/6892b4d5d6ef6_atlantic.jpg', '13.6928828', '123.0618173', 'closed');

-- --------------------------------------------------------

--
-- Table structure for table `test_galleries`
--

CREATE TABLE `test_galleries` (
  `id` int(11) NOT NULL,
  `files_json` text NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_galleries`
--

INSERT INTO `test_galleries` (`id`, `files_json`, `created_at`) VALUES
(1, '[{\"name\":\"68c4fce8eb883_1.jpg\",\"type\":\"image\\/jpeg\",\"size\":206003,\"path\":\"uploads\\/68c4fce8eb883_1.jpg\"},{\"name\":\"68c4fce8ec04a_2.jpg\",\"type\":\"image\\/jpeg\",\"size\":958410,\"path\":\"uploads\\/68c4fce8ec04a_2.jpg\"},{\"name\":\"68c4fce8ec41d_3n.jpg\",\"type\":\"image\\/jpeg\",\"size\":62887,\"path\":\"uploads\\/68c4fce8ec41d_3n.jpg\"},{\"name\":\"68c4fce8ec698_8teatripcafe.jpg\",\"type\":\"image\\/jpeg\",\"size\":97726,\"path\":\"uploads\\/68c4fce8ec698_8teatripcafe.jpg\"},{\"name\":\"68c4fce8ec8e2_atlantic.jpg\",\"type\":\"image\\/jpeg\",\"size\":140588,\"path\":\"uploads\\/68c4fce8ec8e2_atlantic.jpg\"}]', '2025-09-13 05:11:04'),
(2, '[{\"name\":\"68c4fd06d9bc8_atlantic.jpg\",\"type\":\"image\\/jpeg\",\"size\":140588,\"path\":\"uploads\\/68c4fd06d9bc8_atlantic.jpg\"},{\"name\":\"68c4fd06da038_bakeries.jpg\",\"type\":\"image\\/jpeg\",\"size\":32709,\"path\":\"uploads\\/68c4fd06da038_bakeries.jpg\"},{\"name\":\"68c4fd06da820_bakersplaza.jpg\",\"type\":\"image\\/jpeg\",\"size\":40145,\"path\":\"uploads\\/68c4fd06da820_bakersplaza.jpg\"},{\"name\":\"68c4fd06daf08_bigbrew.jpg\",\"type\":\"image\\/jpeg\",\"size\":25374,\"path\":\"uploads\\/68c4fd06daf08_bigbrew.jpg\"}]', '2025-09-13 05:11:34');

-- --------------------------------------------------------

--
-- Table structure for table `test_lang`
--

CREATE TABLE `test_lang` (
  `test_id` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `age` varchar(255) NOT NULL,
  `location` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `test_lang`
--

INSERT INTO `test_lang` (`test_id`, `name`, `age`, `location`) VALUES
(1, 'Jhum', '21', 'Lupi'),
(2, 'Jhum', '21', 'Lupi'),
(3, 'Jhum', '21', 'Lupi'),
(4, 'Jhum', '', ''),
(5, 'Capstone', '', 'Lupi');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `business_applications`
--
ALTER TABLE `business_applications`
  ADD PRIMARY KEY (`ba_id`);

--
-- Indexes for table `fb_owner`
--
ALTER TABLE `fb_owner`
  ADD PRIMARY KEY (`fbowner_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_fbowner_user_id` (`user_id`);

--
-- Indexes for table `test_galleries`
--
ALTER TABLE `test_galleries`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `test_lang`
--
ALTER TABLE `test_lang`
  ADD PRIMARY KEY (`test_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=51;

--
-- AUTO_INCREMENT for table `business_applications`
--
ALTER TABLE `business_applications`
  MODIFY `ba_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=37;

--
-- AUTO_INCREMENT for table `fb_owner`
--
ALTER TABLE `fb_owner`
  MODIFY `fbowner_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `test_galleries`
--
ALTER TABLE `test_galleries`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `test_lang`
--
ALTER TABLE `test_lang`
  MODIFY `test_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=6;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `fb_owner`
--
ALTER TABLE `fb_owner`
  ADD CONSTRAINT `fk_fbowner_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
