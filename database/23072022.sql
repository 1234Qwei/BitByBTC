CREATE TABLE `master_bank_types`(
    `id` INT NOT NULL,
    `label` VARCHAR(200) NOT NULL,
    `value` TINYINT NOT NULL,
    `sort_order` VARCHAR(200) NOT NULL DEFAULT '0',
    `status` TINYINT NOT NULL DEFAULT '1' COMMENT '1-active,0- inactive',
    `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
    `updated_at` timestamp NOT NULL DEFAULT current_timestamp() ON UPDATE current_timestamp(),
    `deleted_at` timestamp NULL DEFAULT NULL
);
ALTER TABLE `master_bank_types`
ADD PRIMARY KEY (`id`);
ALTER TABLE `master_bank_types`
MODIFY `id` int(11) NOT NULL AUTO_INCREMENT;
INSERT INTO `master_bank_types`(
        `id`,
        `label`,
        `value`,
        `sort_order`,
        `status`
    )
VALUES(1, 'Bank', 1, '0', 1),
(2, 'PhonePe', 2, '0', 1),
(3, 'Google Pay', 3, '0', 1);