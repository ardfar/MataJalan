-- Database Spesifikasi Kendaraan OTR Indonesia
-- Harga estimasi OTR Jakarta (Dapat berubah sewaktu-waktu)
-- Dibuat untuk MySQL / MariaDB

CREATE DATABASE IF NOT EXISTS ivip;
USE ivip;

-- 1. Membuat Tabel Kendaraan
CREATE TABLE IF NOT EXISTS vehicle_specs (
    id INT AUTO_INCREMENT PRIMARY KEY,
    brand VARCHAR(50) NOT NULL,              -- Merk (Toyota, Honda, dll)
    model VARCHAR(100) NOT NULL,             -- Model (Avanza, Brio, dll)
    variant VARCHAR(100) NOT NULL,           -- Varian (1.5 G CVT, RS, dll)
    category ENUM('MPV', 'SUV', 'LCGC', 'Sedan', 'Hatchback', 'EV', 'Commercial') NOT NULL,
    -- Kolom price_otr dihapus
    engine_cc INT DEFAULT NULL,              -- Kapasitas Mesin (cc), NULL jika EV
    battery_kwh DECIMAL(5, 1) DEFAULT NULL,  -- Kapasitas Baterai (kWh) untuk EV
    horsepower INT NOT NULL,                 -- Tenaga (HP/PS)
    torque INT NOT NULL,                     -- Torsi (Nm)
    transmission VARCHAR(50) NOT NULL,       -- Manual, CVT, AT, e-CVT
    fuel_type VARCHAR(50) NOT NULL,          -- Bensin, Diesel, Hybrid, Listrik
    seat_capacity INT NOT NULL,              -- Jumlah Kursi
    -- Kolom image_url dihapus
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
);

-- 2. Memasukkan Data (Seeding)
-- Data disesuaikan dengan list model yang diminta (Toyota, Honda, Daihatsu, Mitsubishi, Suzuki, Hyundai, Wuling, Isuzu, Nissan, Mazda, Kia, BMW, Mercedes-Benz, Chery, DFSK)

INSERT INTO vehicle_specs (brand, model, variant, category, engine_cc, battery_kwh, horsepower, torque, transmission, fuel_type, seat_capacity) VALUES

-- TOYOTA
('Toyota', 'Avanza', '1.5 G CVT', 'MPV', 1496, NULL, 106, 137, 'CVT', 'Bensin', 7),
('Toyota', 'Veloz', '1.5 Q CVT TSS', 'MPV', 1496, NULL, 106, 137, 'CVT', 'Bensin', 7),
('Toyota', 'Innova Zenix', '2.0 Q Hybrid TSS', 'MPV', 1987, 1.3, 186, 206, 'e-CVT', 'Hybrid', 7),
('Toyota', 'Fortuner', '2.8 GR Sport 4x4', 'SUV', 2755, NULL, 201, 500, 'Automatic', 'Diesel', 7),
('Toyota', 'Rush', '1.5 S GR Sport AT', 'SUV', 1496, NULL, 104, 136, 'Automatic', 'Bensin', 7),
('Toyota', 'Calya', '1.2 G AT', 'LCGC', 1197, NULL, 88, 108, 'Automatic', 'Bensin', 7),
('Toyota', 'Agya', '1.2 GR Sport CVT', 'LCGC', 1198, NULL, 88, 113, 'CVT', 'Bensin', 5),
('Toyota', 'Raize', '1.0 Turbo GR Sport CVT', 'SUV', 998, NULL, 98, 140, 'CVT', 'Bensin', 5),
('Toyota', 'Yaris Cross', '1.5 S HV CVT TSS', 'SUV', 1496, 0.7, 110, 141, 'CVT', 'Hybrid', 5),
('Toyota', 'Alphard', '2.5 HEV', 'MPV', 2487, NULL, 190, 239, 'e-CVT', 'Hybrid', 7),
('Toyota', 'Voxy', '2.0 CVT', 'MPV', 1986, NULL, 170, 202, 'CVT', 'Bensin', 7),
('Toyota', 'Camry', '2.5 V', 'Sedan', 2487, NULL, 204, 243, 'Automatic', 'Bensin', 5),
('Toyota', 'Corolla Altis', '1.8 Hybrid', 'Sedan', 1798, NULL, 140, 171, 'CVT', 'Hybrid', 5),
('Toyota', 'Hilux', '2.4 V 4x4 AT', 'Commercial', 2393, NULL, 147, 400, 'Automatic', 'Diesel', 5),
('Toyota', 'Hiace', 'Premio', 'Commercial', 2755, NULL, 176, 420, 'Manual', 'Diesel', 12),

-- HONDA
('Honda', 'Brio', 'RS CVT', 'Hatchback', 1199, NULL, 90, 110, 'CVT', 'Bensin', 5),
('Honda', 'HR-V', '1.5 Turbo RS', 'SUV', 1498, NULL, 177, 240, 'CVT', 'Bensin', 5),
('Honda', 'BR-V', 'Prestige Honda Sensing', 'SUV', 1498, NULL, 121, 145, 'CVT', 'Bensin', 7),
('Honda', 'WR-V', 'RS Honda Sensing', 'SUV', 1498, NULL, 121, 145, 'CVT', 'Bensin', 5),
('Honda', 'CR-V', '2.0 RS e:HEV', 'SUV', 1993, 1.0, 207, 335, 'e-CVT', 'Hybrid', 5),
('Honda', 'City Hatchback', 'RS CVT Honda Sensing', 'Hatchback', 1498, NULL, 121, 145, 'CVT', 'Bensin', 5),
('Honda', 'Civic', 'RS Turbo', 'Sedan', 1498, NULL, 178, 240, 'CVT', 'Bensin', 5),
('Honda', 'Accord', '2.0 RS e:HEV', 'Sedan', 1993, 1.3, 207, 335, 'e-CVT', 'Hybrid', 5),
('Honda', 'Mobilio', 'S MT', 'MPV', 1496, NULL, 118, 145, 'Manual', 'Bensin', 7),
('Honda', 'Odyssey', '2.4 L Prestige', 'MPV', 2356, NULL, 173, 225, 'CVT', 'Bensin', 7),

-- DAIHATSU
('Daihatsu', 'Sigra', '1.2 R AT', 'LCGC', 1197, NULL, 88, 108, 'Automatic', 'Bensin', 7),
('Daihatsu', 'Xenia', '1.5 R CVT ASA', 'MPV', 1496, NULL, 106, 138, 'CVT', 'Bensin', 7),
('Daihatsu', 'Terios', 'R Custom AT', 'SUV', 1496, NULL, 104, 136, 'Automatic', 'Bensin', 7),
('Daihatsu', 'Ayla', '1.2 R CVT', 'LCGC', 1198, NULL, 88, 113, 'CVT', 'Bensin', 5),
('Daihatsu', 'Rocky', '1.0 R TC ASA', 'SUV', 998, NULL, 98, 140, 'CVT', 'Bensin', 5),
('Daihatsu', 'Gran Max', 'Minibus 1.5 D PS', 'Commercial', 1495, NULL, 97, 134, 'Manual', 'Bensin', 9),
('Daihatsu', 'Luxio', '1.5 X AT', 'MPV', 1495, NULL, 97, 134, 'Automatic', 'Bensin', 8),
('Daihatsu', 'Sirion', 'R CVT', 'Hatchback', 1329, NULL, 95, 120, 'CVT', 'Bensin', 5),

-- MITSUBISHI
('Mitsubishi', 'Xpander', 'Ultimate CVT', 'MPV', 1499, NULL, 105, 141, 'CVT', 'Bensin', 7),
('Mitsubishi', 'Xpander Cross', 'Premium CVT', 'SUV', 1499, NULL, 105, 141, 'CVT', 'Bensin', 7),
('Mitsubishi', 'Pajero Sport', 'Dakar Ultimate 4x4', 'SUV', 2442, NULL, 181, 430, 'Automatic', 'Diesel', 7),
('Mitsubishi', 'Xforce', 'Ultimate CVT', 'SUV', 1499, NULL, 105, 141, 'CVT', 'Bensin', 5),
('Mitsubishi', 'Triton', 'Ultimate AT Double Cab', 'Commercial', 2442, NULL, 181, 430, 'Automatic', 'Diesel', 5),
('Mitsubishi', 'L300', 'Pickup Flat Deck', 'Commercial', 2268, NULL, 99, 200, 'Manual', 'Diesel', 3),

-- SUZUKI
('Suzuki', 'Ertiga', 'Cruise Hybrid AT', 'MPV', 1462, NULL, 103, 138, 'Automatic', 'Mild Hybrid', 7),
('Suzuki', 'XL7', 'Alpha Hybrid AT', 'SUV', 1462, NULL, 103, 138, 'Automatic', 'Mild Hybrid', 7),
('Suzuki', 'Jimny', '5-Door AT Two Tone', 'SUV', 1462, NULL, 102, 130, 'Automatic', 'Bensin', 4),
('Suzuki', 'Baleno', 'AT', 'Hatchback', 1462, NULL, 104, 138, 'Automatic', 'Bensin', 5),
('Suzuki', 'S-Presso', 'AGS', 'LCGC', 998, NULL, 66, 89, 'AGS', 'Bensin', 4),
('Suzuki', 'Ignis', 'GX AGS', 'Hatchback', 1197, NULL, 83, 113, 'AGS', 'Bensin', 5),
('Suzuki', 'Grand Vitara', 'GX', 'SUV', 1462, NULL, 102, 137, 'Automatic', 'Mild Hybrid', 5),
('Suzuki', 'Carry', 'Pickup Wide Deck', 'Commercial', 1462, NULL, 97, 135, 'Manual', 'Bensin', 3),

-- HYUNDAI
('Hyundai', 'Stargazer', 'Prime IVT', 'MPV', 1497, NULL, 115, 144, 'IVT', 'Bensin', 6),
('Hyundai', 'Creta', 'Prime IVT', 'SUV', 1497, NULL, 115, 144, 'IVT', 'Bensin', 5),
('Hyundai', 'Ioniq 5', 'Signature Long Range', 'EV', NULL, 72.6, 217, 350, 'Single Speed', 'Listrik', 5),
('Hyundai', 'Palisade', 'Signature AWD', 'SUV', 2199, NULL, 200, 440, 'Automatic', 'Diesel', 7),
('Hyundai', 'Santa Fe', '2.2 Diesel Signature', 'SUV', 2151, NULL, 202, 440, 'DCT', 'Diesel', 7),
('Hyundai', 'Staria', 'Signature 9', 'MPV', 2199, NULL, 177, 430, 'Automatic', 'Diesel', 9),
('Hyundai', 'Ioniq 6', 'Signature AWD', 'EV', NULL, 77.4, 320, 605, 'Single Speed', 'Listrik', 5),

-- WULING
('Wuling', 'Confero', 'S 1.5L AC Lux+', 'MPV', 1485, NULL, 98, 135, 'Manual', 'Bensin', 8),
('Wuling', 'Almaz', 'RS Pro Hybrid', 'SUV', 1999, 1.8, 174, 320, 'DHT', 'Hybrid', 7),
('Wuling', 'Air EV', 'Long Range', 'EV', NULL, 26.7, 40, 110, 'Single Speed', 'Listrik', 4),
('Wuling', 'Binguo', '410km Premium Range', 'EV', NULL, 37.9, 68, 150, 'Single Speed', 'Listrik', 5),
('Wuling', 'Cortez', 'EX 1.5T CVT', 'MPV', 1451, NULL, 140, 250, 'CVT', 'Bensin', 7),
('Wuling', 'Formo', 'S 8 Seater', 'Commercial', 1206, NULL, 77, 110, 'Manual', 'Bensin', 8),

-- ISUZU
('Isuzu', 'Panther', 'Grand Touring', 'MPV', 2499, NULL, 80, 191, 'Manual', 'Diesel', 7),
('Isuzu', 'Mu-X', '4x4 AT', 'SUV', 1898, NULL, 150, 350, 'Automatic', 'Diesel', 7),
('Isuzu', 'D-Max', 'Rodeo 4x4 MT', 'Commercial', 1898, NULL, 150, 350, 'Manual', 'Diesel', 5),
('Isuzu', 'Traga', 'Pickup Flat Deck', 'Commercial', 2499, NULL, 80, 191, 'Manual', 'Diesel', 3),

-- NISSAN
('Nissan', 'Livina', 'VL AT', 'MPV', 1499, NULL, 104, 141, 'Automatic', 'Bensin', 7),
('Nissan', 'Magnite', 'Premium CVT', 'SUV', 999, NULL, 100, 152, 'CVT', 'Bensin', 5),
('Nissan', 'Kicks', 'e-Power VL', 'SUV', 1198, 2.1, 134, 280, 'e-Power', 'Hybrid', 5),
('Nissan', 'Serena', 'e-Power Highway Star', 'MPV', 1433, 1.8, 161, 315, 'e-Power', 'Hybrid', 7),
('Nissan', 'Terra', 'VL 4x4 AT', 'SUV', 2488, NULL, 190, 450, 'Automatic', 'Diesel', 7),
('Nissan', 'X-Trail', 'VE', 'SUV', 2488, NULL, 171, 233, 'CVT', 'Bensin', 5),

-- MAZDA
('Mazda', 'CX-5', 'Elite', 'SUV', 2488, NULL, 190, 252, 'Automatic', 'Bensin', 5),
('Mazda', 'CX-3', '2.0 Pro', 'SUV', 1998, NULL, 149, 195, 'Automatic', 'Bensin', 5),
('Mazda', 'Mazda2', 'GT', 'Hatchback', 1496, NULL, 111, 144, 'Automatic', 'Bensin', 5),
('Mazda', 'Mazda3', 'Hatchback', 'Hatchback', 1998, NULL, 155, 200, 'Automatic', 'Bensin', 5),
('Mazda', 'CX-30', 'GT', 'SUV', 1998, NULL, 155, 200, 'Automatic', 'Bensin', 5),
('Mazda', 'CX-60', 'Kuro Edition', 'SUV', 3283, 17.8, 284, 450, 'Automatic', 'Hybrid', 5),

-- KIA
('Kia', 'Sonet', 'Premiere IVT', 'SUV', 1497, NULL, 115, 144, 'IVT', 'Bensin', 5),
('Kia', 'Seltos', 'EXP 1.5 Turbo', 'SUV', 1482, NULL, 140, 242, 'DCT', 'Bensin', 5),
('Kia', 'Carens', '1.5 Captain Seat', 'MPV', 1497, NULL, 115, 144, 'IVT', 'Bensin', 6),
('Kia', 'Carnival', 'Premiere 11-Seater', 'MPV', 2151, NULL, 202, 441, 'Automatic', 'Diesel', 11),
('Kia', 'EV6', 'GT-Line', 'EV', NULL, 77.4, 320, 605, 'Single Speed', 'Listrik', 5),
('Kia', 'EV9', 'GT-Line AWD', 'EV', NULL, 99.8, 380, 700, 'Single Speed', 'Listrik', 7),

-- BMW
('BMW', '3 Series', '330i M Sport', 'Sedan', 1998, NULL, 258, 400, 'Automatic', 'Bensin', 5),
('BMW', '5 Series', '520i M Sport', 'Sedan', 1998, NULL, 184, 290, 'Automatic', 'Bensin', 5),
('BMW', 'X1', 'sDrive18i xLine', 'SUV', 1499, NULL, 156, 230, 'Automatic', 'Bensin', 5),
('BMW', 'X3', 'sDrive20i xLine', 'SUV', 1998, NULL, 184, 300, 'Automatic', 'Bensin', 5),
('BMW', 'X5', 'xDrive40i M Sport', 'SUV', 2998, 25.7, 340, 450, 'Automatic', 'Hybrid', 7),
('BMW', 'iX', 'xDrive40 Sport', 'EV', NULL, 76.6, 326, 630, 'Single Speed', 'Listrik', 5),

-- MERCEDES-BENZ
('Mercedes-Benz', 'C-Class', 'C 300 AMG Line', 'Sedan', 1999, NULL, 258, 400, 'Automatic', 'Bensin', 5),
('Mercedes-Benz', 'E-Class', 'E 300 AMG Line', 'Sedan', 1991, NULL, 258, 370, 'Automatic', 'Bensin', 5),
('Mercedes-Benz', 'GLC', '300 4MATIC AMG Line', 'SUV', 1999, NULL, 258, 400, 'Automatic', 'Bensin', 5),
('Mercedes-Benz', 'GLE', '450 4MATIC AMG Line', 'SUV', 2999, NULL, 367, 500, 'Automatic', 'Hybrid', 7),
('Mercedes-Benz', 'A-Class', 'A 200 Progressive Line', 'Sedan', 1332, NULL, 163, 250, 'Automatic', 'Bensin', 5),
('Mercedes-Benz', 'S-Class', 'S 450 4MATIC Luxury', 'Sedan', 2999, NULL, 367, 500, 'Automatic', 'Hybrid', 5),

-- CHERY
('Chery', 'Tiggo 7 Pro', 'Premium', 'SUV', 1500, NULL, 155, 230, 'CVT', 'Bensin', 5),
('Chery', 'Tiggo 8 Pro', 'Premium', 'SUV', 2000, NULL, 250, 390, 'DCT', 'Bensin', 7),
('Chery', 'Omoda 5', 'GT AWD', 'SUV', 1600, NULL, 197, 290, 'DCT', 'Bensin', 5),

-- DFSK
('DFSK', 'Glory 560', '1.5T L-Type', 'SUV', 1498, NULL, 150, 230, 'CVT', 'Bensin', 7),
('DFSK', 'Glory i-Auto', '1.5T', 'SUV', 1498, NULL, 150, 220, 'CVT', 'Bensin', 7),
('DFSK', 'Gelora', 'E Minibus', 'Commercial', NULL, 42.0, 80, 200, 'Single Speed', 'Listrik', 7);