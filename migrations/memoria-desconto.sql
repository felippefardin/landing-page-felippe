-- Execute once in the website's existing MySQL database. No existing data is changed.
CREATE TABLE IF NOT EXISTS promo_memory_players (
 id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
 campaign VARCHAR(40) NOT NULL,
 email_key CHAR(64) NOT NULL,
 email VARCHAR(254) NOT NULL,
 otp_hash VARCHAR(255) DEFAULT NULL,
 otp_expires DATETIME DEFAULT NULL,
 otp_failures TINYINT UNSIGNED NOT NULL DEFAULT 0,
 otp_day DATE DEFAULT NULL,
 otp_sends TINYINT UNSIGNED NOT NULL DEFAULT 0,
 otp_last DATETIME DEFAULT NULL,
 verified_at DATETIME DEFAULT NULL,
 games_used TINYINT UNSIGNED NOT NULL DEFAULT 0,
 game_state MEDIUMTEXT DEFAULT NULL,
 coupon VARCHAR(40) DEFAULT NULL,
 coupon_status VARCHAR(12) NOT NULL DEFAULT 'none',
 won_at DATETIME DEFAULT NULL,
 expires_at DATETIME DEFAULT NULL,
 redeemed_at DATETIME DEFAULT NULL,
 redeemed_by VARCHAR(190) DEFAULT NULL,
 mail_sent TINYINT UNSIGNED NOT NULL DEFAULT 0,
 mail_attempts TINYINT UNSIGNED NOT NULL DEFAULT 0,
 mail_last DATETIME DEFAULT NULL,
 created_at DATETIME NOT NULL,
 UNIQUE KEY campaign_email (campaign,email_key),
 UNIQUE KEY unique_coupon (coupon),
 KEY issued (campaign,won_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
CREATE TABLE IF NOT EXISTS promo_memory_rates (
 bucket CHAR(64) NOT NULL,
 day DATE NOT NULL,
 hits INT UNSIGNED NOT NULL DEFAULT 0,
 PRIMARY KEY(bucket,day)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
