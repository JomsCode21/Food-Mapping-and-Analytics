-- phpMyAdmin SQL Dump
-- version 5.2.2
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 05, 2026 at 03:22 AM
-- Server version: 11.8.3-MariaDB-log
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u366677621_tastelibmanan`
--

-- --------------------------------------------------------

--
-- Table structure for table `accounts`
--

CREATE TABLE `accounts` (
  `user_id` int(100) NOT NULL,
  `name` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `phone_number` varchar(15) DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `user_type` varchar(255) NOT NULL,
  `date_created` timestamp NOT NULL DEFAULT current_timestamp(),
  `recovery_code` varchar(255) DEFAULT NULL,
  `recovery_code_expiry` timestamp NULL DEFAULT NULL,
  `user_favorites` varchar(255) NOT NULL,
  `email_verified` tinyint(1) DEFAULT 0,
  `verification_code` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `accounts`
--

INSERT INTO `accounts` (`user_id`, `name`, `email`, `phone_number`, `password`, `user_type`, `date_created`, `recovery_code`, `recovery_code_expiry`, `user_favorites`, `email_verified`, `verification_code`) VALUES
(27, 'BPLO Admin', 'bploadmin2025@gmail.com', '09077984234', '$2y$10$JRF.vJ9KFfxAU1z6UE.QYOkEPzN6IaRLjR8sGDMEmCInTGkmDiRl6', 'admin', '2025-07-30 11:27:44', '', NULL, '', 1, NULL),
(29, 'Dale Mar Sandagon', 'dalemarsandagon12@gmail.com', '09077984234', '$2y$10$4zEGXmHuK4C8rB.jakfIL.IWkBtVZuIE1ftIKWDfGhuBGay0ipYvW', 'user', '2025-07-30 11:37:42', '', NULL, '', 1, NULL),
(41, 'Bigbrews Owner', 'bigbrew@gmail.com', '09123456789', '$2y$10$.1ii/vfeydVuYSqbl7/iZOMQ/yOed46EuM.c1O6TL4dc8z2f2ZpnK', 'fb_owner', '2025-08-02 07:25:53', '', NULL, '39,49', 1, NULL),
(42, 'Tiama Owner', 'tiama@gmail.com', '09123456789', '$2y$10$0Vs6T18sxj7/xD/R881rreLiB8HOD/5ChCvaAlgQakjEjrPJhyaC.', 'fb_owner', '2025-08-02 10:03:40', '', NULL, '', 1, NULL),
(43, 'Rekados Owner', 'rekados@gmail.com', '09123456789', '$2y$10$SJ04C5mZExxZCbOsLGD4sO3saBymcAP431Gn2Icezcn2lT9cYTWM2', 'fb_owner', '2025-08-02 10:07:54', '', NULL, '', 1, NULL),
(44, 'Atlantic Owner', 'atlantic@gmail.com', '09123456789', '$2y$10$uGLN62KfVQnXGZFSj7UP5eyK.tocpWLaUma3VZDuj9.zTiM1pfeUu', 'fb_owner', '2025-08-05 09:13:34', '', NULL, '', 1, NULL),
(46, 'Bakers Plaza Owner', 'bakersplaza@gmail.com', '09123456789', '$2y$10$SJTY0vL8SbAZ9lpYG1/u3ei31a.nEJSfZv9xvgty.MZrJXGh2eWSm', 'fb_owner', '2025-08-05 09:46:22', '', NULL, '', 1, NULL),
(47, 'Miggys grill house owner', 'miggys@gmail.com', '09123456789', '$2y$10$Qq1dnDDqO/VH3JtjXnP65.O2l.PCpmGB9BqDajAtCeE.84Y7p9MV.', 'fb_owner', '2025-08-05 10:13:18', '', NULL, '', 1, NULL),
(48, 'Kap Onis Owner', 'kaponi@gmail.com', '09123456789', '$2y$10$4vkgN08AOhJ4wGbJANy18u9qLOZiTotNGEunZn1SUv5AboFoImvXu', 'fb_owner', '2025-08-05 11:21:17', '', NULL, '', 1, NULL),
(50, 'Mark Alvarado', 'alvaradomarkangelo28@gmail.com', '0945643217654', '$2y$10$m8PQVG.3yC0p0o7jpZNn4O36gXSlmnrKRtw/f2HveqdXzcNRiEbCa', 'user', '2025-09-08 04:59:17', '', NULL, '', 1, NULL),
(51, '8teatripcafe', '8teatripcafe@gmail.com', '0912345678', '$2y$10$zjO9fk50DeLdwJ2K5rsWSuAb1PeIwdNMwXYFePgnQWcScNAqUrynC', 'fb_owner', '2025-09-20 07:16:17', '', NULL, '', 1, NULL),
(52, 'Alex Edrian ', 'alex@gmail.com', '0912345678', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-09-27 06:10:43', '', NULL, '[\"54\",\"49\",\"56\",\"53\",\"74\",\"65\"],65,39,49,73,104,56', 1, NULL),
(53, 'roman', 'romandelavegamgg@gmail.com', '09077984234', '$2y$10$Tm04Hp3m0HWHz.HUYWUIROpJHCrrwZ5olitYgektUvwghaO8vtcPC', 'fb_owner', '2025-09-29 07:04:32', '', NULL, '', 1, NULL),
(54, 'Rinnor', 'rinnor@gmail.com', '09123456789', '$2y$10$IpEY8Rt0KmvckKMPESp2T.kzcO6CvFh6PHq8TdvBo1zIWdzos/vZ6', 'user', '2025-10-01 02:25:48', '', NULL, '', 1, NULL),
(55, 'JohnLeoHahaha', 'johnleo@gmail.com', '090909090', '$2y$10$s4CcbN8nsmwddbs9a98Cce5dy9HaMgAOTS/jlZxVtsfjrnzmxtkim', 'user', '2025-10-01 05:06:13', '', NULL, '', 1, NULL),
(56, 'Liters', 'liters@gmail.com', '0912345678', '$2y$10$7TrChl6FJ46CS5g.fNesp.5ajzm8faTerF2/DuCeKeAlzX2F4AYg.', 'user', '2025-10-02 11:44:54', '', NULL, '', 1, NULL),
(57, 'Dale mar Sandagon', 'dalemarsandagon2@gmail.com', '09487670202', '$2y$10$VQsbgydUxzrNhAygYw/vg.6f9BECjnScY1sK9HD9t9sr.D6r6I9li', 'user', '2025-10-04 14:02:14', '', NULL, '', 1, NULL),
(58, 'don roman', 'donroman@gmail.com', '0912345678', '$2y$10$uq78QfEGmDEfHbt932A7g.J4COHIIEgnBnrQsCxYnwmJuProWqaYe', 'user', '2025-10-05 00:02:51', '', NULL, '', 1, NULL),
(59, 'Bigboy Bite Food House', 'iramuhjsolag@gmail.com', '0912345678', '$2y$10$QNjD8yTBGp/BIHtHALl67eqQrU1x64Id.DK5yP0xvylNbxoMnRkJ6', 'fb_owner', '2025-10-05 03:27:35', '', NULL, '', 1, NULL),
(62, 'Jhumari Job Galos', 'alexsmithpogi1@gmail.com', '09104171409', '$2y$10$ktReMvPb/gp15YkvEElayu9969aM09aDwTdKZfBd7UCXBzJFY9qMW', 'user', '2025-10-05 11:50:22', '', NULL, '51,39,54,53,82', 1, NULL),
(63, 'Dale mar Sandagon', 'dalemarsandagon09@gmail.com', '', '$2y$10$OeVNegsC6jos8yD0MevtGOtb1PDvJPqZbBoSrkrO1L01UMZvKXVFi', 'user', '2025-10-06 03:35:21', '', NULL, '[\"82\",\"80\",\"73\",\"54\",\"49\"],49,51,76,56', 1, NULL),
(64, 'daboy', 'daboyantonio26@gmail.com', '', '$2y$10$PTMYGineYYYIxoGeJ/sL0.znwz/ARGgVkukdnLYIXJ3tn0U6viKsC', 'user', '2025-10-06 03:49:19', '', NULL, '', 1, NULL),
(65, 'kuyacap', 'kuyacap01@gmail.com', '', '$2y$10$rj0WQ4uzUpYMoeUPgoxfZ.3L8cA.PIWhnXMAkD5LzgV5kOe2AWrqq', 'fb_owner', '2025-10-06 05:58:42', '', NULL, '', 1, NULL),
(66, 'sample', 'dennis.gabon@cbsua.edu.ph', '09077984234', '$2y$10$laHE8e53Ap4r4eobmtczJONDf0PbAIJLHWVInDY/uOPbSTUkzOmDG', 'user', '2025-10-06 11:42:18', '', NULL, '', 1, NULL),
(67, 'kikay', 'maryjane.bodollo@cbsua.edu.ph', '', '$2y$10$0y7fF6NE7BG3CVq2G5xAb.r7Tq8EnYJBRfg1YG4pSxoiLiA0Fnz3O', 'user', '2025-10-06 13:06:50', '', NULL, '', 1, NULL),
(68, 'mark', 'markjason.canas@cbsua.edu.ph', '', '$2y$10$o4NVhHeZnn5aGwtCu81JT.I6TUcKaUFrsk/djSNaFdETy6e3ljXhy', 'user', '2025-10-06 13:08:41', '', NULL, '', 1, NULL),
(69, 'jes', 'jessamae.solano@cbsua.edu.ph', '', '$2y$10$4w9aMxR4K57WOyi/0OBPFOApyfA2L3PVxCiMs542da8EGyX3tAOSa', 'user', '2025-10-06 13:13:54', '', NULL, '', 1, NULL),
(70, 'francis', 'francis.noche@cbsua.edu.ph', '', '$2y$10$pRJhF1GUEG57Wgjscnoh0uaJCMYnAz8Bzgk.IT6sUS1PqX9ZCl11q', 'user', '2025-10-06 13:15:46', '', NULL, '', 1, NULL),
(71, 'Jhun roland', 'jhunroland.antonio@cbsua.edu.ph', '', '$2y$10$pkOABrf6yEtk6QWpkVwa6essGWxpjFGtnvH9agvEA51nHWty5QQ0a', 'user', '2025-10-06 13:17:41', '', NULL, '', 1, '10ac4294ef7c7a27067c5ada2d0515b0'),
(72, 'Pamboy', 'dalemar.sandagon@cbsua.edu.ph', '', '$2y$10$jwOgV3LvrOCNRINYxw7xzuTEhvFj890MNbxG0eRckQ6M/Y2RgToJK', 'user', '2025-10-06 13:20:22', '', NULL, '', 1, NULL),
(73, 'Kyola', 'jessataba00.00@gmail.com', '', '$2y$10$6GE8bvlAEVPE8VcLDw7APOTD57F2cwI4WXWvV1MaolxgQg8iGqanK', 'user', '2025-10-06 13:24:39', '', NULL, '', 1, NULL),
(74, 'Rayman', 'februss17@gmail.com', '', '$2y$10$UQ4aa.VLQk5gQ2yUgLVsfO79zUoUHusKxI.OTEhCev9FysT24079m', 'user', '2025-10-06 13:27:00', '', NULL, '', 1, NULL),
(75, 'marky', 'mark.canas01@gmail.com', '', '$2y$10$4oQdgHUaBAcbg/5qeI9jEe50IQFp2dUKmCfB4CETLl8/lpLoQMhju', 'user', '2025-10-06 13:30:05', '', NULL, '', 1, NULL),
(76, 'hylene', 'hyleendeguzman7@gmail.com', '', '$2y$10$20UmZgZdhXAoVp12TZQ4ludmPy.5SXJSC0vqo7M.Y42uiESFIaVna', 'user', '2025-10-06 13:32:56', '', NULL, '', 1, NULL),
(77, 'bien', 'bienann06@gmail.com', '', '$2y$10$OmIXQpO99JiLF.pomuDiOOJht5EDKI6axZa9sgKci//3c2S1VtDlS', 'user', '2025-10-06 13:34:12', '', NULL, '', 1, NULL),
(78, 'Krisha', 'kristherjanne.marcaida@cbsua.edu.ph', '', '$2y$10$dk1CNYfR0k1NwcgntvbBEuxWL9CuRWLt6JHGfckqq2Sp6Lly3piYK', 'user', '2025-10-06 13:44:04', '', NULL, '', 1, NULL),
(79, 'charline', 'charline.suarilla@cbsua.edu.ph', '', '$2y$10$Zy2ep6/ZqSAyBLF.RWZIcO1zC35i.HKIR/fgqv/iVpvvAXtkvb/y.', 'user', '2025-10-06 13:47:36', '', NULL, '', 1, NULL),
(80, 'Allean', 'marysionestelalleanmae.esplago@cbsua.edu.ph', '', '$2y$10$OLZij4r.XWcxKW18uGYQHe8HIXD6.xYV91GqL10rGDbje3zRdrW7m', 'user', '2025-10-06 13:51:30', '', NULL, '[\"77\",\"74\"]', 1, NULL),
(81, 'Leo', 'kuyaleoeatery@gmail.com', '', '$2y$10$BBMAL3dBoKBR5.Ak2FjIhufWQO7LlG5z1fBp2m21PZuYPOamUWKjK', 'fb_owner', '2025-10-06 14:57:55', '', NULL, '', 1, '93ac92db06651d44134ce74385ce71a9'),
(82, 'AFFORDABESTTEACAFE', 'affordabestteacafe476@gmail.com', '', '$2y$10$NHX5VdfPeEkRBUeSXn/L4ubZkQZFAQWv6RAbDW7RYuDAPUv6fQ1UK', 'fb_owner', '2025-10-06 15:00:51', '', NULL, '', 1, '99aad564e350141660a0dc5002302f3e'),
(83, 'PADING IKING', 'padingikig22@gmail.com', '', '$2y$10$AH7IHyCnVizjOySD5g7Bte6yo5WsQcD/BBj6qnFmnmVCFBpBYASAm', 'fb_owner', '2025-10-06 15:02:10', '', NULL, '', 1, '2428e13cd674079bdcc6c8b6dac4077c'),
(84, 'ARJOMEL', 'arjomelresto@gmail.com', '', '$2y$10$QVBHwnsOmW94eTy/tUFXReXcp/54PasQJFxtK3qKfw9A9A0RglwiC', 'fb_owner', '2025-10-06 15:03:08', '', NULL, '', 1, 'd3bdcd5f94c9f6bdf6d87ce10d267d7c'),
(85, 'BOBLY', 'boblyfoodplaza@gmail.com', '', '$2y$10$adpZNfqjgafch6aOsOSHo.EKEoma1wv8gCH8ZRAjjA00UH00cKncK', 'fb_owner', '2025-10-06 15:04:04', '', NULL, '', 1, '26e5eabb5b665f2d72c3f6acfd6125ef'),
(86, 'Lib', 'libmananfp02@gmail.com', '', '$2y$10$s8dGkWTVoJMmgNrLPO73J.y5T3psZZGYIJP0yd3out2kUpKkjZW/K', 'fb_owner', '2025-10-06 15:31:03', '', NULL, '', 1, '418421fc2e3227b248814e3e477e881f'),
(88, 'HANDIONG EATERY', 'handiongeatery@gmail.com', '', '$2y$10$sKHkcsrJKtW374EkajNHNuRt3PIyxrD0R/xYvAchMmTlES/cP0MmO', 'fb_owner', '2025-10-06 15:34:36', '', NULL, '', 1, 'e3d1ab8cee1f979962e9d26d2b03e7e5'),
(90, 'ESCAPES', 'escapes448@gmail.com', '', '$2y$10$a8KZgPU3i9YOpsBb8aSJnOBr5RQPDMqRZ.fETjFekSGsaOWpViqi2', 'fb_owner', '2025-10-06 15:47:45', '', NULL, '', 1, 'a0596c6873b3a42c6cba2db9bf869d8b'),
(91, 'MJ', 'mjfoodh@gmail.com', '', '$2y$10$qV4rrDTt51Qnqdn.hRrAzeIIlDFyRhw.Inw9Pi7ygRj82yKdtB6I2', 'fb_owner', '2025-10-06 15:48:45', '', NULL, '', 1, '47d54b4892a02f9c939d13681e53d0ba'),
(92, 'Roman Delavega', 'romanvictor.delavega@cbsua.edu.ph', '09077984234', '$2y$10$1j71q1R0NaQZhtU7IDlULekBJ0LWkmixutOpcrFfjs.S6DxXVlT7W', 'user', '2025-10-09 05:33:31', '', NULL, '[\"74\",\"77\",\"53\"]', 1, NULL),
(93, 'Kirby', 'kirbypintang02@gmail.com', '', '$2y$10$3TFfq1QBA7S.ap.RMGM6LeN3PqIoOgjNzv1oXSuGUo8iGi07eocU6', 'user', '2025-10-09 06:11:11', '', NULL, '', 1, NULL),
(94, 'Don Roman', 'tastelibmanan@gmail.com', '09077984234', '$2y$10$OvsbY1h6j04AiHgjIQ8s/eVv.7LkrafvndU5Vx0GwqGx8j4DhWUqG', 'user', '2025-11-21 10:00:34', '', NULL, '', 0, '267f1b3adac4ca43e99badb86817487e'),
(95, 'Kevin Robert Cabanos', 'kevincabanos07@gmail.com', '09205780682', '$2y$10$bRO3UXKTsOyxuUv0uHNDyeLRSpwE0gKMBV7JW/1mHAEK8Ry40dnnS', 'user', '2025-11-28 06:33:31', '', NULL, '', 1, NULL),
(96, 'Miggy', 'miggyhouse@gmail.com', '', '$2y$10$5LteLpEoi/au8H0YGD32cu/lisz.DYBHkHPn8hNCb8KmkZBMArMMS', 'fb_owner', '2025-11-28 08:58:27', '', NULL, '', 1, NULL),
(97, 'StopOverMilkteaBar', 'stopover232@gmail.com', '', '$2y$10$gFjuNehb.4No1O78eYZHC.szuTm87v9Cj3IHWPSq8Nu7rvcOeWtFm', 'fb_owner', '2025-11-28 09:00:14', '', NULL, '', 1, NULL),
(98, 'Marc Verzosa', 'kimmyverzosa4@gmail.com', '09280550337', '$2y$10$AAOSDN0osNumBUZGgk6zh.Yp9wllPKON24Rqdc9JdwioX3ZKp8.ei', 'user', '2025-11-29 12:11:27', '', NULL, '', 1, NULL),
(99, 'Ian', 'purciaian@gmail.com', '09635053589', '$2y$10$35snkDeLOweG0G9aO0U/uO6.0JwkdKjr3CuR7v1HGoEWVK5mE4P/K', 'user', '2025-11-29 12:11:32', '', NULL, '39', 1, NULL),
(101, 'Steven Francisco', 'steven.francisco@cbsua.edu.ph', '', '$2y$10$5wpopqnZQOQNOnTNZKulGOz36BhXPY79lrxyZGQkaEKh6pEaDoJ6W', 'user', '2025-12-01 11:48:58', '', NULL, '', 1, NULL),
(102, 'Arjay Manlangit', 'arjay.manlangit@cbsua.edu.ph', '09203484235', '$2y$10$pbXPeQPytAZUwxadNDv1j.RjJHAnJaF/hvVur/UITXkAqCFQlkZs.', 'user', '2025-12-02 01:26:39', '', NULL, '', 1, NULL),
(103, 'pogi', 'pascuaralph0546@gmail.com', '', '$2y$10$jrhc6X2QHQ10QNQc42Ib8.e5mMMUvwp8Fq9BnMzNipSPxK4CXMBAy', 'user', '2025-12-03 07:57:14', '', NULL, '', 1, NULL),
(105, 'Jhumari Job Galos', 'jhumarijob.galos@cbsua.edu.ph', '', '$2y$10$Gtctxq6K/U6r9T6uKQ/2EeH8ixJmNWFM/erwkUHOXi1wLpU2zklO2', 'user', '2025-12-04 08:37:38', '', NULL, '', 1, NULL),
(106, 'BlackPepper Camp Pizza', 'blackpepper@gmail.com', '', '$2y$10$wPETIn5Un31x5cxo.k54.OTJ4NOZYrHvnMR0ZxPUZJ7TGcIzozzau', 'fb_owner', '2025-12-05 00:46:47', '', NULL, '', 1, '622b1346bd2104b4a0438593de5a01d0'),
(107, 'Espresso Libmanan', 'espresso@gmail.com', '', '$2y$10$MuJJZuqeZpQ9h4//LH31fevSh/Y2Yx1O2nhvul.NMp9qeiGGF36D.', 'fb_owner', '2025-12-05 00:49:14', '', NULL, '', 1, 'bcf903501579f62a7bfd786fd43f77b3'),
(108, 'Adoy&#039;s Kinalas', 'adoys@gmail.com', '', '$2y$10$d5o9Q9.atDdUt8FO6cqPI.jQ3M1pcUfUa8illwMLmvJHWeO2bf6xu', 'fb_owner', '2025-12-05 00:52:43', '', NULL, '', 1, 'b8b5f69bde444628c07de2f2cd933ce9'),
(109, 'Kkopi Tea', 'kkopitea@gmail.com', '', '$2y$10$/TyhdE2/cO47pTW.zDOd0Ojj4Zw9lCLfokS.jjxYiSqFdNCsMxwJm', 'fb_owner', '2025-12-05 00:54:38', '', NULL, '', 1, '481877654b565c0f37bbfd84ab61fa7b'),
(110, 'Ma Cafe', 'macafe@gmail.com', '', '$2y$10$GYrgYvT28YELNKJnIQ.O1utvutXs00xkShxajF8W7XmWBqmj1QtRi', 'fb_owner', '2025-12-05 00:55:57', '', NULL, '', 1, '5a10fabe4e455ee83ebeab2c347e39f4'),
(111, 'Roy&#039;s Bulalo and Eatery', 'roysbulalo@gmail.com', '', '$2y$10$xWJqQC4i2N4DjjXEQEFFm.91q5HCMk2Cf6KXyBMQNBsJohmQfT.PK', 'fb_owner', '2025-12-05 00:57:54', '', NULL, '', 1, 'fbf48a3a181220446355e9086dc2aed8'),
(112, 'Kainan sa Bagumbayan', 'kainansabagumbayan@gmail.com', '', '$2y$10$6s6icHmwU1KsL3PNNGY3jON.mRNqBCrRoAtRC0.DUitPX6WhIX88K', 'fb_owner', '2025-12-05 00:59:31', '', NULL, '', 1, '206ae43c257b344760d61ac6180c744c'),
(113, 'Jaynaro Pizza', 'jaynaropizza@gmail.com', '', '$2y$10$iukusppvasGzNLYz45vsYew/gwdYHydhn9e5NOhRMjlDTieanxgq2', 'fb_owner', '2025-12-05 01:00:59', '', NULL, '', 1, 'fe9ea5acfe42753fb5bfaec587f880f6'),
(114, 'Aljos Food House', 'aljosfoodhouse@gmail.com', '', '$2y$10$1ZLFPV43quSS4uvlFPpjIegIJF3Url7aC139F9H7Da3SlS9N74UTK', 'fb_owner', '2025-12-05 01:02:12', '', NULL, '', 1, '12374e1f1787f91a58e51a3d50fd1fa7'),
(115, 'Batot Bulalohan and Kinalasan', 'batot@gmail.com', '', '$2y$10$yk/A4VnLJT9Z8XWJAyV1u.Kisi.X8u9AhCOcIXaT/IAk7J9jEV752', 'fb_owner', '2025-12-05 01:04:38', '', NULL, '', 1, 'd864435fb60d3314b05cad0f4dd7f036'),
(116, 'Juan Dela Cruz', 'juandelacruz@gmail.com', '09123456789', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:02:08', '', NULL, '', 1, NULL),
(117, 'Maria Santos', 'mariasantos@gmail.com', '09987654321', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:27:16', '', '2025-12-06 01:27:16', '99', 1, ''),
(118, 'Juan Carlos Reyes', 'juancarlosreyes@gmail.com', '09171234561', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(119, 'Mark Anthony dela Cruz', 'markanthonydelacruz@gmail.com', '09171234562', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(120, 'Jose Miguel Santos', 'josemiguelsantos@gmail.com', '09171234563', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(121, 'Rafael Luis Navarro', 'rafaelluisnavarro@gmail.com', '09171234564', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(122, 'Christian Gabriel Ramos', 'christiangabrielramos@gmail.com', '09171234565', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(123, 'Maria Isabella Cruz', 'mariaisabellacruz@gmail.com', '09171234566', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(124, 'Angelica Mae Flores', 'angelicamaeflores@gmail.com', '09171234567', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(125, 'Jasmine Nicole Tan', 'jasminenicoletan@gmail.com', '09171234568', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(126, 'Patricia Lourdes Mendoza', 'patricialourdesmendoza@gmail.com', '09171234569', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(127, 'Camille Joy Bautista', 'camillejoybautista@gmail.com', '09171234570', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:52:09', NULL, NULL, '', 1, ''),
(128, 'Alvin Joseph Soriano', 'alvinjosephsoriano@gmail.com', '09171234571', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(129, 'Erika Louisa Dela Rosa', 'erikalouisadelarosa@gmail.com', '09171234572', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(130, 'Dennis Angelo Mercado', 'dennisangelomercado@gmail.com', '09171234573', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(131, 'Bianca Rochelle Manalo', 'biancarochellemanalo@gmail.com', '09171234574', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(132, 'Jonathan Patrick Velasco', 'jonathanpatrickvelasco@gmail.com', '09171234575', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(133, 'Karen Sheila Ignacio', 'karensheilaignacio@gmail.com', '09171234576', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(134, 'Miguel Antonio Pascual', 'miguelantoniopascual@gmail.com', '09171234577', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 01:55:30', NULL, NULL, '', 1, ''),
(135, 'Gabriel Angelo Fernandez', 'gabrielangelofernandez@gmail.com', '09181234501', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(136, 'Joshua Kyle De Leon', 'joshuakyledeleon@gmail.com', '09181234502', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(137, 'Ryan Christopher Go', 'ryanchristophergo@gmail.com', '09181234503', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(138, 'Patrick James Villanueva', 'patrickjamesvillanueva@gmail.com', '09181234504', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(139, 'Kevin Mark Espanto', 'kevinmarkespanto@gmail.com', '09181234505', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(140, 'Justin Paul Ocampo', 'justinpaulocampo@gmail.com', '09181234506', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(141, 'Edward John Castillo', 'edwardjohncastillo@gmail.com', '09181234507', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(142, 'Bryan Anthony Diaz', 'bryananthonydiaz@gmail.com', '09181234508', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(143, 'Vincent Karl Medina', 'vincentkarlmedina@gmail.com', '09181234509', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(144, 'Adrian Luke Rivera', 'adrianlukerivera@gmail.com', '09181234510', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(145, 'Sofia Lorraine Gomez', 'sofialorrainegomez@gmail.com', '09181234511', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(146, 'Hannah Mae Pineda', 'hannahmaepineda@gmail.com', '09181234512', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(147, 'Janine Claire Domingo', 'janineclairedomingo@gmail.com', '09181234513', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(148, 'Andrea Louise Aguilar', 'andrealouiseaguilar@gmail.com', '09181234514', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(149, 'Katrina Marie Lopez', 'katrinamarielopez@gmail.com', '09181234515', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(150, 'Melissa Joy Sarmiento', 'melissajoysarmiento@gmail.com', '09181234516', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(151, 'Clarissa Jane Ferrer', 'clarissajaneferrer@gmail.com', '09181234517', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(152, 'Rachel Ann Torres', 'rachelanntorres@gmail.com', '09181234518', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(153, 'Nicole Rose Zamora', 'nicolerosezamora@gmail.com', '09181234519', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(154, 'Kimberly Kate Lim', 'kimberlykatelim@gmail.com', '09181234520', '$2y$10$HsEqfiORUb4kqUDG33Fz6OeUixwtIEmDg1/rQjLsI1sxVme4vEEHO', 'user', '2025-12-06 02:26:38', NULL, NULL, '', 1, ''),
(155, 'Donnalyn Perez', 'perezdonnutlyn06@gmail.com', '09184435202', '$2y$10$sCzhhXdgJyltSY3FofpGb.08cbbkWKSQsgtuwjSFory8Yj/4srwwu', 'user', '2025-12-08 05:05:54', NULL, NULL, '', 1, NULL),
(156, 'Shuwawey', 'sandarakimberly.villar@unc.edu.ph', '', '$2y$10$RvgF4Qwx50qcNLVTN2DnqORZ/V8pAaHr/oeEi4IjPK9.mZF8sCM3i', 'user', '2025-12-10 00:23:32', NULL, NULL, '', 1, NULL),
(157, 'Keith Justin Aguirre', 'deadmankit@gmail.com', '09949762029', '$2y$10$M.CogDdMzG5xmvcHvDi/oueDr6dANDgy8zxpbmjOkq4mFJf/KecO2', 'user', '2025-12-10 02:26:20', NULL, NULL, '', 0, '48abb9b56cc5cd840c6c658cbc3df6d2'),
(158, 'JOHN RYAN LORCA', 'john.ryan.lorca@gmail.com', '09091103028', '$2y$10$pG6MBzZQSQbWLBYqYyKdx.mU93h4mU/GDM3VMQCnJ8p2MNrss9J9W', 'user', '2025-12-10 09:16:57', NULL, NULL, '', 1, NULL),
(159, 'Hitivic', 'hotivic465@zongusa.com', '', '$2y$10$2qnCy57fY3S4XhT8TnN/5OVLrq987I6fUHf/gOu7haSTqllzO7M5C', 'user', '2025-12-20 08:34:08', NULL, NULL, '', 1, NULL),
(160, 'adan', 'ellenjoyceadan9@gmail.com', '', '$2y$10$4TvtjgqvJTtgw03JRqemvecTXBDSizD6iNTZo39Y1.8lJktlzNX.C', 'user', '2026-01-29 01:58:18', NULL, NULL, '', 1, NULL);

-- --------------------------------------------------------

--
-- Table structure for table `application_documents`
--

CREATE TABLE `application_documents` (
  `document_id` int(11) NOT NULL,
  `ba_id` int(11) NOT NULL,
  `barangay_clearance_owner` varchar(500) DEFAULT NULL,
  `barangay_business_clearance` varchar(500) DEFAULT NULL,
  `police_clearance` varchar(500) DEFAULT NULL,
  `cedula` varchar(500) DEFAULT NULL,
  `lease_contract` varchar(500) DEFAULT NULL,
  `business_permit_form` varchar(500) DEFAULT NULL,
  `dti_registration` varchar(500) DEFAULT NULL,
  `sec_registration` varchar(500) DEFAULT NULL,
  `rhu_permit` varchar(500) DEFAULT NULL,
  `meo_clearance` varchar(500) DEFAULT NULL,
  `mpdc_clearance` varchar(500) DEFAULT NULL,
  `menro_clearance` varchar(500) DEFAULT NULL,
  `bfp_certificate` varchar(500) DEFAULT NULL,
  `applicantSignature` varchar(500) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `application_documents`
--

INSERT INTO `application_documents` (`document_id`, `ba_id`, `barangay_clearance_owner`, `barangay_business_clearance`, `police_clearance`, `cedula`, `lease_contract`, `business_permit_form`, `dti_registration`, `sec_registration`, `rhu_permit`, `meo_clearance`, `mpdc_clearance`, `menro_clearance`, `bfp_certificate`, `applicantSignature`) VALUES
(36, 40, 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/barangay_clearance_owner/barangay_clearance_owner_68e35ea0072e76.02548377.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/barangay_business_clearance/barangay_business_clearance_68e35ea0079c53.57726052.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/police_clearance/police_clearance_68e35ea007db26.65617287.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/cedula/cedula_68e35ea0081484.94044528.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/lease_contract/lease_contract_68e35ea0084944.91921941.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/business_permit_form/business_permit_form_68e35ea00870c8.05544540.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/dti_registration/dti_registration_68e35ea008c784.94709280.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/sec_registration/sec_registration_68e35ea00905a1.53619851.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/rhu_permit/rhu_permit_68e35ea0094356.74143068.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/meo_clearance/meo_clearance_68e35ea00981d1.31500862.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/mpdc_clearance/mpdc_clearance_68e35ea009b2f8.52418125.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/menro_clearance/menro_clearance_68e35ea009d989.89970275.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/bfp_certificate/bfp_certificate_68e35ea00a1988.88313534.jpg', 'uploads/requirements/new/Kuya_Kap_Olea_s_Kainian_20251006/applicantSignature/applicantSignature_68e35ea00a38e6.55420424.jpg'),
(38, 42, 'uploads/requirements/new/BIGBOY_BITE_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3c4ed5a4c85.54730550.jfif', 'uploads/requirements/new/BIGBOY_BITE_20251006/barangay_business_clearance/barangay_business_clearance_68e3c4ed5aa061.11595683.jfif', 'uploads/requirements/new/BIGBOY_BITE_20251006/police_clearance/police_clearance_68e3c4ed5adff5.65361802.jfif', 'uploads/requirements/new/BIGBOY_BITE_20251006/cedula/cedula_68e3c4ed5b1814.44342286.jfif', 'uploads/requirements/new/BIGBOY_BITE_20251006/lease_contract/lease_contract_68e3c4ed5b5407.50076778.jfif', '', 'uploads/requirements/new/BIGBOY_BITE_20251006/dti_registration/dti_registration_68e3c4ed5b7b21.33081371.jfif', '', 'uploads/requirements/new/BIGBOY_BITE_20251006/rhu_permit/rhu_permit_68e3c4ed5bbcb8.49520222.jfif', '', '', 'uploads/requirements/new/BIGBOY_BITE_20251006/menro_clearance/menro_clearance_68e3c4ed5c0407.34539247.jfif', '', 'uploads/requirements/new/BIGBOY_BITE_20251006/applicantSignature/applicantSignature_68e3c4ed5c4681.52611336.png'),
(39, 43, 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3e2b0860735.35928992.jfif', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/barangay_business_clearance/barangay_business_clearance_68e3e2b0865929.50541631.jfif', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/police_clearance/police_clearance_68e3e2b08695d7.90905297.jfif', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/cedula/cedula_68e3e2b086ce60.57194506.jfif', '', '', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/dti_registration/dti_registration_68e3e2b08702a0.76829814.jfif', '', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/rhu_permit/rhu_permit_68e3e2b0873eb5.20287985.jfif', '', '', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/menro_clearance/menro_clearance_68e3e2b08795f8.01326443.jfif', '', 'uploads/requirements/new/LIBMANAN_FOOD_PARK_20251006/applicantSignature/applicantSignature_68e3e2b087d890.77509263.png'),
(40, 44, 'uploads/requirements/new/ESCAPES_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3e858943858.10034522.jfif', 'uploads/requirements/new/ESCAPES_20251006/barangay_business_clearance/barangay_business_clearance_68e3e858948217.93925486.jfif', 'uploads/requirements/new/ESCAPES_20251006/police_clearance/police_clearance_68e3e85894ba86.19639542.jfif', 'uploads/requirements/new/ESCAPES_20251006/cedula/cedula_68e3e85894f937.51989740.jfif', 'uploads/requirements/new/ESCAPES_20251006/lease_contract/lease_contract_68e3e858954b59.78941981.jfif', '', '', '', 'uploads/requirements/new/ESCAPES_20251006/rhu_permit/rhu_permit_68e3e8589577c9.95314232.jfif', '', '', 'uploads/requirements/new/ESCAPES_20251006/menro_clearance/menro_clearance_68e3e85895c143.09549981.jfif', '', 'uploads/requirements/new/ESCAPES_20251006/applicantSignature/applicantSignature_68e3e858960c41.68254929.png'),
(41, 45, 'uploads/requirements/new/HANDIONG_EATERY_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3e9d2c2d753.42241006.jfif', 'uploads/requirements/new/HANDIONG_EATERY_20251006/barangay_business_clearance/barangay_business_clearance_68e3e9d2c342b0.56170512.jfif', 'uploads/requirements/new/HANDIONG_EATERY_20251006/police_clearance/police_clearance_68e3e9d2c38490.39645495.jfif', 'uploads/requirements/new/HANDIONG_EATERY_20251006/cedula/cedula_68e3e9d2c3c1f6.88311977.jfif', 'uploads/requirements/new/HANDIONG_EATERY_20251006/lease_contract/lease_contract_68e3e9d2c3f771.21840606.jfif', '', 'uploads/requirements/new/HANDIONG_EATERY_20251006/dti_registration/dti_registration_68e3e9d2c42070.37883396.jfif', '', 'uploads/requirements/new/HANDIONG_EATERY_20251006/rhu_permit/rhu_permit_68e3e9d2c45ed1.48387975.jfif', '', '', 'uploads/requirements/new/HANDIONG_EATERY_20251006/menro_clearance/menro_clearance_68e3e9d2c4a361.22535629.jfif', '', 'uploads/requirements/new/HANDIONG_EATERY_20251006/applicantSignature/applicantSignature_68e3e9d2c4e8e4.62364223.png'),
(42, 46, 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3ea9f72c290.67497106.jfif', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/barangay_business_clearance/barangay_business_clearance_68e3ea9f732ae0.17039292.jfif', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/police_clearance/police_clearance_68e3ea9f7376b6.98177552.jfif', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/cedula/cedula_68e3ea9f73baf1.59905283.jfif', '', '', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/dti_registration/dti_registration_68e3ea9f73f824.26991692.jfif', '', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/rhu_permit/rhu_permit_68e3ea9f743513.51151318.jfif', '', '', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/menro_clearance/menro_clearance_68e3ea9f747325.63487675.jfif', '', 'uploads/requirements/new/MJ_FOOD_HOUSE_20251006/applicantSignature/applicantSignature_68e3ea9f74b168.30156073.png'),
(43, 47, 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3ef2433dd36.06034046.jfif', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/barangay_business_clearance/barangay_business_clearance_68e3ef24342ee5.24684174.jfif', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/police_clearance/police_clearance_68e3ef24346f54.29942355.jfif', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/cedula/cedula_68e3ef2434aaf1.67066952.jfif', '', '', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/dti_registration/dti_registration_68e3ef2434dd99.25041790.jfif', '', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/rhu_permit/rhu_permit_68e3ef243516d2.48096623.jfif', '', '', 'uploads/requirements/new/AFFORDABEST_TEA_CAFE_20251006/menro_clearance/menro_clearance_68e3ef24355686.81265016.jfif', '', NULL),
(44, 48, 'uploads/requirements/new/PADING_IKING_EATERY_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3f21fd044a8.96972839.jfif', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/barangay_business_clearance/barangay_business_clearance_68e3f21fd0ae20.77329260.jfif', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/police_clearance/police_clearance_68e3f21fd0fe80.51441186.jfif', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/cedula/cedula_68e3f21fd14528.13818776.jfif', '', '', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/dti_registration/dti_registration_68e3f21fd18126.62090743.jfif', '', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/rhu_permit/rhu_permit_68e3f21fd1c677.60781358.jfif', '', '', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/menro_clearance/menro_clearance_68e3f21fd214a0.96457734.jfif', '', 'uploads/requirements/new/PADING_IKING_EATERY_20251006/applicantSignature/applicantSignature_68e3f21fd25df0.90589690.png'),
(45, 49, 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3f8989d3c87.89054696.jfif', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/barangay_business_clearance/barangay_business_clearance_68e3f8989d9862.32543585.jfif', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/police_clearance/police_clearance_68e3f8989dd486.86408774.jfif', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/cedula/cedula_68e3f8989e0ab2.55478820.jfif', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/lease_contract/lease_contract_68e3f8989e3da5.02287245.jfif', '', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/dti_registration/dti_registration_68e3f8989e6514.76178281.jfif', '', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/rhu_permit/rhu_permit_68e3f8989e9f07.42916354.jfif', '', '', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/menro_clearance/menro_clearance_68e3f8989edd48.27790908.jfif', '', 'uploads/requirements/new/KUYA_LEO_EATERY_20251006/applicantSignature/applicantSignature_68e3f8989f1c28.13863622.png'),
(46, 50, 'uploads/requirements/new/ARJOMEL_RESTO_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3fa0c2a2896.53684598.jfif', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/barangay_business_clearance/barangay_business_clearance_68e3fa0c2a7a56.52614437.jfif', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/police_clearance/police_clearance_68e3fa0c2ab819.30849259.jfif', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/cedula/cedula_68e3fa0c2aef16.34910779.jfif', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/lease_contract/lease_contract_68e3fa0c2b2112.35990652.jfif', '', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/dti_registration/dti_registration_68e3fa0c2b4750.84969479.jfif', '', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/rhu_permit/rhu_permit_68e3fa0c2b8358.63263374.jfif', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/meo_clearance/meo_clearance_68e3fa0c2bc553.18016384.png', '', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/menro_clearance/menro_clearance_68e3fa0c2bdbb8.10009747.jfif', '', 'uploads/requirements/new/ARJOMEL_RESTO_20251006/applicantSignature/applicantSignature_68e3fa0c2c1b93.18012427.png'),
(47, 51, 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/barangay_clearance_owner/barangay_clearance_owner_68e3fd8cadf178.41963745.jfif', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/barangay_business_clearance/barangay_business_clearance_68e3fd8cae4274.73968780.jfif', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/police_clearance/police_clearance_68e3fd8cae7e09.46442617.jfif', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/cedula/cedula_68e3fd8caeb962.24518316.jfif', '', '', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/dti_registration/dti_registration_68e3fd8caef601.34769320.jfif', '', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/rhu_permit/rhu_permit_68e3fd8caf33a8.10956771.jfif', '', '', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/menro_clearance/menro_clearance_68e3fd8caf6fd0.55870197.jfif', '', 'uploads/requirements/new/BOBLY_S_FOOD_PLAZA_20251006/applicantSignature/applicantSignature_68e3fd8cafaab2.09406255.png'),
(53, 57, 'uploads/requirements/new/Miggy_s_Grill_House_20251128/barangay_clearance_owner/barangay_clearance_owner_692965421fe009.85252109.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/barangay_business_clearance/barangay_business_clearance_69296542204148.91202758.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/police_clearance/police_clearance_69296542217b02.32927936.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/cedula/cedula_6929654221d2c7.26070966.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/lease_contract/lease_contract_6929654222de21.05581534.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/business_permit_form/business_permit_form_6929654223d085.33768305.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/dti_registration/dti_registration_6929654224b152.13471968.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/sec_registration/sec_registration_69296542257bb9.22738731.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/rhu_permit/rhu_permit_6929654225b831.88701590.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/meo_clearance/meo_clearance_69296542270165.32324358.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/mpdc_clearance/mpdc_clearance_69296542273648.09565479.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/menro_clearance/menro_clearance_69296542276bb4.54589779.jpg', 'uploads/requirements/new/Miggy_s_Grill_House_20251128/bfp_certificate/bfp_certificate_69296542279d86.97868841.jpg', ''),
(55, 59, 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/barangay_clearance_owner_renewal/barangay_clearance_owner_renewal_692967575d27b4.90245768.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/barangay_business_clearance_renewal/barangay_business_clearance_renewal_692967575da552.44734550.jpeg', NULL, 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/cedula_renewal/cedula_renewal_692967575de141.59794022.png', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/lease_contract_renewal/lease_contract_renewal_692967575e0a91.50628024.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/business_permit_form_renewal/business_permit_form_renewal_692967575e3859.34208890.jpeg', NULL, 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/sec_registration_renewal/sec_registration_renewal_692967575e7a37.79568548.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/rhu_permit_renewal/rhu_permit_renewal_692967575e9a63.55867599.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/meo_clearance_renewal/meo_clearance_renewal_692967575ec439.49504875.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/mpdc_clearance_renewal/mpdc_clearance_renewal_692967575ef450.84443904.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/menro_clearance_renewal/menro_clearance_renewal_692967575f2a04.95655657.jpeg', 'uploads/requirements/renewal/StopOverMilkteaBar_20251128/bfp_certificate_renewal/bfp_certificate_renewal_692967575f48d7.82784347.jpeg', ''),
(58, 62, 'uploads/requirements/new/Blackpepper_20251205/barangay_clearance_owner/barangay_clearance_owner_6932e6c669b098.27090964.jpg', 'uploads/requirements/new/Blackpepper_20251205/barangay_business_clearance/barangay_business_clearance_6932e6c66a06d8.54257418.jpg', 'uploads/requirements/new/Blackpepper_20251205/police_clearance/police_clearance_6932e6c66a4c24.52436346.jpg', 'uploads/requirements/new/Blackpepper_20251205/cedula/cedula_6932e6c66a7e95.09475246.jpg', 'uploads/requirements/new/Blackpepper_20251205/lease_contract/lease_contract_6932e6c66aad22.08170215.jpg', 'uploads/requirements/new/Blackpepper_20251205/business_permit_form/business_permit_form_6932e6c66ae4b7.49935538.jpg', 'uploads/requirements/new/Blackpepper_20251205/dti_registration/dti_registration_6932e6c66b13f2.36730670.jpg', 'uploads/requirements/new/Blackpepper_20251205/sec_registration/sec_registration_6932e6c66b3971.30690646.jpg', 'uploads/requirements/new/Blackpepper_20251205/rhu_permit/rhu_permit_6932e6c66b5d11.19490214.jpg', 'uploads/requirements/new/Blackpepper_20251205/meo_clearance/meo_clearance_6932e6c66b8cf9.59803163.jpg', 'uploads/requirements/new/Blackpepper_20251205/mpdc_clearance/mpdc_clearance_6932e6c66bb6b3.24050536.jpg', 'uploads/requirements/new/Blackpepper_20251205/menro_clearance/menro_clearance_6932e6c66be150.50793456.jpg', 'uploads/requirements/new/Blackpepper_20251205/bfp_certificate/bfp_certificate_6932e6c66bfea4.55199763.jpg', 'uploads/requirements/new/Blackpepper_20251205/applicantSignature/applicantSignature_6932e6c66c2c53.80416501.jpg'),
(59, 63, 'uploads/requirements/new/Espresso_Cafe_20251205/barangay_clearance_owner/barangay_clearance_owner_6932e7a8630d39.83165128.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/barangay_business_clearance/barangay_business_clearance_6932e7a86354c5.38667570.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/police_clearance/police_clearance_6932e7a8639861.74080387.png', 'uploads/requirements/new/Espresso_Cafe_20251205/cedula/cedula_6932e7a863d3d7.16233242.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/lease_contract/lease_contract_6932e7a86403d5.78954071.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/business_permit_form/business_permit_form_6932e7a8644320.50093517.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/dti_registration/dti_registration_6932e7a86482b9.69781121.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/sec_registration/sec_registration_6932e7a864bc04.88492965.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/rhu_permit/rhu_permit_6932e7a864ef14.90142943.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/meo_clearance/meo_clearance_6932e7a8652a07.77795628.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/mpdc_clearance/mpdc_clearance_6932e7a8655ba9.78926594.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/menro_clearance/menro_clearance_6932e7a86595c4.76658771.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/bfp_certificate/bfp_certificate_6932e7a865cb69.68265611.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/applicantSignature/applicantSignature_6932e7a865f4f9.36340835.jpeg'),
(60, 64, 'uploads/requirements/new/Espresso_Cafe_20251205/barangay_clearance_owner/barangay_clearance_owner_6932e7b3d97fb8.22054900.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/barangay_business_clearance/barangay_business_clearance_6932e7b3d9b546.73819103.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/police_clearance/police_clearance_6932e7b3d9ec97.03417311.png', 'uploads/requirements/new/Espresso_Cafe_20251205/cedula/cedula_6932e7b3da0542.67167526.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/lease_contract/lease_contract_6932e7b3da2cb0.05529860.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/business_permit_form/business_permit_form_6932e7b3da7547.45587203.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/dti_registration/dti_registration_6932e7b3da93a8.80298529.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/sec_registration/sec_registration_6932e7b3dac377.40409434.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/rhu_permit/rhu_permit_6932e7b3daede4.53305952.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/meo_clearance/meo_clearance_6932e7b3db1fe9.99596930.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/mpdc_clearance/mpdc_clearance_6932e7b3db49e8.62434784.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/menro_clearance/menro_clearance_6932e7b3db7e14.82885351.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/bfp_certificate/bfp_certificate_6932e7b3dbabe8.17553733.jpeg', 'uploads/requirements/new/Espresso_Cafe_20251205/applicantSignature/applicantSignature_6932e7b3dbce27.17826251.jpeg'),
(61, 65, 'uploads/requirements/new/Kkopitea_20251205/barangay_clearance_owner/barangay_clearance_owner_6932e95c367d54.65473490.jpeg', 'uploads/requirements/new/Kkopitea_20251205/barangay_business_clearance/barangay_business_clearance_6932e95c36bf35.70004272.jpeg', 'uploads/requirements/new/Kkopitea_20251205/police_clearance/police_clearance_6932e95c36f9f9.90356802.png', 'uploads/requirements/new/Kkopitea_20251205/cedula/cedula_6932e95c371bc4.82434191.jpeg', 'uploads/requirements/new/Kkopitea_20251205/lease_contract/lease_contract_6932e95c374df8.16802602.jpeg', 'uploads/requirements/new/Kkopitea_20251205/business_permit_form/business_permit_form_6932e95c379242.80852770.jpeg', 'uploads/requirements/new/Kkopitea_20251205/dti_registration/dti_registration_6932e95c37bb85.48682443.jpeg', 'uploads/requirements/new/Kkopitea_20251205/sec_registration/sec_registration_6932e95c37f989.80406367.jpeg', 'uploads/requirements/new/Kkopitea_20251205/rhu_permit/rhu_permit_6932e95c3830c4.52938948.jpeg', 'uploads/requirements/new/Kkopitea_20251205/meo_clearance/meo_clearance_6932e95c386dd7.39472210.jpeg', 'uploads/requirements/new/Kkopitea_20251205/mpdc_clearance/mpdc_clearance_6932e95c38a3c0.05193550.jpeg', 'uploads/requirements/new/Kkopitea_20251205/menro_clearance/menro_clearance_6932e95c38dcb7.26163491.jpeg', 'uploads/requirements/new/Kkopitea_20251205/bfp_certificate/bfp_certificate_6932e95c390349.83793502.jpeg', 'uploads/requirements/new/Kkopitea_20251205/applicantSignature/applicantSignature_6932e95c3936e4.80058882.jpeg'),
(62, 66, 'uploads/requirements/new/Adoy_s_Kinalas_20251205/barangay_clearance_owner/barangay_clearance_owner_6932ea33ebbe20.40018123.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/barangay_business_clearance/barangay_business_clearance_6932ea33ebf905.10783375.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/police_clearance/police_clearance_6932ea33ec3741.42050649.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/cedula/cedula_6932ea33ec66c4.87327365.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/lease_contract/lease_contract_6932ea33eca342.60985548.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/business_permit_form/business_permit_form_6932ea33ecdbd6.79135972.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/dti_registration/dti_registration_6932ea33ed1944.94809354.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/sec_registration/sec_registration_6932ea33ed5133.75535956.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/rhu_permit/rhu_permit_6932ea33ed7ef5.15539820.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/meo_clearance/meo_clearance_6932ea33edb438.25253138.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/mpdc_clearance/mpdc_clearance_6932ea33eddea9.32781202.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/menro_clearance/menro_clearance_6932ea33ee0d75.29993480.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/bfp_certificate/bfp_certificate_6932ea33ee4940.27304574.jpg', 'uploads/requirements/new/Adoy_s_Kinalas_20251205/applicantSignature/applicantSignature_6932ea33ee8930.95895647.jpg'),
(63, 67, 'uploads/requirements/new/MaCafe_20251205/barangay_clearance_owner/barangay_clearance_owner_6932ea4480f876.54385101.jpeg', 'uploads/requirements/new/MaCafe_20251205/barangay_business_clearance/barangay_business_clearance_6932ea44813952.48775353.jpeg', 'uploads/requirements/new/MaCafe_20251205/police_clearance/police_clearance_6932ea448179c5.69163343.png', 'uploads/requirements/new/MaCafe_20251205/cedula/cedula_6932ea44819866.91760092.jpeg', 'uploads/requirements/new/MaCafe_20251205/lease_contract/lease_contract_6932ea4481c398.32770019.jpeg', 'uploads/requirements/new/MaCafe_20251205/business_permit_form/business_permit_form_6932ea4481feb7.29972731.jpeg', 'uploads/requirements/new/MaCafe_20251205/dti_registration/dti_registration_6932ea44823df3.48599612.jpeg', 'uploads/requirements/new/MaCafe_20251205/sec_registration/sec_registration_6932ea44828684.68179775.jpeg', 'uploads/requirements/new/MaCafe_20251205/rhu_permit/rhu_permit_6932ea4482c879.03871747.jpeg', 'uploads/requirements/new/MaCafe_20251205/meo_clearance/meo_clearance_6932ea44830976.61705522.jpeg', 'uploads/requirements/new/MaCafe_20251205/mpdc_clearance/mpdc_clearance_6932ea44833d94.20921955.jpeg', 'uploads/requirements/new/MaCafe_20251205/menro_clearance/menro_clearance_6932ea44837ab7.15242503.jpeg', 'uploads/requirements/new/MaCafe_20251205/bfp_certificate/bfp_certificate_6932ea4483beb8.99413774.jpeg', 'uploads/requirements/new/MaCafe_20251205/applicantSignature/applicantSignature_6932ea4483fbf3.11471798.jpeg'),
(64, 68, 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/barangay_clearance_owner/barangay_clearance_owner_6932eb325a1535.78969093.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/barangay_business_clearance/barangay_business_clearance_6932eb325a56a3.11998615.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/police_clearance/police_clearance_6932eb325a8532.15180569.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/cedula/cedula_6932eb325aa743.58735154.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/lease_contract/lease_contract_6932eb325add65.12199307.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/business_permit_form/business_permit_form_6932eb325b07b6.44191746.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/dti_registration/dti_registration_6932eb325b3db2.45514728.png', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/sec_registration/sec_registration_6932eb325b6270.51317424.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/rhu_permit/rhu_permit_6932eb325b8876.13364144.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/meo_clearance/meo_clearance_6932eb325bbc67.71996518.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/mpdc_clearance/mpdc_clearance_6932eb325be086.27761263.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/menro_clearance/menro_clearance_6932eb325c18e0.01681298.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/bfp_certificate/bfp_certificate_6932eb325c4a90.81290259.jpeg', 'uploads/requirements/new/Kainan_Sa_Bagumbayan_20251205/applicantSignature/applicantSignature_6932eb325c8707.75343637.jpeg'),
(65, 69, 'uploads/requirements/new/Roy_s_Bulalo_20251205/barangay_clearance_owner/barangay_clearance_owner_6932ebc386c3f6.45964968.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/barangay_business_clearance/barangay_business_clearance_6932ebc386f143.63087500.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/police_clearance/police_clearance_6932ebc3870dd6.91799090.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/cedula/cedula_6932ebc38728d0.37894042.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/lease_contract/lease_contract_6932ebc38756b9.14610156.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/business_permit_form/business_permit_form_6932ebc3878ff9.30774917.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/dti_registration/dti_registration_6932ebc387b688.09867414.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/sec_registration/sec_registration_6932ebc387e596.32176747.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/rhu_permit/rhu_permit_6932ebc3880bd2.20631710.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/meo_clearance/meo_clearance_6932ebc3882710.64315636.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/mpdc_clearance/mpdc_clearance_6932ebc3884be5.22679244.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/menro_clearance/menro_clearance_6932ebc3888212.98501547.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/bfp_certificate/bfp_certificate_6932ebc388ae19.73306127.jpg', 'uploads/requirements/new/Roy_s_Bulalo_20251205/applicantSignature/applicantSignature_6932ebc388e4e0.62641611.jpg'),
(66, 70, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/barangay_clearance_owner_renewal/barangay_clearance_owner_renewal_6932ec621f0502.78561017.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/barangay_business_clearance_renewal/barangay_business_clearance_renewal_6932ec621f4706.05899374.jpeg', NULL, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/cedula_renewal/cedula_renewal_6932ec621f7c44.01344300.png', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/lease_contract_renewal/lease_contract_renewal_6932ec621f9493.13345317.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/business_permit_form_renewal/business_permit_form_renewal_6932ec621fba51.07638411.jpeg', NULL, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/sec_registration_renewal/sec_registration_renewal_6932ec621fea68.30504821.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/rhu_permit_renewal/rhu_permit_renewal_6932ec62200820.78269191.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/meo_clearance_renewal/meo_clearance_renewal_6932ec622036b6.11201893.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/mpdc_clearance_renewal/mpdc_clearance_renewal_6932ec622061f1.74237572.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/menro_clearance_renewal/menro_clearance_renewal_6932ec62209462.66413978.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/bfp_certificate_renewal/bfp_certificate_renewal_6932ec6220bf00.00971916.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/applicantSignature_renewal/applicantSignature_renewal_6932ec6220e751.64582367.jpeg'),
(67, 71, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/barangay_clearance_owner_renewal/barangay_clearance_owner_renewal_6932ec7923a4e2.00968836.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/barangay_business_clearance_renewal/barangay_business_clearance_renewal_6932ec7923d588.15090741.jpeg', NULL, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/cedula_renewal/cedula_renewal_6932ec79240346.10217076.png', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/lease_contract_renewal/lease_contract_renewal_6932ec79241719.61526442.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/business_permit_form_renewal/business_permit_form_renewal_6932ec79243835.76593709.jpeg', NULL, 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/sec_registration_renewal/sec_registration_renewal_6932ec792464c7.61639147.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/rhu_permit_renewal/rhu_permit_renewal_6932ec79247c85.31255650.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/meo_clearance_renewal/meo_clearance_renewal_6932ec7924a381.99688809.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/mpdc_clearance_renewal/mpdc_clearance_renewal_6932ec7924c4e2.54478475.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/menro_clearance_renewal/menro_clearance_renewal_6932ec7924f4a4.54798488.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/bfp_certificate_renewal/bfp_certificate_renewal_6932ec792519c1.75824418.jpeg', 'uploads/requirements/renewal/Jaynaro_Pizza_20251205/applicantSignature_renewal/applicantSignature_renewal_6932ec79253596.45065236.jpeg'),
(68, 72, 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/barangay_clearance_owner_renewal/barangay_clearance_owner_renewal_6932ed84302986.62362001.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/barangay_business_clearance_renewal/barangay_business_clearance_renewal_6932ed84306638.87381322.jpeg', NULL, 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/cedula_renewal/cedula_renewal_6932ed84309d10.38414299.png', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/lease_contract_renewal/lease_contract_renewal_6932ed8430b656.12063717.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/business_permit_form_renewal/business_permit_form_renewal_6932ed8430dd65.14076862.jpeg', NULL, 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/sec_registration_renewal/sec_registration_renewal_6932ed843115c9.06452086.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/rhu_permit_renewal/rhu_permit_renewal_6932ed84313dd2.47848612.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/meo_clearance_renewal/meo_clearance_renewal_6932ed84316ad8.50921054.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/mpdc_clearance_renewal/mpdc_clearance_renewal_6932ed843194f7.87302428.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/menro_clearance_renewal/menro_clearance_renewal_6932ed8431c470.90927365.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/bfp_certificate_renewal/bfp_certificate_renewal_6932ed8431e6f1.32979131.jpeg', 'uploads/requirements/renewal/Batot_Bulaluhan_and_kinalasan_Branch_20251205/applicantSignature_renewal/applicantSignature_renewal_6932ed84321348.75208179.jpeg'),
(69, 73, 'uploads/requirements/renewal/Aljos_Food_House_20251205/barangay_clearance_owner_renewal/barangay_clearance_owner_renewal_6932ee8668e273.96162203.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/barangay_business_clearance_renewal/barangay_business_clearance_renewal_6932ee866918c4.74066706.jpg', NULL, 'uploads/requirements/renewal/Aljos_Food_House_20251205/cedula_renewal/cedula_renewal_6932ee86693e50.22893333.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/lease_contract_renewal/lease_contract_renewal_6932ee86697442.61627632.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/business_permit_form_renewal/business_permit_form_renewal_6932ee8669b1a0.40031787.jpg', NULL, 'uploads/requirements/renewal/Aljos_Food_House_20251205/sec_registration_renewal/sec_registration_renewal_6932ee8669ded1.68950520.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/rhu_permit_renewal/rhu_permit_renewal_6932ee866a1189.05616221.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/meo_clearance_renewal/meo_clearance_renewal_6932ee866a4903.32715361.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/mpdc_clearance_renewal/mpdc_clearance_renewal_6932ee866a7407.87308098.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/menro_clearance_renewal/menro_clearance_renewal_6932ee866aaa80.71301074.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/bfp_certificate_renewal/bfp_certificate_renewal_6932ee866ae5f6.39368443.jpg', 'uploads/requirements/renewal/Aljos_Food_House_20251205/applicantSignature_renewal/applicantSignature_renewal_6932ee866b06b6.68132268.jpg'),
(70, 74, '', '', '', '', '', '', '', '', '', '', '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `business_application`
--

CREATE TABLE `business_application` (
  `ba_id` int(100) NOT NULL,
  `application_type` enum('New','Renewal') DEFAULT NULL,
  `application_date` date DEFAULT NULL,
  `payment_mode` enum('Annually','Semi-Annually','Quarterly') DEFAULT NULL,
  `status` enum('Approved','Rejected','Pending') DEFAULT NULL,
  `user_id` int(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp() ON UPDATE current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_application`
--

INSERT INTO `business_application` (`ba_id`, `application_type`, `application_date`, `payment_mode`, `status`, `user_id`, `created_at`) VALUES
(40, 'New', '2025-10-06', 'Semi-Annually', 'Approved', 65, '2025-10-06 15:08:29'),
(42, 'New', '2025-09-04', 'Annually', 'Approved', 59, '2025-10-07 02:40:30'),
(43, 'New', '2025-10-06', 'Semi-Annually', 'Approved', 86, '2025-10-06 15:40:55'),
(44, 'New', '2025-10-06', 'Annually', 'Approved', 90, '2025-10-06 16:04:04'),
(45, 'New', '2025-10-07', 'Annually', 'Approved', 88, '2025-10-06 16:12:59'),
(46, 'New', '2025-10-06', 'Annually', 'Approved', 91, '2025-10-06 16:14:11'),
(47, 'New', '2025-10-07', 'Quarterly', 'Approved', 82, '2025-10-06 16:37:23'),
(48, 'New', '2025-10-06', 'Annually', 'Approved', 83, '2025-10-06 17:04:04'),
(49, 'New', '2025-10-06', 'Annually', 'Approved', 81, '2025-10-06 17:19:42'),
(50, 'New', '2025-10-07', 'Annually', 'Approved', 84, '2025-10-06 17:19:35'),
(51, 'New', '2025-10-06', 'Semi-Annually', 'Approved', 85, '2025-10-06 17:34:21'),
(57, 'New', '2025-11-28', 'Annually', 'Approved', 96, '2025-11-28 09:03:55'),
(59, 'New', '2025-11-28', '', 'Approved', 97, '2025-12-06 03:57:14'),
(62, 'New', '2025-12-05', 'Annually', 'Approved', 106, '2025-12-05 14:45:21'),
(63, 'New', '2025-12-05', 'Annually', 'Approved', 107, '2025-12-05 14:50:36'),
(64, 'New', '2025-12-05', 'Annually', 'Pending', 107, '2025-12-05 14:09:55'),
(65, 'New', '2025-12-05', 'Annually', 'Approved', 109, '2025-12-05 14:50:46'),
(66, 'New', '2025-12-05', 'Annually', 'Approved', 108, '2025-12-05 14:50:56'),
(67, 'New', '2025-12-05', 'Annually', 'Approved', 110, '2025-12-05 14:51:04'),
(68, 'New', '2025-12-05', 'Annually', 'Approved', 112, '2025-12-05 15:03:38'),
(69, 'New', '2025-12-05', 'Annually', 'Approved', 111, '2025-12-05 15:14:18'),
(70, 'Renewal', '2025-12-05', 'Annually', 'Pending', 113, '2025-12-05 14:29:54'),
(71, 'Renewal', '2025-12-05', 'Annually', 'Approved', 113, '2025-12-05 15:12:32'),
(72, 'Renewal', '2025-12-05', 'Annually', 'Approved', 115, '2025-12-05 15:12:57'),
(73, 'Renewal', '2025-12-05', 'Annually', 'Approved', 114, '2025-12-05 15:13:08'),
(74, 'New', '2025-12-06', 'Annually', 'Pending', 62, '2025-12-06 04:02:50');

-- --------------------------------------------------------

--
-- Table structure for table `business_details`
--

CREATE TABLE `business_details` (
  `bd_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `tin_no` varchar(20) NOT NULL,
  `dti_reg_no` varchar(50) NOT NULL,
  `dti_reg_date` date NOT NULL,
  `business_type` enum('Single','Partnership','Corporation','Cooperative') NOT NULL,
  `tax_incentive` varchar(10) NOT NULL,
  `tax_entity` varchar(255) NOT NULL,
  `business_name` varchar(255) NOT NULL,
  `trade_name` varchar(255) NOT NULL,
  `business_address` text NOT NULL,
  `postal_code` int(10) NOT NULL,
  `telephone_no` varchar(20) NOT NULL,
  `email_address` varchar(50) NOT NULL,
  `mobile_no` varchar(50) NOT NULL,
  `business_area` varchar(255) NOT NULL,
  `total_employee` int(50) NOT NULL DEFAULT 0,
  `male_employee` int(50) NOT NULL DEFAULT 0,
  `female_employee` int(50) NOT NULL DEFAULT 0,
  `lgu_employee` int(50) NOT NULL DEFAULT 0,
  `line_of_business` varchar(255) NOT NULL DEFAULT '1',
  `no_of_units` int(50) NOT NULL DEFAULT 1,
  `capitalization` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `business_details`
--

INSERT INTO `business_details` (`bd_id`, `ba_id`, `tin_no`, `dti_reg_no`, `dti_reg_date`, `business_type`, `tax_incentive`, `tax_entity`, `business_name`, `trade_name`, `business_address`, `postal_code`, `telephone_no`, `email_address`, `mobile_no`, `business_area`, `total_employee`, `male_employee`, `female_employee`, `lgu_employee`, `line_of_business`, `no_of_units`, `capitalization`) VALUES
(39, 40, '211902370397', '2987344', '2025-09-30', 'Single', 'no', '', 'Kuya Kap Olea\'s Kainian', 'KAponi', 'Zone 1, Beguito Nuevo, Libmanan', 4407, '09668809340', 'kuyacap01@gmail.com', '09668809340', '88', 2, 2, 0, 0, 'Restaurant', 1, 40000),
(41, 42, '224587113233', '7426102', '2025-09-04', 'Single', 'no', '', 'BIGBOY BITE', 'BIGBOY BITES', 'ZURBANO ST, Poblacion, LIBMANAN', 4407, '09104171409', 'bigboy@gmail.com', '09104171409', '60', 5, 3, 2, 0, 'Restaurant', 1, 25000),
(42, 43, '156624432551', '2012334', '2025-10-06', 'Single', 'no', '', 'LIBMANAN FOOD PARK', 'LIBMANAN FOOD PARK', ', Potot, Libmanan', 4407, '', 'libmananfp02@gmail.com', '09452114232', '90', 6, 3, 3, 0, 'Restaurant', 1, 45000),
(43, 44, '123456575414', '4485636', '2025-09-04', 'Single', 'no', '', 'ESCAPES', 'ESCAPES', 'Zone 4, Potot, Libmanan', 4407, '', 'escapes448@gmail.com', '09113258954', '50', 2, 0, 2, 0, 'Cafe', 1, 25000),
(44, 45, '325524799583', '4566932', '2025-10-06', 'Single', 'no', '', 'HANDIONG EATERY', 'HANDIONG EATERY', ', Poblacion, Libmanan', 4407, '', 'handiongeatery@gmail.com', '09635211248', '46', 1, 0, 1, 0, 'FastFood', 1, 20000),
(45, 46, '424771458996', '2341125', '2025-09-04', 'Single', 'no', '', 'MJ FOOD HOUSE', 'MJ FOOD HOUSE', 'Aureus Street, Poblacion,  Libmanan ', 4407, '', 'mjfoodh@gmail.com', '09214552788', '75', 3, 1, 2, 0, 'Restaurant', 1, 30000),
(46, 47, '104874003369', '4483622', '2025-10-05', 'Single', 'no', '', 'AFFORDABEST TEA CAFE', 'AFFORDABEST TEA CAFE', 'Zone 1 Abella St., Poblacion, LIBMANAN', 4407, '', 'affordabestteacafe476@gmail.com', '09192544963', '50', 2, 1, 1, 0, 'Cafe', 1, 25000),
(47, 48, '201360045844', '1202300', '2025-10-06', 'Single', 'no', '', 'PADING IKING EATERY', 'PADING IKING EATERY', 'Liibmanan Road, Mabini, Libmanan', 4407, '', 'padingikingg22@gmail.com', '09217550532', '80', 4, 2, 2, 0, 'Fastfood', 1, 37000),
(48, 49, '198966932501', '2353321', '2025-10-06', 'Single', 'no', '', 'KUYA LEO EATERY', 'KUYA LEO EATERY', 'Liibmanan Road, Mabini, Libmanan', 4407, '', 'kuyaleoeatery@gmail.com', '09637842216', '75', 3, 2, 1, 0, 'Fastfood', 1, 34000),
(49, 50, '332658771423', '1220455', '2025-10-07', 'Single', 'no', '', 'ARJOMEL RESTO', 'ARJOMEL RESTO', 'Liibmanan Road, Loba-loba, Libmanan', 4407, '', 'arjomelresto@gmail.com', '09967844544', '93', 7, 5, 2, 0, 'Restaurant', 1, 48000),
(50, 51, '257848974100', '1032599', '2025-10-06', 'Single', 'no', '', 'BOBLY\'S FOOD PLAZA', 'BOBLY\'S FOOD PLAZA', 'Asian Highway 26, Bagacay, Libmanan', 4407, '', 'boblysfoodplaza@gmail.com', '09331212141', '96', 6, 2, 4, 0, 'Restaurant', 1, 50000),
(56, 57, '', '', '2025-11-28', 'Single', 'no', '', 'Miggy\'s Grill House', '', 'Zone 1, Libod I, Libmanan', 4407, '', '', '', '', 0, 0, 0, 0, '', 0, 0),
(58, 59, '', '', '2025-11-28', '', '', '', 'StopOverMilkteaBar ', 'StopOverMilkteaBar ', 'zone 4 , Select Barangay, libmanan', 4407, '02837738292', 'StopOver232@gmail.com', '09898646564', '', 0, 0, 0, 0, '', 0, 0),
(61, 62, 'N/A', 'N/A', '2025-12-05', 'Single', 'no', '', 'Blackpepper', 'N/A', 'Zone 1, Libod I, Libmanan', 4407, '', 'blackpepper@gmail.com', '09456873214', '', 0, 0, 0, 0, '', 0, 0),
(62, 63, '', '', '2025-12-05', 'Single', 'no', '', 'Espresso Cafe', '', ', Aslong, Libmanan', 4407, '', 'espresso@gmail.com', '09867312456', '', 0, 0, 0, 0, '', 0, 0),
(63, 64, '', '', '2025-12-05', 'Single', 'no', '', 'Espresso Cafe', '', ', Aslong, Libmanan', 4407, '', 'espresso@gmail.com', '09867312456', '', 0, 0, 0, 0, '', 0, 0),
(64, 65, '', '', '2025-12-05', 'Single', 'no', '', 'Kkopitea', '', ', Bahao, Libmanan', 4407, '', '', '', '', 0, 0, 0, 0, '', 0, 0),
(65, 66, 'N/A', 'N/A', '2025-12-05', 'Single', 'no', '', 'Adoy\'s Kinalas', 'N/A', ', Puro-Batia, Libmanan', 4407, '', 'adoys@gmail.com', '09456983219', '', 0, 0, 0, 0, '', 0, 0),
(66, 67, '', '', '2025-12-05', 'Single', 'no', '', 'MaCafe', '', ', Bagumbayan, Libmanan', 4407, '', 'macafe@gmail.com', '09461275813', '', 0, 0, 0, 0, '', 0, 0),
(67, 68, '', '', '2025-12-05', 'Single', 'no', '', 'Kainan Sa Bagumbayan', '', ', Bagumbayan, Libmanan', 4407, '', 'kainansabagumbayan@gmail.com', '09432516794', '', 0, 0, 0, 0, '', 0, 0),
(68, 69, 'N/A', 'N/A', '2025-12-03', 'Single', 'no', '', 'Roy\'s Bulalo', 'N/A', 'Zone 4, Bahay, Libmanan', 4407, '', 'roysbulalo@gmail.com', '09468572340', '', 0, 0, 0, 0, '', 0, 0),
(69, 70, '', '', '2025-12-05', 'Single', 'yes', '', 'Jaynaro Pizza', '', '4, Begajo Sur, Libmanan', 4407, '', 'jaynaropizza@gmail.com', '09613254679', '', 0, 0, 0, 0, '', 0, 0),
(70, 71, '', '', '2025-12-05', 'Single', 'yes', '', 'Jaynaro Pizza', '', '4, Begajo Sur, Libmanan', 4407, '', 'jaynaropizza@gmail.com', '09613254679', '', 0, 0, 0, 0, '', 0, 0),
(71, 72, '', '', '2025-12-05', 'Single', 'no', '', 'Batot Bulaluhan and kinalasan Branch', '', 'zone 4 , Candami, Libmanan', 4407, '', 'batot@gmail.com', '09465348798', '', 0, 0, 0, 0, '', 0, 0),
(72, 73, 'N/A', 'N/A', '2025-12-05', 'Single', 'no', '', 'Aljos Food House', 'N/A', 'Zone 3, Planza, Libmanan', 4407, '', 'aljosfoodhouse@gmail.com', '09054514512', '', 0, 0, 0, 0, '', 0, 0),
(73, 74, '', '', '2025-12-06', 'Single', 'no', '', 'Hatdog', '', 'Liibmanan Road, Caima, Libmanan', 0, '', '', '', '', 0, 0, 0, 0, '', 0, 0);

-- --------------------------------------------------------

--
-- Table structure for table `emergency_contact`
--

CREATE TABLE `emergency_contact` (
  `emergency_contact_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `contact_name` varchar(255) NOT NULL,
  `contact_phone` varchar(255) NOT NULL,
  `contact_email` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `emergency_contact`
--

INSERT INTO `emergency_contact` (`emergency_contact_id`, `ba_id`, `contact_name`, `contact_phone`, `contact_email`) VALUES
(38, 40, 'kuyacap', '09487670202', 'kuyacap01@gmail.com'),
(40, 42, 'Jose Victor R. Dilanco', '5114437', 'josevdilanco@gmail.com'),
(41, 43, 'Libmanan Food Park', '09452114232', 'libmananfp02@gmail.com'),
(42, 44, 'ESCAPES', '09113258954', 'escapes448@gmail.com'),
(43, 45, 'HANDIONG EATERY', '09635211248', 'handiongeatery@gmail.com'),
(44, 46, 'MJ FOOD HOUSE', '09214552788', 'mjfoodh@gmail.com'),
(45, 47, 'AFFORDABEST TEA CAFE', '09192544963', 'affordabestteacafe476@gmail.com'),
(46, 48, 'PADING IKING EATERY', '09217550532', 'padingikingg22@gmail.com'),
(47, 49, 'KUYA LEO EATERY', '09637842216', 'kuyaleoeatery@gmail.com'),
(48, 50, 'ARJOMEL RESTO', '09967844544', 'arjomelresto@gmail.com'),
(49, 51, 'BOBLY\'S FOOD PLAZA', '09331212141', 'boblysfoodplaza@gmail.com'),
(55, 57, '', '', ''),
(57, 59, '', '', ''),
(60, 62, '', '', ''),
(61, 63, '', '', ''),
(62, 64, '', '', ''),
(63, 65, '', '', ''),
(64, 66, '', '', ''),
(65, 67, '', '', ''),
(66, 68, '', '', ''),
(67, 69, '', '', ''),
(68, 70, '', '', ''),
(69, 71, '', '', ''),
(70, 72, '', '', ''),
(71, 73, '', '', ''),
(72, 74, '', '', '');

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
  `fb_status` varchar(255) NOT NULL DEFAULT 'closed',
  `status_last_updated` timestamp NULL DEFAULT current_timestamp(),
  `fb_gallery` text DEFAULT NULL,
  `menu_images` text DEFAULT NULL,
  `menu_manual` text DEFAULT NULL,
  `fb_cover` varchar(255) DEFAULT NULL,
  `activation` enum('Active','Disable') DEFAULT 'Active',
  `summarize_review` text DEFAULT NULL,
  `last_summarize_date` date NOT NULL,
  `created_at` date NOT NULL DEFAULT current_timestamp(),
  `is_new` int(11) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `fb_owner`
--

INSERT INTO `fb_owner` (`fbowner_id`, `user_id`, `fb_name`, `fb_type`, `fb_description`, `fb_phone_number`, `fb_email_address`, `fb_operating_hours`, `fb_address`, `fb_photo`, `fb_latitude`, `fb_longitude`, `fb_status`, `status_last_updated`, `fb_gallery`, `menu_images`, `menu_manual`, `fb_cover`, `activation`, `summarize_review`, `last_summarize_date`, `created_at`, `is_new`) VALUES
(39, 42, 'Tata Tiama Restaurants', 'Restaurant', 'Kakanan sa Centro kan Libmanan', '09123456789', 'tiama@gmail.com', '7:00 AM - 7:00 PM', 'Palo St., Poblacion Libmanan', '../uploads/business_photo/688de33f5a84e_tatatiama.jpg', '13.693325415826884', '123.0625033441812', 'open', '2026-02-19 15:42:56', '[\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e3577191c40_49068201_2007942955958185_6437120314060046336_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e3577191ebf_49414101_2007943299291484_1229036360975777792_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e357719210a_69622209_2565776426841499_5178198054515245056_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e3577192407_481156515_9229681610450914_3305055925199969965_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e35822694af_68840720_2378673898885087_20687762248171520_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e35822696c3_67648095_2356070141145463_3655699364321427456_n.jpg\",\"uploads\\/business_gallery\\/Tata_Tiama_Restaurants\\/68e3582269937_484051405_9288647867887621_6063480545586109115_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e3570f22d08_50303892_2043296969089450_3442817210056179712_n.png\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3570f23022_68840720_2378673898885087_20687762248171520_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3570f231c0_67648095_2356070141145463_3655699364321427456_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3570f23306_484051405_9288647867887621_6063480545586109115_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3570f23534_486374425_1184448940137520_4242216331622505416_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Tata_Tiama_Restaurants/68e356fecea29_481156515_9229681610450914_3305055925199969965_n.jpg', 'Active', 'Customers generally praise the restaurant for its delicious and affordable food, emphasizing the flavorful dishes, beautiful presentation, and the chef\'s attention to detail. Many reviewers expressed their excitement about returning due to the variety of menu options and the overall positive dining experience. However, some customers reported issues with food freshness, indicating that certain dishes felt reheated and that portion sizes were smaller than expected. Inconsistencies in quality control and over-seasoning were also mentioned, highlighting that while many were impressed with their meals, some found them underwhelming.', '2025-11-28', '2025-09-27', 0),
(49, 46, 'Bakers Plaza', 'Bakery', 'Bakers', '09123456789', 'bakersplaza@gmail.com', '7:00 AM - 09:41 PM', 'Palo St., Libod 1, Libmanan', '../uploads/business_photo/6891d3b00737e_bakersplaza.jpg', '13.694222216501124', '123.06192323811719', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/Bakers_Plaza\\/68ce5e2a3d8af_1.jpg\",\"uploads\\/business_gallery\\/Bakers_Plaza\\/68ce5e2a41615_2.jpg\",\"uploads\\/business_gallery\\/Bakers_Plaza\\/68ce5e406c866_1.jpg\",\"uploads\\/business_gallery\\/Bakers_Plaza\\/68ce5e4071675_2.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecd926_547469804_1200574925431131_151454067835143816_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecdc05_545411448_1200573532097937_677885226367510513_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecde74_546755387_1200510632104227_8601197929622329794_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ece07b_539926850_1192359099586047_6206347315825221267_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ece51b_541333918_1194694816019142_7521158444676899578_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecea5c_542755821_1192143356274288_6545217387225614367_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecef66_539564459_1192143336274290_5617025493637576350_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecf43f_539690284_1189765239845433_3858719014233733881_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ecfa2d_539202333_1188064530015504_5370987960551287174_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3532ed0075_540418605_1188064523348838_7516754060913772189_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e355a87dbf8_130552948_4732057956836032_111791541035790634_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Bakers_Plaza/68e35277b4330_488658184_28958475913767565_1776068410343091008_n.jpg', 'Active', 'Customers generally praise the cakes and pastries for being sweet, delicious, and beautifully presented, highlighting the freshness and balance of flavors in each dish. Many were impressed with the quality of the food, noting that it was served hot and seasoned well, making for a comforting dining experience. However, there are negative aspects as well, with some diners expressing disappointment due to long wait times and dishes arriving cold or lacking flavor. Overall, while many reviewers plan to return for more, there is a clear need for improvement in service and consistency.', '2025-11-28', '2025-09-27', 0),
(51, 43, 'Rekados', 'Restaurant', 'rekados eatery', '09123456789', 'rekados@gmail.com', '7:00 AM - 10:00 PM', 'Aursua,  Bigajo Norte, Libmanan', '../uploads/business_photo/6891d8e57021a_rekados.jpg', '13.69625941638638', '123.06286362584875', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/Rekados\\/68e3507a72b0d_481119534_661660356424042_3894211894497670326_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a72f06_481475354_661660666424011_4599288750660817709_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a732b7_481666325_661660733090671_792774377177972354_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a73bb5_481447065_661660499757361_8006986197437083210_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a73f84_481107247_661660486424029_4458145604362652128_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a74322_481493089_661660346424043_6316897361555982720_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a7469a_481904651_661660513090693_3158698052549718956_n.jpg\",\"uploads\\/business_gallery\\/Rekados\\/68e3507a74a52_481776821_661660646424013_2100713864097909688_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e34f2619285_481666803_664534366136641_7637511653449559655_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f3218bb2_481700760_664534186136659_4185501755753959694_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f3996f87_482201148_664534189469992_6580719298575149736_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f4205ee5_481299314_664534449469966_2749231225105732072_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f4c541bb_481917215_664535512803193_2897094385759133679_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f5866f9d_481480409_664535502803194_4069128835293398627_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f678414c_482134635_664534439469967_8731727757399927681_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f733ae29_481177393_663948206195257_230337787339364287_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f8128fe6_481964284_663949189528492_8285574200510126697_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e34f8a5106f_482069277_663724266217651_2398313615584251577_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Rekados/68e3507a7270e_526809574_780186934571383_7015777521657527579_n.jpg', 'Active', 'Customer reviews for the restaurant highlight a mix of experiences. Many diners praised the generous serving sizes, quick service, and flavorful dishes, emphasizing the quality and freshness of the food. It was noted that the staff takes pride in their cooking, making for a memorable dining experience. However, some customers expressed disappointment due to meals that lacked flavor and temperature, suggesting a need for improvement in consistency and service time. Overall, while many found the dining experience enjoyable with highly recommended dishes, others reported areas that require attention.', '2025-11-27', '2025-09-27', 0),
(53, 48, 'Kap Oni Samgyupsal House', 'Fastfood', 'Samgyupsalan', '09123456789', 'kaponis@gmail.com', '7:00 AM - 7:00 PM', '7 J. Hernandez St., Libod 1 Libmanan', '../uploads/business_photo/Kap_Oni_Samgyupsal_House/68ce6aaba02fa_kaponi.jpg', '13.694445816488559', '123.0619171381172', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68ce6aabaae37_f1.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68ce6aabb2473_f2.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68e358b0ef087_5d9bbcc4-d33f-4b0b-939e-b79dad6bf8d6.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68e358b0ef322_90c5fbb1-8721-4ddf-9feb-a510d782b401.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68e358b0ef4c5_62a0171a-2839-4229-9f53-258c347fcf31.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68e358b0ef639_e90f7f1a-17b3-4615-9e42-22df563db055.jpg\",\"uploads\\/business_gallery\\/Kap_Oni_Samgyupsal_House\\/68e358b0ef79e_50924e6d-6d60-4064-8a9d-bf7d714c73a5.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e3341847d19_5cedb036-63f4-4076-9a57-b2fbba643156.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3343bc51af_98d7d9b2-6859-483c-bde1-45c3b6f554ec.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Kap_Oni_Samgyupsal_House/68ce6aaba5914_kaponi.jpg', 'Active', 'Customer reviews for this dining spot reflect a mix of experiences. Many patrons praised the food for being fresh, flavorful, and satisfying with generous portion sizes, often highlighting the thoughtful preparation and balance of flavors. However, some customers expressed disappointment, noting that certain dishes lacked freshness, arrived cold, or had inconsistent quality, such as overcooked rice and dry meat. Additionally, issues like long wait times and varying seasoning in the dishes contributed to an overall sense of frustration for a few reviewers, despite the restaurant\'s attractive ambiance and potential.', '2025-11-28', '2025-10-27', 0),
(54, 44, 'Atlantic Bakery', 'Bakery', 'Bakery', '09123456789', 'atlantic@gmail.com', '7:00 AM - 7:00 PM', 'Judge R.O. Zurbano St., Poblacion, Libmanan', '../uploads/business_photo/6892b4d5d6ef6_atlantic.jpg', '13.693008843983911', '123.06195832926093', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/Atlantic_Bakery\\/68e3598411276_82d22a99-c6a9-43ff-a8b3-1f796b5f1bb8 (1).jpg\",\"uploads\\/business_gallery\\/Atlantic_Bakery\\/68e35984113aa_fc745ffd-2d57-4f0c-bbf6-e4fe832e1008 (1).jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e33511b188d_809d53b5-350f-4f66-be53-d926046a9d78.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e33529e1685_82d22a99-c6a9-43ff-a8b3-1f796b5f1bb8.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3353aeba0e_dd06d69b-a361-4ed3-a6da-58dbef9bbd92.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e335494658b_3c5b40e9-eb57-4187-be11-5ae6f579fe48.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3355ecba60_36f01983-60d6-4e6a-afba-ff729e5fc7e7.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e335804a8ea_a30d3796-4e7b-4fe5-b0d2-9dd030e8676e.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Atlantic_Bakery/68e3598410f1a_fc745ffd-2d57-4f0c-bbf6-e4fe832e1008 (1).jpg', 'Active', 'Overall, the restaurant receives mixed reviews from customers. Many praised the food for being warm, tasty, and beautifully presented, highlighting the fresh ingredients and attention to detail that make each dish enjoyable. However, several customers expressed disappointment with long wait times for food that sometimes arrived cold or bland, and some dishes were noted to be overly salty. Despite these setbacks, many reviewers still recommend the restaurant for its great flavors and variety, suggesting that it is a worthwhile visit for food lovers.', '2025-11-27', '2025-10-27', 0),
(56, 51, '8 Tea Trip Cafe', 'Cafe', 'siram mag kape dito', '09123456789', '8teatripcafe@gmail.com', '7:30 AM - 9:00 PM', 'Judge R.0 Zurbano St., Poblacion, Libmanan', '../uploads/business_photo/8_Tea_Trip_Cafe/6933c557353b4_305204185_390122596639038_6661793417093501988_n.jpg', '13.694601', '123.061107', 'open', '2026-02-11 16:48:35', '[\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e336ba5465c_809d53b5-350f-4f66-be53-d926046a9d78.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e3375056f40_537786005_1091747606476530_3103756729541894496_n.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e337631dd57_539882750_1091747799809844_1451284617626661556_n.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e33769ea64d_538945720_1091747766476514_3855965803878385813_n.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e337720abdd_539348741_1091747736476517_8053784069179676807_n.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e3378419a0a_538645216_1091747706476520_2510962574768655406_n.jpg\",\"uploads\\/business_gallery\\/8_Tea_Trip_Cafe\\/68e3378e3c3fe_540481749_1091747673143190_2318293039124126021_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e3364db2c83_487228110_976059838045308_328967708545040120_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3365bd56f2_486502240_976057594712199_5220774933919807745_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e336666e1a1_486702755_976059071378718_3304859337345352559_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e336721d898_486603890_976057398045552_2472827323289992986_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e3367eafa43_486753486_976058308045461_6065635594419925166_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/8_Tea_Trip_Cafe/68e335d131461_483532970_967771528874139_7830496283971942442_n.jpg', 'Active', 'Customer reviews reveal a mixed experience at the restaurant and café. While many patrons praised the food for its outstanding quality, flavor, and beautiful presentation, others were disappointed with either overly oily dishes or poor seasoning that did not meet their expectations. Some diners highlighted that while the meals were creative and comforting, a few items were served cold or lacked freshness, which detracted from their overall enjoyment. Despite the criticisms, several reviewers emphasized the cozy atmosphere and excellent service, making it a recommended spot for good food lovers.', '2025-11-29', '2025-10-27', 0),
(65, 41, 'Big Brew', 'Cafe', '\"We are a cozy café serving expertly crafted coffee, delicious pastries, and light fare in a welcoming atmosphere.\"', '09123456789', 'bigbrew@gmail.com', '8:00 AM - 9:00 PM', '282 Aureus Street, Poblacion,  Libmanan', 'uploads/business_photo/Big_Brew/692bdbabb7c55_b537982c-3bdc-43d6-bea5-036b0b733a8b.jpg', '13.693509', '123.061232', 'open', '2025-12-02 08:08:38', '[\"uploads\\/business_gallery\\/Big_Brew\\/692fc439a7517_e45fd4b5-8437-40d3-83a2-1493d5867ac7.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/692abc9ace0d8_68d7d0c3ba965_bigbrew.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Big_Brew\\/692b98c85d118_1764320916933.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Big_Brew/692ae09bb735a_Screenshot_20251129-195854.jpg', 'Active', 'Customers have expressed mixed reviews about the food and drinks at this establishment. While many praised the milktea and coffee for their delightful taste and noted the flavorful, well-plated dishes, others criticized the food for being overly oily and not meeting their expectations considering the price. Overall, it seems the restaurant excels in presentation and flavor for some, but the portion sizes and consistency may need improvement.', '2025-11-21', '2025-10-27', 0),
(72, 65, 'Kuya Kap Olea\'s Kainian', 'Fastfood', '', '09668809340', 'kuyacap01@gmail.com', '5:30 AM - 10:30 PM', 'Zone 1, Beguito Nuevo, Libmanan', '../uploads/business_photo/Kuya_Kap_Olea_s_Kainian/68e42f4f48ea7_46ad673c-651a-4a70-9184-f5c5d04243fd.jpg', '13.728337331794574', '122.97929199858827', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430490adb5_\\ud83c\\udf54 KiaanEmpire\'s 2025 Social Pin_ Creative Food Menu Designs & Graphics.jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430490afff_Food Menu _ Food Menu Ideas.jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430490b170_Copy of restaurant menu digital display.jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430490b2c3_download (1).jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430490b46a_download.jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430d745d76_7374618b-5327-4594-b6fe-1fb4996a98cf.jpg\",\"uploads\\/business_gallery\\/Kuya_Kap_Olea_s_Kainian\\/68e430d745f6d_ea04e4ee-182a-4185-ad4d-055088fcf436.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e4307418797_\\ud83c\\udf54 KiaanEmpire\'s 2025 Social Pin_ Creative Food Menu Designs & Graphics.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43074189c9_Food Menu _ Food Menu Ideas.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4307418b05_Copy of restaurant menu digital display.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4307418c07_download (1).jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4307418dea_download.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Kuya_Kap_Olea_s_Kainian/68e430ddef58e_7374618b-5327-4594-b6fe-1fb4996a98cf.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(73, 86, 'LIBMANAN FOOD PARK', 'Restaurant', '', '', 'libmananfp02@gmail.com', '10:00 AM - 10:00 PM', ', Potot, Libmanan ', '../uploads/business_photo/LIBMANAN_FOOD_PARK/68e43159b4c73_413824435_122100183944161176_5012233389185212307_n.jpg', '13.68507580202885', '123.04257512544788', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/LIBMANAN_FOOD_PARK\\/68e4320280518_475784766_122197609502161176_1327127172453525924_n.jpg\",\"uploads\\/business_gallery\\/LIBMANAN_FOOD_PARK\\/68e4320280724_475839315_122197609580161176_4525232199695699840_n.jpg\",\"uploads\\/business_gallery\\/LIBMANAN_FOOD_PARK\\/68e43202808ce_475560509_122197609370161176_8846186219272089267_n.jpg\",\"uploads\\/business_gallery\\/LIBMANAN_FOOD_PARK\\/68e4320280a69_475803421_122197834724161176_2890045092028057188_n.jpg\",\"uploads\\/business_gallery\\/LIBMANAN_FOOD_PARK\\/68e4320280d36_475482322_122197855448161176_5482672165159184816_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e431eea05a2_Menu.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e431eea07ba_download (3).jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e431eea092c_Food Menu design.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e431eea0a90_Restaurant Menu Design.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e431eea0c27_download (2).jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/LIBMANAN_FOOD_PARK/68e43159b54f5_469391140_122188003340161176_2361010360714618212_n.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(74, 90, 'ESCAPES', 'Cafe', 'A relaxing and modern hangout place that serves coffee, milk tea, and comfort food — ideal for those looking to unwind, study, or spend quality time with friends in Libmanan.', '', 'escapes448@gmail.com', '4:00 PM - 10:00 PM', 'Zone 4, Potot, Libmanan', '../uploads/business_photo/ESCAPES/68e4397e3fd5c_449714404_850520043799250_8851477059496275185_n.jpg', '13.68843238740573', '123.0433690592878', 'closed', '2025-12-06 11:36:33', '[\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b62b78_554824908_1169264455258139_1869588397386779920_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b62cef_554421214_1170632175121367_2240010635425738558_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b62dc9_554508755_1170638675120717_2033462301998864266_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b62ea2_557368096_1171193131731938_324333008099693790_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b62f71_555007695_1171190435065541_795457250784811648_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b63054_555596284_1171190341732217_1656548259450949617_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b6313b_557014730_1172045831646668_6722092405904360745_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b6320e_555907388_1172045761646675_3710300555866319217_n (1).jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b632f0_555714344_1172045764980008_2971119600934580854_n.jpg\",\"uploads\\/business_gallery\\/ESCAPES\\/68e43a6b633c9_556652496_1172045728313345_7242939236554952036_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e43a767a4b1_490015437_1039860591531860_7493523537294100477_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43a767a6b1_490099395_1039860621531857_6652161607376862475_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43a767a860_489830714_1039860588198527_8063832378426014450_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/ESCAPES/68e4397e3ff45_482063990_1018126590371927_4089164921993582241_n.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(75, 88, 'HANDIONG EATERY', 'Fastfood', 'A local food stop that highlights traditional Bicolano and Filipino dishes, offering flavorful meals that reflect the rich culinary culture of Libmanan.', '09668809340', 'handiongeatery@gmail.com', '7:00 AM - 5:00 PM', ', Poblacion, Libmanan', '../uploads/business_photo/HANDIONG_EATERY/68e436f38231e_07a7787c-6bfb-427f-aef2-cc21aa065f82.jpg', '13.693872480424648', '123.06337583307598', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f3826e5_73d0ed19-2d63-408b-b7e7-d1f2ce0216e6.jpg\",\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f382829_bb520f01-fe15-431f-9fae-1a4f2134ba6d.jpg\",\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f382912_8a49a0e2-44ab-44cd-8192-8ff74e86cb24.jpg\",\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f382a33_701729d1-2f82-4bd9-8bdf-248eb995e56d.jpg\",\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f382b5a_03345b46-6c31-4e10-a67c-60f0d7c2f4e7.jpg\",\"uploads\\/business_gallery\\/HANDIONG_EATERY\\/68e436f382c74_07a7787c-6bfb-427f-aef2-cc21aa065f82.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e4372dcd8a1_download (6).jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4372dcdc92_Menu card design.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4372dcddcb_menu card.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4372dcdf05_download (5).jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4374d55ca2_menu card.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/HANDIONG_EATERY/68e436f382560_03345b46-6c31-4e10-a67c-60f0d7c2f4e7.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(76, 91, 'MJ FOOD HOUSE', 'Restaurant', '', '', 'mjfoodh@gmail.com', '8:00 AM - 9:00 PM', ', Poblacion, Libmanan ', '../uploads/business_photo/MJ_FOOD_HOUSE/68e4339bb0629_346640387_1351391032098372_1999027945797519787_n.jpg', '13.693538882273511', '123.06124077594784', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/MJ_FOOD_HOUSE\\/68e4339bb0add_524641877_746160484830570_1284495055204696316_n.jpg\",\"uploads\\/business_gallery\\/MJ_FOOD_HOUSE\\/68e4339bb0c6b_525396021_746160448163907_9179947239302914834_n.jpg\",\"uploads\\/business_gallery\\/MJ_FOOD_HOUSE\\/68e4339bb0dc3_524963787_746160411497244_2900002889148446622_n.jpg\",\"uploads\\/business_gallery\\/MJ_FOOD_HOUSE\\/68e4339bb0f05_525279675_746160378163914_7012086194213543971_n.jpg\",\"uploads\\/business_gallery\\/MJ_FOOD_HOUSE\\/68e4339bb1071_524819087_746160324830586_7385103644060013863_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa94b75_525773579_746163928163559_2824009073801442504_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa94f3b_525308849_746163894830229_1610290117579132963_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa952f4_524290243_746163794830239_2526398707122298119_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa956e3_515496569_746163644830254_5751381689338171960_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa95ba9_524570394_746160808163871_3227749784383618995_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa95dad_524718519_746160764830542_4201014094179554637_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa95f8b_524588465_746160731497212_6804381056794291484_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa9614e_524299578_746160694830549_8257124893936443571_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e433aa9632d_524823299_746160664830552_6231594212849092462_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/MJ_FOOD_HOUSE/68e4339bb0913_475747772_610533385059948_2462035392771427952_n.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(77, 82, 'AFFORDABEST TEA CAFE', 'Cafe', '', '', 'affordabestteacafe476@gmail.com', '7:00 AM - 9:00 PM', 'Zone 1 Abella St., Poblacion, LIBMANAN', '../uploads/business_photo/AFFORDABEST_TEA_CAFE/68e43885210bb_475738413_512943161819030_5246981024938900603_n.jpg', '13.692209761054315', '123.05768554387207', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ebfe99_473565019_548411924913455_9197867758774414834_n.jpg\",\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ec0124_473229450_548677491553565_7858079518325537846_n.jpg\",\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ec02ba_473545112_548677211553593_3322485086874704028_n.jpg\",\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ec043d_473581973_548677521553562_4628793304004812002_n.jpg\",\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ec0604_472950688_548677174886930_2751861169440800631_n.jpg\",\"uploads\\/business_gallery\\/AFFORDABEST_TEA_CAFE\\/68e4390ec07a4_470168846_476377852142228_8729787164412622792_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b4ee9_473188884_548424564912191_3086973768958622472_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b514b_473415273_548424484912199_620659556406427591_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b52ff_473389976_548424448245536_6926805378447759049_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b545d_473188516_548424478245533_6350199930338125741_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b55e9_472861000_548411818246799_1245659788604272711_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e43921b573e_473565019_548411924913455_9197867758774414834_n.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/AFFORDABEST_TEA_CAFE/68e43885212c4_469598302_474847958961884_450018585712042020_n.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(78, 83, 'PADING IKING EATERY', 'Fastfood', '', '', 'padingikingg22@gmail.com', 'Open 24 hours', 'Libmanan Road, Mabini, Libmanan', '../uploads/business_photo/PADING_IKING_EATERY/68e465f616d22_621e2ede-9cbf-4b05-9f42-dcf62fd7a2f0.jpg', '13.680557581648202', '123.00356824467576', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6171ef_76242200-9dd7-4597-aab0-eed1259581da.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f61735a_5897734a-791c-43e1-ad3b-fced586452fd.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f61746e_254a1a84-0147-4c50-b24c-a57c328d7f1f.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6175ac_b80bcaeb-502b-41e8-83a1-8c05d9206cc7.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6176d7_2b190f26-d4a2-4e34-993f-14f751e8e12b.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6177de_32b19f6c-0240-44ea-b715-48601d3d0ba6.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6178d5_e2d8fcb6-6dc4-4407-aab7-bb07c0a902a3.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f6179c9_4dacc25a-ed90-4f2b-9ab5-7cb47548b161.jpg\",\"uploads\\/business_gallery\\/PADING_IKING_EATERY\\/68e465f617b10_621e2ede-9cbf-4b05-9f42-dcf62fd7a2f0.jpg\"]', NULL, NULL, '../uploads/business_cover/PADING_IKING_EATERY/68e465f616efe_4dacc25a-ed90-4f2b-9ab5-7cb47548b161.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(79, 84, 'ARJOMEL RESTAURANT', 'Restaurant', '', '', 'arjomelresto@gmail.com', 'Open 24 hours', 'Liibmanan Road, Loba-loba, Libmanan', 'uploads/business_photo/ARJOMEL_RESTAURANT/692bd0f44495e_Screenshot 2025-11-30 130628.png', '13.689925834610978', '122.99823765024941', 'open', '2025-11-23 07:05:06', NULL, NULL, NULL, NULL, 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(80, 81, 'KUYA LEO EATERY', 'Restaurant', '', '', 'kuyaleoeatery@gmail.com', 'Open 24 hours', 'Liibmanan Road, Mabini, Libmanan', '../uploads/business_photo/KUYA_LEO_EATERY/68e435253fde3_406204884_122120214980088653_8721538639221202351_n.jpg', '13.677130401969151', '123.00528366777665', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/KUYA_LEO_EATERY\\/68e435254025c_468942726_122193082874088653_5401602333825014378_n.jpg\",\"uploads\\/business_gallery\\/KUYA_LEO_EATERY\\/68e43525403df_468950802_122193077684088653_8592525821655005685_n.jpg\",\"uploads\\/business_gallery\\/KUYA_LEO_EATERY\\/68e435254050b_468595951_122192901578088653_1025077855494766075_n.jpg\",\"uploads\\/business_gallery\\/KUYA_LEO_EATERY\\/68e435254066c_468640615_122192375792088653_7966792294319347832_n.jpg\",\"uploads\\/business_gallery\\/KUYA_LEO_EATERY\\/68e435254076c_468841727_122192376038088653_6864252525224914897_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/68e435371701d_Menu list flyer.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e435371723f_FOOD MENU.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4353717391_download (4).jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4353717503_SUMAN KHADKA.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/68e4353717655_FOOD MENU LIST DESIGN.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/KUYA_LEO_EATERY/68e4352540066_494197335_10026112250774640_3962099646523620833_n.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(81, 85, 'BOBLY FOOD PLAZA', 'Restaurant', 'Masarap dito.', '', 'boblysfoodplaza@gmail.com', 'Open 24 hours', 'Asian Highway 26, Bagacay, Libmanan', 'uploads/business_photo/BOBLY_S_FOOD_PLAZA/692bdad0e190f_e1a169ba-cb56-402b-904d-3fd23ac01a88.jpg', '13.648119161695416', '123.02291729820564', 'open', '2025-11-23 07:05:06', '[\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb06600d2_36446cf0-050e-4673-af75-faed4bb03a1b.jpg\",\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb0660305_f85d3e85-4d9c-417a-bcc7-d1508df77916.jpg\",\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb066044b_e1a169ba-cb56-402b-904d-3fd23ac01a88.jpg\",\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb066055f_01c00408-029e-43ca-a4ee-3f4b68dabb66.jpg\",\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb0660632_77b93bdf-207c-49a6-a5e9-16dc42cee10f.jpg\",\"uploads\\/business_gallery\\/BOBLY__039_S_FOOD_PLAZA\\/692bdb06606f7_f6df19d5-280d-4908-9013-4bc5b69b7fbc.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a025_e744f8f5-7256-4fc3-aa41-fd7d8690499d.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a17d_b5552d42-95b6-40c6-b6fd-511d5b2d7b82.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a2a5_39c00d49-752c-466e-bfa7-d483991ceea4.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a3b2_4e159b57-f28b-4ff3-a494-0a2d2ceaefab.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a4b4_1e5bdb1b-21bb-4542-86c8-836b03859679.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a5bd_293340ed-20dd-4e36-9ebb-640122b84ae7.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a6f0_c32c4b95-17df-4227-a389-e2856dc4bba2.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/BOBLY__039_S_FOOD_PLAZA\\/692bdaed4a81c_b5815e03-5b43-4b54-b225-8bb86cf0fc96.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/BOBLY_S_FOOD_PLAZA/692bdadacf8ce_72c1d281-af19-4625-91b3-de7aaa472800.jpg', 'Active', NULL, '0000-00-00', '2025-10-27', 0),
(82, 59, 'BIGBOY BITE', 'Restaurant', 'tasty foods available', '09104171409', 'bigboy@gmail.com', '8:00 AM - 9:00 PM', 'ZURBANO ST, Poblacion, LIBMANAN', '../uploads/business_photo/BIGBOY_BITE/68e48014c2ea6_sisig.jpg', '13.69293744736247', '123.06113086864221', 'open', '2025-11-23 07:05:06', NULL, NULL, NULL, '../uploads/business_cover/BIGBOY_BITE/68e48014c30c4_bo.jpg', 'Active', 'Customers praised the food for being incredibly delicious, describing it as a \"yummy overload\" that exceeded their expectations. However, some reviews lacked detailed feedback on specific aspects of the dining experience. While the overall taste and quality received high marks, there were no significant negative comments mentioned. Overall, the positive sentiments about the food stand out prominently in the reviews.', '2025-11-27', '2025-10-27', 0),
(84, 96, 'Miggy\'s Grill House', 'Restaurant', 'Masarap dito', '09487670202', 'miggyhouse@gmail.com', '9:00 AM-9:00 Pm', 'Zone 1, Libod I, Libmanan', '../uploads/business_photo/Miggy_s_Grill_House/6929684693a4a_1764320748796.jpg', '13.676992', '123.037901', 'open', '2025-11-29 21:00:21', '[\"uploads\\/business_gallery\\/Miggy_s_Grill_House\\/692968710a4d6_1764320897407.jpg\",\"uploads\\/business_gallery\\/Miggy_s_Grill_House\\/692968710a71b_1764320899840.jpg\",\"uploads\\/business_gallery\\/Miggy_s_Grill_House\\/692968710a8f1_1764320901857.jpg\",\"uploads\\/business_gallery\\/Miggy_s_Grill_House\\/692968710aa6a_1764320903957.jpg\",\"uploads\\/business_gallery\\/Miggy_s_Grill_House\\/692968710acc3_1764320910704.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/default\\/692966e20e02c_1764320943720.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20e388_1764320941155.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20e6ed_1764320935794.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20e96c_1764320933522.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20ecac_1764320931599.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20f01a_1764320929277.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20f401_1764320925001.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20f661_1764320922306.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20f8d4_1764320920000.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692966e20fb4a_1764320916933.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/Miggy_s_Grill_House/692968710a25f_Screenshot_20251128-171302.jpg', 'Active', NULL, '0000-00-00', '2025-10-28', 0),
(85, 97, 'StopOverMilkteaBar ', 'Cafe', '', '09123456789', 'StopOver232@gmail.com', '8:00 AM - 9:00 PM', ', Select Barangay, ', '../uploads/business_photo/StopOverMilkteaBar_/692969b1a23c6_FB_IMG_1764321477269.jpg', '13.694379', '123.061095', 'open', '2025-11-28 09:06:33', NULL, '[{\"path\":\"uploads\\/menu_images\\/default\\/692969797ae8e_FB_IMG_1764321489164.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692969797b0eb_FB_IMG_1764321494617.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692969797b2b3_FB_IMG_1764321497508.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692969797b4c0_FB_IMG_1764321501318.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692969797b68e_FB_IMG_1764321503697.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/default\\/692969797b885_FB_IMG_1764321506018.jpg\",\"hidden\":0}]', NULL, '../uploads/business_cover/StopOverMilkteaBar_/692969b1a25f3_FB_IMG_1764321491769.jpg', 'Active', NULL, '0000-00-00', '2025-11-28', 0),
(94, 106, 'Blackpepper', 'Restaurant', 'Good here at Blackpepper', '09456873214', 'blackpepper@gmail.com', '9am-7pm', 'Zone 1, Libod I, Libmanan', 'uploads/business_photo/Blackpepper/6932f030572b0_327305004_1225271731470041_8741327712913850108_n.jpg', '13.694455120460145', '123.0616933082557', 'open', '2025-12-05 14:45:35', '[\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c7420_493132810_1277742347685652_2554556137344003891_n.jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c75bb_493609377_1277780007681886_8834098676371682898_n.jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c76c4_496318694_1292363366223550_6310552261793220639_n.jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c7866_544828813_1390583383068214_2230091248178407510_n (1).jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c7950_544828813_1390583383068214_2230091248178407510_n.jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c7c21_571147195_1432036775589541_5035696690584108350_n.jpg\",\"uploads\\/business_gallery\\/Blackpepper\\/6932f096c7eec_513546371_1325265176266702_8592727094449937735_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f0662b3df_507665954_1319401286853091_6654343383735989215_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f0662b5d7_495211712_1319401600186393_1131990344206285205_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f06d1ae37_507665954_1319401286853091_6654343383735989215_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f06d1b049_495211712_1319401600186393_1131990344206285205_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f06d1b324_494420369_1319401570186396_4796468170969286741_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f06d1b6bc_493216117_1319401550186398_8043639003472652841_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f06d1b9d6_497042647_1319401533519733_1394294500489554071_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f080aed4d_493216117_1319401550186398_8043639003472652841_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f080af083_497042647_1319401533519733_1394294500489554071_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f080af342_492755792_1319401490186404_6928364183353931745_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f080af615_503805749_1316077530518800_3136350212656139385_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Blackpepper\\/6932f080af7d5_505763651_1316077533852133_6683888681879092392_n.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Blackpepper/6932f0383e3d9_503805749_1316077530518800_3136350212656139385_n.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(97, 107, 'Espresso Cafe', 'Cafe', '', '09867312456', 'espresso@gmail.com', '', ', Aslong, Libmanan', 'uploads/business_photo/Espresso_Cafe/6932f194b3ef7_FB_IMG_1764945985104.jpg', '13.692992869186169', '123.06006546777684', 'closed', '2025-12-05 14:51:20', '[\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a50e5_FB_IMG_1764946008199.jpg\",\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a5319_FB_IMG_1764946005549.jpg\",\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a5496_FB_IMG_1764946002761.jpg\",\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a55a3_FB_IMG_1764946000346.jpg\",\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a5694_FB_IMG_1764945998397.jpg\",\"uploads\\/business_gallery\\/Espresso_Cafe\\/6932f1c3a5815_FB_IMG_1764945995418.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e198315_FB_IMG_1764946048487.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e19858b_FB_IMG_1764946044650.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e198806_FB_IMG_1764946029605.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e1989f5_FB_IMG_1764946032673.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e198b93_FB_IMG_1764946024780.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e198d2d_FB_IMG_1764946022166.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e198eb8_FB_IMG_1764946020047.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Espresso_Cafe\\/6932f1e199010_FB_IMG_1764946017833.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Espresso_Cafe/6932f1a265816_FB_IMG_1764946048487.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(98, 110, 'MaCafe', 'Cafe', '', '09461275813', 'macafe@gmail.com', '', ', Bagumbayan, Libmanan', 'uploads/business_photo/MaCafe/6932f2cdc87ad_FB_IMG_1764946626512.jpg', '13.694329372841294', '123.06325831010547', 'closed', '2025-12-05 14:51:27', '[\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02c39c_FB_IMG_1764946525007.jpg\",\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02c618_FB_IMG_1764946522315.jpg\",\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02c7c9_FB_IMG_1764946519972.jpg\",\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02c973_FB_IMG_1764946517811.jpg\",\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02cae5_FB_IMG_1764946543917.jpg\",\"uploads\\/business_gallery\\/MaCafe\\/6932f2f02cc85_FB_IMG_1764946540626.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/MaCafe\\/6932f306d6e8e_FB_IMG_1764946531834.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/MaCafe\\/6932f306d6ff0_FB_IMG_1764946508247.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/MaCafe\\/6932f306d71e3_FB_IMG_1764946499745.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/MaCafe\\/6932f306d7443_FB_IMG_1764946505804.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/MaCafe\\/6932f306d75ef_FB_IMG_1764946495968.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/MaCafe/6932f2d22e090_Screenshot_2025-12-05-22-54-43-05_a23b203fd3aafc6dcb84e438dda678b6.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(99, 108, 'Adoy&#039;s Kinalas', 'Restaurant', 'Madya na kamo', '09456983219', 'adoys@gmail.com', '8:00 AM - 9:00 PM', 'Puro-Batia, Libmanan', 'uploads/business_photo/Adoy_s_Kinalas/6932f515ec447_Screenshot_20251205-230114.jpg', '13.669256910834552', '123.00985925243383', 'open', '2025-12-05 14:51:35', '[\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0be4b_Screenshot_20251205-223956.png\",\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0c396_Screenshot_20251205-223954.png\",\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0c8f3_Screenshot_20251205-223946.png\",\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0cdb1_Screenshot_20251205-223921.png\",\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0d25d_download.jpeg\",\"uploads\\/business_gallery\\/Adoy_s_Kinalas\\/6932f54e0d322_images (4).jpeg\"]', '[{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f637583c2_images (8).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63758544_images (7).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63758652_images (6).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f6375881d_images (5).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63758920_images (4).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63758a08_download.jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63a08413_images (8).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63a085d2_images (7).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63a08719_images (6).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63a0886c_images (5).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63aea383_images (6).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Adoy__039_s_Kinalas\\/6932f63d1f9aa_images (6).jpeg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Adoy_s_Kinalas/6932f519cd8f1_images (8).jpeg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(100, 109, 'Kkopitea', 'Cafe', 'Masarap Dito', '09123456789', 'kkopitea@gmail.com', '7:00 AM - 7:00 PM', ', Bahao, Libmanan', 'uploads/business_photo/Kkopitea/6932f2e97e4d2_592951093_822280837235430_1860515009432093350_n.jpg', '13.693429840547205', '123.06154379476273', 'open', '2025-12-05 23:04:12', '[\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753763_584961782_815022497961264_4565164516819709841_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f347538cc_585495023_814265041370343_6295574497717979979_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f347539ab_585405438_815023057961208_6441259084970694103_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753a8c_588970352_818055274324653_7993656433452986925_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753b67_591235657_821636660633181_8437536553215109846_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753c39_592262769_824654746998039_967473317247322351_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753d12_592405713_824654786998035_5920522058273322887_n.jpg\",\"uploads\\/business_gallery\\/Kkopitea\\/6932f34753de1_592383928_824654550331392_1330271091513179537_n.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358ae6d6_483920009_618186800978169_2742088806763880694_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358ae821_483486785_617514174378765_1236165836008851870_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358ae902_495430170_661898986606950_2704656104198455763_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358aea08_501872101_675443558585826_1228387320837031151_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358aeade_514701482_701421445988037_2838720415921229950_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358aebba_573565102_800152539448260_3400607881511680008_n.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kkopitea\\/6932f358aec9a_574906333_803774942419353_700783692880256516_n.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Kkopitea/6932f2eee5b86_578273915_804447509018763_1230115012351333238_n.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(101, 112, 'Kainan Sa Bagumbayan', 'Restaurant', '', '09432516794', 'kainansabagumbayan@gmail.com', '', ', Bagumbayan, Libmanan', 'uploads/business_photo/Kainan_Sa_Bagumbayan/6932f4761b86a_FB_IMG_1764946820882.jpg', '13.698284565427684', '123.06032792359835', 'closed', '2025-12-05 15:03:51', '[\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f491c9dad_FB_IMG_1764946874297.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f491c9fe7_FB_IMG_1764946871716.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f492efc20_FB_IMG_1764946874297.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f492efeac_FB_IMG_1764946871716.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996d530_FB_IMG_1764946874297.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996d7fe_FB_IMG_1764946871716.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996da04_FB_IMG_1764946867080.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996dc4f_FB_IMG_1764946861648.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996deb3_FB_IMG_1764946858803.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996e0ed_FB_IMG_1764946855931.jpg\",\"uploads\\/business_gallery\\/Kainan_Sa_Bagumbayan\\/6932f4996e2f7_FB_IMG_1764946851471.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Kainan_Sa_Bagumbayan\\/6932f4a80da27_Screenshot_2025-12-05-23-00-37-00_a23b203fd3aafc6dcb84e438dda678b6.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Kainan_Sa_Bagumbayan\\/6932f4ac13acf_Screenshot_2025-12-05-23-00-37-00_a23b203fd3aafc6dcb84e438dda678b6.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Kainan_Sa_Bagumbayan/6932f47f02818_Screenshot_2025-12-05-23-00-37-00_a23b203fd3aafc6dcb84e438dda678b6.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0);
INSERT INTO `fb_owner` (`fbowner_id`, `user_id`, `fb_name`, `fb_type`, `fb_description`, `fb_phone_number`, `fb_email_address`, `fb_operating_hours`, `fb_address`, `fb_photo`, `fb_latitude`, `fb_longitude`, `fb_status`, `status_last_updated`, `fb_gallery`, `menu_images`, `menu_manual`, `fb_cover`, `activation`, `summarize_review`, `last_summarize_date`, `created_at`, `is_new`) VALUES
(102, 113, 'Jaynaro Pizza', 'Restaurant', '', '09613254679', 'jaynaropizza@gmail.com', '', '4, Begajo Sur, Libmanan', 'uploads/business_photo/Jaynaro_Pizza/6932f6f1d07f1_Screenshot_2025-12-05-23-07-30-84_3d9111e2d3171bf4882369f490c087b4.jpg', '13.728110478328675', '123.05576148127008', 'closed', '2025-12-05 15:12:50', '[\"uploads\\/business_gallery\\/Jaynaro_Pizza\\/6932f721d76d0_Screenshot_2025-12-05-23-10-07-73_3d9111e2d3171bf4882369f490c087b4.jpg\",\"uploads\\/business_gallery\\/Jaynaro_Pizza\\/6932f721d7f8f_Screenshot_2025-12-05-23-09-56-99_3d9111e2d3171bf4882369f490c087b4.jpg\",\"uploads\\/business_gallery\\/Jaynaro_Pizza\\/6932f721d82be_Screenshot_2025-12-05-23-08-08-71_3d9111e2d3171bf4882369f490c087b4.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Jaynaro_Pizza\\/6932f82c09b5c_Screenshot_2025-12-05-23-10-07-73_3d9111e2d3171bf4882369f490c087b4.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Jaynaro_Pizza\\/6932f82c0a39e_Screenshot_2025-12-05-23-09-56-99_3d9111e2d3171bf4882369f490c087b4.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Jaynaro_Pizza\\/6932f82c0a6a7_Screenshot_2025-12-05-23-08-08-71_3d9111e2d3171bf4882369f490c087b4.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Jaynaro_Pizza\\/6932f82c0aa7e_Screenshot_2025-12-05-23-07-58-87_3d9111e2d3171bf4882369f490c087b4.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Jaynaro_Pizza/6932f6ec1f1fd_Screenshot_2025-12-05-23-07-30-84_3d9111e2d3171bf4882369f490c087b4.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(103, 115, 'Batot Bulaluhan and kinalasan Branch', 'Restaurant', '', '09465348798', 'batot@gmail.com', '', 'zone 4 , Bahay, Libmanan', 'uploads/business_photo/Batot_Bulaluhan_and_kinalasan_Branch/6932f90cccb40_FB_IMG_1764948033785.jpg', '13.659468169146185', '123.01586196777639', 'closed', '2025-12-05 15:13:21', '[\"uploads\\/business_gallery\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f9b6a8141_FB_IMG_1764948152843.jpg\",\"uploads\\/business_gallery\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f9b6a83fe_FB_IMG_1764948136677.jpg\",\"uploads\\/business_gallery\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f9b6a85c2_FB_IMG_1764948071185.jpg\",\"uploads\\/business_gallery\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f9b6a8817_FB_IMG_1764948068899.jpg\"]', '[{\"path\":\"uploads\\/menu_images\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f965b5ea6_FB_IMG_1764948084859.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f965b609a_FB_IMG_1764948082950.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f965b62d8_FB_IMG_1764948076719.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f965b64e9_FB_IMG_1764948080751.jpg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Batot_Bulaluhan_and_kinalasan_Branch\\/6932f965b674b_FB_IMG_1764948068899.jpg\",\"hidden\":0}]', NULL, 'uploads/business_cover/Batot_Bulaluhan_and_kinalasan_Branch/6932f914e0c07_Screenshot_2025-12-05-23-20-48-74_a23b203fd3aafc6dcb84e438dda678b6.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(104, 114, 'Aljos Food House', 'Restaurant', 'Masarap na, malasa pa', '09054514512', 'aljosfoodhouse@gmail.com', '9:00 AM-9:00 PM', 'Zone 3, Planza, Libmanan', 'uploads/business_photo/Aljos_Food_House/6932f8566fbf6_Screenshot_20251205-231819.jpg', '13.661031191856665', '123.01461461010497', 'open', '2025-12-05 23:20:15', '[\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb688d_download.jpeg\",\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb69d5_images (4).jpeg\",\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb6ae8_images (5).jpeg\",\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb6c26_images (6).jpeg\",\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb6e31_images (7).jpeg\",\"uploads\\/business_gallery\\/Aljos_Food_House\\/6932f89eb6f69_Screenshot_20251205-230124.png\"]', '[{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba29aa7_images (8).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba29ccd_images (7).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba29e47_images (6).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba29fab_images (5).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba2a0e9_images (4).jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba2a21e_download.jpeg\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba2a318_Screenshot_20251205-230124.png\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba2aa57_Screenshot_20251205-230121.png\",\"hidden\":0},{\"path\":\"uploads\\/menu_images\\/Aljos_Food_House\\/6932f8ba2aef7_Screenshot_20251205-230117.png\",\"hidden\":0}]', NULL, 'uploads/business_cover/Aljos_Food_House/6932f860cb199_Screenshot_20251205-231822.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0),
(105, 111, 'Roy\'s Bulalo', 'Fastfood', '', '09468572340', 'roysbulalo@gmail.com', '', 'Zone 4, Bahay, Libmanan', 'uploads/business_photo/Roy_s_Bulalo/6933c3be865b2_Screenshot_20251206-134641.jpg', '13.699904', '123.065652', 'closed', '2025-12-05 15:14:31', '[\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e29623f_Screenshot_20251206-134343.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e2963ea_Screenshot_20251206-134348.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e29653b_Screenshot_20251206-134351.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e296633_Screenshot_20251206-134356.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e296767_Screenshot_20251206-134400.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e2969b9_Screenshot_20251206-134403.jpg\",\"uploads\\/business_gallery\\/Roy_s_Bulalo\\/6933c3e296b09_Screenshot_20251206-134408.jpg\"]', NULL, NULL, 'uploads/business_cover/Roy_s_Bulalo/6933c3c8c02a6_Screenshot_20251206-134343.jpg', 'Active', NULL, '0000-00-00', '2025-12-05', 0);

-- --------------------------------------------------------

--
-- Table structure for table `lessor_details`
--

CREATE TABLE `lessor_details` (
  `lessor_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `lessor_fullname` varchar(255) NOT NULL,
  `lessor_fulladdress` text NOT NULL,
  `lessor_mobile_no` varchar(20) NOT NULL,
  `lessor_email_address` varchar(50) NOT NULL,
  `montly_rental` int(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `lessor_details`
--

INSERT INTO `lessor_details` (`lessor_id`, `ba_id`, `lessor_fullname`, `lessor_fulladdress`, `lessor_mobile_no`, `lessor_email_address`, `montly_rental`) VALUES
(39, 40, '', '', '', '', 0),
(41, 42, 'ADELFA L. NOLASCO', 'ZONE 4, APAD POTOT, LIBMANAN, CAMARINES SUR', '099194991912', 'nolascoadelfa@gmail.com', 12000),
(42, 43, '', '', '', '', 0),
(43, 44, '', '', '', '', 0),
(44, 45, '', '', '', '', 0),
(45, 46, '', '', '', '', 0),
(46, 47, '', '', '', '', 0),
(47, 48, '', '', '', '', 0),
(48, 49, '', '', '', '', 0),
(49, 50, '', '', '', '', 0),
(50, 51, '', '', '', '', 0),
(55, 57, '', '', '', '', 0),
(59, 62, '', '', '', '', 0),
(60, 63, '', '', '', '', 0),
(61, 64, '', '', '', '', 0),
(62, 65, '', '', '', '', 0),
(63, 66, '', '', '', '', 0),
(64, 67, '', '', '', '', 0),
(65, 68, '', '', '', '', 0),
(66, 69, '', '', '', '', 0),
(67, 74, '', '', '', '', 0);

-- --------------------------------------------------------

--
-- Table structure for table `menus`
--

CREATE TABLE `menus` (
  `menu_id` int(11) NOT NULL,
  `fbowner_id` int(11) NOT NULL,
  `category_id` int(11) NOT NULL,
  `menu_name` varchar(255) NOT NULL,
  `menu_price` decimal(10,2) NOT NULL,
  `menu_image` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp(),
  `is_available` tinyint(4) NOT NULL DEFAULT 1
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menus`
--

INSERT INTO `menus` (`menu_id`, `fbowner_id`, `category_id`, `menu_name`, `menu_price`, `menu_image`, `created_at`, `is_available`) VALUES
(24, 65, 7, 'BlackPink', 66.00, 'uploads/menus/1759719430_470219016_553262890953846_1878272929451310001_n__1_.jpg', '2025-10-06 02:57:10', 1),
(25, 65, 7, 'Supreme Moca', 52.00, 'uploads/menus/1759719430_470179582_553262944287174_74121002525953830_n.jpg', '2025-10-06 02:57:10', 1),
(26, 65, 7, 'Kape KMJS', 60.00, 'uploads/menus/1759719430_470181083_553262854287183_8382351957814583111_n.jpg', '2025-10-06 02:57:10', 1),
(27, 65, 7, 'Kara- Van', 70.00, 'uploads/menus/1759719430_470197335_553263004287168_5205597590998173795_n.jpg', '2025-10-06 02:57:10', 1),
(28, 65, 7, 'Boss Brew', 66.00, 'uploads/menus/1759719430_470227426_553262997620502_7419328295758939614_n.jpg', '2025-10-06 02:57:10', 1),
(29, 65, 7, 'Super Choco', 59.00, 'uploads/menus/1759719607_470219230_553263014287167_2217011377108276737_n.jpg', '2025-10-06 03:00:07', 1),
(30, 65, 7, 'All menu', 0.00, 'uploads/menus/1759719723_524902327_753474390968850_6090822071602298583_n.jpg', '2025-10-06 03:02:03', 0),
(33, 49, 12, 'Hopia', 60.00, 'uploads/menus/1759728290_547469804_1200574925431131_151454067835143816_n.jpg', '2025-10-06 05:24:50', 1),
(43, 39, 14, 'kaldereta', 99.00, 'uploads/menus/1759806686_bk.jpg', '2025-10-07 03:11:26', 1),
(44, 39, 15, 'Adobo', 99.00, 'uploads/menus/1759806761_ca.jpg', '2025-10-07 03:12:41', 1),
(50, 56, 17, 'Combo 1', 129.00, 'uploads/menus/1764999655_586339215_1164225925895364_7486775450535149433_n.jpg', '2025-12-06 05:40:55', 1),
(51, 56, 18, 'Chicken Inasal', 148.00, 'uploads/menus/1764999655_586520588_1164225839228706_6937300494928804687_n.jpg', '2025-12-06 05:40:55', 1),
(52, 56, 18, 'Barbeque', 129.00, 'uploads/menus/1764999655_587793624_1164225869228703_2400406899318217092_n.jpg', '2025-12-06 05:40:55', 1),
(53, 56, 17, 'Combo 2', 298.00, 'uploads/menus/1764999655_550449609_1111356384515652_6031014218475302338_n.jpg', '2025-12-06 05:40:55', 1),
(54, 56, 16, 'Milktea', 98.00, 'uploads/menus/1764999655_191251342_104823105152100_8630320875067217269_n.jpg', '2025-12-06 05:40:55', 1),
(55, 105, 19, 'Chapseoy', 50.00, 'uploads/menus/1765000336_Screenshot_20251206-134343.jpg', '2025-12-06 05:52:16', 1),
(56, 105, 20, 'Green Ice Candy', 10.00, 'uploads/menus/1765000336_Screenshot_20251206-134348.jpg', '2025-12-06 05:52:16', 1),
(57, 105, 21, 'Burger', 50.00, 'uploads/menus/1765000336_Screenshot_20251206-134351.jpg', '2025-12-06 05:52:16', 1),
(58, 105, 19, 'Ulam', 50.00, 'uploads/menus/1765000336_Screenshot_20251206-134356.jpg', '2025-12-06 05:52:16', 1),
(59, 105, 19, 'Fish', 70.00, 'uploads/menus/1765000336_Screenshot_20251206-134400.jpg', '2025-12-06 05:52:16', 1),
(60, 105, 20, 'Chocolate Ice Candy', 10.00, 'uploads/menus/1765000336_Screenshot_20251206-134403.jpg', '2025-12-06 05:52:16', 1),
(61, 105, 19, 'Natong', 50.00, 'uploads/menus/1765000336_Screenshot_20251206-134408.jpg', '2025-12-06 05:52:16', 1),
(63, 99, 22, 'Combo 1', 200.00, 'uploads/menus/1765291604_download__19_.jpg', '2025-12-09 14:46:44', 1),
(64, 99, 22, 'Kinalas Twin', 150.00, 'uploads/menus/1765291604_download__17_.jpg', '2025-12-09 14:46:44', 1),
(65, 99, 22, 'Special Kinalas', 180.00, 'uploads/menus/1765291604_download__16_.jpg', '2025-12-09 14:46:44', 1),
(66, 99, 22, 'Little Kinalas', 80.00, 'uploads/menus/1765291604_download__18_.jpg', '2025-12-09 14:46:44', 1),
(67, 99, 22, 'Combo 2', 50.00, 'uploads/menus/1765291604_images.jpg', '2025-12-09 14:46:44', 1),
(68, 104, 23, 'Ulam 1', 60.00, 'uploads/menus/1765291881_6932f8ba29fab_images__5_.jpeg', '2025-12-09 14:51:21', 1),
(69, 104, 23, 'Ulam 2', 50.00, 'uploads/menus/1765291881_6932f8ba2a21e_download.jpeg', '2025-12-09 14:51:21', 1),
(70, 104, 23, 'Ulam 3', 70.00, 'uploads/menus/1765291881_6932f8ba29ccd_images__7_.jpeg', '2025-12-09 14:51:21', 1),
(71, 104, 23, 'Ulam 4', 100.00, 'uploads/menus/1765291881_6932f8ba2a0e9_images__4_.jpeg', '2025-12-09 14:51:21', 1),
(72, 77, 24, 'Strawberry', 50.00, 'uploads/menus/1765322479_469862907_475850962194917_1465952532643312931_n.jpg', '2025-12-09 23:21:19', 1),
(73, 77, 24, 'Mango', 50.00, 'uploads/menus/1765322479_469893183_475851088861571_128146651086869438_n.jpg', '2025-12-09 23:21:19', 1),
(74, 77, 24, 'Matcha', 60.00, 'uploads/menus/1765322479_469824891_475850945528252_976390748662961667_n.jpg', '2025-12-09 23:21:19', 1),
(75, 77, 24, 'Ube', 60.00, 'uploads/menus/1765322479_469820602_475851332194880_4929794263983919822_n.jpg', '2025-12-09 23:21:19', 1),
(76, 77, 24, 'Okinawa', 40.00, 'uploads/menus/1765322479_469894171_475851032194910_7130122526808529728_n.jpg', '2025-12-09 23:21:19', 1),
(77, 79, 25, 'Chicken Fried', 60.00, 'uploads/menus/1765322765_download__24_.jpg', '2025-12-09 23:26:05', 1),
(78, 79, 25, 'Adobo', 60.00, 'uploads/menus/1765322765_download__22_.jpg', '2025-12-09 23:26:05', 1),
(79, 79, 25, 'Igado', 60.00, 'uploads/menus/1765322765_download__20_.jpg', '2025-12-09 23:26:05', 1),
(80, 79, 25, 'Menudo', 60.00, 'uploads/menus/1765322765_download__23_.jpg', '2025-12-09 23:26:05', 1),
(81, 79, 25, 'Kaldereta', 60.00, 'uploads/menus/1765322765_download__21_.jpg', '2025-12-09 23:26:05', 1),
(82, 65, 7, 'Matcha', 50.00, 'uploads/menus/1765323010_596807027_122150858492905410_2891125775022684663_n.jpg', '2025-12-09 23:30:10', 0),
(83, 65, 7, 'Okinawa', 50.00, 'uploads/menus/1765323010_595286806_122151093422905410_6389016679448500832_n.jpg', '2025-12-09 23:30:10', 1),
(84, 65, 7, 'Praff', 50.00, 'uploads/menus/1765323010_597352596_122151490418905410_1025909408037523924_n.jpg', '2025-12-09 23:30:10', 1),
(85, 65, 7, 'Fruit tea', 50.00, 'uploads/menus/1765323010_596804399_122151086018905410_6422831902413666706_n.jpg', '2025-12-09 23:30:10', 1),
(86, 65, 7, 'Ube', 50.00, 'uploads/menus/1765323010_596413738_122151276920905410_5153916785968459795_n.jpg', '2025-12-09 23:30:10', 1);

-- --------------------------------------------------------

--
-- Table structure for table `menu_categories`
--

CREATE TABLE `menu_categories` (
  `category_id` int(11) NOT NULL,
  `fbowner_id` int(11) NOT NULL,
  `category_name` varchar(100) NOT NULL,
  `created_at` timestamp NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `menu_categories`
--

INSERT INTO `menu_categories` (`category_id`, `fbowner_id`, `category_name`, `created_at`) VALUES
(4, 65, 'Meals', '2025-10-05 12:33:50'),
(5, 65, 'Snacks', '2025-10-05 12:37:24'),
(6, 65, 'Desserts', '2025-10-05 12:37:24'),
(7, 65, 'Drinks', '2025-10-05 12:37:24'),
(8, 51, 'Meals', '2025-10-05 18:17:51'),
(9, 51, 'Snacks', '2025-10-06 01:32:26'),
(10, 51, 'Desserts', '2025-10-06 01:32:26'),
(11, 53, 'Meals', '2025-10-06 03:13:52'),
(12, 49, 'Snacks', '2025-10-06 05:24:50'),
(13, 51, 'Drinks', '2025-10-06 12:38:04'),
(14, 39, 'Meals', '2025-10-07 03:11:26'),
(15, 39, 'Snacks', '2025-10-07 03:12:41'),
(16, 56, 'Drinks', '2025-11-27 05:55:52'),
(17, 56, 'Snacks', '2025-11-30 03:12:20'),
(18, 56, 'Meals', '2025-11-30 15:28:18'),
(19, 105, 'Meals', '2025-12-06 05:52:16'),
(20, 105, 'Desserts', '2025-12-06 05:52:16'),
(21, 105, 'Snacks', '2025-12-06 05:52:16'),
(22, 99, 'Meals', '2025-12-09 14:46:44'),
(23, 104, 'Meals', '2025-12-09 14:51:21'),
(24, 77, 'Desserts', '2025-12-09 23:21:19'),
(25, 79, 'Meals', '2025-12-09 23:26:05');

-- --------------------------------------------------------

--
-- Table structure for table `messages`
--

CREATE TABLE `messages` (
  `id` int(11) NOT NULL,
  `sender_id` int(11) NOT NULL,
  `receiver_id` int(11) NOT NULL,
  `message` text NOT NULL,
  `timestamp` datetime DEFAULT current_timestamp(),
  `is_read` tinyint(4) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `messages`
--

INSERT INTO `messages` (`id`, `sender_id`, `receiver_id`, `message`, `timestamp`, `is_read`) VALUES
(2, 41, 27, 'hello admin', '2025-10-05 01:22:47', 1),
(3, 41, 27, 'hello', '2025-10-05 01:26:36', 1),
(4, 41, 27, 'hi admin', '2025-10-05 01:32:21', 1),
(5, 41, 27, 'admin reply', '2025-10-05 01:33:54', 1),
(6, 41, 27, 'admin love u', '2025-10-05 01:38:44', 1),
(7, 41, 27, 'love u admin', '2025-10-05 01:45:30', 1),
(8, 41, 27, 'admin love u', '2025-10-05 01:46:43', 1),
(9, 41, 27, 'admin love dale', '2025-10-05 02:04:44', 1),
(10, 41, 27, 'admin love u', '2025-10-05 02:06:32', 1),
(11, 41, 27, 'hi admin', '2025-10-05 02:08:39', 1),
(12, 41, 27, 'roman pogi', '2025-10-05 02:08:46', 1),
(13, 41, 27, 'admin reply', '2025-10-05 02:12:32', 1),
(14, 41, 27, 'admin reply', '2025-10-05 02:16:52', 1),
(17, 41, 27, 'i love you', '2025-10-05 02:21:31', 1),
(18, 41, 27, 'hello admin', '2025-10-05 02:22:21', 1),
(19, 41, 27, 'hello', '2025-10-05 02:22:56', 1),
(23, 41, 41, 'bigbrews owner cant see this', '2025-10-05 02:39:45', 1),
(24, 41, 27, 'hi admin', '2025-10-05 02:42:03', 1),
(25, 41, 41, 'hi', '2025-10-05 02:59:51', 1),
(26, 41, 41, 'hihihi', '2025-10-05 03:25:01', 1),
(27, 41, 27, 'hhhh', '2025-10-05 03:25:13', 1),
(29, 59, 27, 'hello dale', '2025-10-05 03:55:12', 1),
(30, 27, 46, 'Hey', '2025-10-05 04:00:52', 1),
(31, 41, 41, 'hi jhom', '2025-10-05 04:16:50', 1),
(33, 44, 27, 'hi bplo', '2025-10-05 04:18:08', 1),
(34, 44, 44, 'hi', '2025-10-05 04:18:25', 1),
(35, 59, 27, 'hello', '2025-10-05 04:24:07', 1),
(36, 59, 59, 'hi', '2025-10-05 04:24:20', 1),
(37, 27, 51, 'Hello', '2025-10-05 06:10:22', 1),
(38, 27, 44, 'hello nigga', '2025-10-05 08:31:03', 1),
(39, 27, 44, 'how are you', '2025-10-05 08:31:27', 1),
(40, 44, 27, 'Im fine', '2025-10-05 08:31:38', 1),
(41, 44, 27, 'Hey', '2025-10-05 08:46:02', 1),
(42, 44, 27, 'I love you', '2025-10-05 08:52:29', 1),
(43, 27, 44, 'i love you too', '2025-10-05 08:52:44', 1),
(44, 44, 27, 'Love', '2025-10-05 08:56:41', 1),
(45, 51, 27, 'What can we do for you?', '2025-10-05 11:09:45', 1),
(46, 43, 27, 'Jhum love dale', '2025-10-06 01:37:03', 1),
(47, 43, 27, 'Hello', '2025-10-06 01:40:30', 1),
(48, 27, 43, 'Okay baga??', '2025-10-06 01:41:29', 1),
(49, 43, 27, 'Iyo palan haha nawara kaya si last convo', '2025-10-06 01:46:12', 1),
(50, 27, 43, 'Nawara?', '2025-10-06 01:46:23', 1),
(51, 43, 27, 'Check mo daw sa db kung yaon pa si last convo before ini', '2025-10-06 01:46:51', 1),
(52, 43, 27, 'jhum', '2025-10-06 02:09:11', 1),
(53, 27, 27, 'jhum', '2025-10-06 02:09:53', 1),
(54, 27, 43, 'Goods na baga', '2025-10-06 02:11:07', 1),
(55, 27, 43, 'Dae man naapektuhan si code', '2025-10-06 02:11:21', 1),
(56, 41, 27, 'jhum', '2025-10-06 02:11:31', 1),
(57, 27, 41, 'hi', '2025-10-06 02:12:07', 1),
(58, 41, 27, 'low', '2025-10-06 02:12:17', 1),
(59, 41, 27, 'nababasa moni jhum?', '2025-10-06 02:13:41', 1),
(60, 27, 41, 'eu', '2025-10-06 02:14:14', 1),
(61, 27, 41, 'accessible baga', '2025-10-06 02:14:21', 1),
(62, 41, 27, 'nice', '2025-10-06 02:14:34', 1),
(63, 27, 51, 'hello', '2025-10-06 08:26:58', 1),
(64, 51, 27, 'hi', '2025-10-06 08:27:09', 1),
(65, 27, 59, 'hi', '2025-10-06 10:41:28', 1),
(66, 41, 27, 'hello', '2025-10-06 14:33:23', 1),
(67, 41, 27, 'hi', '2025-10-06 14:33:43', 1),
(68, 41, 27, 'hey', '2025-10-06 14:34:09', 1),
(69, 41, 27, 'hey', '2025-10-06 14:36:22', 1),
(70, 41, 27, 'ROMAAAAAAAAAN', '2025-10-06 14:36:43', 1),
(71, 53, 27, 'Hey', '2025-11-21 10:02:29', 1),
(72, 27, 41, 'hi', '2025-11-21 16:51:00', 1),
(73, 27, 41, 'HI', '2025-11-21 16:51:06', 1),
(74, 41, 27, 'Hello', '2025-11-27 13:40:26', 1),
(75, 41, 27, 'Hello', '2025-11-27 13:40:36', 1),
(76, 41, 27, 'Hey', '2025-11-27 13:40:52', 1),
(77, 41, 27, 'Heyy', '2025-11-27 13:41:08', 1),
(78, 27, 27, 'Hello', '2025-11-27 13:42:11', 1),
(79, 41, 27, 'Hey', '2025-11-27 13:43:56', 1),
(80, 41, 41, 'Hello', '2025-11-27 13:46:14', 1),
(81, 51, 27, 'Hello', '2025-11-27 13:47:41', 1),
(82, 51, 27, 'Kamusta', '2025-11-27 13:47:57', 1),
(83, 27, 51, 'nagana pa man baga ata?', '2025-11-27 13:51:08', 1),
(84, 27, 51, 'Goods ka na ba?', '2025-11-27 15:48:42', 1),
(85, 51, 27, 'eyy okay na', '2025-11-27 15:49:15', 1),
(86, 27, 41, 'hi', '2025-11-28 06:43:06', 1),
(87, 27, 27, 'hi', '2025-11-28 06:43:21', 1),
(88, 41, 27, 'Hello', '2025-11-28 06:43:34', 1),
(89, 41, 27, 'Hi', '2025-11-29 05:35:16', 1),
(90, 27, 41, 'What can I do for you', '2025-11-29 05:36:56', 1),
(91, 41, 27, 'ah eh', '2025-11-29 06:49:47', 1),
(92, 41, 27, 'hey', '2025-11-29 14:16:14', 1),
(93, 41, 41, 'Hey', '2025-11-30 00:45:34', 1),
(94, 41, 84, 'Hello', '2025-11-30 00:46:09', 1),
(95, 27, 51, 'Hello po', '2025-11-30 03:23:02', 1),
(96, 51, 27, 'Hello', '2025-11-30 04:30:31', 1),
(97, 27, 51, 'Hello', '2025-11-30 08:51:41', 1),
(98, 27, 44, 'Hello', '2025-11-30 08:52:03', 1),
(99, 27, 44, 'Hi there', '2025-11-30 09:11:55', 1),
(100, 44, 27, 'okay na to diba?', '2025-11-30 17:53:28', 1),
(101, 27, 44, 'yes sir, okay na okay na', '2025-11-30 17:54:26', 1),
(102, 27, 41, 'hello', '2025-11-30 18:05:46', 1),
(103, 51, 27, 'Okay na ba?', '2025-11-30 20:22:11', 1),
(104, 27, 59, 'Hello', '2025-11-30 20:52:36', 1),
(105, 41, 27, 'Hi din po', '2025-11-30 20:53:10', 1),
(106, 27, 41, 'Proceed to the office', '2025-11-30 21:28:24', 1),
(107, 51, 27, 'Hello po', '2025-12-02 07:28:43', 1),
(108, 27, 51, 'vbgfds', '2025-12-02 07:28:53', 1),
(109, 27, 59, 'hello', '2025-12-02 16:36:30', 1),
(110, 27, 51, 'hi', '2025-12-02 16:36:43', 1),
(111, 41, 27, 'hi', '2025-12-03 15:51:10', 1),
(112, 27, 51, 'blah blah', '2025-12-06 10:49:11', 1),
(113, 51, 27, 'hi hello', '2025-12-06 10:58:56', 1),
(114, 41, 27, 'Still working?', '2025-12-09 18:39:59', 1),
(115, 41, 27, 'Yes?', '2025-12-09 18:40:12', 1),
(116, 27, 41, 'Yes sir', '2025-12-09 18:41:36', 1),
(117, 51, 27, 'Good morning', '2025-12-10 09:44:48', 1),
(118, 41, 27, 'hi', '2025-12-10 10:37:31', 1),
(119, 41, 27, 'Good morning BPLO DEC 10,2025', '2025-12-10 10:38:11', 1),
(120, 27, 41, 'approved', '2025-12-10 10:38:17', 1);

-- --------------------------------------------------------

--
-- Table structure for table `notification`
--

CREATE TABLE `notification` (
  `notification_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `type` varchar(255) NOT NULL,
  `ref_id` int(100) NOT NULL,
  `message` varchar(255) NOT NULL,
  `created_at` datetime NOT NULL,
  `is_read` int(11) NOT NULL DEFAULT 0
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `notification`
--

INSERT INTO `notification` (`notification_id`, `user_id`, `type`, `ref_id`, `message`, `created_at`, `is_read`) VALUES
(2, 96, 'Registration', 57, 'submit New Application.', '2025-11-28 09:03:01', 1),
(4, 97, 'Renewal', 59, 'submit Renewal Application.', '2025-11-28 09:11:54', 1),
(7, 106, 'Registration', 62, 'submit New Application.', '2025-12-05 14:06:01', 1),
(8, 107, 'Registration', 64, 'submit New Application.', '2025-12-05 14:09:58', 0),
(9, 109, 'Registration', 65, 'submit New Application.', '2025-12-05 14:17:03', 0),
(10, 108, 'Registration', 66, 'submit New Application.', '2025-12-05 14:20:38', 0),
(11, 110, 'Registration', 67, 'submit New Application.', '2025-12-05 14:20:55', 0),
(12, 112, 'Registration', 68, 'submit New Application.', '2025-12-05 14:24:53', 1),
(13, 111, 'Registration', 69, 'submit New Application.', '2025-12-05 14:27:18', 0),
(14, 113, 'Renewal', 71, 'submit Renewal Application.', '2025-12-05 14:30:19', 0),
(15, 115, 'Renewal', 72, 'submit Renewal Application.', '2025-12-05 14:34:46', 1),
(16, 114, 'Renewal', 73, 'submit Renewal Application.', '2025-12-05 14:39:05', 1),
(17, 62, 'Registration', 74, 'submit New Application.', '2025-12-06 04:02:52', 1);

-- --------------------------------------------------------

--
-- Table structure for table `owner_details`
--

CREATE TABLE `owner_details` (
  `owner_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `owner_name` varchar(255) DEFAULT NULL,
  `owner_address` text NOT NULL,
  `owner_postal_code` int(10) NOT NULL,
  `owner_telephone_no` varchar(20) NOT NULL,
  `owner_email_address` varchar(255) NOT NULL,
  `owner_mobile_no` varchar(255) NOT NULL,
  `position_title` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `owner_details`
--

INSERT INTO `owner_details` (`owner_id`, `ba_id`, `owner_name`, `owner_address`, `owner_postal_code`, `owner_telephone_no`, `owner_email_address`, `owner_mobile_no`, `position_title`) VALUES
(42, 40, 'Kuya Cap ', 'Beguito Nuevo, Libmanan, Camarines Sur', 4407, '9398373290729', 'kuyacap01@gmail.com', '239847394', 'owner'),
(44, 42, 'ARCELYM. ZABALA', 'Puro-Batia, Libmanan, Camarines Sur', 4407, '09104171409', 'arcelyzabala@gmail.com', '09104171409', 'Owner'),
(45, 43, 'LIBMANAN FOOD PARK', 'Potot, Libmanan, Camarines Sur', 4407, '09452114232', 'libmananfp02@gmail.com', '09452114232', 'Owner'),
(46, 44, 'ESCAPES', 'Zone 4, Potot, Libmanan', 4407, '09113258954', 'escapes448@gmail.com', '09113258954', 'Owner'),
(47, 45, 'HANDIONG', 'Poblacion, Libmanan', 4407, '09635211248', 'handiongeatery@gmail.com', '09635211248', 'Owner'),
(48, 46, 'MJ', 'Aureus Street, Poblacion,  Libmanan ', 4407, '09214552788', 'mjfoodh@gmail.com', '09214552788', 'Owner'),
(49, 47, 'AFFORDABEST TEA CAFE', 'Zone 1 Abella St., Poblacion, LIBMANAN', 4407, '09192544963', 'affordabestteacafe476@gmail.com', '09192544963', ''),
(50, 48, 'IKING', 'Libmanan Road, Mabini, Libmanan', 4407, '09217550532', 'padingikingg22@gmail.com', '09217550532', 'Owner'),
(51, 49, 'LEO', 'Libmanan Road, Mabini, Libmanan', 4407, '09637842216', 'kuyaleoeatery@gmail.com', '09637842216', 'Owner'),
(52, 50, 'ARJOMEL', 'Libmanan Road, Loba-loba, Libmanan', 4407, '09967844544', 'arjomelresto@gmail.com', '09967844544', 'Owner'),
(53, 51, 'BOB', 'Asian Highway 26, Bagacay, Libmanan', 4407, '09331212141', 'boblysfoodplaza@gmail.com', '09331212141', 'Owner'),
(59, 57, 'Miggy Antonio', '', 0, '', '', '', ''),
(61, 59, '', 'Libmanan', 4407, '73837383883', 'StopOver232@gmail.com', '09864312784', ''),
(64, 62, 'Jes S. Solano', 'Libod 1, Libmanan, Camarines Sur', 4407, '', 'jessolano@gmail.com', '09456873214', 'Owner'),
(65, 63, 'Divine Cruz', 'Aslong, libmanan Camarines Sur', 4407, '', '', '', ''),
(66, 64, 'Divine Cruz', 'Aslong, libmanan Camarines Sur', 4407, '', '', '', ''),
(67, 65, 'Christian Antonio', '', 4407, '', 'kkopitea@gmail.com', '09643164646', ''),
(68, 66, 'Francis O. Noche', 'Puro_Batia, Libmaanan, Camarines Sur', 4407, '', 'francisnooche@gmail.com', '09456873214', 'Owner'),
(69, 67, 'Kris Martin', '', 0, '', '', '', ''),
(70, 68, 'Kim Chiu', '', 0, '', '', '', ''),
(71, 69, 'Jhun Roland C. Antonio', 'Bahay, Libmaanan, Camarines Sur', 4407, '', 'jhunrolandantonio@gmail.com', '09468572340', 'Owner'),
(72, 70, 'Ann Magdaline Ampongan ', '', 0, '', 'jaynaropizza@gmail.com', '', ''),
(73, 71, 'Ann Magdaline Ampongan ', '', 0, '', 'jaynaropizza@gmail.com', '', ''),
(74, 72, 'Mariel Indefenso', '', 0, '', '', '', ''),
(75, 73, 'Hyleen Deguzman', 'Zone 3, Planza, Libmanan Camarines Sur', 4407, '', '', '', ''),
(76, 74, 'Jhumari Job Galos', '', 0, '', '', '', '');

-- --------------------------------------------------------

--
-- Table structure for table `replies`
--

CREATE TABLE `replies` (
  `reply_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `user_id` int(100) NOT NULL,
  `reply_message` text NOT NULL,
  `created_at` datetime NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `replies`
--

INSERT INTO `replies` (`reply_id`, `ba_id`, `user_id`, `reply_message`, `created_at`) VALUES
(2, 37, 27, 'dfghjhgfdc', '2025-10-03 03:23:05');

-- --------------------------------------------------------

--
-- Table structure for table `reviews`
--

CREATE TABLE `reviews` (
  `id` int(11) NOT NULL,
  `fbowner_id` int(11) NOT NULL,
  `user_id` int(11) DEFAULT NULL,
  `reviewer_name` varchar(100) DEFAULT 'Anonymous',
  `rating` int(11) NOT NULL,
  `comment` text DEFAULT NULL,
  `photo` text DEFAULT NULL,
  `video` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` datetime DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `reviews`
--

INSERT INTO `reviews` (`id`, `fbowner_id`, `user_id`, `reviewer_name`, `rating`, `comment`, `photo`, `video`, `created_at`, `updated_at`) VALUES
(27, 51, 52, 'Alex', 5, 'Our place is all about good vibes and clean, tasty meals that feel like home. Every dish is prepared with care, keeping freshness and quality at the center. Best of all, you get it at a price that won’t hurt your wallet.', NULL, NULL, '2025-09-01 18:43:55', NULL),
(28, 51, 52, 'Alex', 4, 'We believe good food doesn’t have to be expensive. That’s why we serve generous portions of clean, delicious meals at the best value possible. Simple, honest, and satisfying—that’s our promise.', NULL, NULL, '2025-09-01 18:44:29', NULL),
(29, 51, 52, 'Alex', 5, 'Fresh ingredients, clean cooking, and friendly service—that’s what makes us stand out. We bring you flavors you’ll love without the high price tag. Here, every bite is worth more than what you pay.', NULL, NULL, '2025-09-01 18:45:05', NULL),
(30, 51, 52, 'Alex', 4, 'Great food, great price, and great people—this is what we’re known for. We make sure every dish is cooked cleanly and served with a smile. Because good food should always be easy to enjoy.', NULL, NULL, '2025-09-01 18:45:39', NULL),
(31, 51, 52, 'Alex', 5, 'At our food spot, quality and affordability go hand in hand. We serve meals that are flavorful, clean, and made with care. For the best taste at the best price, we’ve got you covered.', NULL, NULL, '2025-09-01 18:46:11', NULL),
(33, 56, 52, 'Alex Edrian', 4, '8 Tea Trio Café is a cozy spot where good service meets good food. Customers are welcomed with warm hospitality and a relaxing ambiance perfect for casual hangouts or study sessions. The café offers a wide selection of refreshing milk teas, flavorful snacks, and satisfying meals at fair prices. Known for its friendly staff and consistent quality, every visit feels comfortable and enjoyable. It’s a go-to place for those who love delicious drinks and comfort food paired with excellent service.', 'review_uploads/photo_1759503880_68dfe60890ddf.jpeg', NULL, '2025-09-01 15:04:40', '2025-11-30 00:53:20'),
(45, 56, 63, 'Dale mar Sandagon', 2, 'I recently visited 8 Tea Trip Café, and honestly, I was quite disappointed with their menu. The milk teas were too sweet even when I asked for less sugar.', 'review_uploads/photo_1759721954_68e339e2962f3.jpg', NULL, '2025-10-06 03:39:14', '2025-11-30 00:47:55'),
(47, 56, 64, 'daboy', 3, 'I really love the place', 'review_uploads/photo_1759726662_68e34c46c56af.jpg', NULL, '2025-10-06 04:57:42', NULL),
(49, 51, 64, 'daboy', 3, 'I love the place', 'review_uploads/photo_1759727813_68e350c54e0ce.jpg', NULL, '2025-10-06 05:16:53', NULL),
(50, 51, 64, 'daboy', 2, 'Muahh sa restau nato', NULL, 'review_uploads/video_1759727843_68e350e367036.mp4', '2025-10-06 05:17:23', NULL),
(51, 49, 64, 'daboy', 4, 'I love the cake', 'review_uploads/photo_1759729213_68e3563dbc383.jpg', NULL, '2025-10-06 05:40:13', NULL),
(52, 39, 64, 'daboy', 3, 'Ang sarap ng pansit guisado niyo???', 'review_uploads/photo_1759729616_68e357d03feaf.jpg', NULL, '2025-10-06 05:46:56', NULL),
(53, 53, 64, 'daboy', 5, 'Ilove the samgyup here', 'review_uploads/photo_1759729938_68e35912d26e6.jpg', NULL, '2025-10-06 05:52:18', NULL),
(54, 54, 64, 'daboy', 1, 'I don\'t like the bread', 'review_uploads/photo_1759730093_68e359adaac57.jpeg', NULL, '2025-10-06 05:54:53', NULL),
(62, 51, 67, 'kikay', 4, 'The food was absolutely delicious! Every dish was full of flavor and perfectly cooked. I’ll definitely be coming back for more.', NULL, NULL, '2025-10-06 15:06:30', NULL),
(63, 56, 67, 'kikay', 5, 'I really loved how fresh and well-prepared the meals were. You can tell they use high-quality ingredients. Highly recommended!', NULL, NULL, '2025-10-06 15:07:16', NULL),
(64, 39, 67, 'kikay', 5, 'The presentation of the food was amazing, and it tasted even better than it looked. Great job to the chef!', NULL, NULL, '2025-10-06 15:07:45', NULL),
(65, 53, 67, 'kikay', 4, 'Every bite was worth it! The flavors were well-balanced, and the portion sizes were generous. Excellent dining experience!', NULL, NULL, '2025-10-06 15:08:14', NULL),
(66, 54, 67, 'kikay', 4, 'I appreciate the variety on the menu — everything I tried was tasty and satisfying. You can really feel the passion in the cooking.', NULL, NULL, '2025-10-06 15:08:46', NULL),
(67, 49, 67, 'kikay', 5, 'The food was served hot and fresh, and the seasoning was just right. It’s one of the best meals I’ve had in a long time!', NULL, NULL, '2025-10-06 15:09:17', NULL),
(68, 65, 67, 'kikay', 5, 'Such a wonderful experience! The dishes were flavorful, well-plated, and made with care. I’ll definitely recommend this place to my friends.', NULL, NULL, '2025-10-06 15:09:44', NULL),
(69, 51, 68, 'mark', 2, 'The food was quite disappointing. It lacked flavor, and the texture was not what I expected. Definitely needs improvement.', NULL, NULL, '2025-10-06 15:11:11', NULL),
(70, 56, 68, 'mark', 1, 'The dishes were served cold, and some items tasted like they weren’t freshly cooked. I didn’t really enjoy the meal.', NULL, NULL, '2025-10-06 15:11:37', NULL),
(71, 39, 68, 'mark', 3, 'The presentation looked nice, but the taste didn’t match. The food was too salty and oily for my liking.', NULL, NULL, '2025-10-06 15:12:03', NULL),
(72, 53, 68, 'mark', 2, 'I was expecting more from the menu, but the flavors were very bland. It felt like something was missing in every dish.', NULL, NULL, '2025-10-06 15:12:34', NULL),
(73, 54, 68, 'mark', 1, 'The bread was too small for the price, and the quality didn’t feel worth it. I left the place unsatisfied.', NULL, NULL, '2025-10-06 15:13:08', NULL),
(74, 49, 68, 'mark', 2, 'The cake took too long to be served, and when it arrived, it wasn’t hot anymore. The overall dining experience wasn’t great.', NULL, NULL, '2025-10-06 15:13:38', NULL),
(75, 65, 68, 'mark', 1, 'I don\'t like the taste of milktea of this shop', NULL, NULL, '2025-10-06 15:14:19', NULL),
(76, 51, 69, 'jes', 4, 'The food was absolutely amazing! Every dish was full of flavor and cooked perfectly. I’ll definitely be coming back again.', NULL, NULL, '2025-10-06 15:15:50', NULL),
(77, 56, 69, 'jes', 5, 'I really enjoyed my meal. Everything tasted fresh and well-prepared, and the portions were just right. Highly recommended!', NULL, NULL, '2025-10-06 15:16:17', NULL),
(78, 39, 69, 'jes', 4, 'The food presentation was beautiful, and the taste didn’t disappoint. You can tell the chef puts effort into every plate.', NULL, NULL, '2025-10-06 15:16:51', NULL),
(79, 53, 69, 'jes', 4, 'The dishes were flavorful, served hot, and the ingredients tasted fresh. One of the best dining experiences I’ve had in a while!', NULL, NULL, '2025-10-06 15:17:15', NULL),
(80, 54, 69, 'jes', 5, 'Great variety on the menu and every item I tried was delicious. The flavors were balanced and satisfying. Excellent work!', NULL, NULL, '2025-10-06 15:17:48', NULL),
(81, 49, 69, 'jes', 2, 'The food wasn’t as good as expected. Some dishes lacked flavor, and a few items were served cold. It needs improvement.', NULL, NULL, '2025-10-06 15:18:16', NULL),
(82, 65, 69, 'jes', 2, 'The portions were too small for the price, and the food quality didn’t match the cost. I hope they make some adjustments soon.', NULL, NULL, '2025-10-06 15:18:42', NULL),
(83, 65, 70, 'francis', 5, 'I was really impressed with the food here! Every dish we ordered was bursting with flavor and cooked to perfection. You can tell that the ingredients were fresh and handled with care. The presentation was also beautiful — it made the meal feel special. It’s clear that the chefs take pride in their work. Definitely a place worth recommending to friends and family!', NULL, NULL, '2025-10-06 15:20:12', NULL),
(84, 49, 70, 'francis', 5, 'The food exceeded my expectations! From the first bite, you could taste the quality and effort put into each dish. The flavors were well-balanced, the portions were generous, and everything was served hot and fresh. The atmosphere also made the experience even better. I’ll surely come back to try more items on the menu.', NULL, NULL, '2025-10-06 15:20:38', NULL),
(85, 54, 70, 'francis', 5, 'I love how this restaurant pays attention to both taste and presentation. The dishes were not only delicious but also looked amazing. The flavors complemented each other perfectly, and the serving sizes were just right. It’s rare to find a place that delivers both quality and consistency like this. Truly a must-try for food lovers!', NULL, NULL, '2025-10-06 15:21:06', NULL),
(86, 53, 70, 'francis', 4, 'Every time I eat here, the food never disappoints. The meals are always freshly prepared, and you can tell that they use good-quality ingredients. The flavors are rich, satisfying, and perfectly seasoned. I also appreciate how the staff serve everything warm and on time. It’s a great dining spot for anyone who enjoys hearty, flavorful meals.', NULL, NULL, '2025-10-06 15:21:27', NULL),
(87, 53, 70, 'francis', 5, 'The food was simply outstanding! Each dish was thoughtfully prepared and full of taste. The mix of textures and flavors made every bite enjoyable. You can really see how much effort is put into making the food both appetizing and memorable. I’ll surely recommend this place to anyone looking for a satisfying and enjoyable dining experience.', NULL, NULL, '2025-10-06 15:21:48', NULL),
(88, 53, 70, 'francis', 5, 'The food was simply outstanding! Each dish was thoughtfully prepared and full of taste. The mix of textures and flavors made every bite enjoyable. You can really see how much effort is put into making the food both appetizing and memorable. I’ll surely recommend this place to anyone looking for a satisfying and enjoyable dining experience.', NULL, NULL, '2025-10-06 15:22:25', NULL),
(89, 39, 70, 'francis', 4, 'The food was simply outstanding! Each dish was thoughtfully prepared and full of taste. The mix of textures and flavors made every bite enjoyable. You can really see how much effort is put into making the food both appetizing and memorable. I’ll surely recommend this place to anyone looking for a satisfying and enjoyable dining experience', NULL, NULL, '2025-10-06 15:22:45', NULL),
(90, 56, 70, 'francis', 5, 'I had such a great experience with the food here. The dishes were creative, flavorful, and presented beautifully. Everything tasted homemade, but with a professional touch that made it extra special. The seasoning was just right — not too strong, not too plain. It’s the kind of food that makes you want to come back again and again.', NULL, NULL, '2025-10-06 15:23:40', NULL),
(91, 51, 70, 'francis', 5, 'This restaurant truly knows how to serve good food. The variety of dishes is impressive, and everything I’ve tried so far has been excellent. The flavors are authentic, the ingredients fresh, and the overall quality consistent. It’s clear that they care about giving their customers a memorable dining experience. I’ll definitely be a regular here!', NULL, NULL, '2025-10-06 15:24:04', NULL),
(92, 51, 51, '8teatripcafe', 1, 'I was quite disappointed with my meal. The food lacked flavor, and it didn’t seem freshly prepared. Some dishes were cold by the time they reached our table, which really affected the overall experience. I hope they work on improving the taste and serving time.', NULL, NULL, '2025-10-06 15:28:27', NULL),
(94, 39, 51, '8teatripcafe', 1, 'I had high expectations, but the food was underwhelming. The rice was overcooked, and the meat was dry. It seemed like the meals were rushed in preparation. A little more attention to quality control would make a big difference.', NULL, NULL, '2025-10-06 15:29:25', NULL),
(95, 53, 51, '8teatripcafe', 1, 'The waiting time was too long, and when the food finally arrived, it wasn’t warm anymore. Some dishes were bland, while others were too salty. It’s frustrating because the place looks nice, but the food didn’t match the ambiance.', NULL, NULL, '2025-10-06 15:29:53', NULL),
(96, 54, 51, '8teatripcafe', 2, 'The waiting time was too long, and when the food finally arrived, it wasn’t warm anymore. Some dishes were bland, while others were too salty. It’s frustrating because the place looks nice, but the food didn’t match the ambiance.', NULL, NULL, '2025-10-06 15:30:10', NULL),
(97, 51, 72, 'Pamboy', 5, 'I really enjoyed my dining experience here. The dishes were well-prepared and beautifully presented. The flavors were balanced, and every bite was satisfying. Highly recommended for anyone who loves good food and great service!', NULL, NULL, '2025-10-06 15:31:53', NULL),
(98, 56, 72, 'Pamboy', 5, 'I really enjoyed my dining experience here. The dishes were well-prepared and beautifully presented. The flavors were balanced, and every bite was satisfying. Highly recommended for anyone who loves good food and great service!', NULL, NULL, '2025-10-06 15:32:26', NULL),
(99, 39, 72, 'Pamboy', 1, 'The food didn’t taste fresh at all. It seemed reheated rather than newly cooked. The portion sizes were also smaller than expected. I really wanted to enjoy my meal, but it just wasn’t worth the price.', NULL, NULL, '2025-10-06 15:32:55', NULL),
(100, 53, 72, 'Pamboy', 2, '. I had high expectations, but the food was underwhelming. The rice was overcooked, and the meat was dry. It seemed like the meals were rushed in preparation. A little more attention to quality control would make a big difference.', NULL, NULL, '2025-10-06 15:33:44', NULL),
(101, 54, 72, 'Pamboy', 1, 'The waiting time was too long, and when the food finally arrived, it wasn’t warm anymore. Some dishes were bland, while others were too salty. It’s frustrating because the place looks nice, but the food didn’t match the ambiance.', NULL, NULL, '2025-10-06 15:34:14', NULL),
(102, 49, 72, 'Pamboy', 1, 'I was quite disappointed with my meal. The food lacked flavor, and it didn’t seem freshly prepared. Some dishes were cold by the time they reached our table, which really affected the overall experience. I hope they work on improving the taste and serving time.', NULL, NULL, '2025-10-06 15:34:40', NULL),
(103, 65, 72, 'Pamboy', 2, 'The presentation looked nice, but the food didn’t live up to it. The dishes were too oily, and the seasoning was off. For the price, I expected something better. It felt like the focus was more on appearance than actual taste.', NULL, NULL, '2025-10-06 15:35:03', NULL),
(104, 54, 73, 'Kyola', 4, 'The food was absolutely delicious! Every dish was cooked perfectly and full of flavor. You can really tell that the ingredients are fresh and carefully prepared. I enjoyed every bite!', NULL, NULL, '2025-10-06 20:37:02', NULL),
(105, 49, 73, 'Kyola', 5, 'I was so impressed with the quality of the food. Everything was served hot, fresh, and seasoned just right. It’s rare to find a place that maintains this level of consistency.', NULL, NULL, '2025-10-06 20:37:33', NULL),
(106, 51, 73, 'Kyola', 5, 'I was so impressed with the quality of the food. Everything was served hot, fresh, and seasoned just right. It’s rare to find a place that maintains this level of consistency.', NULL, NULL, '2025-10-06 20:39:06', NULL),
(107, 39, 73, 'Kyola', 5, 'I was so impressed with the quality of the food. Everything was served hot, fresh, and seasoned just right. It’s rare to find a place that maintains this level of consistency.', NULL, NULL, '2025-10-06 20:39:31', NULL),
(108, 39, 74, 'Rayman', 5, 'The dishes not only tasted amazing but also looked beautiful. The presentation was spot-on, and the flavors were well-balanced. Definitely worth recommending to friends!', NULL, NULL, '2025-10-06 20:40:51', NULL),
(109, 51, 74, 'Rayman', 5, 'Everything we ordered was so tasty! The serving sizes were generous, and the food came out quickly. It’s clear the staff takes pride in their cooking.', NULL, NULL, '2025-10-06 20:42:27', NULL),
(110, 54, 74, 'Rayman', 5, 'The food here always hits the spot. You can taste the effort and passion behind every dish. Each meal feels comforting and satisfying.', NULL, NULL, '2025-10-06 20:42:58', NULL),
(111, 49, 74, 'Rayman', 5, 'The food here always hits the spot. You can taste the effort and passion behind every dish. Each meal feels comforting and satisfying.', NULL, NULL, '2025-10-06 20:43:16', NULL),
(112, 39, 75, 'marky', 5, 'The food was packed with flavor and cooked to perfection. I love how they managed to balance taste and presentation so well. It’s a great place for food lovers!', NULL, NULL, '2025-10-06 20:44:55', NULL),
(113, 54, 75, 'marky', 5, 'The food was packed with flavor and cooked to perfection. I love how they managed to balance taste and presentation so well. It’s a great place for food lovers!', NULL, NULL, '2025-10-06 20:45:12', NULL),
(114, 49, 75, 'marky', 5, 'Everything tasted fresh and homemade. The meals were hearty, delicious, and beautifully served. It’s clear that quality is a top priority here.', NULL, NULL, '2025-10-06 20:45:41', NULL),
(115, 56, 76, 'hylene', 5, 'The food was beyond my expectations. Every bite was flavorful and satisfying. You can tell the chefs really know what they’re doing.', NULL, NULL, '2025-10-06 20:47:33', NULL),
(116, 53, 76, 'hylene', 4, 'I loved the variety of dishes on the menu — each one tasted different yet equally good. The freshness and balance of flavors stood out the most.', NULL, NULL, '2025-10-06 20:47:52', NULL),
(117, 39, 76, 'hylene', 4, 'I loved the variety of dishes on the menu — each one tasted different yet equally good. The freshness and balance of flavors stood out the most.', NULL, NULL, '2025-10-06 20:48:14', NULL),
(118, 54, 76, 'hylene', 3, 'This place truly delivers great food! The dishes were rich in flavor, and the portions were more than enough. Perfect for family dining or hanging out with friends.', NULL, NULL, '2025-10-06 20:48:36', '2025-11-27 11:14:23'),
(119, 49, 76, 'hylene', 4, 'I was pleasantly surprised by how good everything tasted. The food was warm, perfectly seasoned, and beautifully plated. Such a great experience!', NULL, NULL, '2025-10-06 20:48:58', NULL),
(120, 56, 77, 'bien', 5, 'The flavors were incredible! I loved how everything blended so well together. You can taste the effort and creativity behind each recipe.', NULL, NULL, '2025-10-06 20:49:54', NULL),
(121, 53, 77, 'bien', 4, 'The meals were fresh, flavorful, and comforting. It’s the kind of food that makes you feel good after eating. Definitely one of my favorite dining spots!', NULL, NULL, '2025-10-06 20:50:22', NULL),
(122, 39, 77, 'bien', 4, 'The meals were fresh, flavorful, and comforting. It’s the kind of food that makes you feel good after eating. Definitely one of my favorite dining spots!', NULL, NULL, '2025-10-06 20:50:40', NULL),
(123, 54, 77, 'bien', 4, 'Every dish we tried was full of taste and beautifully presented. The freshness and flavor really made it stand out from other restaurants.', NULL, NULL, '2025-10-06 20:51:07', NULL),
(124, 49, 77, 'bien', 5, 'Every dish we tried was full of taste and beautifully presented. The freshness and flavor really made it stand out from other restaurants.', NULL, NULL, '2025-10-06 20:51:24', NULL),
(125, 56, 78, 'Krisha', 4, 'The food quality here is outstanding. Everything from the texture to the taste was perfect. It’s clear that the kitchen staff puts love into what they do.', NULL, NULL, '2025-10-06 20:52:27', NULL),
(126, 53, 78, 'Krisha', 4, 'I’ve eaten here multiple times, and the food never disappoints. The flavors are always consistent, and the servings are very satisfying.', NULL, NULL, '2025-10-06 20:52:50', NULL),
(127, 39, 78, 'Krisha', 5, 'I really appreciate how well-prepared the dishes are. You can tell that everything is made with care and attention to detail. Definitely worth a visit!', NULL, NULL, '2025-10-06 20:53:45', NULL),
(128, 54, 78, 'Krisha', 4, 'I really appreciate how well-prepared the dishes are. You can tell that everything is made with care and attention to detail. Definitely worth a visit!', NULL, NULL, '2025-10-06 20:54:05', NULL),
(129, 49, 78, 'Krisha', 5, 'The food was simply amazing! The mix of flavors was just right, and the ingredients tasted so fresh. I’ll definitely come back for more.', NULL, NULL, '2025-10-06 20:54:30', NULL),
(130, 56, 79, 'charline', 4, 'The food was absolutely amazing! Every dish had the right flavor, and it really felt freshly cooked. I could eat here every day.', NULL, NULL, '2025-10-06 20:57:06', NULL),
(131, 53, 79, 'charline', 2, 'Honestly, the food didn’t taste fresh. Some parts were cold, and the texture felt weird, like it had been sitting out too long.', NULL, NULL, '2025-10-06 20:57:36', NULL),
(132, 39, 79, 'charline', 5, 'I loved everything we ordered! The dishes were flavorful, nicely plated, and came out fast. Such a great experience overall.', NULL, NULL, '2025-10-06 20:58:06', NULL),
(133, 54, 79, 'charline', 2, 'The food took forever to arrive, and when it did, it wasn’t even warm. Pretty disappointing for the price.', NULL, NULL, '2025-10-06 20:58:35', NULL),
(134, 49, 79, 'charline', 2, 'The food took forever to arrive, and when it did, it wasn’t even warm. Pretty disappointing for the price.', NULL, NULL, '2025-10-06 20:58:52', NULL),
(135, 56, 80, 'Allean', 5, 'The meals were full of flavor and cooked perfectly. You can tell they used quality ingredients — I’ll definitely come back!', NULL, NULL, '2025-10-06 21:00:10', NULL),
(136, 53, 80, 'Allean', 5, 'Everything tasted so fresh and balanced. The serving size was generous too — I left really satisfied.', NULL, NULL, '2025-10-06 21:00:39', NULL),
(137, 54, 80, 'Allean', 4, 'Such a pleasant surprise! The food was warm, tasty, and full of flavor. Definitely a bread I’ll recommend.', NULL, NULL, '2025-10-06 21:01:11', NULL),
(138, 49, 80, 'Allean', 5, 'The flavors were amazing! Everything tasted freshly made, and the balance of flavor was just right.', NULL, NULL, '2025-10-06 21:01:48', NULL),
(140, 82, 63, 'Dale mar Sandagon', 3, 'Yummy overload', 'review_uploads/photo_1759805755_68e4813b606ee.jpg', NULL, '2025-10-07 02:55:55', NULL),
(141, 82, 62, 'Jhumari Job Galos', 5, 'The food was delicious', NULL, NULL, '2025-10-07 02:58:01', NULL),
(142, 65, 92, 'Roman Delavega', 4, 'Good yumminess', NULL, NULL, '2025-11-21 10:42:22', '2025-11-21 13:11:18'),
(143, 56, 92, 'Roman Delavega', 5, 'Good Yummy foodies must try', NULL, NULL, '2025-11-21 11:32:50', '2025-11-21 12:28:44'),
(150, 65, 52, 'Alex Edrian ', 5, 'The milktea is affordable and has clean cat with his mom.', 'review_uploads/photo_1764238961_69282671b4ec3.jpeg', NULL, '2025-11-27 10:22:41', '2025-12-22 07:30:37'),
(152, 39, 54, 'Rinnor', 5, 'The price list is cheap but the quality is good as heaven...', NULL, NULL, '2025-11-28 05:22:06', '2025-11-28 09:32:19'),
(153, 49, 54, 'Rinnor', 5, 'The cakes and pastries are sweet and delicious! Definitely I will comeback here.', NULL, NULL, '2025-11-28 05:31:44', NULL),
(154, 49, 52, 'Alex Edrian ', 4, 'The price was to much but the quality of cakes are seems to be nice.', NULL, NULL, '2025-11-28 05:32:57', '2025-11-28 05:33:20'),
(155, 65, 95, 'Kevin Robert Cabanos', 1, '...', NULL, NULL, '2025-11-28 06:37:36', NULL),
(156, 84, 98, 'Marc Verzosa', 5, 'Magayon', 'review_uploads/photo_1764418903_692ae557d892c.jpg', NULL, '2025-11-29 12:21:43', NULL),
(157, 84, 99, 'Ian', 4, 'Ang sarap ng mga pagkain. Sa subrang sarap ayaw ko na bumalik. You should go but not order. Thank you. Must try', NULL, NULL, '2025-11-29 12:23:21', NULL),
(158, 49, 62, 'Jhumari Job Galos', 5, 'Masarap ang tinapay dito', NULL, NULL, '2025-11-29 16:15:21', NULL),
(160, 65, 63, 'Dale mar Sandagon', 3, 'the milktea is yummy.', NULL, NULL, '2025-11-30 08:56:26', NULL),
(161, 54, 52, 'Alex Edrian ', 5, 'the bread is always new and fresh', NULL, NULL, '2025-12-03 07:30:14', '2025-12-07 10:18:53'),
(162, 39, 116, 'Juan Dela Cruz', 5, 'The ambiance is very relaxing and the food is superb!', NULL, NULL, '2025-11-02 12:30:00', NULL),
(163, 39, 117, 'Maria Santos', 4, 'Service was a bit slow but the taste made up for it.', NULL, NULL, '2025-11-03 13:15:00', NULL),
(164, 39, 118, 'Juan Carlos Reyes', 5, 'Highly recommended for family dinners.', NULL, NULL, '2025-11-05 18:45:00', NULL),
(165, 39, 119, 'Mark Anthony dela Cruz', 5, 'Loved the local dishes here. Sarap!', NULL, NULL, '2025-11-06 19:00:00', NULL),
(166, 39, 120, 'Jose Miguel Santos', 5, 'Perfect place for a date night.', NULL, NULL, '2025-11-09 20:10:00', NULL),
(167, 39, 121, 'Rafael Luis Navarro', 4, 'Good food, affordable prices.', NULL, NULL, '2025-11-10 12:00:00', NULL),
(168, 39, 122, 'Christian Gabriel Ramos', 5, 'Staff are very accommodating.', NULL, NULL, '2025-11-11 11:30:00', NULL),
(169, 39, 123, 'Maria Isabella Cruz', 5, 'One of the best in Libmanan!', NULL, NULL, '2025-11-12 14:20:00', NULL),
(170, 39, 124, 'Angelica Mae Flores', 5, 'Whatever you order, it is delicious.', NULL, NULL, '2025-11-13 10:45:00', NULL),
(171, 39, 125, 'Jasmine Nicole Tan', 4, 'Parking is a bit hard but food is 10/10.', NULL, NULL, '2025-11-14 19:30:00', NULL),
(172, 39, 126, 'Patricia Lourdes Mendoza', 5, 'Super sulit ang servings!', NULL, NULL, '2025-11-17 12:15:00', NULL),
(173, 39, 127, 'Camille Joy Bautista', 5, 'Will definitely come back again.', NULL, NULL, '2025-11-20 13:40:00', NULL),
(174, 39, 128, 'Alvin Joseph Soriano', 5, 'Clean restaurant and fast service.', NULL, NULL, '2025-11-23 18:10:00', NULL),
(175, 39, 129, 'Erika Louisa Dela Rosa', 5, 'My kids loved the food here.', NULL, NULL, '2025-11-24 19:50:00', NULL),
(176, 39, 130, 'Dennis Angelo Mercado', 4, 'Nice spot to hang out with friends.', NULL, NULL, '2025-11-25 15:30:00', NULL),
(177, 39, 131, 'Bianca Rochelle Manalo', 5, 'Authentic taste!', NULL, NULL, '2025-11-27 11:15:00', NULL),
(178, 39, 132, 'Jonathan Patrick Velasco', 5, 'Ending the month with a great meal.', NULL, NULL, '2025-11-28 12:05:00', NULL),
(179, 39, 133, 'Karen Sheila Ignacio', 5, 'Everything was perfect.', NULL, NULL, '2025-11-29 18:30:00', NULL),
(180, 39, 134, 'Miguel Antonio Pascual', 5, 'Great experience at Tata Tiama.', NULL, NULL, '2025-11-30 13:00:00', NULL),
(181, 39, 135, 'Gabriel Angelo Fernandez', 5, 'Tried their sisig and it was the best!', NULL, NULL, '2025-11-02 11:30:00', NULL),
(182, 39, 136, 'Joshua Kyle De Leon', 4, 'Great vibes for hanging out.', NULL, NULL, '2025-11-03 19:15:00', NULL),
(183, 39, 137, 'Ryan Christopher Go', 5, 'Nice music and good food.', NULL, NULL, '2025-11-04 12:45:00', NULL),
(184, 39, 138, 'Patrick James Villanueva', 5, 'Very affordable for students.', NULL, NULL, '2025-11-06 15:20:00', NULL),
(185, 39, 139, 'Kevin Mark Espanto', 3, 'Food was okay but took a while.', NULL, NULL, '2025-11-07 13:00:00', NULL),
(186, 39, 140, 'Justin Paul Ocampo', 5, 'Waiters are very kind and attentive.', NULL, NULL, '2025-11-09 18:30:00', NULL),
(187, 39, 141, 'Edward John Castillo', 5, 'Clean CR and spacious parking.', NULL, NULL, '2025-11-11 10:10:00', NULL),
(188, 39, 142, 'Bryan Anthony Diaz', 4, 'A bit crowded but worth the wait.', NULL, NULL, '2025-11-13 20:00:00', NULL),
(189, 39, 143, 'Vincent Karl Medina', 5, 'Service was incredibly fast today.', NULL, NULL, '2025-11-15 12:05:00', NULL),
(190, 39, 144, 'Adrian Luke Rivera', 5, 'The Bulalo is a must-try!', NULL, NULL, '2025-11-16 19:45:00', NULL),
(191, 39, 145, 'Sofia Lorraine Gomez', 5, 'Such a cute place for photos.', NULL, NULL, '2025-11-18 14:30:00', NULL),
(192, 39, 146, 'Hannah Mae Pineda', 4, 'Very instagrammable spot.', NULL, NULL, '2025-11-19 16:15:00', NULL),
(193, 39, 147, 'Janine Claire Domingo', 5, 'Perfect spot for our date night.', NULL, NULL, '2025-11-21 20:30:00', NULL),
(194, 39, 148, 'Andrea Louise Aguilar', 5, 'We will definitely return here.', NULL, NULL, '2025-11-23 11:45:00', NULL),
(195, 39, 149, 'Katrina Marie Lopez', 5, 'Big servings, good for sharing.', NULL, NULL, '2025-11-24 13:20:00', NULL),
(196, 39, 150, 'Melissa Joy Sarmiento', 5, 'Crispy Pata is legendary.', NULL, NULL, '2025-11-26 18:50:00', NULL),
(197, 39, 151, 'Clarissa Jane Ferrer', 4, 'Aircon is cold and comfy.', NULL, NULL, '2025-11-28 15:10:00', NULL),
(198, 39, 152, 'Rachel Ann Torres', 5, 'Our family favorite in Libmanan.', NULL, NULL, '2025-11-29 11:55:00', NULL),
(199, 39, 153, 'Nicole Rose Zamora', 4, 'Good lunch stopover.', NULL, NULL, '2025-11-30 12:40:00', NULL),
(200, 39, 154, 'Kimberly Kate Lim', 5, 'Had a lovely dinner time here.', NULL, NULL, '2025-11-30 19:25:00', NULL),
(201, 104, 154, 'Kimberly Kate Lim', 5, 'The place is spotless and the food is amazing.', NULL, NULL, '2025-11-01 11:30:00', NULL),
(202, 104, 153, 'Nicole Rose Zamora', 5, 'Very relaxing vibe, perfect for a quiet lunch.', NULL, NULL, '2025-11-01 12:15:00', NULL),
(203, 104, 152, 'Rachel Ann Torres', 4, 'Service was fast and the table was very clean.', NULL, NULL, '2025-11-02 13:00:00', NULL),
(204, 104, 151, 'Clarissa Jane Ferrer', 5, 'Superb food quality! Will come back.', NULL, NULL, '2025-11-02 18:30:00', NULL),
(205, 104, 150, 'Melissa Joy Sarmiento', 5, 'Staff were very polite and accommodating.', NULL, NULL, '2025-11-03 12:45:00', NULL),
(206, 104, 149, 'Katrina Marie Lopez', 4, 'Good value for money. Big servings.', NULL, NULL, '2025-11-03 19:00:00', NULL),
(207, 104, 148, 'Andrea Louise Aguilar', 5, 'Best Sisig in town!', NULL, NULL, '2025-11-04 11:15:00', NULL),
(208, 104, 147, 'Janine Claire Domingo', 5, 'Comfortable seats and nice aircon.', NULL, NULL, '2025-11-04 13:20:00', NULL),
(209, 104, 146, 'Hannah Mae Pineda', 4, 'Clean comfort rooms, which is a big plus.', NULL, NULL, '2025-11-05 10:30:00', NULL),
(210, 104, 145, 'Sofia Lorraine Gomez', 5, 'The fried chicken is crispy and juicy.', NULL, NULL, '2025-11-05 17:45:00', NULL),
(211, 104, 144, 'Adrian Luke Rivera', 5, 'Love the homestyle cooking here.', NULL, NULL, '2025-11-06 12:00:00', NULL),
(212, 104, 143, 'Vincent Karl Medina', 4, 'A bit busy but the food is worth the wait.', NULL, NULL, '2025-11-06 14:10:00', NULL),
(213, 104, 142, 'Bryan Anthony Diaz', 5, 'Great place for family bonding.', NULL, NULL, '2025-11-07 11:50:00', NULL),
(214, 104, 141, 'Edward John Castillo', 5, 'The soup was served hot and fresh.', NULL, NULL, '2025-11-07 13:05:00', NULL),
(215, 104, 140, 'Justin Paul Ocampo', 5, 'Highly recommended! 5 stars.', NULL, NULL, '2025-11-07 18:40:00', NULL),
(216, 104, 139, 'Kevin Mark Espanto', 5, 'Delicious food and very hygienic place.', NULL, NULL, '2025-11-08 11:25:00', NULL),
(217, 104, 138, 'Patrick James Villanueva', 4, 'Nice ambiance, very Instagrammable.', NULL, NULL, '2025-11-08 19:15:00', NULL),
(218, 104, 137, 'Ryan Christopher Go', 5, 'Everything we ordered was tasty.', NULL, NULL, '2025-11-09 12:10:00', NULL),
(219, 104, 136, 'Joshua Kyle De Leon', 5, 'Fast wifi and good coffee too.', NULL, NULL, '2025-11-09 15:30:00', NULL),
(220, 104, 135, 'Gabriel Angelo Fernandez', 4, 'Parking is spacious.', NULL, NULL, '2025-11-10 11:45:00', NULL),
(221, 104, 134, 'Miguel Antonio Pascual', 5, 'My kids loved the spaghetti.', NULL, NULL, '2025-11-10 18:20:00', NULL),
(222, 104, 133, 'Karen Sheila Ignacio', 5, 'The staff were smiling and helpful.', NULL, NULL, '2025-11-11 13:00:00', NULL),
(223, 104, 132, 'Jonathan Patrick Velasco', 5, 'Very affordable student meal prices.', NULL, NULL, '2025-11-11 16:45:00', NULL),
(224, 104, 131, 'Bianca Rochelle Manalo', 4, 'Quiet place to study or work.', NULL, NULL, '2025-11-12 10:15:00', NULL),
(225, 104, 130, 'Dennis Angelo Mercado', 5, 'The pork chop was tender and flavorful.', NULL, NULL, '2025-11-12 12:35:00', NULL),
(226, 104, 129, 'Erika Louisa Dela Rosa', 5, 'Cleanest restaurant in Libmanan.', NULL, NULL, '2025-11-13 11:55:00', NULL),
(227, 104, 128, 'Alvin Joseph Soriano', 4, 'Good music playlist, sets a nice mood.', NULL, NULL, '2025-11-13 19:30:00', NULL),
(228, 104, 127, 'Camille Joy Bautista', 5, 'Presentation of food is 10/10.', NULL, NULL, '2025-11-14 12:50:00', NULL),
(229, 104, 126, 'Patricia Lourdes Mendoza', 5, 'Tried the Sinigang, super asim and sarap!', NULL, NULL, '2025-11-14 18:05:00', NULL),
(230, 104, 125, 'Jasmine Nicole Tan', 5, 'Efficient service even during lunch rush.', NULL, NULL, '2025-11-14 12:20:00', NULL),
(231, 104, 124, 'Angelica Mae Flores', 5, 'Aljos is our favorite go-to spot.', NULL, NULL, '2025-11-15 11:35:00', NULL),
(232, 104, 123, 'Maria Isabella Cruz', 4, 'Tables are always wiped clean.', NULL, NULL, '2025-11-15 13:40:00', NULL),
(233, 104, 122, 'Christian Gabriel Ramos', 5, 'The serving size justifies the price.', NULL, NULL, '2025-11-16 12:05:00', NULL),
(234, 104, 121, 'Rafael Luis Navarro', 5, 'Love the rustic interior design.', NULL, NULL, '2025-11-16 19:00:00', NULL),
(235, 104, 120, 'Jose Miguel Santos', 5, 'Food arrived hot and on time.', NULL, NULL, '2025-11-17 11:15:00', NULL),
(236, 104, 119, 'Mark Anthony dela Cruz', 4, 'Friendly environment for groups.', NULL, NULL, '2025-11-17 18:25:00', NULL),
(237, 104, 118, 'Juan Carlos Reyes', 5, 'The desserts are a must try!', NULL, NULL, '2025-11-18 12:45:00', NULL),
(238, 104, 117, 'Maria Santos', 5, 'Sanitation is clearly a priority here.', NULL, NULL, '2025-11-18 14:30:00', NULL),
(239, 104, 116, 'Juan Dela Cruz', 5, 'Authentic Filipino taste.', NULL, NULL, '2025-11-19 11:50:00', NULL),
(240, 104, 105, 'Jhumari Job Galos', 4, 'Good place to chill with friends.', NULL, NULL, '2025-11-19 17:15:00', NULL),
(241, 104, 103, 'pogi', 5, 'Never had a bad meal here.', NULL, NULL, '2025-11-20 12:10:00', NULL),
(242, 104, 95, 'Kevin Robert Cabanos', 5, 'Comfort food at its finest.', NULL, NULL, '2025-11-22 11:40:00', NULL),
(243, 104, 94, 'Don Roman', 5, 'No insects or flies, very clean.', NULL, NULL, '2025-11-22 13:15:00', NULL),
(244, 104, 93, 'Kirby', 4, 'Good selection of drinks.', NULL, NULL, '2025-11-23 12:25:00', NULL),
(245, 104, 92, 'Roman Delavega', 5, 'The grilled fish is superb.', NULL, NULL, '2025-11-23 19:10:00', NULL),
(246, 104, 80, 'Allean', 5, 'Feels just like home.', NULL, NULL, '2025-11-24 11:20:00', NULL),
(247, 104, 79, 'charline', 5, 'Staff wears hairnets and masks. Good hygiene.', NULL, NULL, '2025-11-24 15:45:00', NULL),
(248, 104, 78, 'Krisha', 4, 'Nice place for a date.', NULL, NULL, '2025-11-25 18:35:00', NULL),
(249, 104, 77, 'bien', 5, 'Fast service, we did not wait long.', NULL, NULL, '2025-11-25 12:00:00', NULL),
(250, 104, 76, 'hylene', 5, 'The best Bicol Express I tried.', NULL, NULL, '2025-11-26 13:30:00', NULL),
(251, 104, 75, 'marky', 5, 'Very accessible location.', NULL, NULL, '2025-11-26 16:15:00', NULL),
(252, 104, 74, 'Rayman', 4, 'Fresh ingredients used in all dishes.', NULL, NULL, '2025-11-27 11:10:00', NULL),
(253, 104, 73, 'Kyola', 5, 'Utensils are wrapped and clean.', NULL, NULL, '2025-11-27 19:50:00', NULL),
(254, 104, 72, 'Pamboy', 5, 'Will recommend to my friends.', NULL, NULL, '2025-11-28 12:40:00', NULL),
(255, 104, 71, 'Jhun roland', 5, 'The owner is very hands-on and kind.', NULL, NULL, '2025-11-28 14:55:00', NULL),
(256, 74, 116, 'Juan Dela Cruz', 5, 'The view of the Libmanan river is so relaxing while sipping coffee.', NULL, NULL, '2025-11-01 08:30:00', NULL),
(257, 74, 117, 'Maria Santos', 5, 'Perfect spot to escape the noise of the town. The breeze is lovely.', NULL, NULL, '2025-11-01 16:15:00', NULL),
(258, 74, 118, 'Juan Carlos Reyes', 5, 'Best ambiance for a cafe! Nature vibes + Iced Coffee.', NULL, NULL, '2025-11-02 09:00:00', NULL),
(259, 74, 119, 'Mark Anthony dela Cruz', 4, 'Very chill place. The river scenery adds to the experience.', NULL, NULL, '2025-11-02 14:45:00', NULL),
(260, 74, 120, 'Jose Miguel Santos', 5, 'Coffee tastes great, but the view is the real winner here.', NULL, NULL, '2025-11-03 10:20:00', NULL),
(261, 74, 121, 'Rafael Luis Navarro', 5, 'A hidden gem by the river. So peaceful.', NULL, NULL, '2025-11-03 17:30:00', NULL),
(262, 74, 122, 'Christian Gabriel Ramos', 4, 'Good pastries and fresh air. Will come back.', NULL, NULL, '2025-11-04 11:10:00', NULL),
(263, 74, 123, 'Maria Isabella Cruz', 5, 'Love the acoustic vibes near the water.', NULL, NULL, '2025-11-04 15:55:00', NULL),
(264, 74, 124, 'Angelica Mae Flores', 5, 'The sunset view here is unmatched.', NULL, NULL, '2025-11-05 16:40:00', NULL),
(265, 74, 125, 'Jasmine Nicole Tan', 5, 'Very aesthetic cafe. Instagrammable everywhere.', NULL, NULL, '2025-11-05 12:25:00', NULL),
(266, 74, 126, 'Patricia Lourdes Mendoza', 4, 'Staff is friendly and the place is clean.', NULL, NULL, '2025-11-06 13:50:00', NULL),
(267, 74, 127, 'Camille Joy Bautista', 5, 'My new favorite study spot. Quiet and serene.', NULL, NULL, '2025-11-06 09:15:00', NULL),
(268, 74, 128, 'Alvin Joseph Soriano', 5, 'The sound of the flowing river is so calming.', NULL, NULL, '2025-11-07 08:45:00', NULL),
(269, 74, 129, 'Erika Louisa Dela Rosa', 5, 'Refreshing drinks and a nice outdoor seating area.', NULL, NULL, '2025-11-07 14:35:00', NULL),
(270, 74, 130, 'Dennis Angelo Mercado', 4, 'Great place to unwind after a stressful week.', NULL, NULL, '2025-11-07 18:10:00', NULL),
(271, 74, 131, 'Bianca Rochelle Manalo', 5, 'Their Latte Art is so cute!', NULL, NULL, '2025-11-08 10:30:00', NULL),
(272, 74, 132, 'Jonathan Patrick Velasco', 5, 'Romantic spot for a date by the river.', NULL, NULL, '2025-11-09 17:45:00', NULL),
(273, 74, 133, 'Karen Sheila Ignacio', 4, 'Cozy chairs and good WiFi connection.', NULL, NULL, '2025-11-10 11:20:00', NULL),
(274, 74, 134, 'Miguel Antonio Pascual', 5, 'Nothing beats drinking coffee with this view.', NULL, NULL, '2025-11-11 09:40:00', NULL),
(275, 74, 135, 'Gabriel Angelo Fernandez', 5, 'The outdoor area is perfect when the weather is nice.', NULL, NULL, '2025-11-12 15:15:00', NULL),
(276, 74, 136, 'Joshua Kyle De Leon', 5, 'Premium tasting coffee at an affordable price.', NULL, NULL, '2025-11-12 13:00:00', NULL),
(277, 74, 137, 'Ryan Christopher Go', 4, 'Chill vibes only. No stress here.', NULL, NULL, '2025-11-13 16:50:00', NULL),
(278, 74, 138, 'Patrick James Villanueva', 5, 'Taking a break from work here is totally worth it.', NULL, NULL, '2025-11-13 10:10:00', NULL),
(279, 74, 139, 'Kevin Mark Espanto', 5, 'Love the rustic wood theme of the cafe.', NULL, NULL, '2025-11-14 14:05:00', NULL),
(280, 74, 140, 'Justin Paul Ocampo', 5, 'The Spanish Latte is a must try!', NULL, NULL, '2025-11-14 11:35:00', NULL),
(281, 74, 141, 'Edward John Castillo', 5, 'Quiet and serene atmosphere.', NULL, NULL, '2025-11-15 08:50:00', NULL),
(282, 74, 142, 'Bryan Anthony Diaz', 4, 'Good value for money given the location.', NULL, NULL, '2025-11-17 12:45:00', NULL),
(283, 74, 143, 'Vincent Karl Medina', 5, 'The fresh river breeze makes the coffee taste better.', NULL, NULL, '2025-11-18 16:20:00', NULL),
(284, 74, 144, 'Adrian Luke Rivera', 5, 'Baristas are very skilled and friendly.', NULL, NULL, '2025-11-19 13:10:00', NULL),
(285, 74, 145, 'Sofia Lorraine Gomez', 5, 'Truly lives up to its name, a great Escape.', NULL, NULL, '2025-11-20 15:30:00', NULL),
(286, 74, 150, 'Melissa Joy Sarmiento', 5, 'Ending November with a peaceful cup of coffee here.', NULL, NULL, '2025-11-29 16:10:00', NULL),
(287, 74, 151, 'Clarissa Jane Ferrer', 5, 'Their Frappes are delicious!', NULL, NULL, '2025-11-29 11:50:00', NULL),
(288, 74, 152, 'Rachel Ann Torres', 5, 'Nature vibes in the heart of Libmanan.', NULL, NULL, '2025-11-30 08:20:00', NULL),
(289, 74, 153, 'Nicole Rose Zamora', 5, 'Will definitely return for the cakes and view.', NULL, NULL, '2025-11-30 13:45:00', NULL),
(290, 74, 154, 'Kimberly Kate Lim', 5, 'Highly recommended for all coffee lovers.', NULL, NULL, '2025-11-30 10:05:00', NULL),
(291, 94, 116, 'Juan Dela Cruz', 5, 'Best Pizza place in the Sentro of Libmanan!', NULL, NULL, '2025-11-01 11:30:00', NULL),
(292, 94, 117, 'Maria Santos', 5, 'Their burgers are huge and very juicy.', NULL, NULL, '2025-11-01 17:15:00', NULL),
(293, 94, 118, 'Juan Carlos Reyes', 4, 'Very accessible location, easy to find.', NULL, NULL, '2025-11-02 12:45:00', NULL),
(294, 94, 119, 'Mark Anthony dela Cruz', 5, 'Loved the cheesy crust pizza.', NULL, NULL, '2025-11-03 18:20:00', NULL),
(295, 94, 120, 'Jose Miguel Santos', 5, 'Fries were crispy and seasoned well.', NULL, NULL, '2025-11-04 13:00:00', NULL),
(296, 94, 121, 'Rafael Luis Navarro', 4, 'Good spot for a quick lunch while in town.', NULL, NULL, '2025-11-05 11:50:00', NULL),
(297, 94, 122, 'Christian Gabriel Ramos', 5, 'The Overload Pizza is definitely a must-try.', NULL, NULL, '2025-11-06 19:10:00', NULL),
(298, 94, 123, 'Maria Isabella Cruz', 5, 'My family enjoyed the burger bundle.', NULL, NULL, '2025-11-07 16:30:00', NULL),
(299, 94, 124, 'Angelica Mae Flores', 5, 'The burger patty tastes 100% pure beef.', NULL, NULL, '2025-11-08 10:40:00', NULL),
(300, 94, 125, 'Jasmine Nicole Tan', 4, 'Hawaiian Pizza is my favorite here.', NULL, NULL, '2025-11-08 15:25:00', NULL),
(301, 94, 126, 'Patricia Lourdes Mendoza', 5, 'Fast service considering it was busy.', NULL, NULL, '2025-11-09 12:15:00', NULL),
(302, 94, 127, 'Camille Joy Bautista', 5, 'Place is clean and air-conditioned.', NULL, NULL, '2025-11-09 18:45:00', NULL),
(303, 94, 128, 'Alvin Joseph Soriano', 5, 'Affordable prices for such big servings.', NULL, NULL, '2025-11-10 13:30:00', NULL),
(304, 94, 129, 'Erika Louisa Dela Rosa', 5, 'Convenient location right in the heart of Libmanan.', NULL, NULL, '2025-11-11 11:20:00', NULL),
(305, 94, 130, 'Dennis Angelo Mercado', 4, 'Pepperoni pizza was delicious.', NULL, NULL, '2025-11-12 17:05:00', NULL),
(306, 94, 131, 'Bianca Rochelle Manalo', 5, 'Refreshing drinks to pair with the burgers.', NULL, NULL, '2025-11-13 14:10:00', NULL),
(307, 94, 132, 'Jonathan Patrick Velasco', 5, 'Staff are very polite and accommodating.', NULL, NULL, '2025-11-14 09:50:00', NULL),
(308, 94, 133, 'Karen Sheila Ignacio', 5, 'Their combo meals are super sulit.', NULL, NULL, '2025-11-14 19:30:00', NULL),
(309, 94, 134, 'Miguel Antonio Pascual', 5, 'I like that the pizza crust is not too thick.', NULL, NULL, '2025-11-15 12:05:00', NULL),
(310, 94, 135, 'Gabriel Angelo Fernandez', 5, 'The Bacon Burger is a game changer!', NULL, NULL, '2025-11-16 16:15:00', NULL),
(311, 94, 136, 'Joshua Kyle De Leon', 4, 'Great stopover when you are in the sentro.', NULL, NULL, '2025-11-17 10:30:00', NULL),
(312, 94, 137, 'Ryan Christopher Go', 5, 'Surprisingly, their pasta is good too.', NULL, NULL, '2025-11-18 13:45:00', NULL),
(313, 94, 138, 'Patrick James Villanueva', 5, 'Kid-friendly menu, my children loved it.', NULL, NULL, '2025-11-19 18:00:00', NULL),
(314, 94, 139, 'Kevin Mark Espanto', 4, 'Food is tasty and not too salty.', NULL, NULL, '2025-11-20 11:40:00', NULL),
(315, 94, 140, 'Justin Paul Ocampo', 5, 'Perfect place for merienda.', NULL, NULL, '2025-11-21 15:55:00', NULL),
(316, 94, 141, 'Edward John Castillo', 5, 'Classic cheeseburger never disappoints.', NULL, NULL, '2025-11-22 12:30:00', NULL),
(317, 94, 142, 'Bryan Anthony Diaz', 5, 'Pizza toppings are generous.', NULL, NULL, '2025-11-23 17:20:00', NULL),
(318, 94, 143, 'Vincent Karl Medina', 4, 'Easy to find parking nearby.', NULL, NULL, '2025-11-24 13:10:00', NULL),
(319, 94, 144, 'Adrian Luke Rivera', 5, 'Nice and cool place to escape the heat.', NULL, NULL, '2025-11-25 11:00:00', NULL),
(320, 94, 145, 'Sofia Lorraine Gomez', 5, 'The Quarter Pounder is heavy and satisfying.', NULL, NULL, '2025-11-26 18:40:00', NULL),
(321, 94, 146, 'Hannah Mae Pineda', 5, 'Service with a smile always.', NULL, NULL, '2025-11-26 14:50:00', NULL),
(322, 94, 147, 'Janine Claire Domingo', 5, 'My go-to spot whenever I am in the Poblacion.', NULL, NULL, '2025-11-27 12:20:00', NULL),
(323, 94, 148, 'Andrea Louise Aguilar', 5, 'Milkshakes match perfectly with the burger.', NULL, NULL, '2025-11-28 16:35:00', NULL),
(324, 94, 149, 'Katrina Marie Lopez', 4, 'Consistent taste every time we visit.', NULL, NULL, '2025-11-28 19:15:00', NULL),
(325, 94, 150, 'Melissa Joy Sarmiento', 5, 'Last food trip for November, worth it!', NULL, NULL, '2025-11-29 11:45:00', NULL),
(326, 94, 151, 'Clarissa Jane Ferrer', 5, 'Also tried the wings, spicy and good!', NULL, NULL, '2025-11-29 17:55:00', NULL),
(327, 94, 152, 'Rachel Ann Torres', 5, 'Very accessible for commuters.', NULL, NULL, '2025-11-30 09:30:00', NULL),
(328, 94, 153, 'Nicole Rose Zamora', 4, 'Waiting for their next Buy 1 Take 1 promo.', NULL, NULL, '2025-11-30 13:15:00', NULL),
(329, 94, 154, 'Kimberly Kate Lim', 5, 'Great way to end the week with good food.', NULL, NULL, '2025-11-30 18:10:00', NULL),
(332, 99, 116, 'Juan Dela Cruz', 5, 'Best Kinalas in Libmanan! The sauce is rich and thick.', NULL, NULL, '2025-11-01 09:30:00', NULL),
(333, 99, 117, 'Maria Santos', 5, 'Perfect comfort food for the rainy weather.', NULL, NULL, '2025-11-01 16:15:00', NULL),
(334, 99, 118, 'Juan Carlos Reyes', 4, 'Serving size is generous for the price.', NULL, NULL, '2025-11-02 11:45:00', NULL),
(335, 99, 119, 'Mark Anthony dela Cruz', 5, 'The meat is very tender, melts in your mouth.', NULL, NULL, '2025-11-03 13:20:00', NULL),
(336, 99, 120, 'Jose Miguel Santos', 5, 'Authentic Bicolano taste. Highly recommended.', NULL, NULL, '2025-11-04 10:00:00', NULL),
(337, 99, 121, 'Rafael Luis Navarro', 5, 'Love the chili garlic oil pairing with the soup.', NULL, NULL, '2025-11-05 18:30:00', NULL),
(338, 99, 122, 'Christian Gabriel Ramos', 4, 'Very affordable meal for students like me.', NULL, NULL, '2025-11-06 12:10:00', NULL),
(339, 99, 123, 'Maria Isabella Cruz', 5, 'Soup was served piping hot, just how I like it.', NULL, NULL, '2025-11-07 15:45:00', NULL),
(340, 99, 124, 'Angelica Mae Flores', 5, 'Noodles are cooked perfectly, not soggy.', NULL, NULL, '2025-11-08 14:20:00', NULL),
(341, 99, 125, 'Jasmine Nicole Tan', 5, 'Always my go-to merienda spot.', NULL, NULL, '2025-11-08 17:00:00', NULL),
(342, 99, 126, 'Patricia Lourdes Mendoza', 4, 'The place is simple but the food is amazing.', NULL, NULL, '2025-11-09 11:15:00', NULL),
(343, 99, 127, 'Camille Joy Bautista', 5, 'I requested extra sauce and they happily gave it.', NULL, NULL, '2025-11-10 13:40:00', NULL),
(344, 99, 128, 'Alvin Joseph Soriano', 5, 'Delicious with egg and toasted bread.', NULL, NULL, '2025-11-11 09:30:00', NULL),
(345, 99, 129, 'Erika Louisa Dela Rosa', 5, 'Satisfied my Kinalas cravings today!', NULL, NULL, '2025-11-12 16:50:00', NULL),
(346, 99, 130, 'Dennis Angelo Mercado', 4, 'Good broth flavor, not too salty.', NULL, NULL, '2025-11-13 12:25:00', NULL),
(347, 99, 131, 'Bianca Rochelle Manalo', 5, 'Staff is quick to serve orders.', NULL, NULL, '2025-11-14 10:50:00', NULL),
(348, 99, 132, 'Jonathan Patrick Velasco', 5, 'Very \"sulit\" for the taste and quantity.', NULL, NULL, '2025-11-14 18:10:00', NULL),
(349, 99, 133, 'Karen Sheila Ignacio', 5, 'Brought my family here and they loved it.', NULL, NULL, '2025-11-15 11:55:00', NULL),
(350, 99, 134, 'Miguel Antonio Pascual', 4, 'Clean bowls and spoons, hygienic place.', NULL, NULL, '2025-11-16 15:35:00', NULL),
(351, 99, 135, 'Gabriel Angelo Fernandez', 5, 'Ordered takeout and it was still hot when I got home.', NULL, NULL, '2025-11-17 12:45:00', NULL),
(352, 99, 136, 'Joshua Kyle De Leon', 5, 'The best hangover cure soup.', NULL, NULL, '2025-11-18 08:30:00', NULL),
(353, 99, 137, 'Ryan Christopher Go', 5, 'So savory, I finished the whole bowl.', NULL, NULL, '2025-11-19 19:20:00', NULL),
(354, 99, 138, 'Patrick James Villanueva', 4, 'Parking is a bit tight but worth the visit.', NULL, NULL, '2025-11-20 13:10:00', NULL),
(355, 99, 139, 'Kevin Mark Espanto', 5, 'Classic Bicolano comfort food done right.', NULL, NULL, '2025-11-21 17:40:00', NULL),
(356, 99, 140, 'Justin Paul Ocampo', 5, 'If you like spicy food, put lots of chili!', NULL, NULL, '2025-11-22 12:15:00', NULL),
(357, 99, 141, 'Edward John Castillo', 5, 'Great lunch spot with officemates.', NULL, NULL, '2025-11-23 11:30:00', NULL),
(358, 99, 142, 'Bryan Anthony Diaz', 4, 'You can really taste the brain sauce flavor.', NULL, NULL, '2025-11-24 14:50:00', NULL),
(359, 99, 143, 'Vincent Karl Medina', 5, 'Tried the Log-log and it was delicious.', NULL, NULL, '2025-11-25 16:30:00', NULL),
(360, 99, 144, 'Adrian Luke Rivera', 5, 'Cheap but very filling meal.', NULL, NULL, '2025-11-26 10:45:00', NULL),
(361, 99, 145, 'Sofia Lorraine Gomez', 5, 'No wonder this place is always full.', NULL, NULL, '2025-11-27 13:00:00', NULL),
(362, 99, 146, 'Hannah Mae Pineda', 5, 'Meat toppings are generous.', NULL, NULL, '2025-11-28 18:00:00', NULL),
(363, 99, 147, 'Janine Claire Domingo', 4, 'Very distinct and savory gravy.', NULL, NULL, '2025-11-28 12:20:00', NULL),
(364, 99, 148, 'Andrea Louise Aguilar', 5, 'Ending November with a hot bowl of Kinalas.', NULL, NULL, '2025-11-29 09:40:00', NULL),
(365, 99, 149, 'Katrina Marie Lopez', 5, 'Never gets old. I eat here weekly.', NULL, NULL, '2025-11-29 17:10:00', NULL),
(366, 99, 150, 'Melissa Joy Sarmiento', 5, 'Highly recommended for visitors in Libmanan.', NULL, NULL, '2025-11-30 11:00:00', NULL),
(367, 99, 151, 'Clarissa Jane Ferrer', 4, 'Waiting time is short.', NULL, NULL, '2025-11-30 13:45:00', NULL),
(368, 99, 152, 'Rachel Ann Torres', 5, 'Simply the best Kinalan in town.', NULL, NULL, '2025-11-30 15:20:00', NULL),
(369, 99, 153, 'Nicole Rose Zamora', 5, 'My tummy is full and happy.', NULL, NULL, '2025-11-30 18:30:00', NULL),
(370, 99, 154, 'Kimberly Kate Lim', 5, 'Five stars for the broth alone!', NULL, NULL, '2025-11-30 19:45:00', NULL),
(371, 99, 52, 'Alex Edrian ', 5, 'Good food and great price. Must visit!', NULL, NULL, '2025-12-07 09:27:55', NULL),
(372, 79, 116, 'Juan Dela Cruz', 5, 'We had a wonderful family dinner here last night. The ambiance was very cozy and perfect for gatherings. We will definitely come back soon.', NULL, NULL, '2025-11-01 18:30:00', NULL),
(373, 79, 117, 'Maria Santos', 5, 'The food tastes exactly like homemade cooking, which I love. The staff were also very attentive to our needs throughout the meal. Highly recommended for comfort food lovers.', NULL, NULL, '2025-11-02 12:15:00', NULL),
(374, 79, 118, 'Juan Carlos Reyes', 4, 'Service was surprisingly fast even though the place was packed. The dishes were served hot and fresh from the kitchen. I really enjoyed the Sinigang.', NULL, NULL, '2025-11-02 19:45:00', NULL),
(375, 79, 119, 'Mark Anthony dela Cruz', 5, 'This is my favorite spot for lunch breaks because the serving time is quick. The prices are very affordable for the serving size. It is a great value for money.', NULL, NULL, '2025-11-03 12:05:00', NULL);
INSERT INTO `reviews` (`id`, `fbowner_id`, `user_id`, `reviewer_name`, `rating`, `comment`, `photo`, `video`, `created_at`, `updated_at`) VALUES
(376, 79, 120, 'Jose Miguel Santos', 4, 'The restaurant is clean and well-maintained. I felt comfortable eating here with my kids. The only downside was the limited parking space.', NULL, NULL, '2025-11-04 13:30:00', NULL),
(377, 79, 121, 'Rafael Luis Navarro', 5, 'You have to try their specialty dishes, they are absolutely delicious. The meat was tender and full of flavor. It was a satisfying dining experience.', NULL, NULL, '2025-11-05 17:20:00', NULL),
(378, 79, 122, 'Christian Gabriel Ramos', 5, 'Arjomel never disappoints when it comes to taste. Everything on the menu looks appetizing. I am glad we decided to stop by here.', NULL, NULL, '2025-11-06 11:50:00', NULL),
(379, 79, 123, 'Maria Isabella Cruz', 4, 'A nice place to unwind and eat good food after work. The environment is not too noisy. I liked the variety of drinks available.', NULL, NULL, '2025-11-07 18:40:00', NULL),
(380, 79, 124, 'Angelica Mae Flores', 5, 'We celebrated my birthday here and the staff made it extra special. They helped arrange the tables for our big group. The food platters were wiped clean by my guests.', NULL, NULL, '2025-11-08 19:10:00', NULL),
(381, 79, 125, 'Jasmine Nicole Tan', 5, 'Stopped by for a quick breakfast and the coffee was great. The fried rice and egg combo started my day right. Will make this my morning routine.', NULL, NULL, '2025-11-09 08:30:00', NULL),
(382, 79, 126, 'Patricia Lourdes Mendoza', 4, 'Ordered takeout for dinner and the packaging was secure. The food was still warm when I got home. Portions are generous enough for sharing.', NULL, NULL, '2025-11-10 17:45:00', NULL),
(383, 79, 127, 'Camille Joy Bautista', 5, 'I have been eating here for years and the taste has remained consistent. It feels like eating in your own home. The owner is also very friendly to customers.', NULL, NULL, '2025-11-11 12:45:00', NULL),
(384, 79, 128, 'Alvin Joseph Soriano', 5, 'If you love spicy Bicolano food, this is the place to be. The Bicol Express had just the right amount of kick. I finished two cups of rice effortlessly.', NULL, NULL, '2025-11-12 13:15:00', NULL),
(385, 79, 129, 'Erika Louisa Dela Rosa', 4, 'Don’t forget to try their desserts after your meal. It was the perfect way to cleanse the palate. Not too sweet, just right.', NULL, NULL, '2025-11-13 14:20:00', NULL),
(386, 79, 130, 'Dennis Angelo Mercado', 4, 'It was a bit busy during lunch hour but the wait was worth it. The staff managed the crowd well. The food quality made up for the waiting time.', NULL, NULL, '2025-11-14 12:30:00', NULL),
(387, 79, 131, 'Bianca Rochelle Manalo', 5, 'The lighting inside is warm and inviting for a dinner date. My partner and I enjoyed the quiet atmosphere. The grilled dishes were cooked to perfection.', NULL, NULL, '2025-11-15 19:30:00', NULL),
(388, 79, 132, 'Jonathan Patrick Velasco', 5, 'We ordered the group platter and it was more than enough for 5 people. Everything tasted fresh and savory. It’s a great option for barkada eat-outs.', NULL, NULL, '2025-11-16 11:40:00', NULL),
(389, 79, 133, 'Karen Sheila Ignacio', 5, 'My children are picky eaters but they loved the fried chicken here. It is very family-friendly with a nice menu selection. We had a stress-free meal.', NULL, NULL, '2025-11-17 13:00:00', NULL),
(390, 79, 134, 'Miguel Antonio Pascual', 4, 'The soup was served boiling hot which is perfect for this weather. It really warmed us up. The broth was rich and flavorful.', NULL, NULL, '2025-11-18 18:15:00', NULL),
(391, 79, 135, 'Gabriel Angelo Fernandez', 5, 'Rice servings are generous so you definitely won’t leave hungry. The viands are savory and pair well with plain rice. A solid 10/10 experience.', NULL, NULL, '2025-11-19 12:10:00', NULL),
(392, 79, 136, 'Joshua Kyle De Leon', 4, 'They have a good selection of refreshing drinks. The iced tea was not watered down unlike other places. It complemented the meal nicely.', NULL, NULL, '2025-11-19 14:45:00', NULL),
(393, 79, 137, 'Ryan Christopher Go', 5, 'The waiters were smiling and very helpful with menu recommendations. Good customer service makes the food taste even better. Keep up the good work!', NULL, NULL, '2025-11-20 11:25:00', NULL),
(394, 79, 138, 'Patrick James Villanueva', 5, 'The location is very easy to find and accessible. It’s convenient for meetings or quick catch-ups. I will recommend this to my colleagues.', NULL, NULL, '2025-11-20 16:30:00', NULL),
(395, 79, 139, 'Kevin Mark Espanto', 5, 'You get more than what you pay for here. The prices are cheap but the quality is high. It is definitely one of the best budget-friendly spots in town.', NULL, NULL, '2025-11-21 13:50:00', NULL),
(396, 79, 140, 'Justin Paul Ocampo', 5, 'Nothing beats Arjomel’s Bulalo on a rainy day. The beef was so soft it falls off the bone. It is the ultimate comfort food.', NULL, NULL, '2025-11-22 11:30:00', NULL),
(397, 79, 141, 'Edward John Castillo', 5, 'The crispy pata was cooked to perfection, crunchy skin and tender meat. It was the highlight of our dinner. Make sure to order it when you visit.', NULL, NULL, '2025-11-23 19:00:00', NULL),
(398, 79, 142, 'Bryan Anthony Diaz', 4, 'I appreciate that the seafood tasted fresh and not frozen. You can really taste the difference in quality. The grilled squid was amazing.', NULL, NULL, '2025-11-24 12:40:00', NULL),
(399, 79, 143, 'Vincent Karl Medina', 5, 'For those looking for healthy options, their vegetable dishes are great. The Chopsuey was crunchy and flavorful. It is nice to have balanced meal options.', NULL, NULL, '2025-11-25 13:15:00', NULL),
(400, 79, 144, 'Adrian Luke Rivera', 4, 'The air conditioning inside was cool and comfortable. It is a nice escape from the heat outside. We stayed a bit longer just to relax.', NULL, NULL, '2025-11-26 14:00:00', NULL),
(401, 79, 145, 'Sofia Lorraine Gomez', 5, 'I liked the background music, it was not too loud. It added to the relaxing vibe of the restaurant. A perfect place to chill.', NULL, NULL, '2025-11-27 18:20:00', NULL),
(402, 79, 146, 'Hannah Mae Pineda', 4, 'The comfort rooms were clean and smelled fresh. It shows that the management cares about hygiene. It is a big plus for me.', NULL, NULL, '2025-11-27 11:10:00', NULL),
(403, 79, 147, 'Janine Claire Domingo', 5, 'Overall, I would rate this place a solid 5 stars. From food to service, everything was excellent. I will bring my parents here next time.', NULL, NULL, '2025-11-28 17:50:00', NULL),
(404, 79, 148, 'Andrea Louise Aguilar', 5, 'Ending the month with a treat at Arjomel was a good decision. We left with full stomachs and happy smiles. Can’t wait to return next month.', NULL, NULL, '2025-11-29 12:00:00', NULL),
(405, 79, 149, 'Katrina Marie Lopez', 5, 'This is officially our barkada’s new hangout spot. The food is good for sharing and the vibe is chill. See you again soon!', NULL, NULL, '2025-11-29 19:30:00', NULL),
(406, 79, 150, 'Melissa Joy Sarmiento', 5, 'Every time I visit, I try a different dish and they are all good. I have yet to be disappointed. The menu has so much variety to offer.', NULL, NULL, '2025-11-30 11:45:00', NULL),
(407, 79, 151, 'Clarissa Jane Ferrer', 5, 'The variety in the menu ensures there is something for everyone. Even my picky friends found something they liked. Great job to the kitchen team.', NULL, NULL, '2025-11-30 13:20:00', NULL),
(408, 79, 152, 'Rachel Ann Torres', 5, 'Kudos to the chef for the amazing flavors. You can tell that the food is prepared with care. It truly tastes authentic.', NULL, NULL, '2025-11-30 15:40:00', NULL),
(409, 79, 153, 'Nicole Rose Zamora', 4, 'If you want a quick but satisfying bite, go here. The service is efficient and the food is filling. Perfect for busy days.', NULL, NULL, '2025-11-30 16:50:00', NULL),
(410, 79, 154, 'Kimberly Kate Lim', 5, 'My final verdict is that Arjomel is a must-visit in Libmanan. The combination of good food and great service is rare. Two thumbs up!', NULL, NULL, '2025-11-30 20:10:00', NULL),
(411, 103, 116, 'Juan Dela Cruz', 5, 'Perfect na perfect ang Bulalo dito lalo na pag maulan. The soup is very rich and flavorful, halatang pinakuluan ng matagal. Babalik kami dito for sure.', NULL, NULL, '2025-11-01 11:30:00', NULL),
(412, 103, 117, 'Maria Santos', 5, 'Grabe ang siram kan Bulalo ninda, talagang malasa ang sabaw. Garo luto ni mama sa harong kaya nakaka-miss. Very comforting food talaga.', NULL, NULL, '2025-11-01 18:15:00', NULL),
(413, 103, 118, 'Juan Carlos Reyes', 5, 'The bone marrow was huge and incredibly sinful but delicious. I suggest asking for extra soup because it is free. Truly the best Bulalo in town.', NULL, NULL, '2025-11-02 12:45:00', NULL),
(414, 103, 119, 'Mark Anthony dela Cruz', 4, 'Solid ang anghang ng chili oil nila, bagay na bagay sa mainit na sabaw. Nakakawala ng hangover pag humigop ka nito. Sulit na sulit ang bayad.', NULL, NULL, '2025-11-03 10:20:00', NULL),
(415, 103, 120, 'Jose Miguel Santos', 5, 'Dai ako nagbasol na nagkakan kami digdi, siramhon baga! The beef was so tender it falls off the bone easily. Highly recommended for family lunches.', NULL, NULL, '2025-11-04 13:00:00', NULL),
(416, 103, 121, 'Rafael Luis Navarro', 4, 'Service was fast and the staff were very accommodating to our requests. The place is simple but the food quality is top-notch. A great spot for lunch.', NULL, NULL, '2025-11-05 11:50:00', NULL),
(417, 103, 122, 'Christian Gabriel Ramos', 5, 'Sobrang lambot ng karne, hindi ka mahihirapan nguyaain. Naparami ako ng rice dahil sa sarap ng sabaw. The best comfort food talaga.', NULL, NULL, '2025-11-06 17:10:00', NULL),
(418, 103, 123, 'Maria Isabella Cruz', 5, 'Sulit ang biyahe namin, sobrang sarap ng Kinalas at Bulalo. The serving size is generous for the price. We left the restaurant very satisfied.', NULL, NULL, '2025-11-07 19:30:00', NULL),
(419, 103, 124, 'Angelica Mae Flores', 5, 'This is undeniably the best place to fix your Bulalo cravings. The meat is fresh and not smelling gamey at all. Everything tasted clean and savory.', NULL, NULL, '2025-11-08 12:15:00', NULL),
(420, 103, 125, 'Jasmine Nicole Tan', 4, 'Sakto lang ang timpla, hindi masyadong maalat. Masarap isawsaw ang karne sa patis na may calamansi at sili. Babalik-balikan talaga namin ito.', NULL, NULL, '2025-11-09 13:40:00', NULL),
(421, 103, 126, 'Patricia Lourdes Mendoza', 5, 'Iyo ini ang hanap ko na namit, garo luto sa probinsya. Authentic Bicolano comfort food at its finest. Mari na kamo digdi, dai kamo magsisisi.', NULL, NULL, '2025-11-10 18:25:00', NULL),
(422, 103, 127, 'Camille Joy Bautista', 4, 'The restaurant is clean and well-ventilated despite being busy. It’s nice to eat hot soup when the place is airy. Good job to the management.', NULL, NULL, '2025-11-11 11:20:00', NULL),
(423, 103, 128, 'Alvin Joseph Soriano', 5, 'Busog na busog kami, hindi tinipid sa sahog. The vegetables in the Bulalo were still crunchy and fresh. Definitely worth every peso.', NULL, NULL, '2025-11-12 14:05:00', NULL),
(424, 103, 129, 'Erika Louisa Dela Rosa', 5, 'Masiram ang kinalas ninda, daog pa ang ibang sikat na kinalasan. The sauce is thick and flavorful, perfect with egg. I will recommend this to my friends.', NULL, NULL, '2025-11-13 09:50:00', NULL),
(425, 103, 130, 'Dennis Angelo Mercado', 5, 'Portions are huge, good for sharing with friends. We ordered one big bowl and it was enough for three of us. Great value for groups.', NULL, NULL, '2025-11-13 19:15:00', NULL),
(426, 103, 131, 'Bianca Rochelle Manalo', 4, 'Mabilis ang service kahit lunch time kami pumunta. Mainit pa yung sabaw nung sinerve samin. Nakakawala ng pagod kumain dito.', NULL, NULL, '2025-11-14 12:55:00', NULL),
(427, 103, 132, 'Jonathan Patrick Velasco', 5, 'My parents loved the food here, especially the broth. It’s a great place to bring your family for a Sunday lunch. We had a wonderful time.', NULL, NULL, '2025-11-15 11:40:00', NULL),
(428, 103, 133, 'Karen Sheila Ignacio', 5, 'Napakasiram kan utak! Sinful pero worth it. The broth was milky and rich from the bone marrow. I will definitely eat here again.', NULL, NULL, '2025-11-16 13:30:00', NULL),
(429, 103, 134, 'Miguel Antonio Pascual', 5, 'Ang sarap humigop ng sabaw lalo na pag galing sa trabaho. Nakaka-relax yung lasa ng Bulalo nila. Simple lang yung lugar pero panalo ang lasa.', NULL, NULL, '2025-11-17 18:45:00', NULL),
(430, 103, 135, 'Gabriel Angelo Fernandez', 4, 'Consistency is key, and Batot always delivers good taste. I have been here multiple times and it never disappoints. Always a satisfying meal.', NULL, NULL, '2025-11-18 12:10:00', NULL),
(431, 103, 136, 'Joshua Kyle De Leon', 5, 'Dakul ang laman, bako puro buto lang. You really get what you pay for here. It’s one of the most honest Bulaluhan in Libmanan.', NULL, NULL, '2025-11-19 11:15:00', NULL),
(432, 103, 137, 'Ryan Christopher Go', 4, 'Nag-request ako ng extra calamansi at binigyan naman agad. Very attentive ang mga staff sa needs ng customer. Masarap ang timpla ng sabaw.', NULL, NULL, '2025-11-20 13:20:00', NULL),
(433, 103, 138, 'Patrick James Villanueva', 4, 'Parking is a bit limited but the food makes up for it. Just make sure to come early to get a table. The Bulalo is legendary.', NULL, NULL, '2025-11-21 10:30:00', NULL),
(434, 103, 139, 'Kevin Mark Espanto', 5, 'Dai nakakasawa ang lasa, pwede mo araw-arawin. The soup is light enough but packed with beefy flavor. Favorite ko na ini.', NULL, NULL, '2025-11-21 17:50:00', NULL),
(435, 103, 140, 'Justin Paul Ocampo', 5, 'Authentic Filipino taste that hits the spot. If you want real Bulalo without fancy gimmicks, this is the place. Highly recommended for travelers.', NULL, NULL, '2025-11-22 12:35:00', NULL),
(436, 103, 141, 'Edward John Castillo', 5, 'Sinubukan namin yung Bulalo Steak at masarap din pala. Pero iba pa rin talaga yung original na may sabaw. The best for rainy days.', NULL, NULL, '2025-11-23 18:00:00', NULL),
(437, 103, 142, 'Bryan Anthony Diaz', 5, 'Maray na aga, nag-breakfast kami digdi ng Kinalas. Perfect way to start the day. The hot soup wakes you up instantly.', NULL, NULL, '2025-11-24 08:45:00', NULL),
(438, 103, 143, 'Vincent Karl Medina', 5, 'The bone marrow was trembling, so fatty and good! It’s a guilty pleasure that I keep coming back for. Don’t tell my doctor!', NULL, NULL, '2025-11-25 13:10:00', NULL),
(439, 103, 144, 'Adrian Luke Rivera', 5, 'Dinala ko ang barkada ko dito at nag-enjoy kaming lahat. Masarap ang kwentuhan habang humihigop ng mainit na sabaw. Ang presyo ay swak sa budget.', NULL, NULL, '2025-11-26 19:20:00', NULL),
(440, 103, 145, 'Sofia Lorraine Gomez', 4, 'Barato pero masiram, sulit na sulit ang kwarta mo. Even if it is affordable, they don’t compromise on quality. I will come back again.', NULL, NULL, '2025-11-27 11:55:00', NULL),
(441, 103, 146, 'Hannah Mae Pineda', 4, 'Compared to other Bulaluhan in the area, Batot has the richer broth. You can tell they simmered the bones for hours. The taste is superior.', NULL, NULL, '2025-11-28 12:50:00', NULL),
(442, 103, 147, 'Janine Claire Domingo', 5, 'Sobrang busog, hindi ko naubos yung kanin ko. Ang dami kasi ng serving ng ulam. Good for sharing talaga siya.', NULL, NULL, '2025-11-28 17:30:00', NULL),
(443, 103, 148, 'Andrea Louise Aguilar', 5, 'Ending the month with a hearty bowl of Bulalo. This place is perfect for celebrations or just casual dining. The food never fails to impress.', NULL, NULL, '2025-11-29 12:20:00', NULL),
(444, 103, 149, 'Katrina Marie Lopez', 5, 'Sarap ng pechay at mais na kasama sa Bulalo. Fresh na fresh ang mga gulay nila. Balanse ang lasa ng karne at gulay.', NULL, NULL, '2025-11-29 18:40:00', NULL),
(445, 103, 150, 'Melissa Joy Sarmiento', 5, 'I appreciate that the soup is not too oily unlike other places. It feels cleaner and lighter on the stomach. A very pleasant dining experience.', NULL, NULL, '2025-11-30 11:30:00', NULL),
(446, 103, 151, 'Clarissa Jane Ferrer', 5, 'Iyo na ini ang pinakamasiram na Bulalo sa Libmanan. No doubt about it, the taste is legendary. Keep up the good work Batot!', NULL, NULL, '2025-11-30 13:45:00', NULL),
(447, 103, 152, 'Rachel Ann Torres', 5, 'Nasira ang diet ko dahil sa sarap ng pagkain! Mapapa-extra rice ka talaga ng wala sa oras. Highly recommended for food lovers.', NULL, NULL, '2025-11-30 16:15:00', NULL),
(448, 103, 153, 'Nicole Rose Zamora', 5, 'Fast service and hot food, what more can you ask for? The staff were quick to refill our soup. Excellent customer service.', NULL, NULL, '2025-11-30 19:10:00', NULL),
(449, 103, 154, 'Kimberly Kate Lim', 5, 'Sa uulitin, babalik kami kasama ang buong pamilya. Masiram an pagkakan, malinig an lugar. Two thumbs up for Batot Bulaluhan.', NULL, NULL, '2025-11-30 20:00:00', NULL),
(450, 81, 116, 'Juan Dela Cruz', 5, 'Bobly Food Plaza serves one of the best Bulalo in town. The soup was piping hot and very flavorful. Perfect for a quick lunch break.', NULL, NULL, '2025-11-01 11:30:00', NULL),
(451, 81, 117, 'Maria Santos', 5, 'Ang lambot ng karne, halos humiwalay na sa buto. Sobrang sarap ng sabaw lalo na ngayong maulan ang panahon. Babalik kami dito siguradong sigurado.', NULL, NULL, '2025-11-02 12:15:00', NULL),
(452, 81, 118, 'Juan Carlos Reyes', 5, 'Masiram an Bulalo ninda digdi, garo luto sa harong. The serving size is generous and good for sharing. It really satisfied my cravings.', NULL, NULL, '2025-11-03 18:45:00', NULL),
(453, 81, 119, 'Mark Anthony dela Cruz', 4, 'The bone marrow was rich and creamy, though a bit sinful! I paired it with extra rice and patis with calamansi. A very satisfying meal overall.', NULL, NULL, '2025-11-04 13:20:00', NULL),
(454, 81, 120, 'Jose Miguel Santos', 5, 'Sulit na sulit ang bayad dito, hindi tinipid sa sahog. Gustong-gusto ng mga anak ko yung sabaw. This is our new favorite spot in the plaza.', NULL, NULL, '2025-11-05 17:30:00', NULL),
(455, 81, 121, 'Rafael Luis Navarro', 5, 'Dai ako nagbasol na nag-order ako kaining Bulalo. The broth tastes natural and not full of MSG. It’s clean and delicious comfort food.', NULL, NULL, '2025-11-06 19:10:00', NULL),
(456, 81, 122, 'Christian Gabriel Ramos', 4, 'Service was fast, our Bulalo arrived within 15 minutes. The vegetables like cabbage and corn were fresh and crunchy. Good balance of meat and veggies.', NULL, NULL, '2025-11-08 12:40:00', NULL),
(457, 81, 123, 'Maria Isabella Cruz', 5, 'Napaka-linamnam ng sabaw, ramdam mo yung lasa ng baka. Hindi siya malansa gaya ng iba. Highly recommended ito sa mga mahilig sa sabaw.', NULL, NULL, '2025-11-09 13:15:00', NULL),
(458, 81, 124, 'Angelica Mae Flores', 5, 'Sabaw pa sana, ulam na! Very rich ang flavor kan broth ninda. I will definitely bring my friends here next time.', NULL, NULL, '2025-11-10 18:00:00', NULL),
(459, 81, 125, 'Jasmine Nicole Tan', 4, 'The place is simple but the food quality is excellent. The Bulalo is comparable to more expensive restaurants. Great value for money.', NULL, NULL, '2025-11-11 11:50:00', NULL),
(460, 81, 126, 'Patricia Lourdes Mendoza', 5, 'Tamang-tama ang timpla, hindi masyadong maalat. Masarap humigop ng mainit na sabaw habang nagpapahinga. Very relaxing dining experience.', NULL, NULL, '2025-11-12 14:30:00', NULL),
(461, 81, 127, 'Camille Joy Bautista', 5, 'I was surprised by how tender the beef was. Usually, plaza food can be tough, but this was perfect. Bobly Food Plaza exceeded my expectations.', NULL, NULL, '2025-11-13 16:45:00', NULL),
(462, 81, 128, 'Alvin Joseph Soriano', 5, 'An siram kan Bulalo, daog pa ang ibang restawran. Affordable pa kaya swak sa budget. Iyo na ini ang best lunch spot ko.', NULL, NULL, '2025-11-15 11:25:00', NULL),
(463, 81, 129, 'Erika Louisa Dela Rosa', 4, 'Even during busy lunch hours, the soup was served hot. The corn added a nice sweetness to the broth. Will surely eat here again.', NULL, NULL, '2025-11-16 12:55:00', NULL),
(464, 81, 130, 'Dennis Angelo Mercado', 5, 'Malinis ang pagkakagawa, walang lansa yung karne. Masarap siya i-partner sa fish sauce at sili. Busog na busog ako pagkatapos kumain.', NULL, NULL, '2025-11-17 19:20:00', NULL),
(465, 81, 131, 'Bianca Rochelle Manalo', 5, 'This is pure comfort food. The beef shanks were generous with plenty of meat. It’s definitely a must-try when at Bobly Food Plaza.', NULL, NULL, '2025-11-18 13:40:00', NULL),
(466, 81, 132, 'Jonathan Patrick Velasco', 5, 'Dakul ang laman, bako puro buto lang an binibigay. Worth it ang pag-hulat kasi bagong luto. Two thumbs up para sa cook.', NULL, NULL, '2025-11-19 17:15:00', NULL),
(467, 81, 133, 'Karen Sheila Ignacio', 4, 'The ambiance is casual, but the food is serious business. The Bulalo soup is thick and savory. Perfect for a heavy meal.', NULL, NULL, '2025-11-20 10:30:00', NULL),
(468, 81, 134, 'Miguel Antonio Pascual', 5, 'Staff were very polite when I asked for extra soup refill. It’s nice that they allow refills for the broth. Great customer service.', NULL, NULL, '2025-11-22 14:10:00', NULL),
(469, 81, 135, 'Gabriel Angelo Fernandez', 5, 'Ang sarap ng utak ng baka, grabe ang cholesterol pero minsan lang naman! Bagay na bagay sa mainit na kanin. Mapapa-extra rice ka talaga.', NULL, NULL, '2025-11-23 18:50:00', NULL),
(470, 81, 136, 'Joshua Kyle De Leon', 5, 'Mainiton an sabaw, nakakahalighod sa mati. Perfect comfort food after a tiring day. This place never disappoints me.', NULL, NULL, '2025-11-24 19:30:00', NULL),
(471, 81, 137, 'Ryan Christopher Go', 4, 'Consistent ang lasa, ilang beses na kami kumain dito pareho pa rin. The beef is always soft and easy to chew. Reliable spot for Bulalo.', NULL, NULL, '2025-11-25 12:20:00', NULL),
(472, 81, 138, 'Patrick James Villanueva', 5, 'Dinala ko ang pamilya ko dito at nagustuhan nila lahat. Malaki ang serving kaya sulit para sa grupo. Happy tummy kaming lahat.', NULL, NULL, '2025-11-26 13:45:00', NULL),
(473, 81, 139, 'Kevin Mark Espanto', 4, 'Barato pero dakul ang serving, swak sa budget kan estudyante. Even if it is cheap, the quality is good. I will eat here again.', NULL, NULL, '2025-11-27 16:40:00', NULL),
(474, 81, 140, 'Justin Paul Ocampo', 5, 'Ending November with a hot bowl of Bulalo from Bobly. It was exactly what I was craving for. Highly recommended to everyone.', NULL, NULL, '2025-11-29 11:35:00', NULL),
(475, 81, 141, 'Edward John Castillo', 5, 'Rekomendado ko ito sa mga naghahanap ng lutong bahay na lasa. Hindi tinipid sa rekados at pampalasa. Panalo ang lasa!', NULL, NULL, '2025-11-29 17:55:00', NULL),
(476, 81, 142, 'Bryan Anthony Diaz', 5, 'Dai nakakasawa ang namit, pwede mo araw-arawin. It’s simple food but cooked perfectly. I am a satisfied customer.', NULL, NULL, '2025-11-30 09:30:00', NULL),
(477, 81, 143, 'Vincent Karl Medina', 5, 'The meat was so tender it literally melts in your mouth. The broth had a deep beefy flavor. 5 stars for this food plaza gem.', NULL, NULL, '2025-11-30 13:00:00', NULL),
(478, 81, 144, 'Adrian Luke Rivera', 4, 'Simpleng lugar pero napakasarap ng pagkain. Mas pipiliin ko pa kumain dito kesa sa mahal na restaurant. Good food, good mood.', NULL, NULL, '2025-11-30 15:20:00', NULL),
(479, 81, 145, 'Sofia Lorraine Gomez', 5, 'Hands down the best Bulalo experience in this plaza. Fast service, clean food, and affordable price. Good job Bobly Food Plaza!', NULL, NULL, '2025-11-30 19:40:00', NULL),
(480, 102, 116, 'Juan Dela Cruz', 5, 'Jaynaro serves the best Pinoy-style pizza in town. The sauce is sweet and savory just how I like it. My kids really enjoyed the meal.', NULL, NULL, '2025-12-01 11:30:00', NULL),
(481, 102, 117, 'Maria Santos', 5, 'Ang daming cheese at toppings, hindi tinipid. Sulit na sulit ang binayad namin para sa Overload Pizza. Babalik kami dito siguradong sigurado.', NULL, NULL, '2025-12-01 13:15:00', NULL),
(482, 102, 118, 'Juan Carlos Reyes', 5, 'Masiram an tinapay ninda, malumoy maski bugnaw na. I really like their Hawaiian flavor. Perfect for merienda with the family.', NULL, NULL, '2025-12-01 16:45:00', NULL),
(483, 102, 119, 'Mark Anthony dela Cruz', 4, 'The crust is not too thick and not too thin, it is just right. The pizza arrived hot and fresh from the oven. Great value for money.', NULL, NULL, '2025-12-01 18:20:00', NULL),
(484, 102, 120, 'Jose Miguel Santos', 5, 'Tried their Pepperoni pizza and it was delicious. The edges were crispy and the cheese was gooey. Highly recommended for pizza lovers.', NULL, NULL, '2025-12-01 19:10:00', NULL),
(485, 102, 121, 'Rafael Luis Navarro', 5, 'Sobrang bango ng pizza, amoy pa lang gutom ka na. Masarap ipares sa softdrinks kasama ang barkada. Panalo ang lasa!', NULL, NULL, '2025-12-02 12:40:00', NULL),
(486, 102, 122, 'Christian Gabriel Ramos', 4, 'Dai nakakasawa ang namit, pwede mo araw-arawin. It’s affordable but tastes premium. I will order again for my office snacks.', NULL, NULL, '2025-12-02 15:30:00', NULL),
(487, 102, 123, 'Maria Isabella Cruz', 5, 'We ordered the family size and it was huge. Everyone in the house got a slice and was satisfied. Jaynaro never disappoints.', NULL, NULL, '2025-12-02 17:55:00', NULL),
(488, 102, 124, 'Angelica Mae Flores', 5, 'Love the generous amount of ham and pineapple. The balance of flavors is perfect. This is my go-to pizza place in Libmanan.', NULL, NULL, '2025-12-02 19:00:00', NULL),
(489, 102, 125, 'Jasmine Nicole Tan', 4, 'Mabilis ang service, mainit pa nung sinerve samin. Gustong gusto ko yung texture ng dough nila. Hindi siya matigas kainin.', NULL, NULL, '2025-12-03 11:20:00', NULL),
(490, 102, 126, 'Patricia Lourdes Mendoza', 5, 'Barato pero dakul ang toppings, swak sa budget. Even students can afford to eat good pizza here. Two thumbs up!', NULL, NULL, '2025-12-03 13:45:00', NULL),
(491, 102, 127, 'Camille Joy Bautista', 5, 'The cheesy bacon pizza is a must-try! The cheese stretches when you pull a slice. It was an amazing dining experience.', NULL, NULL, '2025-12-03 16:10:00', NULL),
(492, 102, 128, 'Alvin Joseph Soriano', 5, 'Their All-Meat pizza is loaded with flavor. I was full after just two slices because it is heavy. Definitely worth the price.', NULL, NULL, '2025-12-03 18:30:00', NULL),
(493, 102, 129, 'Erika Louisa Dela Rosa', 4, 'Nag-takeout ako para sa bahay at naubos agad ng mga kapatid ko. Sabi nila ang sarap daw ng sauce. Oorder ulit ako bukas.', NULL, NULL, '2025-12-04 10:50:00', NULL),
(494, 102, 130, 'Dennis Angelo Mercado', 5, 'Iyo na ini ang pinakamasiram na pizza na natikman ko digdi. The ingredients taste fresh and high quality. Keep up the good work Jaynaro!', NULL, NULL, '2025-12-04 12:15:00', NULL),
(495, 102, 131, 'Bianca Rochelle Manalo', 5, 'Perfect place to hang out with friends. We shared a large pizza and it was enough for the group. Good food and good vibes.', NULL, NULL, '2025-12-04 15:40:00', NULL),
(496, 102, 132, 'Jonathan Patrick Velasco', 4, 'The crust was crispy on the outside but soft on the inside. I appreciate that it wasn’t too oily. A very well-made pizza.', NULL, NULL, '2025-12-04 17:25:00', NULL),
(497, 102, 133, 'Karen Sheila Ignacio', 5, 'Solid ang lasa, lasang mamahalin. Hindi ka magsisisi sa binayad mo. This is now my favorite pizza joint.', NULL, NULL, '2025-12-04 19:50:00', NULL),
(498, 102, 134, 'Miguel Antonio Pascual', 5, 'Finally tried Jaynaro Pizza and I was impressed. The cheese blend they use is very tasty. I will bring my parents here next time.', NULL, NULL, '2025-12-05 11:35:00', NULL),
(499, 102, 135, 'Gabriel Angelo Fernandez', 5, 'Grabe, apaw-apaw ang toppings! Hindi tulad sa iba na tinapay lang ang malasa. Dito talagang busog ka sa laman.', NULL, NULL, '2025-12-05 13:00:00', NULL),
(500, 102, 136, 'Joshua Kyle De Leon', 5, 'Masiram ipares sa Coke ang pizza ninda. Great treat to end the work week. I highly recommend the Beef and Mushroom flavor.', NULL, NULL, '2025-12-05 18:15:00', NULL),
(501, 102, 137, 'Ryan Christopher Go', 4, 'Service was quick and the staff were polite. The pizza was served fresh from the oven. I burned my tongue a little but it was worth it!', NULL, NULL, '2025-12-05 20:00:00', NULL),
(502, 102, 138, 'Patrick James Villanueva', 5, 'Saturday night pizza party with the family. We ordered three boxes and finished them all. Jaynaro is always a hit in our household.', NULL, NULL, '2025-12-06 18:30:00', NULL),
(503, 102, 139, 'Kevin Mark Espanto', 5, 'Kung naghahanap ka ng masarap na pizza, dito ka na. Hindi ka bibiguin ng lasa. The best among the local pizza shops.', NULL, NULL, '2025-12-06 12:20:00', NULL),
(504, 102, 140, 'Justin Paul Ocampo', 5, 'Dakulaon ang serving, good for sharing talaga. The dough has a nice chewiness to it. Very satisfied customer here.', NULL, NULL, '2025-12-06 14:45:00', NULL),
(505, 102, 141, 'Edward John Castillo', 4, 'The pizza is not greasy which is a huge plus. You can taste the freshness of the bell peppers and onions. Healthy tasting but delicious.', NULL, NULL, '2025-12-06 16:10:00', NULL),
(506, 102, 142, 'Bryan Anthony Diaz', 5, 'Sunday brunch was made better with this pizza. It is so cheesy and savory. Even my grandmother liked it because the bread was soft.', NULL, NULL, '2025-12-07 10:30:00', NULL),
(507, 102, 143, 'Vincent Karl Medina', 5, 'Walang tapon, pati gilid ng tinapay masarap kainin. Crunchy siya at malasa. Oorder ulit kami sa susunod.', NULL, NULL, '2025-12-07 13:50:00', NULL),
(508, 102, 144, 'Adrian Luke Rivera', 5, 'Garo ako nasa Manila sa siram kan pizza. Standard is high quality. I am glad we have Jaynaro in Libmanan.', NULL, NULL, '2025-12-07 17:15:00', NULL),
(509, 102, 145, 'Sofia Lorraine Gomez', 5, '5 stars for the food and service. Everything was perfect from ordering to eating. A great way to start December.', NULL, NULL, '2025-12-07 19:40:00', NULL),
(510, 101, 116, 'Juan Dela Cruz', 5, 'It feels like eating in your own dining room. The food tastes like authentic home-cooked meals. I really missed this kind of cooking.', NULL, NULL, '2025-11-01 11:30:00', NULL),
(511, 101, 117, 'Maria Santos', 5, 'Ang asim ng Sinigang nila, swak na swak sa panlasa ko. Tamang-tama ang lambot ng baboy at dami ng gulay. Babalik-balikan ko ito.', NULL, NULL, '2025-11-02 12:45:00', NULL),
(512, 101, 118, 'Juan Carlos Reyes', 5, 'Masiram an Bicol Express ninda, tama lang ang anghang. It is creamy and spicy at the same time. Perfect ipares sa mainit na kanin.', NULL, NULL, '2025-11-03 18:15:00', NULL),
(513, 101, 119, 'Mark Anthony dela Cruz', 4, 'Great spot for a quick lunch break. They have a lot of viands to choose from daily. The Pork Adobo is my personal favorite.', NULL, NULL, '2025-11-05 13:00:00', NULL),
(514, 101, 120, 'Jose Miguel Santos', 5, 'Nakakabusog ang serving nila, sulit ang binayad mo. Hindi sila madamot sa sabaw. Talagang uuwi kang masaya ang tiyan.', NULL, NULL, '2025-11-07 19:20:00', NULL),
(515, 101, 121, 'Rafael Luis Navarro', 5, 'They offer a wide variety of Filipino dishes. Every time I visit, there is something new to try. I highly recommend their Caldereta.', NULL, NULL, '2025-11-08 11:40:00', NULL),
(516, 101, 122, 'Christian Gabriel Ramos', 5, 'Dakulon an sahog kan Laing ninda, bako puro dahon lang. It has a distinct savory taste that you won’t find elsewhere. Truly authentic Bicolano food.', NULL, NULL, '2025-11-09 12:20:00', NULL),
(517, 101, 123, 'Maria Isabella Cruz', 4, 'Mura na, masarap pa. Ito ang takbuhan namin pag gusto namin ng lutong bahay. Hindi nakakasawa ang lasa ng ulam nila.', NULL, NULL, '2025-11-10 17:50:00', NULL),
(518, 101, 124, 'Angelica Mae Flores', 5, 'The place is kept clean and the utensils are hygienic. I appreciate that they cover the food trays properly. Good sanitation practices here.', NULL, NULL, '2025-11-11 13:10:00', NULL),
(519, 101, 125, 'Jasmine Nicole Tan', 5, 'Ang crunchy ng Sisig nila, perfect pulutan o ulam. Hindi siya masyadong mamantika gaya ng iba. The best Sisig in Bagumbayan area.', NULL, NULL, '2025-11-12 18:30:00', NULL),
(520, 101, 126, 'Patricia Lourdes Mendoza', 4, 'Maray na aga, nag-breakfast kami digdi ng Tapsilog. The garlic rice was flavorful and the beef tapa was tender. Good way to start the day.', NULL, NULL, '2025-11-13 08:45:00', NULL),
(521, 101, 127, 'Camille Joy Bautista', 5, 'Staff are very smiling and welcoming. They serve the food fast even during peak hours. It makes the dining experience pleasant.', NULL, NULL, '2025-11-13 12:05:00', NULL),
(522, 101, 128, 'Alvin Joseph Soriano', 5, 'Ang sarap ng peanut sauce ng Kare-kare nila. Kuhang kuha ang lasa ng luto ni nanay. Mapapa-extra rice ka talaga.', NULL, NULL, '2025-11-14 19:00:00', NULL),
(523, 101, 129, 'Erika Louisa Dela Rosa', 5, 'This is my go-to comfort food spot. The tinola soup warms you up on a rainy day. Simple but very satisfying.', NULL, NULL, '2025-11-15 11:15:00', NULL),
(524, 101, 130, 'Dennis Angelo Mercado', 5, 'Masiram an Ginataang Manok, namit mo an gata. The chicken was cooked well and absorbed the sauce. A must-try dish here.', NULL, NULL, '2025-11-17 12:40:00', NULL),
(525, 101, 131, 'Bianca Rochelle Manalo', 5, 'Parang luto lang sa bahay, walang halong artificial. Ramdam mo yung pagmamahal sa pagluluto. Highly recommended sa pamilya.', NULL, NULL, '2025-11-18 18:20:00', NULL),
(526, 101, 132, 'Jonathan Patrick Velasco', 4, 'If you are in a rush but want a good meal, go here. Service is efficient and food is ready to eat. Convenient and delicious.', NULL, NULL, '2025-11-20 13:00:00', NULL),
(527, 101, 133, 'Karen Sheila Ignacio', 5, 'Their Fried Chicken is crispy on the outside and juicy inside. My kids loved it so much. It tastes better than fast food chicken.', NULL, NULL, '2025-11-22 11:50:00', NULL),
(528, 101, 134, 'Miguel Antonio Pascual', 5, 'Mainit na sabaw ang kailangan ko at hindi ako nabigo. Binigyan pa ako ng libreng refill ng sabaw. Napakabait ng mga tindera.', NULL, NULL, '2025-11-23 19:10:00', NULL),
(529, 101, 135, 'Gabriel Angelo Fernandez', 5, 'An Dinuguan ninda dae masyadong maalsom, tamang tama lang. Perfect ipares sa puto o kanin. Favorite ko na ini digdi.', NULL, NULL, '2025-11-24 12:30:00', NULL),
(530, 101, 136, 'Joshua Kyle De Leon', 4, 'Location is easy to find in Bagumbayan. Parking is a bit limited but food is worth it. A gem of a restaurant in the area.', NULL, NULL, '2025-11-25 13:45:00', NULL),
(531, 101, 137, 'Ryan Christopher Go', 5, 'Masarap ang Halo-halo nila bilang panghimagas. Hindi tinipid sa sahog at gatas. Perfect na pang-tanggal ng umay.', NULL, NULL, '2025-11-26 15:00:00', NULL),
(532, 101, 138, 'Patrick James Villanueva', 5, 'The Lechon Kawali remains crispy even after a while. The dipping sauce complements it perfectly. I will definitely order this again.', NULL, NULL, '2025-11-27 18:40:00', NULL),
(533, 101, 139, 'Kevin Mark Espanto', 5, 'Masiram an Pinangat, authentic na gata ang gamit. You can taste the richness of the coconut milk. One of the best dishes here.', NULL, NULL, '2025-11-28 11:30:00', NULL),
(534, 101, 140, 'Justin Paul Ocampo', 5, 'Ending the month with a hearty meal at Kainan Sa Bagumbayan. The Beef Nilaga was so tender. Satisfied customer here.', NULL, NULL, '2025-11-29 12:15:00', NULL),
(535, 101, 141, 'Edward John Castillo', 4, 'Gustong gusto ko ang Chopsuey nila, fresh ang gulay. Hindi siya overcooked kaya malutong pa. Healthy choice for lunch.', NULL, NULL, '2025-11-29 17:50:00', NULL),
(536, 101, 142, 'Bryan Anthony Diaz', 5, 'Mahilig ako sa maanghang kaya nag-enjoy ako sa spicy dishes nila. Talagang pinagpawisan ako sa sarap. Two thumbs up!', NULL, NULL, '2025-11-30 11:10:00', NULL),
(537, 101, 143, 'Vincent Karl Medina', 5, 'We came here as a large group and they accommodated us well. Everyone found a dish they liked. It is a great place for gatherings.', NULL, NULL, '2025-11-30 13:20:00', NULL),
(538, 101, 144, 'Adrian Luke Rivera', 4, 'Nag-takeout ako ng Menudo para sa dinner namin. Maayos ang packaging at hindi tumapon. Masarap pa rin kahit initin sa bahay.', NULL, NULL, '2025-11-30 16:30:00', NULL),
(539, 101, 145, 'Sofia Lorraine Gomez', 5, 'My final verdict is that this place is a must-visit. Great food, affordable price, and nice people. I will keep coming back.', NULL, NULL, '2025-11-30 19:45:00', NULL),
(540, 80, 116, 'Juan Dela Cruz', 5, 'Perfect spot for students on a budget. The meals are very affordable but filling. I always eat lunch here with my classmates.', NULL, NULL, '2025-11-01 11:30:00', NULL),
(541, 80, 117, 'Maria Santos', 5, 'Ang sarap ng Menudo ni Kuya Leo, lasang luto sa bahay. Hindi tinipid sa sahog kahit mura lang. Ito ang paborito kong carinderia.', NULL, NULL, '2025-11-02 12:15:00', NULL),
(542, 80, 118, 'Juan Carlos Reyes', 5, 'Masiram an Bicol Express ninda, tama lang ang anghang. Bagay na bagay sa mainit na kanin. Mapapa-extra rice ka talaga.', NULL, NULL, '2025-11-03 18:40:00', NULL),
(543, 80, 119, 'Mark Anthony dela Cruz', 4, 'Very accessible location and fast service. You just point at what you want and they serve it immediately. Good for quick breaks.', NULL, NULL, '2025-11-05 13:00:00', NULL),
(544, 80, 120, 'Jose Miguel Santos', 4, 'Malinis ang eatery at maayos ang mga lamesa. Gusto ko na laging may takip ang mga ulam nila. Safe at masarap kumain dito.', NULL, NULL, '2025-11-07 19:10:00', NULL),
(545, 80, 121, 'Rafael Luis Navarro', 5, 'Humigop ako ng mainit na sabaw ng Sinigang, napakasarap! Tamang-tama ang asim ng sabaw. Nakakawala ng pagod sa trabaho.', NULL, NULL, '2025-11-08 12:20:00', NULL),
(546, 80, 122, 'Christian Gabriel Ramos', 5, 'Their Porkchop is crispy and tasty. It is one of their best sellers for a reason. I order this almost every time I visit.', NULL, NULL, '2025-11-09 11:45:00', NULL),
(547, 80, 123, 'Maria Isabella Cruz', 5, 'Barato pero dakul ang serving, swak sa budget. Even if it is cheap, the quality is not compromised. Highly recommended eatery.', NULL, NULL, '2025-11-10 17:30:00', NULL),
(548, 80, 124, 'Angelica Mae Flores', 4, 'They have a lot of viands to choose from daily. Hindi ka magsasawa kasi iba-iba ang ulam araw-araw. Great variety.', NULL, NULL, '2025-11-11 13:15:00', NULL),
(549, 80, 125, 'Jasmine Nicole Tan', 5, 'Kuya Leo is very friendly to all customers. He always greets us with a smile. Good customer service makes the food taste better.', NULL, NULL, '2025-11-12 08:30:00', NULL),
(550, 80, 126, 'Patricia Lourdes Mendoza', 5, 'Busog-sulit ang experience ko dito. Sa halagang maliit, may gulay at karne ka na. Panalo sa mga nagtitipid.', NULL, NULL, '2025-11-13 12:05:00', NULL),
(551, 80, 127, 'Camille Joy Bautista', 4, 'Nag-breakfast ako dito ng Tapsilog, masarap ang tapa nila. The garlic rice was also flavorful. Nice way to start the morning.', NULL, NULL, '2025-11-14 09:00:00', NULL),
(552, 80, 128, 'Alvin Joseph Soriano', 5, 'Masiram an Ginataang Gulay ninda, authentic na gata. You can really taste the richness of the coconut milk. Healthy choice for lunch.', NULL, NULL, '2025-11-15 11:25:00', NULL),
(553, 80, 129, 'Erika Louisa Dela Rosa', 4, 'I ordered takeout for dinner and they packed it well. The food was still good even after reheating. Convenient for busy days.', NULL, NULL, '2025-11-17 18:45:00', NULL),
(554, 80, 130, 'Dennis Angelo Mercado', 5, 'Gustong-gusto ko na may libreng sabaw sila lagi. Masarap humigop ng mainit na sabaw habang kumakain. Simple things make a difference.', NULL, NULL, '2025-11-19 12:40:00', NULL),
(555, 80, 131, 'Bianca Rochelle Manalo', 4, 'Simple eatery but the food hits the spot. It feels like eating in your own dining room. Very unpretentious and honest food.', NULL, NULL, '2025-11-20 13:50:00', NULL),
(556, 80, 132, 'Jonathan Patrick Velasco', 5, 'The Laing here is the best I have tasted in a carinderia. Hindi siya tinipid sa rekados at anghang. Authentic Bicolano dish.', NULL, NULL, '2025-11-22 11:55:00', NULL),
(557, 80, 133, 'Karen Sheila Ignacio', 5, 'Large servings of rice, which is perfect for heavy eaters. You definitely won’t leave hungry here. Value for money is 10/10.', NULL, NULL, '2025-11-23 12:30:00', NULL),
(558, 80, 134, 'Miguel Antonio Pascual', 5, 'Parang luto ni Nanay yung mga ulam nila. Nakaka-homesick yung lasa ng Adobong Baboy. Babalik ako dito palagi.', NULL, NULL, '2025-11-24 19:20:00', NULL),
(559, 80, 135, 'Gabriel Angelo Fernandez', 5, 'Dakul mapipilian na ulam kaya dai ka magsasawa. I tried the fish stew and it was fresh. Nice place for lunch.', NULL, NULL, '2025-11-25 13:10:00', NULL),
(560, 80, 136, 'Joshua Kyle De Leon', 4, 'I appreciate the hygiene practices of the staff. They wear hairnets and serve food cleanly. It makes me trust the food more.', NULL, NULL, '2025-11-26 10:45:00', NULL),
(561, 80, 137, 'Ryan Christopher Go', 5, 'Favorite ko ang Dinuguan nila, hindi maasim masyado. Masarap siya ipares sa puto o kanin. The texture is just right.', NULL, NULL, '2025-11-26 17:40:00', NULL),
(562, 80, 138, 'Patrick James Villanueva', 4, 'Medyo crowded pag lunch time pero mabilis naman ang service. The turnover of tables is fast. It shows that people really love the food.', NULL, NULL, '2025-11-27 12:15:00', NULL),
(563, 80, 139, 'Kevin Mark Espanto', 5, 'This is where I go when I want a full meal without spending much. 50 pesos goes a long way here. Best budget meal in town.', NULL, NULL, '2025-11-28 11:10:00', NULL),
(564, 80, 140, 'Justin Paul Ocampo', 5, 'Namit kan pagkakan, daog pa ang ibang mahal na restawran. It’s the taste that keeps me coming back. Simple pero rock!', NULL, NULL, '2025-11-28 18:30:00', NULL),
(565, 80, 141, 'Edward John Castillo', 5, 'Ending the month with a tipid but yummy meal at Kuya Leo. Perfect for those waiting for payday. Highly recommended!', NULL, NULL, '2025-11-29 12:00:00', NULL),
(566, 80, 142, 'Bryan Anthony Diaz', 5, 'Ang sarap ng kanin nila, laging mainit at hindi bahaw. Mahalaga yun sa carinderia experience. Good job po.', NULL, NULL, '2025-11-29 19:00:00', NULL),
(567, 80, 143, 'Vincent Karl Medina', 5, 'Masiram an Sisig, crispy asin malinamnam. It’s perfect with calamansi and soy sauce. I will definitely order this again.', NULL, NULL, '2025-11-30 11:35:00', NULL),
(568, 80, 144, 'Adrian Luke Rivera', 5, 'A reliable eatery that never fails to satisfy my hunger. The food is always fresh and newly cooked. My go-to place.', NULL, NULL, '2025-11-30 13:20:00', NULL),
(569, 80, 145, 'Sofia Lorraine Gomez', 5, 'Kung naghahanap ka ng lutong bahay na mura, dito ka na. You will not regret eating here. 5 stars for Kuya Leo!', NULL, NULL, '2025-11-30 19:45:00', NULL),
(570, 73, 116, 'Juan Dela Cruz', 5, 'This is the perfect spot for a food trip with the barkada. There are so many stalls to choose from. We tried the barbecue and it was marinated perfectly.', NULL, NULL, '2025-11-01 18:30:00', NULL),
(571, 73, 117, 'Maria Santos', 5, 'Ang sarap ng Isaw dito, malinis at walang pait. Bagay na bagay sa suka na may sili. Favorite tambayan namin ito pag hapon.', NULL, NULL, '2025-11-02 16:15:00', NULL),
(572, 73, 118, 'Juan Carlos Reyes', 4, 'Masiram an Kwek-kwek ninda, dakula asin malutong. The sauce is sweet and spicy, just how I like it. Very affordable snack.', NULL, NULL, '2025-11-03 15:45:00', NULL),
(573, 73, 119, 'Mark Anthony dela Cruz', 5, 'I love the open-air vibe of this food park. It is nice to eat while feeling the fresh air. The acoustic music adds to the relaxation.', NULL, NULL, '2025-11-04 19:20:00', NULL),
(574, 73, 120, 'Jose Miguel Santos', 5, 'Sulit na sulit ang budget mo dito, andaming mura na pagkain. Tried the nachos and fries platter. Good for sharing with friends.', NULL, NULL, '2025-11-05 17:10:00', NULL),
(575, 73, 121, 'Rafael Luis Navarro', 4, 'Dai ka mauubusan ki pilian, gabos masiram. From drinks to heavy meals, they have it. I recommend the grilled liempo.', NULL, NULL, '2025-11-07 18:50:00', NULL),
(576, 73, 122, 'Christian Gabriel Ramos', 5, 'The Siomai Rice here is a life saver for hungry students. It is cheap but very filling. The chili garlic sauce is legit spicy.', NULL, NULL, '2025-11-08 12:30:00', NULL),
(577, 73, 123, 'Maria Isabella Cruz', 5, 'Sobrang refreshing ng Mango Shake nila, ang tamis ng mangga. Perfect pang-himagas pagkatapos kumain ng BBQ. Oorder ulit ako next time.', NULL, NULL, '2025-11-09 14:15:00', NULL),
(578, 73, 124, 'Angelica Mae Flores', 4, 'Medyo crowded pag weekend pero masaya ang vibe. It feels very lively and energetic. Good place to socialize and eat.', NULL, NULL, '2025-11-10 19:00:00', NULL),
(579, 73, 125, 'Jasmine Nicole Tan', 5, 'Barato an mga pagkakan, swak sa bulsa kan estudyante. Even with 100 pesos, you can already be full. My go-to dinner spot.', NULL, NULL, '2025-11-12 18:10:00', NULL),
(580, 73, 126, 'Patricia Lourdes Mendoza', 4, 'Tried the Takoyaki and it was authentic and tasty. The bonito flakes and mayo were generous. One of the best snacks in the park.', NULL, NULL, '2025-11-14 16:45:00', NULL),
(581, 73, 127, 'Camille Joy Bautista', 5, 'Street food galore! I ate so much Fishball and Kikiam. The sauce is what makes it special, sweet and spicy.', NULL, NULL, '2025-11-15 15:30:00', NULL),
(582, 73, 128, 'Alvin Joseph Soriano', 5, 'Buy 1 Take 1 Burger is the bomb! Sobrang sulit para sa aming magbabarkada. The patty was juicy and the dressing was good.', NULL, NULL, '2025-11-16 17:20:00', NULL),
(583, 73, 129, 'Erika Louisa Dela Rosa', 4, 'Malinig an lugar dawa dakul tawo nagkakakan. The tables are wiped regularly by the staff. It’s nice to see a clean food park.', NULL, NULL, '2025-11-17 19:45:00', NULL),
(584, 73, 130, 'Dennis Angelo Mercado', 5, 'Ang sarap ng Dynamite Lumpia, ang anghang pero masarap. Bagay siya ipares sa malamig na juice. Exciting kainin!', NULL, NULL, '2025-11-18 16:10:00', NULL),
(585, 73, 131, 'Bianca Rochelle Manalo', 5, 'The Sisig here is crispy and flavorful. I paired it with rice and it was a heavy meal. Highly recommended for dinner.', NULL, NULL, '2025-11-19 18:30:00', NULL),
(586, 73, 132, 'Jonathan Patrick Velasco', 4, 'Good variety of drinks from milk tea to fruit juices. It balances out the savory snacks perfectly. Refreshing choices.', NULL, NULL, '2025-11-20 14:20:00', NULL),
(587, 73, 133, 'Karen Sheila Ignacio', 5, 'Masiram an Calamares, malutong an pagkapritos. The vinegar dip compliments the squid well. Best pica-pica food here.', NULL, NULL, '2025-11-20 17:50:00', NULL),
(588, 73, 134, 'Miguel Antonio Pascual', 5, 'Great place to bring kids, they loved the Potato Twists. It is a fun environment for the whole family. We will come back.', NULL, NULL, '2025-11-21 18:40:00', NULL),
(589, 73, 135, 'Gabriel Angelo Fernandez', 5, 'The Shawarma wrap was packed with meat and veggies. The garlic sauce was very garlicky and tasty. One wrap is enough to make you full.', NULL, NULL, '2025-11-22 17:15:00', NULL),
(590, 73, 136, 'Joshua Kyle De Leon', 4, 'Nakaka-relax tumambay dito pag gabi dahil sa ilaw at hangin. Masarap kumain ng Tapsilog habang nagkukwentuhan. Safe place to chill.', NULL, NULL, '2025-11-23 19:30:00', NULL),
(591, 73, 137, 'Ryan Christopher Go', 5, 'Daog pa an restaurant sa namit kan Pares ninda. The soup is hot and beefy. Perfect comfort food for a cold night.', NULL, NULL, '2025-11-24 18:20:00', NULL),
(592, 73, 138, 'Patrick James Villanueva', 5, 'I tried the grilled Hotdogs and they were juicy. Simple snack but very satisfying. Reminds me of childhood snacks.', NULL, NULL, '2025-11-25 15:45:00', NULL),
(593, 73, 139, 'Kevin Mark Espanto', 4, 'Parking is easy and accessible. We didn’t have a hard time finding a spot. Convenient location for a food trip.', NULL, NULL, '2025-11-26 19:10:00', NULL),
(594, 73, 140, 'Justin Paul Ocampo', 5, 'Ang bango ng inihaw na liempo, amoy pa lang gutom ka na. The dipping sauce is legendary. Worth the wait.', NULL, NULL, '2025-11-28 17:55:00', NULL),
(595, 73, 141, 'Edward John Castillo', 5, 'Ending the month with a giant Halo-halo. It has so many ingredients like leche flan and ube. Super creamy and delicious.', NULL, NULL, '2025-11-29 14:30:00', NULL),
(596, 73, 142, 'Bryan Anthony Diaz', 5, 'Rekomendado ko ito sa mga mahilig sa street food. Malinis at masarap ang luto nila. Two thumbs up para sa Libmanan Food Park.', NULL, NULL, '2025-11-29 18:00:00', NULL),
(597, 73, 143, 'Vincent Karl Medina', 5, 'Masiram mag-date digdi, casual lang pero enjoy. We shared a platter of mixed fried balls. Very affordable date night.', NULL, NULL, '2025-11-30 17:40:00', NULL),
(598, 73, 144, 'Adrian Luke Rivera', 5, 'The Fried Chicken skin was so crunchy and sinful. Perfect pulutan with cold drinks. I will definitely buy this again.', NULL, NULL, '2025-11-30 19:15:00', NULL),
(599, 73, 145, 'Sofia Lorraine Gomez', 5, 'Great way to end November with good food and friends. The vibe here never gets old. Always a happy tummy experience.', NULL, NULL, '2025-11-30 20:30:00', NULL),
(601, 65, 160, 'adan', 5, 'Masarap', NULL, NULL, '2026-01-29 02:02:02', NULL);

-- --------------------------------------------------------

--
-- Table structure for table `taxpayer_details`
--

CREATE TABLE `taxpayer_details` (
  `taxpayer_id` int(100) NOT NULL,
  `ba_id` int(100) NOT NULL,
  `last_name` varchar(255) DEFAULT NULL,
  `first_name` varchar(255) DEFAULT NULL,
  `middle_name` varchar(255) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `taxpayer_details`
--

INSERT INTO `taxpayer_details` (`taxpayer_id`, `ba_id`, `last_name`, `first_name`, `middle_name`) VALUES
(6, 40, 'Antonio', 'Kap', 'O'),
(8, 42, 'ZABALA', 'ARCELY', 'MANANSALA'),
(9, 43, '', '', ''),
(10, 44, 'ZABALA', 'ARCELY', 'MANANSALA'),
(11, 45, '', '', ''),
(12, 46, '', '', ''),
(13, 47, '', '', ''),
(14, 48, '', '', ''),
(15, 49, '', '', ''),
(16, 50, '', '', ''),
(17, 51, '', '', ''),
(23, 57, 'Antonio', 'Miggy', ''),
(25, 59, '', '', ''),
(28, 62, 'Solano', 'Jes', 'S'),
(29, 63, 'Cruz', 'Divine', ''),
(30, 64, 'Cruz', 'Divine', ''),
(31, 65, 'Antonio', 'Christian', ''),
(32, 66, 'Noche', 'Francis', 'O'),
(33, 67, 'Martin', 'Kris', ''),
(34, 68, 'Chiu', 'Kim', ''),
(35, 69, 'Antonio', 'Jhun Roland', 'C'),
(36, 70, 'Ampongan', 'Ann Magdaline', ''),
(37, 71, 'Ampongan', 'Ann Magdaline', ''),
(38, 72, 'Mariel', 'Indefenso', ''),
(39, 73, 'Deguzman', 'Hyleen', ''),
(40, 74, 'Galos', 'Jhumari', '');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `accounts`
--
ALTER TABLE `accounts`
  ADD PRIMARY KEY (`user_id`);

--
-- Indexes for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD PRIMARY KEY (`document_id`),
  ADD KEY `application_documents_ibfk` (`ba_id`);

--
-- Indexes for table `business_application`
--
ALTER TABLE `business_application`
  ADD PRIMARY KEY (`ba_id`),
  ADD KEY `indx_user_id` (`user_id`),
  ADD KEY `indx_status` (`status`);

--
-- Indexes for table `business_details`
--
ALTER TABLE `business_details`
  ADD PRIMARY KEY (`bd_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- Indexes for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD PRIMARY KEY (`emergency_contact_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- Indexes for table `fb_owner`
--
ALTER TABLE `fb_owner`
  ADD PRIMARY KEY (`fbowner_id`),
  ADD UNIQUE KEY `user_id` (`user_id`),
  ADD KEY `fk_fbowner_user_id` (`user_id`);

--
-- Indexes for table `lessor_details`
--
ALTER TABLE `lessor_details`
  ADD PRIMARY KEY (`lessor_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- Indexes for table `menus`
--
ALTER TABLE `menus`
  ADD PRIMARY KEY (`menu_id`),
  ADD KEY `category_id` (`category_id`),
  ADD KEY `fk_menus_fbowner` (`fbowner_id`);

--
-- Indexes for table `menu_categories`
--
ALTER TABLE `menu_categories`
  ADD PRIMARY KEY (`category_id`),
  ADD UNIQUE KEY `uq_category` (`fbowner_id`,`category_name`);

--
-- Indexes for table `messages`
--
ALTER TABLE `messages`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `notification`
--
ALTER TABLE `notification`
  ADD PRIMARY KEY (`notification_id`),
  ADD KEY `user_id` (`user_id`),
  ADD KEY `ba_id` (`ref_id`);

--
-- Indexes for table `owner_details`
--
ALTER TABLE `owner_details`
  ADD PRIMARY KEY (`owner_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- Indexes for table `replies`
--
ALTER TABLE `replies`
  ADD PRIMARY KEY (`reply_id`),
  ADD KEY `ba_id` (`ba_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `reviews`
--
ALTER TABLE `reviews`
  ADD PRIMARY KEY (`id`),
  ADD KEY `fbowner_id` (`fbowner_id`),
  ADD KEY `user_id` (`user_id`);

--
-- Indexes for table `taxpayer_details`
--
ALTER TABLE `taxpayer_details`
  ADD PRIMARY KEY (`taxpayer_id`),
  ADD KEY `ba_id` (`ba_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `accounts`
--
ALTER TABLE `accounts`
  MODIFY `user_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=161;

--
-- AUTO_INCREMENT for table `application_documents`
--
ALTER TABLE `application_documents`
  MODIFY `document_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=71;

--
-- AUTO_INCREMENT for table `business_application`
--
ALTER TABLE `business_application`
  MODIFY `ba_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=75;

--
-- AUTO_INCREMENT for table `business_details`
--
ALTER TABLE `business_details`
  MODIFY `bd_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=74;

--
-- AUTO_INCREMENT for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  MODIFY `emergency_contact_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=73;

--
-- AUTO_INCREMENT for table `fb_owner`
--
ALTER TABLE `fb_owner`
  MODIFY `fbowner_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=106;

--
-- AUTO_INCREMENT for table `lessor_details`
--
ALTER TABLE `lessor_details`
  MODIFY `lessor_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=68;

--
-- AUTO_INCREMENT for table `menus`
--
ALTER TABLE `menus`
  MODIFY `menu_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=87;

--
-- AUTO_INCREMENT for table `menu_categories`
--
ALTER TABLE `menu_categories`
  MODIFY `category_id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `messages`
--
ALTER TABLE `messages`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=121;

--
-- AUTO_INCREMENT for table `notification`
--
ALTER TABLE `notification`
  MODIFY `notification_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=18;

--
-- AUTO_INCREMENT for table `owner_details`
--
ALTER TABLE `owner_details`
  MODIFY `owner_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=77;

--
-- AUTO_INCREMENT for table `replies`
--
ALTER TABLE `replies`
  MODIFY `reply_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=3;

--
-- AUTO_INCREMENT for table `reviews`
--
ALTER TABLE `reviews`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=602;

--
-- AUTO_INCREMENT for table `taxpayer_details`
--
ALTER TABLE `taxpayer_details`
  MODIFY `taxpayer_id` int(100) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=41;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `application_documents`
--
ALTER TABLE `application_documents`
  ADD CONSTRAINT `fk_ad_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_application`
--
ALTER TABLE `business_application`
  ADD CONSTRAINT `fk_a_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `business_details`
--
ALTER TABLE `business_details`
  ADD CONSTRAINT `fk_bd_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `emergency_contact`
--
ALTER TABLE `emergency_contact`
  ADD CONSTRAINT `fk_ec_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `fb_owner`
--
ALTER TABLE `fb_owner`
  ADD CONSTRAINT `fk_fbowner_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `lessor_details`
--
ALTER TABLE `lessor_details`
  ADD CONSTRAINT `fk_ld_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `menus`
--
ALTER TABLE `menus`
  ADD CONSTRAINT `fk_menus_fbowner` FOREIGN KEY (`fbowner_id`) REFERENCES `fb_owner` (`fbowner_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `menus_ibfk_1` FOREIGN KEY (`category_id`) REFERENCES `menu_categories` (`category_id`) ON DELETE CASCADE;

--
-- Constraints for table `notification`
--
ALTER TABLE `notification`
  ADD CONSTRAINT `fk_notification_ba_id` FOREIGN KEY (`ref_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE,
  ADD CONSTRAINT `fk_notification_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `owner_details`
--
ALTER TABLE `owner_details`
  ADD CONSTRAINT `fk_od_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `replies`
--
ALTER TABLE `replies`
  ADD CONSTRAINT `fk_replies_user_id` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`) ON DELETE CASCADE ON UPDATE CASCADE;

--
-- Constraints for table `reviews`
--
ALTER TABLE `reviews`
  ADD CONSTRAINT `reviews_ibfk_1` FOREIGN KEY (`fbowner_id`) REFERENCES `fb_owner` (`fbowner_id`),
  ADD CONSTRAINT `reviews_ibfk_2` FOREIGN KEY (`user_id`) REFERENCES `accounts` (`user_id`);

--
-- Constraints for table `taxpayer_details`
--
ALTER TABLE `taxpayer_details`
  ADD CONSTRAINT `fk_taxpayer_ba_id` FOREIGN KEY (`ba_id`) REFERENCES `business_application` (`ba_id`) ON DELETE CASCADE ON UPDATE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
