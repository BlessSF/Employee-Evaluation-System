-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1:3306
-- Generation Time: Mar 19, 2025 at 02:42 AM
-- Server version: 10.11.10-MariaDB
-- PHP Version: 7.2.34

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `u731964465_employee`
--

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
(1905, 'Alarcon', 'Jhon Rey', '2023-05-24', 'regular', 'STELLA', 'Main Office', 'Dishwasher'),
(1906, 'Ardelosa', 'Eric ', '2024-02-03', 'regular', 'STELLA', 'Main Office', 'Waiter'),
(1907, 'Arquilola', 'Lourence Mark', '2020-09-21', 'regular', 'STELLA', NULL, 'Supervisor'),
(1908, 'Bibanco', 'Dave Mitchel', '2024-03-07', 'regular', 'STELLA', 'Main Office', 'Cook'),
(1909, 'Bongais', 'John Aris', '2022-06-24', 'regular', 'STELLA', 'Main Office', 'Cook'),
(1910, 'Canada', 'Jose Jr.', '2021-11-15', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1911, 'Catalan', 'Ryan', '2023-04-13', 'regular', 'STELLA', 'Main Office', 'Waiter'),
(1912, 'Estancia', 'Nole', '2025-01-16', 'probationary', 'DOIS', NULL, 'Cook'),
(1913, 'Estibal', 'John Adonis', '2024-01-16', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1914, 'Estibal', 'Zymer', '2023-07-31', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1915, 'Gadian', 'Kristine', '2022-04-22', 'regular', 'STELLA', NULL, 'Kitchen Coordinator'),
(1916, 'Honorio', 'Lara Joy', '2023-05-22', 'regular', 'STELLA', NULL, 'Cashier'),
(1917, 'Labarosa', 'Stephen Bryan', '2022-07-26', 'regular', 'STELLA', 'Main Office', 'Kitchen Dispatch'),
(1918, 'Labos', 'Aira Joyce ', '2022-12-12', 'regular', 'STELLA', 'Main Office', 'CSR'),
(1919, 'Napilay', 'John Christian', '2024-02-04', 'regular', 'STELLA', 'Main Office', 'Kitchen Staff'),
(1920, 'Patanao', 'Martin John', '2023-03-28', 'regular', 'STELLA', NULL, 'Asst. Kitchen Coordinator'),
(1921, 'Tajonera', 'Romagel', '2017-06-07', 'regular', 'STELLA', NULL, 'Asst. Operations Coordinator'),
(1922, 'Radjudin', 'Norama', '2024-04-09', 'regular', 'STELLA', 'Main Office', 'Cashier'),
(1923, 'Villaruz', 'Carl Angel', '2023-06-24', 'regular', 'STELLA', 'Main Office', 'Cook'),
(1924, 'Anlap', 'Valent John', '2024-06-02', 'regular', 'STELLA', 'Main Office', 'Waiter'),
(1925, 'Dela cruz', 'Jeremy', '2024-05-22', 'regular', 'STELLA', 'Main Office', 'Diswasher'),
(1926, 'Young', 'Kevin Ken', '2024-04-01', 'regular', 'STELLA', 'Main Office', 'Waiter'),
(1927, 'Veloso', 'Christine Mae', '2024-06-22', 'regular', 'STELLA', 'Main Office', 'Cashier'),
(1928, 'Ngalan', 'Jennieveb', '2024-07-13', 'regular', 'STELLA', 'Main Office', 'Cashier'),
(1929, 'Pabilona', 'Eira Faith', '2024-09-28', 'probationary', 'STELLA', 'Main Office', 'Kitchen Dispatch'),
(1932, 'Altura', 'John Hyder', '2024-01-19', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(1935, 'Belgira', 'Mar John', '2023-01-21', 'regular', 'DOIS', 'Main Office', 'Waiter/Cleaner'),
(1937, 'Cordero', 'Spike Ian', '2024-11-28', 'probationary', 'DOIS', NULL, 'Fryer Staff'),
(1939, 'Gavileño', 'Rizaldy', '2024-01-18', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(1940, 'Lencioco', 'Lyka', '2018-12-15', 'regular', 'DOIS', 'Main Office', 'RND'),
(1941, 'Malijoc', 'Micheal Jan', '2023-05-02', 'regular', 'DOIS', NULL, 'Cook'),
(1942, 'Naparato', 'Shantily', '2019-02-18', 'regular', 'DOIS', NULL, 'Cashier'),
(1944, 'Pragados', 'Mary Rose', '2019-03-17', 'regular', 'DOIS', NULL, 'Cook'),
(1945, 'Tinasas', 'Jerry', '2024-04-01', 'regular', 'DOIS', 'Main Office', 'Dishwasher'),
(1946, 'Traña', 'Micheal', '2023-02-14', 'regular', 'DOIS', 'Main Office', 'Head Cook'),
(1947, 'Valencia', 'Erwin Paul', '2023-02-28', 'regular', 'DOIS', NULL, 'Waiter'),
(1948, 'Hermogenes', 'Adrian', '2024-05-11', 'regular', 'DOIS', NULL, 'Fryer'),
(1952, 'Abella', 'Randy', '2021-12-01', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Cook'),
(1953, 'Alacrito', 'Genard', '2024-06-10', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1954, 'Andrade', 'Remar', '2019-06-04', 'regular', 'PUB', 'Nina Food Products Trading', 'Company Driver'),
(1955, 'Batara', 'Moriel Raquel', '2024-07-23', 'probationary', 'PUB', 'Main Office', NULL),
(1956, 'Billones', 'Ella', '2024-10-22', 'probationary', 'PUB', 'Main Office', 'Admin staff'),
(1957, 'Berber', 'Reina', '2023-06-03', 'regular', 'PUB', 'Main Office', 'Accounting Staff'),
(1958, 'Calawigan', 'Jaime', '2017-05-05', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1960, 'Emolaga', 'Genoveva', '2023-11-28', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1961, 'Gonzales', 'Roben', '2023-10-16', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1962, 'Haudar', 'Jerlyn', '2018-07-05', 'regular', 'PUB', 'Shock Sisig', NULL),
(1963, 'Ituriaga', 'Mary Queen', '2024-04-17', 'regular', 'PUB', 'Main Office', 'Roving Operations Manager '),
(1964, 'Mateo', 'Jun Mark', '2018-12-01', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1965, 'Moquete', 'Claribel', '2021-09-30', 'regular', 'PUB', 'Main Office', 'Accounting Officer'),
(1966, 'Servidad ', 'Dean', '2021-06-24', 'regular', 'PUB', 'Main Office', 'Admin/HR'),
(1967, 'Pasol', 'Priselle Anne', '2024-10-30', 'probationary', 'PUB', 'Pub Express Resto-Employers', NULL),
(1969, 'Posadas', 'Lynie', '2021-06-15', 'regular', 'PUB', 'Nina Food Products Trading', 'Production staff'),
(1970, 'Pusoc', 'Rhea Mae', '2022-09-26', 'regular', 'PUB', 'Nina Food Products Trading', 'Purchaser/ OIC'),
(1971, 'Rubido', 'Cherry Joy', '2023-09-15', 'regular', 'PUB', 'Main Office', 'Accounting Staff'),
(1972, 'Sarasola', 'Dungie', '2024-07-02', 'regular', 'PUB', 'Main Office', 'Lay out Artist'),
(1973, 'Silveo', 'Stephanie Grace', '2022-08-23', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Kitchen Coordinator'),
(1974, 'Sunio', 'Hannah Grace', '2024-11-14', 'probationary', 'PUB', 'Main Office', 'HR Talent Acquisition'),
(1975, 'Velagio', 'Evangeline', '2024-05-15', 'regular', 'PUB', 'Pub Express Resto-Employers', 'Cashier'),
(1977, 'Villeta', 'Rolly', '2024-11-22', 'probationary', 'STELLA', 'Main Office', 'Cook'),
(1979, 'Jaranilla', 'Henessy', '2025-01-24', 'Probationary', 'DOIS', '', 'Restaurant Supervisor'),
(1980, 'Diesto', 'Carlo', '2025-01-25', 'Probationary', 'PUB', 'Main Office', 'Accounting Staff'),
(1981, 'Lorilla', 'Jerson', '2025-01-02', 'Probationary', 'PUB', 'Pub Express Resto-Employers', 'Kitchen staff'),
(1982, 'Alitao', 'Lerissa', '2024-11-16', 'probationary', 'STELLA', 'Main Office', 'Cashier');

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
(48, 1905, 'Update', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: probationary, position: Dishwasher, branch: STELLA, sub_branch: ', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: regular, position: Dishwasher, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:46:25'),
(49, 1924, 'Update', 'surname: Anlap, first_name: Valent John, date_hired: 2024-06-02, status: probationary, position: Waiter, branch: STELLA, sub_branch: ', 'surname: Anlap, first_name: Valent John, date_hired: 2024-06-02, status: regular, position: Waiter, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:46:41'),
(50, 1906, 'Update', 'surname: Ardelosa, first_name: Eric , date_hired: 2024-02-03, status: probationary, position: Waiter, branch: STELLA, sub_branch: ', 'surname: Ardelosa, first_name: Eric , date_hired: 2024-02-03, status: regular, position: Waiter, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:47:01'),
(51, 1908, 'Update', 'surname: Bibanco, first_name: Dave Mitchel, date_hired: 2024-03-07, status: probationary, position: Cook, branch: STELLA, sub_branch: ', 'surname: Bibanco, first_name: Dave Mitchel, date_hired: 2024-03-07, status: regular, position: Cook, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:47:11'),
(52, 1909, 'Update', 'surname: Bongais, first_name: John Aris, date_hired: 2022-06-24, status: probationary, position: Cook, branch: STELLA, sub_branch: ', 'surname: Bongais, first_name: John Aris, date_hired: 2022-06-24, status: regular, position: Cook, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:47:24'),
(53, 1911, 'Update', 'surname: Catalan, first_name: Ryan, date_hired: 2023-04-13, status: probationary, position: Waiter, branch: STELLA, sub_branch: ', 'surname: Catalan, first_name: Ryan, date_hired: 2023-04-13, status: regular, position: Waiter, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:47:37'),
(54, 1925, 'Update', 'surname: Dela cruz, first_name: Jeremy, date_hired: 2024-05-22, status: probationary, position: Diswasher, branch: STELLA, sub_branch: ', 'surname: Dela cruz, first_name: Jeremy, date_hired: 2024-05-22, status: regular, position: Diswasher, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:47:54'),
(55, 1912, 'Update', 'surname: Estancia, first_name: Nole, date_hired: 2022-06-06, status: probationary, position: Cook, branch: STELLA, sub_branch: ', 'surname: Estancia, first_name: Nole, date_hired: 2025-01-16, status: probationary, position: Cook, branch: PUB, sub_branch: Pub Express Resto-Employers', NULL, '2025-02-15 02:50:22'),
(56, 1917, 'Update', 'surname: Labarosa, first_name: Stephen Bryan, date_hired: 2022-07-26, status: probationary, position: Kitchen Dispatch, branch: STELLA, sub_branch: ', 'surname: Labarosa, first_name: Stephen Bryan, date_hired: 2022-07-26, status: regular, position: Kitchen Dispatch, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:50:34'),
(57, 1918, 'Update', 'surname: Labos, first_name: Aira Joyce , date_hired: 2022-12-12, status: probationary, position: CSR, branch: STELLA, sub_branch: ', 'surname: Labos, first_name: Aira Joyce , date_hired: 2022-12-12, status: regular, position: CSR, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:51:04'),
(58, 1919, 'Update', 'surname: Napilay, first_name: John Christian, date_hired: 2024-02-04, status: probationary, position: Kitchen Staff, branch: STELLA, sub_branch: ', 'surname: Napilay, first_name: John Christian, date_hired: 2024-02-04, status: regular, position: Kitchen Staff, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:51:17'),
(59, 1928, 'Update', 'surname: Ngalan, first_name: Jennieveb, date_hired: 2024-07-13, status: probationary, position: Cashier, branch: STELLA, sub_branch: ', 'surname: Ngalan, first_name: Jennieveb, date_hired: 2024-07-13, status: regular, position: Cashier, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:51:33'),
(60, 1922, 'Update', 'surname: Radjudin, first_name: Norama, date_hired: 2024-04-09, status: probationary, position: Cashier, branch: STELLA, sub_branch: ', 'surname: Radjudin, first_name: Norama, date_hired: 2024-04-09, status: regular, position: Cashier, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:51:45'),
(61, 1927, 'Update', 'surname: Veloso, first_name: Christine Mae, date_hired: 2024-06-22, status: probationary, position: Cashier, branch: STELLA, sub_branch: ', 'surname: Veloso, first_name: Christine Mae, date_hired: 2024-06-22, status: regular, position: Cashier, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:52:13'),
(62, 1923, 'Update', 'surname: Villaruz, first_name: Carl Angel, date_hired: 2023-06-24, status: probationary, position: Cook, branch: STELLA, sub_branch: ', 'surname: Villaruz, first_name: Carl Angel, date_hired: 2023-06-24, status: regular, position: Cook, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:52:39'),
(63, 1926, 'Update', 'surname: Young, first_name: Kevin Ken, date_hired: 2024-04-01, status: probationary, position: Waiter, branch: STELLA, sub_branch: ', 'surname: Young, first_name: Kevin Ken, date_hired: 2024-04-01, status: regular, position: Waiter, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:52:52'),
(64, 1977, 'Add', '', 'surname: VILLETA, first_name: ROLLY, date_hired: 2024-11-22, branch: STELLA, sub_branch: , position: Cook', '', '2025-02-15 02:53:43'),
(65, 1977, 'Update', 'surname: VILLETA, first_name: ROLLY, date_hired: 2024-11-22, status: Probationary, position: Cook, branch: STELLA, sub_branch: ', 'surname: Villeta, first_name: Rolly, date_hired: 2024-11-22, status: probationary, position: Cook, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 02:54:08'),
(66, 1935, 'Update', 'surname: Belgira, first_name: Mar John, date_hired: 2023-01-21, status: probationary, position: Waiter/Cleaner, branch: DOIS, sub_branch: ', 'surname: Belgira, first_name: Mar John, date_hired: 2023-01-21, status: regular, position: Waiter/Cleaner, branch: DOIS, sub_branch: Main Office', NULL, '2025-02-15 02:54:38'),
(67, 1940, 'Update', 'surname: Lencioco, first_name: Lyka, date_hired: 2018-12-15, status: probationary, position: RND, branch: DOIS, sub_branch: ', 'surname: Lencioco, first_name: Lyka, date_hired: 2018-12-15, status: regular, position: RND, branch: DOIS, sub_branch: Main Office', NULL, '2025-02-15 02:55:55'),
(68, 1945, 'Update', 'surname: Tinasas, first_name: Jerry, date_hired: 2024-04-01, status: probationary, position: Dishwasher, branch: DOIS, sub_branch: ', 'surname: Tinasas, first_name: Jerry, date_hired: 2024-04-01, status: regular, position: Dishwasher, branch: DOIS, sub_branch: Main Office', NULL, '2025-02-15 02:57:45'),
(69, 1946, 'Update', 'surname: Traña, first_name: Micheal, date_hired: 2023-02-14, status: probationary, position: Head Cook, branch: DOIS, sub_branch: ', 'surname: Traña, first_name: Micheal, date_hired: 2023-02-14, status: regular, position: Head Cook, branch: DOIS, sub_branch: Main Office', NULL, '2025-02-15 02:57:57'),
(71, 1979, 'Add', '', 'surname: Jaranilla, first_name: Henessy, date_hired: 2025-01-24, branch: DOIS, sub_branch: , position: Restaurant Supervisor', '', '2025-02-15 03:09:18'),
(72, 1957, 'Update', 'surname: Berber, first_name: Reina, date_hired: 2023-06-03, status: probationary, position: , branch: PUB, sub_branch: Main Office', 'surname: Berber, first_name: Reina, date_hired: 2023-06-03, status: regular, position: Accounting Staff, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:10:42'),
(73, 1956, 'Update', 'surname: Billones, first_name: Ella, date_hired: 2024-10-22, status: probationary, position: , branch: PUB, sub_branch: Main Office', 'surname: Billones, first_name: Ella, date_hired: 2024-10-22, status: probationary, position: Admin staff, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:11:02'),
(74, 1956, 'Update', 'surname: Billones, first_name: Ella, date_hired: 2024-10-22, status: probationary, position: Admin staff, branch: PUB, sub_branch: Main Office', 'surname: Billones, first_name: Ella, date_hired: 2024-10-22, status: probationary, position: Admin staff, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:11:18'),
(75, 1972, 'Update', 'surname: Sarasola, first_name: Dungie, date_hired: 2024-07-02, status: probationary, position: , branch: PUB, sub_branch: Main Office', 'surname: Sarasola, first_name: Dungie, date_hired: 2024-07-02, status: probationary, position: Lay out Artist, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:11:50'),
(76, 1974, 'Update', 'surname: Sunio, first_name: Hannah Grace, date_hired: 2024-11-14, status: probationary, position: , branch: PUB, sub_branch: Main Office', 'surname: Sunio, first_name: Hannah Grace, date_hired: 2024-11-14, status: probationary, position: HR Talent Acquisition, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:12:59'),
(77, 1980, 'Add', '', 'surname: Diesto, first_name: Carlo, date_hired: 2025-01-25, branch: PUB, sub_branch: Main Office, position: Accounting Staff', '', '2025-02-15 03:13:44'),
(78, 1953, 'Update', 'surname: Alacrito, first_name: Genard, date_hired: 2024-06-10, status: probationary, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Alacrito, first_name: Genard, date_hired: 2024-06-10, status: regular, position: Food Processor staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:14:12'),
(79, 1953, 'Update', 'surname: Alacrito, first_name: Genard, date_hired: 2024-06-10, status: regular, position: Food Processor staff, branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Alacrito, first_name: Genard, date_hired: 2024-06-10, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:14:38'),
(80, 1961, 'Update', 'surname: Gonzales, first_name: Roben, date_hired: 2023-10-16, status: probationary, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Gonzales, first_name: Roben, date_hired: 2023-10-16, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:15:17'),
(81, 1970, 'Update', 'surname: Pusoc, first_name: Rhea Mae, date_hired: 2022-09-26, status: probationary, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Pusoc, first_name: Rhea Mae, date_hired: 2022-09-26, status: regular, position: Purchaser/ OIC, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:15:37'),
(82, 1975, 'Update', 'surname: Velagio, first_name: Evangeline, date_hired: 2024-05-15, status: probationary, position: , branch: PUB, sub_branch: Pub Express Resto-Employers', 'surname: Velagio, first_name: Evangeline, date_hired: 2024-05-15, status: regular, position: Cashier, branch: PUB, sub_branch: Pub Express Resto-Employers', NULL, '2025-02-15 03:16:30'),
(83, 1952, 'Update', 'surname: Abella, first_name: Randy, date_hired: 2021-12-01, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Abella, first_name: Randy, date_hired: 2021-12-01, status: regular, position: Cook, branch: PUB, sub_branch: Pub Express Resto-Employers', NULL, '2025-02-15 03:17:24'),
(84, 1957, 'Update', 'surname: Berber, first_name: Reina, date_hired: 2023-06-03, status: regular, position: Accounting Staff, branch: PUB, sub_branch: Main Office', 'surname: Berber, first_name: Reina, date_hired: 2023-06-03, status: regular, position: Accounting Staff, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:17:45'),
(85, 1963, 'Update', 'surname: Ituriaga, first_name: Mary Queen, date_hired: 2024-04-17, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Ituriaga, first_name: Mary Queen, date_hired: 2024-04-17, status: regular, position: Roving Operations Manager , branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:18:29'),
(86, 1965, 'Update', 'surname: Moquete, first_name: Claribel, date_hired: 2021-09-30, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Moquete, first_name: Claribel, date_hired: 2021-09-30, status: regular, position: Accounting Officer, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:19:20'),
(87, 1969, 'Update', 'surname: Posadas, first_name: Lynie, date_hired: 2021-06-15, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Posadas, first_name: Lynie, date_hired: 2021-06-15, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:19:50'),
(88, 1971, 'Update', 'surname: Rubido, first_name: Cherry Joy, date_hired: 2023-09-15, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Rubido, first_name: Cherry Joy, date_hired: 2023-09-15, status: regular, position: Accounting Staff, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:20:15'),
(89, 1973, 'Update', 'surname: Silveo, first_name: Stephanie Grace, date_hired: 2022-08-23, status: regular, position: , branch: PUB, sub_branch: Main Office', 'surname: Silveo, first_name: Stephanie Grace, date_hired: 2022-08-23, status: regular, position: Kitchen Coordinator, branch: PUB, sub_branch: Pub Express Resto-Employers', NULL, '2025-02-15 03:20:52'),
(90, 1954, 'Update', 'surname: Andrade, first_name: Remar, date_hired: 2019-06-04, status: regular, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Andrade, first_name: Remar, date_hired: 2019-06-04, status: regular, position: Company Driver, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:21:28'),
(91, 1958, 'Update', 'surname: Calawigan, first_name: Jaime, date_hired: 2017-05-05, status: regular, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Calawigan, first_name: Jaime, date_hired: 2017-05-05, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:21:44'),
(92, 1961, 'Update', 'surname: Gonzales, first_name: Roben, date_hired: 2023-10-16, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Gonzales, first_name: Roben, date_hired: 2023-10-16, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:22:05'),
(93, 1966, 'Update', 'surname: Palomo, first_name: Dean, date_hired: 2021-06-24, status: regular, position: , branch: PUB, sub_branch: Nina Food Products Trading', 'surname: Palomo, first_name: Dean, date_hired: 2021-06-24, status: regular, position: Admin/HR, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:22:28'),
(94, 1966, 'Update', 'surname: Palomo, first_name: Dean, date_hired: 2021-06-24, status: regular, position: Admin/HR, branch: PUB, sub_branch: Main Office', 'surname: Servidad , first_name: Dean, date_hired: 2021-06-24, status: regular, position: Admin/HR, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:22:52'),
(95, 1960, 'Update', 'surname: Emolaga, first_name: Genoveva, date_hired: 2023-11-28, status: regular, position: , branch: PUB, sub_branch: Pub Express Resto-Employers', 'surname: Emolaga, first_name: Genoveva, date_hired: 2023-11-28, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:23:40'),
(96, 1964, 'Update', 'surname: Mateo, first_name: Jun Mark, date_hired: 2018-12-01, status: regular, position: , branch: PUB, sub_branch: Pub Express Resto-Employers', 'surname: Mateo, first_name: Jun Mark, date_hired: 2018-12-01, status: regular, position: Production staff, branch: PUB, sub_branch: Nina Food Products Trading', NULL, '2025-02-15 03:23:58'),
(97, 1972, 'Update', 'surname: Sarasola, first_name: Dungie, date_hired: 2024-07-02, status: probationary, position: Lay out Artist, branch: PUB, sub_branch: Main Office', 'surname: Sarasola, first_name: Dungie, date_hired: 2024-07-02, status: regular, position: Lay out Artist, branch: PUB, sub_branch: Main Office', NULL, '2025-02-15 03:27:24'),
(98, 1929, 'Update', 'surname: Pabilona, first_name: Eira Faith, date_hired: 2024-09-28, status: probationary, position: Kitchen Dispatch, branch: STELLA, sub_branch: ', 'surname: Pabilona, first_name: Eira Faith, date_hired: 2024-09-28, status: probationary, position: Kitchen Dispatch, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-15 03:33:57'),
(99, 1981, 'Add', '', 'surname: Lorilla, first_name: Jerson, date_hired: 2025-01-02, branch: PUB, sub_branch: Pub Express Resto-Employers, position: Kitchen staff', '', '2025-02-15 03:43:26'),
(100, 1982, 'Add', '', 'surname: Alitao, first_name: Lerissa, date_hired: 2024-11-16, branch: STELLA, sub_branch: , position: Cashier', '', '2025-02-15 05:38:08'),
(101, 1982, 'Update', 'surname: Alitao, first_name: Lerissa, date_hired: 2024-11-16, status: Probationary, position: Cashier, branch: STELLA, sub_branch: ', 'surname: Alitao, first_name: Lerissa, date_hired: 2024-11-16, status: probationary, position: Cashier, branch: STELLA, sub_branch: Main Office', NULL, '2025-02-16 23:54:54'),
(102, 1912, 'Update', 'surname: Estancia, first_name: Nole, date_hired: 2025-01-16, status: probationary, position: Cook, branch: PUB, sub_branch: Pub Express Resto-Employers', 'surname: Estancia, first_name: Nole, date_hired: 2025-01-16, status: probationary, position: Cook, branch: DOIS, sub_branch: ', NULL, '2025-03-17 02:18:24');

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
(34, 1955, 5, 1.0, 'Termination', '2025-02-15 03:35:27');

--
-- Indexes for dumped tables
--

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
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `employees`
--
ALTER TABLE `employees`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1983;

--
-- AUTO_INCREMENT for table `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=103;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=35;

--
-- Constraints for dumped tables
--

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
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
