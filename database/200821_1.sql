CREATE TABLE `banks` (
  `id` int(11) NOT NULL,
  `user_id` int(11) NOT NULL,
  `selected_account` int(11) NOT NULL,
  `name` varchar(255) NOT NULL,
  `bank_name` varchar(200) DEFAULT NULL,
  `ifsc_code` varchar(30) DEFAULT NULL,
  `account_number` varchar(30) DEFAULT NULL,
  `upi` varchar(150) DEFAULT NULL,
  `account_type` enum('1','2') DEFAULT NULL,
  `status` tinyint(4) NOT NULL DEFAULT 0,
  `approved_by` int(11) DEFAULT NULL,
  `is_primary` int(11) NOT NULL DEFAULT 0,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
  `deleted_at` timestamp NULL DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;
ALTER TABLE `banks` ADD PRIMARY KEY (`id`);
ALTER TABLE `banks` MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;


