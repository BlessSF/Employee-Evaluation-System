-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Feb 06, 2025 at 04:09 AM
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
(1751, 'Alarcon', 'Jhon Rey', '2023-05-24', 'probationary', 'STELLA', NULL, 'Dishwasher'),
(1752, 'Ardelosa', 'Eric ', '2024-02-03', 'probationary', 'STELLA', NULL, 'Waiter'),
(1753, 'Arquilola', 'Lourence Mark', '2020-09-21', 'regular', 'STELLA', NULL, 'Supervisor'),
(1754, 'Bibanco', 'Dave Mitchel', '2024-03-07', 'probationary', 'STELLA', NULL, 'Cook'),
(1755, 'Bongais', 'John Aris', '2022-06-24', 'probationary', 'STELLA', NULL, 'Cook'),
(1756, 'Canada', 'Jose Jr.', '2021-11-15', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1757, 'Catalan', 'Ryan', '2023-04-13', 'probationary', 'STELLA', NULL, 'Waiter'),
(1758, 'Estancia', 'Nole', '2022-06-06', 'probationary', 'STELLA', NULL, 'Cook'),
(1759, 'Estibal', 'John Adonis', '2024-01-16', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1760, 'Estibal', 'Zymer', '2023-07-31', 'regular', 'STELLA', NULL, 'Kitchen Staff'),
(1761, 'Gadian', 'Kristine', '2022-04-22', 'regular', 'STELLA', NULL, 'Kitchen Coordinator'),
(1762, 'Honorio', 'Lara Joy', '2023-05-22', 'regular', 'STELLA', NULL, 'Cashier'),
(1763, 'Labarosa', 'Stephen Bryan', '2022-07-26', 'probationary', 'STELLA', NULL, 'Kitchen Dispatch'),
(1764, 'Labos', 'Aira Joyce ', '2022-12-12', 'probationary', 'STELLA', NULL, 'CSR'),
(1765, 'Napilay', 'John Christian', '2024-02-04', 'probationary', 'STELLA', NULL, 'Kitchen Staff'),
(1766, 'Patanao', 'Martin John', '2023-03-28', 'regular', 'STELLA', NULL, 'Asst. Kitchen Coordinator'),
(1767, 'Tajonera', 'Romagel', '2017-06-07', 'regular', 'STELLA', NULL, 'Asst. Operations Coordinator'),
(1768, 'Radjudin', 'Norama', '2024-04-09', 'probationary', 'STELLA', NULL, 'Cashier'),
(1769, 'Villaruz', 'Carl Angel', '2023-06-24', 'probationary', 'STELLA', NULL, 'Cook'),
(1770, 'Anlap', 'Valent John', '2024-06-02', 'probationary', 'STELLA', NULL, 'Waiter'),
(1771, 'Dela cruz', 'Jeremy', '2024-05-22', 'probationary', 'STELLA', NULL, 'Diswasher'),
(1772, 'Young', 'Kevin Ken', '2024-04-01', 'probationary', 'STELLA', NULL, 'Waiter'),
(1773, 'Veloso', 'Christine Mae', '2024-06-22', 'probationary', 'STELLA', NULL, 'Cashier'),
(1774, 'Ngalan', 'Jennieveb', '2024-07-13', 'probationary', 'STELLA', NULL, 'Cashier'),
(1775, 'Pabilona', 'Eira Faith', '2024-09-28', 'probationary', 'STELLA', NULL, 'Kitchen Dispatch'),
(1776, 'Sentil', 'Wendel', '2024-10-29', 'probationary', 'STELLA', NULL, 'Waiter'),
(1777, 'Aaron', 'Melanie Shieme', '2024-05-02', 'probationary', 'DOIS', NULL, 'Kitchen Dispatch'),
(1778, 'Altura', 'John Hyder', '2024-01-19', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(1779, 'Barredo', 'Christian Glen', '2024-01-20', 'probationary', 'DOIS', NULL, 'Waiter'),
(1780, 'Bayona', 'Vicente Jr.', '2024-04-24', 'probationary', 'DOIS', NULL, 'Grillman'),
(1781, 'Belgira', 'Mar John', '2023-01-21', 'probationary', 'DOIS', NULL, 'Waiter/Cleaner'),
(1782, 'Cenal', 'Patrick', '2024-11-28', 'probationary', 'DOIS', NULL, 'Cook'),
(1783, 'Cordero', 'Spike Ian', '2024-11-28', 'probationary', 'DOIS', NULL, 'Fryer Staff'),
(1784, 'Diaz', 'Flora Mae', '2024-05-10', 'probationary', 'DOIS', NULL, 'Dining Staff'),
(1785, 'Gavileño', 'Rizaldy', '2024-01-18', 'regular', 'DOIS', NULL, 'Kitchen Staff'),
(1786, 'Lencioco', 'Lyka', '2018-12-15', 'probationary', 'DOIS', NULL, 'RND'),
(1787, 'Malijoc', 'Micheal Jan', '2023-05-02', 'regular', 'DOIS', NULL, 'Cook'),
(1788, 'Naparato', 'Shantily', '2019-02-18', 'regular', 'DOIS', NULL, 'Cashier'),
(1789, 'Palomo', 'Melboy', '2024-01-13', 'regular', 'DOIS', NULL, 'Waiter'),
(1790, 'Pragados', 'Mary Rose', '2019-03-17', 'regular', 'DOIS', NULL, 'Cook'),
(1791, 'Tinasas', 'Jerry', '2024-04-01', 'probationary', 'DOIS', NULL, 'Dishwasher'),
(1792, 'Traña', 'Micheal', '2023-02-14', 'probationary', 'DOIS', NULL, 'Head Cook'),
(1793, 'Valencia', 'Erwin Paul', '2023-02-28', 'regular', 'DOIS', NULL, 'Waiter'),
(1794, 'Hermogenes', 'Adrian', '2024-05-11', 'regular', 'DOIS', NULL, 'Fryer'),
(1795, 'Patriarca', 'Angie Lyn', '2024-08-16', 'probationary', 'DOIS', NULL, 'Cashier'),
(1796, 'Tajuela', 'John Peter', '2024-08-03', 'probationary', 'DOIS', NULL, 'Kitchen Staff'),
(1797, 'Vega', 'Vince  Chrisler', '2024-11-28', 'probationary', 'DOIS', NULL, 'Lechonan Staff'),
(1798, 'Abella', 'Randy', '2021-12-01', 'regular', 'PUB', 'Main Office', NULL),
(1799, 'Alacrito', 'Genard', '2024-06-10', 'probationary', 'PUB', 'Nina Food Products Trading', NULL),
(1800, 'Andrade', 'Remar', '2019-06-04', 'regular', 'PUB', 'Nina Food Products Trading', NULL),
(1801, 'Batara', 'Moriel Raquel', '2024-07-23', 'probationary', 'PUB', 'Main Office', NULL),
(1802, 'Billones', 'Ella', '2024-10-22', 'probationary', 'PUB', 'Main Office', NULL),
(1803, 'Berber', 'Reina', '2023-06-03', 'probationary', 'PUB', 'Main Office', NULL),
(1804, 'Calawigan', 'Jaime', '2017-05-05', 'regular', 'PUB', 'Nina Food Products Trading', NULL),
(1805, 'Damasco', 'Realyn', '2025-11-12', 'probationary', 'PUB', 'Main Office', NULL),
(1806, 'Emolaga', 'Genoveva', '2023-11-28', 'regular', 'PUB', 'Pub Express Resto-Employers', NULL),
(1807, 'Gonzales', 'Roben', '2023-10-16', 'probationary', 'PUB', 'Nina Food Products Trading', NULL),
(1808, 'Haudar', 'Jerlyn', '2018-07-05', 'regular', 'PUB', 'Shock Sisig', NULL),
(1809, 'Ituriaga', 'Mary Queen', '2024-04-17', 'regular', 'PUB', 'Main Office', NULL),
(1810, 'Mateo', 'Jun Mark', '2018-12-01', 'regular', 'PUB', 'Pub Express Resto-Employers', NULL),
(1811, 'Moquete', 'Claribel', '2021-09-30', 'regular', 'PUB', 'Main Office', NULL),
(1812, 'Palomo', 'Dean', '2021-06-24', 'regular', 'PUB', 'Nina Food Products Trading', NULL),
(1813, 'Pasol', 'Priselle Anne', '2024-10-30', 'probationary', 'PUB', 'Pub Express Resto-Employers', NULL),
(1814, 'Patino', 'Honey Vee', '2022-06-08', 'probationary', 'PUB', 'Pub Express Resto-Employers', NULL),
(1815, 'Posadas', 'Lynie', '2021-06-15', 'regular', 'PUB', 'Main Office', NULL),
(1816, 'Pusoc', 'Rhea Mae', '2022-09-26', 'probationary', 'PUB', 'Nina Food Products Trading', NULL),
(1817, 'Rubido', 'Cherry Joy', '2023-09-15', 'regular', 'PUB', 'Main Office', NULL),
(1818, 'Sarasola', 'Dungie', '2024-07-02', 'probationary', 'PUB', 'Main Office', NULL),
(1819, 'Silveo', 'Stephanie Grace', '2022-08-23', 'regular', 'PUB', 'Main Office', NULL),
(1820, 'Sunio', 'Hannah Grace', '2024-11-14', 'probationary', 'PUB', 'Main Office', NULL),
(1821, 'Velagio', 'Evangeline', '2024-05-15', 'probationary', 'PUB', 'Pub Express Resto-Employers', NULL),
(1822, 'Verdadero', 'Jose', '2022-06-06', 'probationary', 'PUB', 'Nina Food Products Trading', NULL);

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
(27, 1751, 'Update', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: probationary, position: Dishwasher', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: regular, position: Dishwasher', NULL, '2025-02-06 01:57:18'),
(28, 1751, 'Update', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: regular, position: Dishwasher', 'surname: Alarcon, first_name: Jhon Rey, date_hired: 2023-05-24, status: probationary, position: Dishwasher', NULL, '2025-02-06 02:02:30');

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
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1831;

--
-- AUTO_INCREMENT for table `employee_audit_log`
--
ALTER TABLE `employee_audit_log`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=33;

--
-- AUTO_INCREMENT for table `evaluations`
--
ALTER TABLE `evaluations`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=16;

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
