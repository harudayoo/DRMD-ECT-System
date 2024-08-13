-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Aug 07, 2024 at 10:17 AM
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
-- Database: `payroll_system`
--

-- --------------------------------------------------------

--
-- Table structure for table `admins`
--

CREATE TABLE `admins` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `admins`
--

INSERT INTO `admins` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`) VALUES
(6, 'Admin 1', 'admin1@sample.com', NULL, '$2y$12$xkYsFq./6CJjcOcRs4fuB.br7J1sbAwTOInTCbyfYmfPuHX/FXXAC', NULL, '2024-07-31 03:21:00', '2024-07-31 03:21:00');

-- --------------------------------------------------------

--
-- Table structure for table `barangays`
--

CREATE TABLE `barangays` (
  `barangayID` bigint(20) UNSIGNED NOT NULL,
  `municipalityID` bigint(20) UNSIGNED NOT NULL,
  `barangayName` varchar(255) NOT NULL,
  `totalBeneficiaries` int(11) DEFAULT NULL,
  `totalAmountReleased` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `beneficiaries`
--

CREATE TABLE `beneficiaries` (
  `beneficiaryID` bigint(20) UNSIGNED NOT NULL,
  `barangayID` bigint(20) UNSIGNED NOT NULL,
  `beneficiaryNumber` varchar(255) NOT NULL,
  `lastName` varchar(255) NOT NULL,
  `firstName` varchar(255) NOT NULL,
  `middleName` varchar(255) DEFAULT NULL,
  `extensionName` varchar(255) DEFAULT NULL,
  `dateOfBirth` date NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `status` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `cache`
--

CREATE TABLE `cache` (
  `key` varchar(255) NOT NULL,
  `value` mediumtext NOT NULL,
  `expiration` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `cache`
--

INSERT INTO `cache` (`key`, `value`, `expiration`) VALUES
('asdasd@asdasd.com|127.0.0.1', 'i:1;', 1722447139),
('asdasd@asdasd.com|127.0.0.1:timer', 'i:1722447139;', 1722447139),
('hello@sample.com|127.0.0.1', 'i:1;', 1722964426),
('hello@sample.com|127.0.0.1:timer', 'i:1722964426;', 1722964426),
('hello@sapme.com|127.0.0.1', 'i:1;', 1722967056),
('hello@sapme.com|127.0.0.1:timer', 'i:1722967056;', 1722967056),
('joshmojica@gmail.com|127.0.0.1', 'i:1;', 1722819544),
('joshmojica@gmail.com|127.0.0.1:timer', 'i:1722819544;', 1722819544),
('user2@sample.com|127.0.0.1', 'i:1;', 1722604228),
('user2@sample.com|127.0.0.1:timer', 'i:1722604228;', 1722604228),
('wefwf@wdhsakj.vapodcsrsfbvs|127.0.0.1', 'i:1;', 1722969627),
('wefwf@wdhsakj.vapodcsrsfbvs|127.0.0.1:timer', 'i:1722969627;', 1722969627);

-- --------------------------------------------------------

--
-- Table structure for table `migrations`
--

CREATE TABLE `migrations` (
  `id` int(10) UNSIGNED NOT NULL,
  `migration` varchar(255) NOT NULL,
  `batch` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `municipalities`
--

CREATE TABLE `municipalities` (
  `municipalityID` bigint(20) UNSIGNED NOT NULL,
  `provinceID` bigint(20) UNSIGNED NOT NULL,
  `municipalityName` varchar(255) NOT NULL,
  `totalBeneficiaries` int(11) DEFAULT NULL,
  `totalAmountReleased` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payrolls`
--

CREATE TABLE `payrolls` (
  `payrollID` bigint(20) UNSIGNED NOT NULL,
  `payrollNumber` varchar(255) NOT NULL,
  `projectName` varchar(255) NOT NULL,
  `date` date NOT NULL,
  `subTotal` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll_details`
--

CREATE TABLE `payroll_details` (
  `payrollDetailID` bigint(20) UNSIGNED NOT NULL,
  `payrollID` bigint(20) UNSIGNED NOT NULL,
  `beneficiaryID` bigint(20) UNSIGNED NOT NULL,
  `beneficiaryPayrollNumber` varchar(255) NOT NULL,
  `amount` decimal(15,2) DEFAULT NULL,
  `signatureDate` date DEFAULT NULL,
  `datePaid` date DEFAULT NULL,
  `claimStub` varchar(255) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `provinces`
--

CREATE TABLE `provinces` (
  `provinceID` bigint(20) UNSIGNED NOT NULL,
  `provinceName` varchar(255) NOT NULL,
  `totalBeneficiaries` int(11) DEFAULT NULL,
  `totalAmountReleased` decimal(15,2) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_of_cash_disbursements`
--

CREATE TABLE `report_of_cash_disbursements` (
  `ReportOfCashDisbursementID` bigint(20) UNSIGNED NOT NULL,
  `fundCluster` varchar(255) NOT NULL,
  `reportNumber` varchar(255) NOT NULL,
  `dateCovered` date NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `report_of_cash_disbursement_details`
--

CREATE TABLE `report_of_cash_disbursement_details` (
  `ReportOfCashDisbursementDetailID` bigint(20) UNSIGNED NOT NULL,
  `ReportOfCashDisbursementID` bigint(20) UNSIGNED NOT NULL,
  `date` date NOT NULL,
  `orDVNumber` varchar(255) NOT NULL,
  `responsibilityCenterCode` varchar(255) NOT NULL,
  `payee` varchar(255) NOT NULL,
  `useObjectCode` varchar(255) NOT NULL,
  `natureOfPayment` varchar(255) NOT NULL,
  `amount` decimal(15,2) NOT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- --------------------------------------------------------

--
-- Table structure for table `sessions`
--

CREATE TABLE `sessions` (
  `id` varchar(255) NOT NULL,
  `user_id` bigint(20) UNSIGNED DEFAULT NULL,
  `ip_address` varchar(45) DEFAULT NULL,
  `user_agent` text DEFAULT NULL,
  `payload` longtext NOT NULL,
  `last_activity` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `sessions`
--

INSERT INTO `sessions` (`id`, `user_id`, `ip_address`, `user_agent`, `payload`, `last_activity`) VALUES
('QQ2NpQXk9ayW3qOuPOMEsWf3y2GhnVx0jCKgbuuj', NULL, '127.0.0.1', 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/127.0.0.0 Safari/537.36', 'YToyOntzOjY6Il90b2tlbiI7czo0MDoiMURabXBIRjE5MUpMQW90QmUyN2NqRkpmR0lqa3B3TDlldVhCQVZmZiI7czo2OiJfZmxhc2giO2E6Mjp7czozOiJvbGQiO2E6MDp7fXM6MzoibmV3IjthOjA6e319fQ==', 1723013946);

-- --------------------------------------------------------

--
-- Table structure for table `users`
--

CREATE TABLE `users` (
  `id` bigint(20) UNSIGNED NOT NULL,
  `name` varchar(255) NOT NULL,
  `email` varchar(255) NOT NULL,
  `email_verified_at` timestamp NULL DEFAULT NULL,
  `password` varchar(255) NOT NULL,
  `remember_token` varchar(100) DEFAULT NULL,
  `created_at` timestamp NULL DEFAULT NULL,
  `updated_at` timestamp NULL DEFAULT NULL,
  `adminID` bigint(20) UNSIGNED DEFAULT NULL,
  `loginNum` int(11) DEFAULT 0,
  `last_login_reset` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

--
-- Dumping data for table `users`
--

INSERT INTO `users` (`id`, `name`, `email`, `email_verified_at`, `password`, `remember_token`, `created_at`, `updated_at`, `adminID`, `loginNum`, `last_login_reset`) VALUES
(12, 'User 1', 'user1@sample.com', NULL, '$2y$12$YBa66z0G8zhixFpfHorF1.RBAu1YOYOy41cQURF3MdVCrLFhZ1d8e', NULL, '2024-07-31 09:09:50', '2024-08-06 22:57:36', 6, 7, '2024-08-04 08:31:48'),
(15, 'Jurgen Anzures', 'jurgen@sample.com', NULL, '$2y$12$95PoogHA89ph0L6Lu7KHFuQpGKq6eGYR60nO7oQqTPEyGZ3XbrYoq', NULL, '2024-08-04 17:33:05', '2024-08-04 17:38:42', 6, 2, '2024-08-04 17:37:30'),
(16, 'Harold Cayan', 'harold@sample.com', NULL, '$2y$12$rZGmUkZ79SeAOagGLCqXhOrVBvB.XMa7vruPESnrE/Bqgwn8YGmwa', NULL, '2024-08-04 17:33:36', '2024-08-04 17:34:29', 6, 1, '2024-08-04 17:34:29');

--
-- Indexes for dumped tables
--

--
-- Indexes for table `admins`
--
ALTER TABLE `admins`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `admins_email_unique` (`email`);

--
-- Indexes for table `barangays`
--
ALTER TABLE `barangays`
  ADD PRIMARY KEY (`barangayID`),
  ADD KEY `barangays_municipalityid_foreign` (`municipalityID`);

--
-- Indexes for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD PRIMARY KEY (`beneficiaryID`),
  ADD UNIQUE KEY `beneficiaries_beneficiarynumber_unique` (`beneficiaryNumber`),
  ADD KEY `beneficiaries_barangayid_foreign` (`barangayID`);

--
-- Indexes for table `cache`
--
ALTER TABLE `cache`
  ADD PRIMARY KEY (`key`);

--
-- Indexes for table `migrations`
--
ALTER TABLE `migrations`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD PRIMARY KEY (`municipalityID`),
  ADD KEY `municipalities_provinceid_foreign` (`provinceID`);

--
-- Indexes for table `payrolls`
--
ALTER TABLE `payrolls`
  ADD PRIMARY KEY (`payrollID`),
  ADD UNIQUE KEY `payrolls_payrollnumber_unique` (`payrollNumber`);

--
-- Indexes for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD PRIMARY KEY (`payrollDetailID`),
  ADD UNIQUE KEY `payroll_details_beneficiarypayrollnumber_unique` (`beneficiaryPayrollNumber`),
  ADD KEY `payroll_details_payrollid_foreign` (`payrollID`),
  ADD KEY `payroll_details_beneficiaryid_foreign` (`beneficiaryID`);

--
-- Indexes for table `provinces`
--
ALTER TABLE `provinces`
  ADD PRIMARY KEY (`provinceID`);

--
-- Indexes for table `report_of_cash_disbursements`
--
ALTER TABLE `report_of_cash_disbursements`
  ADD PRIMARY KEY (`ReportOfCashDisbursementID`);

--
-- Indexes for table `report_of_cash_disbursement_details`
--
ALTER TABLE `report_of_cash_disbursement_details`
  ADD PRIMARY KEY (`ReportOfCashDisbursementDetailID`);

--
-- Indexes for table `sessions`
--
ALTER TABLE `sessions`
  ADD PRIMARY KEY (`id`),
  ADD KEY `sessions_user_id_index` (`user_id`),
  ADD KEY `sessions_last_activity_index` (`last_activity`);

--
-- Indexes for table `users`
--
ALTER TABLE `users`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `users_email_unique` (`email`),
  ADD KEY `users_adminid_foreign` (`adminID`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `admins`
--
ALTER TABLE `admins`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `barangays`
--
ALTER TABLE `barangays`
  MODIFY `barangayID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  MODIFY `beneficiaryID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `migrations`
--
ALTER TABLE `migrations`
  MODIFY `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `municipalities`
--
ALTER TABLE `municipalities`
  MODIFY `municipalityID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payrolls`
--
ALTER TABLE `payrolls`
  MODIFY `payrollID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `payroll_details`
--
ALTER TABLE `payroll_details`
  MODIFY `payrollDetailID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `provinces`
--
ALTER TABLE `provinces`
  MODIFY `provinceID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_of_cash_disbursements`
--
ALTER TABLE `report_of_cash_disbursements`
  MODIFY `ReportOfCashDisbursementID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `report_of_cash_disbursement_details`
--
ALTER TABLE `report_of_cash_disbursement_details`
  MODIFY `ReportOfCashDisbursementDetailID` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `users`
--
ALTER TABLE `users`
  MODIFY `id` bigint(20) UNSIGNED NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=19;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `barangays`
--
ALTER TABLE `barangays`
  ADD CONSTRAINT `barangays_municipalityid_foreign` FOREIGN KEY (`municipalityID`) REFERENCES `municipalities` (`municipalityID`) ON DELETE CASCADE;

--
-- Constraints for table `beneficiaries`
--
ALTER TABLE `beneficiaries`
  ADD CONSTRAINT `beneficiaries_barangayid_foreign` FOREIGN KEY (`barangayID`) REFERENCES `barangays` (`barangayID`) ON DELETE CASCADE;

--
-- Constraints for table `municipalities`
--
ALTER TABLE `municipalities`
  ADD CONSTRAINT `municipalities_provinceid_foreign` FOREIGN KEY (`provinceID`) REFERENCES `provinces` (`provinceID`) ON DELETE CASCADE;

--
-- Constraints for table `payroll_details`
--
ALTER TABLE `payroll_details`
  ADD CONSTRAINT `payroll_details_beneficiaryid_foreign` FOREIGN KEY (`beneficiaryID`) REFERENCES `beneficiaries` (`beneficiaryID`) ON DELETE CASCADE,
  ADD CONSTRAINT `payroll_details_payrollid_foreign` FOREIGN KEY (`payrollID`) REFERENCES `payrolls` (`payrollID`) ON DELETE CASCADE;

--
-- Constraints for table `users`
--
ALTER TABLE `users`
  ADD CONSTRAINT `users_adminid_foreign` FOREIGN KEY (`adminID`) REFERENCES `admins` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
