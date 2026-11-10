CREATE TABLE IF NOT EXISTS builder_refunds (
 order_id CHAR(32) PRIMARY KEY,
 status VARCHAR(24) NOT NULL DEFAULT 'requested',
 reason TEXT NOT NULL,
 requested_at DATETIME NOT NULL,
 deadline_at DATETIME NOT NULL,
 amount_cents INT UNSIGNED NOT NULL,
 provider_refund_id VARCHAR(100) DEFAULT NULL,
 processed_at DATETIME DEFAULT NULL,
 customer_notified_at DATETIME DEFAULT NULL,
 admin_notified_at DATETIME DEFAULT NULL,
 completed_notified_at DATETIME DEFAULT NULL,
 last_error VARCHAR(255) DEFAULT NULL,
 KEY queue(status,requested_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
