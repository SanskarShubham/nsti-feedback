-- 1-6-25
ALTER TABLE `admin` ADD `dp_file_path` TEXT NULL AFTER `status`;


-- 10-06-2025
ALTER TABLE `subject` ADD `common_for_cts` TINYINT NOT NULL DEFAULT '0' AFTER `name`, ADD `common_for_cits` TINYINT NOT NULL DEFAULT '0' AFTER `common_for_cts`;

ALTER TABLE `trade` ADD `program` ENUM('CTS','CITS') NOT NULL DEFAULT 'CITS' AFTER `trade_name`;