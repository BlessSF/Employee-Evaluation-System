-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Mar 27, 2025 at 01:48 AM
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
-- Database: `employee_evaluation`
--

-- --------------------------------------------------------

--
-- Table structure for table `dois_payroll`
--

CREATE TABLE `dois_payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) DEFAULT 0.00,
  `rate_per_hour` decimal(10,2) DEFAULT 0.00,
  `no_of_days` int(11) DEFAULT 0,
  `no_of_hours` int(11) DEFAULT 0,
  `basic_pay` decimal(10,2) DEFAULT 0.00,
  `days` int(11) DEFAULT 0,
  `amount` decimal(10,2) DEFAULT 0.00,
  `rate_ovt` decimal(10,2) DEFAULT 0.00,
  `hour_ovt` decimal(10,2) DEFAULT 0.00,
  `total_ovt` decimal(10,2) DEFAULT 0.00,
  `no_of_days_tr` int(11) DEFAULT 0,
  `trainee_allowance` decimal(10,2) DEFAULT 0.00,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `medical_reimbursement` decimal(10,2) DEFAULT 0.00,
  `hours_nd` decimal(10,2) DEFAULT 0.00,
  `ten_percent` decimal(10,2) DEFAULT 0.00,
  `hours_hp` decimal(10,2) DEFAULT 0.00,
  `hundred_percent` decimal(10,2) DEFAULT 0.00,
  `hours_hdp` decimal(10,2) DEFAULT 0.00,
  `thirty_percent` decimal(10,2) DEFAULT 0.00,
  `gross_pay` decimal(10,2) DEFAULT 0.00,
  `sss_loan` decimal(10,2) DEFAULT 0.00,
  `sss_d` decimal(10,2) DEFAULT 0.00,
  `phic` decimal(10,2) DEFAULT 0.00,
  `pag_ibig` decimal(10,2) DEFAULT 0.00,
  `insurance` decimal(10,2) DEFAULT 0.00,
  `others` decimal(10,2) DEFAULT 0.00,
  `cp` decimal(10,2) DEFAULT 0.00,
  `cash_advance` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `adjustment` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `payslip_status` varchar(20) NOT NULL DEFAULT 'ONGOING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `employees`
--

CREATE TABLE `employees` (
  `id` int(11) NOT NULL,
  `surname` varchar(255) NOT NULL,
  `first_name` varchar(255) NOT NULL,
  `date_hired` date NOT NULL,
  `status` varchar(50) DEFAULT 'Probationary',
  `branch` varchar(50) NOT NULL,
  `sub_branch` varchar(100) DEFAULT NULL,
  `position` varchar(100) DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employees`
--

INSERT INTO `employees` (`id`, `surname`, `first_name`, `date_hired`, `status`, `branch`, `sub_branch`, `position`) VALUES
(2124, 'Alarcon', 'Jhon Rey', '2023-05-24', 'probationary', 'STELLA', 'Main Office', 'Dishwasher'),
(2125, 'Ardelosa', 'Eric ', '2024-02-03', 'regular', 'STELLA', NULL, 'Waiter'),
(2126, 'Arquilola', 'Lourence Mark', '2020-09-21', 'regular', 'STELLA', NULL, 'Supervisor'),
(2127, 'Bibanco', 'Dave Mitchel', '2024-03-07', 'regular', 'STELLA', NULL, 'Cook'),
(2128, 'Bongais', 'John Aris', '2022-06-24', 'regular', 'STELLA', NULL, 'Cook'),
(2129, 'Canada', 'Jose Jr.', '2021-11-15', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(2130, 'Catalan', 'Ryan', '2023-04-13', 'regular', 'STELLA', NULL, 'Waiter'),
(2132, 'Estibal', 'John Adonis', '2024-01-16', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(2133, 'Estibal', 'Zymer', '2023-07-31', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(2134, 'Gadian', 'Kristine', '2022-04-22', 'regular', 'STELLA', NULL, 'Kitchen Coordinator'),
(2135, 'Honorio', 'Lara Joy', '2023-05-22', 'regular', 'STELLA', NULL, 'Cashier'),
(2136, 'Labarosa', 'Stephen Bryan', '2022-07-26', 'regular', 'STELLA', NULL, 'Kitchen Dispatch'),
(2137, 'Labos', 'Aira Joyce ', '2022-12-12', 'regular', 'STELLA', NULL, 'CSR'),
(2138, 'Napilay', 'John Christian', '2024-02-04', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(2139, 'Patanao', 'Martin John', '2023-03-28', 'regular', 'STELLA', NULL, 'Asst. Kitchen Coordinator'),
(2140, 'Tajonera', 'Romagel', '2017-06-07', 'regular', 'STELLA', NULL, 'Asst. Operations Coordinator'),
(2141, 'Radjudin', 'Norama', '2024-04-09', 'regular', 'STELLA', NULL, 'Cashier'),
(2142, 'Villaruz', 'Carl Angel', '2023-06-24', 'regular', 'STELLA', NULL, 'Cook'),
(2143, 'Anlap', 'Valent John', '2024-06-02', 'regular', 'STELLA', NULL, 'Waiter'),
(2144, 'Dela cruz', 'Jeremy', '2024-05-22', 'regular', 'STELLA', NULL, 'Diswasher'),
(2145, 'Young', 'Kevin Ken', '2024-04-01', 'regular', 'STELLA', NULL, 'Waiter'),
(2146, 'Veloso', 'Christine Mae', '2024-06-22', 'regular', 'STELLA', NULL, 'Cashier'),
(2147, 'Ngalan', 'Jennieveb', '2024-07-13', 'regular', 'STELLA', NULL, 'Cashier'),
(2148, 'Pabilona', 'Eira Faith', '2024-09-28', 'probationary', 'STELLA', NULL, 'Kitchen Dispatch'),
(2152, 'Altura', 'John Hyder', '2024-01-19', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(2155, 'Belgira', 'Mar John', '2023-01-21', 'regular', 'DOIS', NULL, 'Waiter/Cleaner'),
(2157, 'Cordero', 'Spike Ian', '2024-11-28', 'probationary', 'DOIS', NULL, 'Fryer Staff'),
(2159, 'Gavileño', 'Rizaldy', '2024-01-18', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(2160, 'Lencioco', 'Lyka', '2018-12-15', 'regular', 'DOIS', NULL, 'RND'),
(2161, 'Malijoc', 'Micheal Jan', '2023-05-02', 'regular', 'DOIS', NULL, 'Cook'),
(2162, 'Naparato', 'Shantily', '2019-02-18', 'regular', 'DOIS', NULL, 'Cashier'),
(2164, 'Pragados', 'Mary Rose', '2019-03-17', 'regular', 'DOIS', NULL, 'Cook'),
(2165, 'Tinasas', 'Jerry', '2024-04-01', 'regular', 'DOIS', NULL, 'Dishwasher'),
(2166, 'Traña', 'Micheal', '2023-02-14', 'regular', 'DOIS', NULL, 'Head Cook'),
(2167, 'Valencia', 'Erwin Paul', '2023-02-28', 'regular', 'DOIS', NULL, 'Waiter'),
(2168, 'Hermogenes', 'Adrian', '2024-05-11', 'regular', 'DOIS', NULL, 'Fryer'),
(2172, 'Estancia', 'Nole', '2025-01-16', 'probationary', 'DOIS', NULL, 'Cook'),
(2173, 'Abella', 'Randy', '2021-12-01', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Cook'),
(2174, 'Alacrito', 'Genard', '2024-06-10', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(2175, 'Andrade', 'Remar', '2019-06-04', 'regular', 'PUB', 'Nina Food Products Trading', 'Company Driver'),
(2176, 'Batara', 'Moriel Raquel', '2024-07-23', 'probationary', 'PUB', 'Main Office', NULL),
(2177, 'Billones', 'Ella', '2024-10-22', 'probationary', 'PUB', 'Main Office', 'Admin staff'),
(2178, 'Berber', 'Reina', '2023-06-03', 'regular', 'PUB', 'Main Office', 'Accounting staff'),
(2179, 'Calawigan', 'Jaime', '2017-05-05', 'regular', 'PUB', 'Nina Food Products Trading', 'Production Staff'),
(2181, 'Emolaga', 'Genoveva', '2023-11-28', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(2182, 'Gonzales', 'Roben', '2023-10-16', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(2183, 'Haudar', 'Jerlyn', '2018-07-05', 'regular', 'PUB', 'Shock Sisig', NULL),
(2184, 'Ituriaga', 'Mary Queen', '2024-04-17', 'regular', 'PUB', 'Main Office', 'Roving Operations Manager '),
(2185, 'Mateo', 'Jun Mark', '2018-12-01', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(2186, 'Moquete', 'Claribel', '2021-09-30', 'regular', 'PUB', 'Main Office', 'Accounting Officer'),
(2187, 'Servidad', 'Dean', '2021-06-24', 'regular', 'PUB', 'Main Office', 'Admin/HR'),
(2188, 'Pasol', 'Priselle Anne', '2024-10-30', 'probationary', 'PUB', 'Pub Express Resto-Employers', NULL),
(2189, 'Posadas', 'Lynie', '2021-06-15', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(2190, 'Pusoc', 'Rhea Mae', '2022-09-26', 'regular', 'PUB', 'Nina Food Products Trading', 'Purchaser/ OIC'),
(2191, 'Rubido', 'Cherry Joy', '2023-09-15', 'regular', 'PUB', 'Main Office', 'Accounting Staff'),
(2192, 'Sarasola', 'Dungie', '2024-07-02', 'regular', 'PUB', 'Main Office', 'Lay out Artist'),
(2193, 'Silveo', 'Stephanie Grace', '2022-08-23', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Kitchen Coordinator'),
(2194, 'Sunio', 'Hannah Grace', '2024-11-14', 'probationary', 'PUB', 'Main Office', 'HR Talent Acquisition'),
(2195, 'Velagio', 'Evangeline', '2024-05-15', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Cashier'),
(2196, 'Diesto', 'Carlo', '2025-01-25', 'probationary', 'PUB', 'Main Office', 'Accounting Staff'),
(2197, 'Lorilla', 'Jerson', '2025-01-02', 'probationary', 'PUB', 'Pub Express Resto-Employers', 'Kitchen staff'),
(2198, 'Villeta', 'Rolly', '2024-11-22', 'Probationary', 'STELLA', '', 'Cook'),
(2199, 'Jaranilla', 'Henessy', '2025-01-24', 'Probationary', 'DOIS', '', 'Restaurant Supervisor'),
(2200, 'Alitao', 'Lerissa', '2024-11-16', 'Probationary', 'STELLA', '', 'Cashier'),
(2202, 'Elizario', 'Ged', '2025-02-11', 'Probationary', 'PUB', 'Main Office', 'None');

-- --------------------------------------------------------

--
-- Table structure for table `employee_audit_log`
--

CREATE TABLE `employee_audit_log` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `action` varchar(50) NOT NULL,
  `old_value` text DEFAULT NULL,
  `new_value` text DEFAULT NULL,
  `changed_by` varchar(255) DEFAULT NULL,
  `changed_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `employee_audit_log`
--

INSERT INTO `employee_audit_log` (`id`, `employee_id`, `action`, `old_value`, `new_value`, `changed_by`, `changed_at`) VALUES
(44, 2198, 'Add', '', 'surname: Villeta, first_name: Rolly, date_hired: 2024-11-22, branch: STELLA, sub_branch: , position: Cook', '', '2025-03-19 02:10:24'),
(45, 2199, 'Add', '', 'surname: Jaranilla, first_name: Henessy, date_hired: 2025-01-24, branch: DOIS, sub_branch: , position: Restaurant Supervisor', '', '2025-03-19 02:14:33'),
(46, 2200, 'Add', '', 'surname: Alitao, first_name: Lerissa, date_hired: 2024-11-16, branch: STELLA, sub_branch: , position: Cashier', '', '2025-03-19 02:19:38'),
(54, 2124, 'Update', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: regular, position: Dishwasher, branch: STELLA, sub_branch: ', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: probationary, position: Dishwasher, branch: STELLA, sub_branch: Main Office', NULL, '2025-03-25 08:24:20');

-- --------------------------------------------------------

--
-- Table structure for table `evaluations`
--

CREATE TABLE `evaluations` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `appraisal_rating` int(11) NOT NULL,
  `summary_performance` decimal(3,1) NOT NULL,
  `recommendation` text NOT NULL,
  `evaluation_date` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `evaluations`
--

INSERT INTO `evaluations` (`id`, `employee_id`, `appraisal_rating`, `summary_performance`, `recommendation`, `evaluation_date`) VALUES
(28, 2176, 1, 1.0, 'Termination', '2025-03-19 02:24:09');

-- --------------------------------------------------------

--
-- Table structure for table `ojt_employees`
--

CREATE TABLE `ojt_employees` (
  `id` int(11) NOT NULL,
  `surname` varchar(50) NOT NULL,
  `first_name` varchar(50) NOT NULL,
  `date_hired` date NOT NULL,
  `hours_to_achieve` int(11) DEFAULT NULL,
  `branch` varchar(50) NOT NULL,
  `sub_branch` varchar(50) DEFAULT NULL,
  `status` enum('OJT') DEFAULT 'OJT',
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `ojt_hours`
--

CREATE TABLE `ojt_hours` (
  `id` int(11) NOT NULL,
  `ojt_id` int(11) NOT NULL,
  `date_worked` date NOT NULL,
  `hours_worked` decimal(5,2) NOT NULL,
  `recorded_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `payroll`
--

CREATE TABLE `payroll` (
  `employee_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `pub_payroll`
--

CREATE TABLE `pub_payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) DEFAULT 0.00,
  `rate_per_hour` decimal(10,2) DEFAULT 0.00,
  `no_of_days` int(11) DEFAULT 0,
  `no_of_hours` int(11) DEFAULT 0,
  `basic_pay` decimal(10,2) DEFAULT 0.00,
  `days` int(11) DEFAULT 0,
  `amount` decimal(10,2) DEFAULT 0.00,
  `rate_ovt` decimal(10,2) DEFAULT 0.00,
  `hour_ovt` decimal(10,2) DEFAULT 0.00,
  `total_ovt` decimal(10,2) DEFAULT 0.00,
  `no_of_days_tr` int(11) DEFAULT 0,
  `trainee_allowance` decimal(10,2) DEFAULT 0.00,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `medical_reimbursement` decimal(10,2) DEFAULT 0.00,
  `hours_nd` decimal(10,2) DEFAULT 0.00,
  `ten_percent` decimal(10,2) DEFAULT 0.00,
  `hours_hp` decimal(10,2) DEFAULT 0.00,
  `hundred_percent` decimal(10,2) DEFAULT 0.00,
  `hours_hdp` decimal(10,2) DEFAULT 0.00,
  `thirty_percent` decimal(10,2) DEFAULT 0.00,
  `gross_pay` decimal(10,2) DEFAULT 0.00,
  `sss_loan` decimal(10,2) DEFAULT 0.00,
  `sss_d` decimal(10,2) DEFAULT 0.00,
  `phic` decimal(10,2) DEFAULT 0.00,
  `pag_ibig` decimal(10,2) DEFAULT 0.00,
  `insurance` decimal(10,2) DEFAULT 0.00,
  `others` decimal(10,2) DEFAULT 0.00,
  `cp` decimal(10,2) DEFAULT 0.00,
  `cash_advance` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `adjustment` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `payslip_status` varchar(20) NOT NULL DEFAULT 'ONGOING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `save_payrolls`
--

CREATE TABLE `save_payrolls` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) DEFAULT 0.00,
  `rate_per_hour` decimal(10,2) DEFAULT 0.00,
  `no_of_days` int(11) DEFAULT 0,
  `no_of_hours` int(11) DEFAULT 0,
  `basic_pay` decimal(10,2) DEFAULT 0.00,
  `days` int(11) DEFAULT 0,
  `amount` decimal(10,2) DEFAULT 0.00,
  `rate` decimal(10,2) DEFAULT 0.00,
  `hour` decimal(10,2) DEFAULT 0.00,
  `total` decimal(10,2) DEFAULT 0.00,
  `allow` decimal(10,2) DEFAULT 0.00,
  `medical_reimbursement` decimal(10,2) DEFAULT 0.00,
  `hours_nd` decimal(10,2) DEFAULT 0.00,
  `percent10` decimal(10,2) DEFAULT 0.00,
  `hours_hp` decimal(10,2) DEFAULT 0.00,
  `percent100` decimal(10,2) DEFAULT 0.00,
  `hours_hd` decimal(10,2) DEFAULT 0.00,
  `percent30` decimal(10,2) DEFAULT 0.00,
  `gross_pay` decimal(10,2) DEFAULT 0.00,
  `philhealth` decimal(10,2) DEFAULT 0.00,
  `sss_loan` decimal(10,2) DEFAULT 0.00,
  `sss` decimal(10,2) DEFAULT 0.00,
  `hdmf` decimal(10,2) DEFAULT 0.00,
  `hdmf_loan` decimal(10,2) DEFAULT 0.00,
  `insurance` decimal(10,2) DEFAULT 0.00,
  `cash_advance` decimal(10,2) DEFAULT 0.00,
  `bhouse` decimal(10,2) DEFAULT 0.00,
  `total_deductions` decimal(10,2) DEFAULT 0.00,
  `leave_with_pay` decimal(10,2) DEFAULT 0.00,
  `incentives` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp()
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Table structure for table `stella_payroll`
--

CREATE TABLE `stella_payroll` (
  `id` int(11) NOT NULL,
  `employee_id` int(11) NOT NULL,
  `daily_rate` decimal(10,2) NOT NULL,
  `rate_per_hour` decimal(10,2) NOT NULL,
  `no_of_days` int(11) NOT NULL,
  `no_of_hours` int(11) NOT NULL,
  `basic_pay` decimal(10,2) NOT NULL,
  `days` int(11) DEFAULT 0,
  `amount` decimal(10,2) DEFAULT 0.00,
  `rate_ovt` decimal(10,2) DEFAULT 0.00,
  `hour_ovt` decimal(10,2) DEFAULT 0.00,
  `total_ovt` decimal(10,2) DEFAULT 0.00,
  `no_of_days_tr` int(11) DEFAULT 0,
  `trainee_allowance` decimal(10,2) DEFAULT 0.00,
  `allowance` decimal(10,2) DEFAULT 0.00,
  `medical_reimbursement` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hours_nd` decimal(10,2) NOT NULL DEFAULT 0.00,
  `ten_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hours_hp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hundred_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `hours_hdp` decimal(10,2) DEFAULT 0.00,
  `thirty_percent` decimal(10,2) NOT NULL DEFAULT 0.00,
  `gross_pay` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sss_loan` decimal(10,2) NOT NULL DEFAULT 0.00,
  `sss_d` decimal(10,2) NOT NULL DEFAULT 0.00,
  `phic` decimal(10,2) NOT NULL DEFAULT 0.00,
  `pag_ibig` decimal(10,2) NOT NULL DEFAULT 0.00,
  `insurance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `others` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cp` decimal(10,2) NOT NULL DEFAULT 0.00,
  `cash_advance` decimal(10,2) NOT NULL DEFAULT 0.00,
  `total_deductions` decimal(10,2) NOT NULL DEFAULT 0.00,
  `adjustment` decimal(10,2) DEFAULT 0.00,
  `net_pay` decimal(10,2) DEFAULT 0.00,
  `payslip_status` varchar(20) NOT NULL DEFAULT 'ONGOING'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Indexes for dumped tables
--

--
-- Indexes for table `dois_payroll`
--
ALTER TABLE `dois_payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `employees`
--
ALTER TABLE `employees`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_status` (`status`);

--
-- Indexes for table `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  ADD PRIMARY KEY (`id`),
  ADD KEY `idx_employee_id` (`employee_id`);

--
-- Indexes for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `ojt_employees`
--
ALTER TABLE `ojt_employees`
  ADD PRIMARY KEY (`id`);

--
-- Indexes for table `ojt_hours`
--
ALTER TABLE `ojt_hours`
  ADD PRIMARY KEY (`id`),
  ADD KEY `ojt_id` (`ojt_id`);

--
-- Indexes for table `payroll`
--
ALTER TABLE `payroll`
  ADD PRIMARY KEY (`employee_id`);

--
-- Indexes for table `pub_payroll`
--
ALTER TABLE `pub_payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `save_payrolls`
--
ALTER TABLE `save_payrolls`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- Indexes for table `stella_payroll`
--
ALTER TABLE `stella_payroll`
  ADD PRIMARY KEY (`id`),
  ADD KEY `employee_id` (`employee_id`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `dois_payroll`
--
ALTER TABLE `dois_payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=27;

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2203;

--
-- AUTO_INCREMENT for table `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=55;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=31;

--
-- AUTO_INCREMENT for table `ojt_employees`
--
ALTER TABLE `ojt_employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=26;

--
-- AUTO_INCREMENT for table `ojt_hours`
--
ALTER TABLE `ojt_hours`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `pub_payroll`
--
ALTER TABLE `pub_payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=20;

--
-- AUTO_INCREMENT for table `save_payrolls`
--
ALTER TABLE `save_payrolls`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;

--
-- AUTO_INCREMENT for table `stella_payroll`
--
ALTER TABLE `stella_payroll`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=119;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dois_payroll`
--
ALTER TABLE `dois_payroll`
  ADD CONSTRAINT `dois_payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  ADD CONSTRAINT `fk_employee_audit_log_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `evaluations`
--
ALTER TABLE `evaluations`
  ADD CONSTRAINT `evaluations_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `ojt_hours`
--
ALTER TABLE `ojt_hours`
  ADD CONSTRAINT `ojt_hours_ibfk_1` FOREIGN KEY (`ojt_id`) REFERENCES `ojt_employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `payroll`
--
ALTER TABLE `payroll`
  ADD CONSTRAINT `fk_payroll_employee` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `pub_payroll`
--
ALTER TABLE `pub_payroll`
  ADD CONSTRAINT `pub_payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `save_payrolls`
--
ALTER TABLE `save_payrolls`
  ADD CONSTRAINT `save_payrolls_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;

--
-- Constraints for table `stella_payroll`
--
ALTER TABLE `stella_payroll`
  ADD CONSTRAINT `stella_payroll_ibfk_1` FOREIGN KEY (`employee_id`) REFERENCES `employees` (`id`) ON DELETE CASCADE;
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
