-- ParcelTrack Lite: FAKE sample data only (policy P4). Never import real recipient data.
-- Demo passwords are listed in README.md. Delete or change these accounts before going online.
USE parceltrack;

INSERT INTO users (id, full_name, email, password_hash, role, phone) VALUES
(1, 'Demo Admin', 'admin@example.com', '$2y$10$4YkltvoG2pVMD6rkCJ4s2uIVvmMTWpqWPUQEpsFB5bPmLwO0OAZPy', 'admin', '0100000001'),
(2, 'Demo Seller One', 'seller1@example.com', '$2y$10$.1xAn8X9Bj7ScWuUr9CLVu4KMl7Xtnb06M.NcPG5VH8X6S5mAS.5.', 'seller', '0100000002'),
(3, 'Demo Seller Two', 'seller2@example.com', '$2y$10$bqoMi5/tv41cP93J7Nu09eSv2Y0AbYC8.YKogZ7KQCXaI0MjWas7K', 'seller', '0100000003'),
(4, 'Demo Rider One', 'rider1@example.com', '$2y$10$IC13fzPLMTF.zx7B/bhxBO7aAnRKRSLs87ybRBU7wTOhjGVRLVVj2', 'rider', '0100000004'),
(5, 'Demo Rider Two', 'rider2@example.com', '$2y$10$TdEDkEdHLzZG57ElDcYbQugt9fGB17e2tooOWTOkTGSZpNGH5AA0W', 'rider', '0100000005');

INSERT INTO parcels (id, tracking_no, seller_id, rider_id, recipient_name, recipient_phone, recipient_address, weight_kg, current_status) VALUES
(1, 'PTEDBFB1C7B9F84A93', 2, NULL, 'Test Recipient 01', '0120000001', '01 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 1, 'Registered'),
(2, 'PT20E63E3AE46BA987', 2, NULL, 'Test Recipient 02', '0120000002', '02 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 1.5, 'Registered'),
(3, 'PT46B8E61CBEEC74FC', 3, NULL, 'Test Recipient 03', '0120000003', '03 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 2, 'Registered'),
(4, 'PTBC54266B041FFC6B', 3, NULL, 'Test Recipient 04', '0120000004', '04 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 2.5, 'Registered'),
(5, 'PT42FA9DD603D99AA9', 2, 4, 'Test Recipient 05', '0120000005', '05 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 3, 'Assigned'),
(6, 'PT38DD9159C58821C5', 3, 5, 'Test Recipient 06', '0120000006', '06 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 3.5, 'Assigned'),
(7, 'PT9AC9146E8230D386', 2, 4, 'Test Recipient 07', '0120000007', '07 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 4, 'Out for Delivery'),
(8, 'PT38760825459FFA89', 3, 5, 'Test Recipient 08', '0120000008', '08 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 4.5, 'Delivered'),
(9, 'PT495F0D27A9D1A361', 2, 4, 'Test Recipient 09', '0120000009', '09 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 5, 'Delivered'),
(10, 'PTBE5AA085041F8877', 3, 5, 'Test Recipient 10', '0120000010', '10 Jalan Contoh, Taman Fake, 00000 Bandar Contoh', 5.5, 'Failed');

INSERT INTO status_history (parcel_id, status, changed_by) VALUES
(1, 'Registered', 2),
(2, 'Registered', 2),
(3, 'Registered', 3),
(4, 'Registered', 3),
(5, 'Registered', 2),
(5, 'Assigned', 1),
(6, 'Registered', 3),
(6, 'Assigned', 1),
(7, 'Registered', 2),
(7, 'Assigned', 1),
(7, 'Out for Delivery', 4),
(8, 'Registered', 3),
(8, 'Assigned', 1),
(8, 'Out for Delivery', 5),
(8, 'Delivered', 5),
(9, 'Registered', 2),
(9, 'Assigned', 1),
(9, 'Out for Delivery', 4),
(9, 'Delivered', 4),
(10, 'Registered', 3),
(10, 'Assigned', 1),
(10, 'Out for Delivery', 5),
(10, 'Failed', 5);
