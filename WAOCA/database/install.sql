-- WAOCA DATABASE INSTALLER
-- Jalankan pada database aplikasi WAOCA/SIMIFM.
-- Backup database terlebih dahulu.
-- Sesuaikan kolom dengan source WAOCA yang digunakan.

CREATE DATABASE IF NOT EXISTS simifm
  CHARACTER SET utf8mb4
  COLLATE utf8mb4_unicode_ci;

USE simifm;

-- Template WhatsApp
CREATE TABLE IF NOT EXISTS wa_template (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    template_code VARCHAR(150) NOT NULL,
    template_name VARCHAR(150) NOT NULL,
    category VARCHAR(50) DEFAULT NULL,
    language VARCHAR(20) DEFAULT 'id',
    body TEXT NOT NULL,
    variables TEXT DEFAULT NULL,
    status ENUM('ACTIVE','INACTIVE') NOT NULL DEFAULT 'ACTIVE',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_wa_template_code (template_code)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Outbox / antrean pengiriman WhatsApp
CREATE TABLE IF NOT EXISTS wa_outbox (
    id BIGINT UNSIGNED NOT NULL AUTO_INCREMENT,
    destination VARCHAR(30) NOT NULL,
    template_code VARCHAR(150) NOT NULL,
    payload LONGTEXT DEFAULT NULL,
    status VARCHAR(30) NOT NULL DEFAULT 'PENDING',
    attempts INT UNSIGNED NOT NULL DEFAULT 0,
    msgid VARCHAR(150) DEFAULT NULL,
    response LONGTEXT DEFAULT NULL,
    error_message TEXT DEFAULT NULL,
    scheduled_at DATETIME DEFAULT NULL,
    sent_at DATETIME DEFAULT NULL,
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    KEY idx_wa_outbox_status (status),
    KEY idx_wa_outbox_schedule (scheduled_at),
    KEY idx_wa_outbox_destination (destination),
    KEY idx_wa_outbox_msgid (msgid)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- User aplikasi WAOCA.
-- Password harus disimpan sebagai password_hash(), bukan plaintext.
CREATE TABLE IF NOT EXISTS simifm_user (
    id INT UNSIGNED NOT NULL AUTO_INCREMENT,
    username VARCHAR(100) NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama VARCHAR(150) DEFAULT NULL,
    status ENUM('aktif','nonaktif') NOT NULL DEFAULT 'aktif',
    created_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    PRIMARY KEY (id),
    UNIQUE KEY uk_simifm_user_username (username)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- Contoh template.
-- Ganti sesuai template yang SUDAH DI-APPROVE OCA.
INSERT INTO wa_template
(template_code, template_name, category, language, body, variables, status)
VALUES
('contoh_template', 'Contoh Template', 'MARKETING', 'id',
 'Template contoh. Jangan digunakan sebelum diganti dengan template OCA yang approved.',
 '[]', 'INACTIVE')
ON DUPLICATE KEY UPDATE template_name=VALUES(template_name);
