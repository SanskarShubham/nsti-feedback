-- 1-6-25
ALTER TABLE `admin` ADD `dp_file_path` TEXT NULL AFTER `status`;


-- 10-06-2025
ALTER TABLE `subject` ADD `common_for_cts` TINYINT NOT NULL DEFAULT '0' AFTER `name`, ADD `common_for_cits` TINYINT NOT NULL DEFAULT '0' AFTER `common_for_cts`;

ALTER TABLE `trade` ADD `program` ENUM('CTS','CITS') NOT NULL DEFAULT 'CITS' AFTER `trade_name`;




-- 10-06-2025 ADDING SUBJECTS
INSERT INTO `subject` (`id`, `name`, `common_for_cts`, `common_for_cits`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES (NULL, 'TRADE THEORY', '1', '1', current_timestamp(), NULL, current_timestamp(), NULL, '1');


INSERT INTO `subject` (`id`, `name`, `common_for_cts`, `common_for_cits`, `created_at`, `created_by`, `updated_at`, `updated_by`, `status`) VALUES (NULL, 'TRADE PRACTICAL', '1', '1', current_timestamp(), NULL, current_timestamp(), NULL, '1');


-- 16/-06-2025
ALTER TABLE `admin` ADD `role` ENUM('teacher','admin') NOT NULL DEFAULT 'teacher' AFTER `dp_file_path`;