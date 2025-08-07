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

-- 17-06-2025
CREATE TABLE `feedback` (
    `id` INT(11) NOT NULL AUTO_INCREMENT PRIMARY KEY,
    `teacher_id` INT(11) NOT NULL,
    `trade_id` INT(11) NOT NULL,
    `subject_id` INT(11) NOT NULL,
    `program` ENUM('CTS', 'CITS') NOT NULL,
    `attendance_id` VARCHAR(50) NOT NULL,
    `rating` INT(1) NOT NULL CHECK (rating BETWEEN 1 AND 5),
    `remarks` TEXT NOT NULL,
    `created_at` DATETIME DEFAULT CURRENT_TIMESTAMP,
    `created_by` INT(11) DEFAULT NULL,
    `updated_at` DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    `updated_by` INT(11) DEFAULT NULL,
    `status` TINYINT(1) DEFAULT 1
);






CREATE TABLE `student_activity` (
  `id` INT(11) NOT NULL AUTO_INCREMENT,
  `student_id` INT(11) NOT NULL,
  `total_lesson` INT(11) DEFAULT 0,
  `total_demo` INT(11) DEFAULT 0,
  `total_practical` INT(11) DEFAULT 0,
  `total_test` INT(11) DEFAULT 0,
  `total_tmp` INT(11) DEFAULT 0,
  `status` VARCHAR(50) NOT NULL DEFAULT 'active',
  `created_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
  `updated_at` TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
  `created_by` INT(11) DEFAULT NULL,
  `updated_by` INT(11) DEFAULT NULL,
  PRIMARY KEY (`id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;




UPDATE `students` SET `trade` = 'Machinist CTS' WHERE `students`.`trade`='machinist' and `students`.`program` = 'CTS' ;

UPDATE `students` SET `trade` = 'Machinist Grinder CTS' WHERE `students`.`trade`='machinist grinder' and `students`.`program` = 'CTS';








-- 19-06-2025 for adding student activity cycle

CREATE TABLE `student_activity_cycle` (
  `cycle_id` int(11) NOT NULL AUTO_INCREMENT,
  `cycle_name` varchar(100) NOT NULL,
  `start_date` datetime NOT NULL,
  `end_date` datetime NOT NULL,
  `is_active` tinyint(1) DEFAULT 1,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`cycle_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Also, add the new column to your student_activity table
ALTER TABLE `student_activity` 
ADD `student_activity_cycle_id` INT(11) NULL DEFAULT NULL AFTER `remarks`,
ADD INDEX `idx_cycle_id` (`student_activity_cycle_id`);

-- IMPORTANT: Add a unique key to prevent duplicate entries for the same student in the same cycle
ALTER TABLE `student_activity`
ADD UNIQUE `unique_student_cycle`(`student_id`, `student_activity_cycle_id`);