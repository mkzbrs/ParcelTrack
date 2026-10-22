-- ParcelTrack Lite database (MySQL / MariaDB)
-- Import this first, then seed.sql (fake data only).
-- This file does NOT create a database password. Create the app's own
-- database user yourself (see README) and keep its password in .env (policy P4).

CREATE DATABASE IF NOT EXISTS parceltrack CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE parceltrack;

CREATE TABLE users (
  id              INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  full_name       VARCHAR(100) NOT NULL,
  email           VARCHAR(150) NOT NULL UNIQUE,
  password_hash   VARCHAR(255) NOT NULL,                -- hashed, never plain text
  role            ENUM('admin','seller','rider') NOT NULL,
  phone           VARCHAR(20)  NULL,
  is_active       TINYINT(1)   NOT NULL DEFAULT 1,      -- 0 = blocked (P1: rider leaves)
  failed_attempts INT          NOT NULL DEFAULT 0,      -- for login lockout (M1)
  locked_until    DATETIME     NULL,                    -- for login lockout (M1)
  created_at      TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;

CREATE TABLE parcels (
  id                INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  tracking_no       CHAR(18)     NOT NULL UNIQUE,       -- PT + 16 random hex characters
  seller_id         INT UNSIGNED NOT NULL,
  rider_id          INT UNSIGNED NULL,                  -- set when admin assigns (M3)
  recipient_name    VARCHAR(100) NOT NULL,
  recipient_phone   VARCHAR(20)  NOT NULL,
  recipient_address VARCHAR(255) NOT NULL,
  weight_kg         DECIMAL(6,2) NOT NULL,
  current_status    ENUM('Registered','Assigned','Out for Delivery','Delivered','Failed')
                    NOT NULL DEFAULT 'Registered',
  created_at        TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  delivered_at      DATETIME     NULL,                  -- used by the 12-month cleanup (P3)
  FOREIGN KEY (seller_id) REFERENCES users(id),
  FOREIGN KEY (rider_id)  REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE status_history (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  parcel_id  INT UNSIGNED NOT NULL,
  status     ENUM('Registered','Assigned','Out for Delivery','Delivered','Failed') NOT NULL,
  changed_by INT UNSIGNED NOT NULL,
  changed_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP,
  photo_file VARCHAR(100) NULL,                         -- random file name, file kept outside public/
  FOREIGN KEY (parcel_id)  REFERENCES parcels(id),
  FOREIGN KEY (changed_by) REFERENCES users(id)
) ENGINE=InnoDB;

CREATE TABLE audit_log (
  id         INT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY,
  user_id    INT UNSIGNED NULL,
  event_type VARCHAR(50)  NOT NULL,
  detail     VARCHAR(255) NULL,                         -- never passwords or full addresses (P5)
  ip         VARCHAR(45)  NULL,
  created_at TIMESTAMP    NOT NULL DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB;
