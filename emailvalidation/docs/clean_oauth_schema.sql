
SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";

-- DB Name CRM_API

CREATE TABLE IF NOT EXISTS `users` (
  `id` int(10) UNSIGNED NOT NULL AUTO_INCREMENT,
  `name` varchar(191) NOT NULL,
  `email` varchar(191) NOT NULL,
  `password` varchar(255) NOT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `uniq_email` (`email`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- Create emails table (stores individual email verification entries)
CREATE TABLE IF NOT EXISTS `emails` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `user_id` int(10) UNSIGNED DEFAULT NULL,
  `raw_emailid` varchar(150) DEFAULT NULL,
  `sp_account` varchar(100) NOT NULL,
  `sp_domain` varchar(100) NOT NULL,
  `domain_verified` tinyint(1) DEFAULT 0,
  `domain_status` tinyint(1) DEFAULT 0,
  `validation_response` text DEFAULT NULL,
  `domain_processed` tinyint(1) DEFAULT 0,
  `client_ip` varchar(45) DEFAULT NULL,
  `csv_list_id` int(10) UNSIGNED DEFAULT NULL,
  `validation_status` varchar(20) DEFAULT NULL,
  `worker_id` tinyint(3) UNSIGNED DEFAULT NULL,
  PRIMARY KEY (`id`),
  KEY `fk_emails_csvlist` (`csv_list_id`),
  KEY `idx_user_id` (`user_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- OAuth tables
CREATE TABLE IF NOT EXISTS `oauth_clients` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `client_id` varchar(128) NOT NULL,
  `client_secret_hash` varchar(255) NOT NULL,
  `name` varchar(191) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `client_id` (`client_id`)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS `oauth_access_tokens` (
  `id` int(11) NOT NULL AUTO_INCREMENT,
  `access_token_hash` char(64) NOT NULL,
  `client_id` varchar(128) NOT NULL,
  `expires_at` int(11) NOT NULL,
  `client_ip` varchar(45) DEFAULT NULL,
  `client_email` varchar(255) DEFAULT NULL,
  `created_at` timestamp NOT NULL DEFAULT current_timestamp(),
  PRIMARY KEY (`id`),
  UNIQUE KEY `access_token_hash` (`access_token_hash`),
  KEY `client_id_idx` (`client_id`),
  CONSTRAINT `oauth_access_tokens_ibfk_client` FOREIGN KEY (`client_id`) REFERENCES `oauth_clients` (`client_id`) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

COMMIT;
