<?php

define('DB_HOST', 'localhost');
define('DB_NAME', 'logistic_corner');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

date_default_timezone_set('Asia/Jakarta');

error_reporting(E_ALL);
ini_set('display_errors', 1);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $options = [
        PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES   => false,
    ];

    $pdo = new PDO($dsn, DB_USER, DB_PASS, $options);
} catch (PDOException $e) {
    error_log("DB Error: " . $e->getMessage());
    die(json_encode([
        'success' => false,
        'message' => 'Koneksi database gagal.'
    ]));
}

function sanitize($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

function isLoggedIn() {
    return isset($_SESSION['user_id']);
}

function hasRole($role) {
    return isset($_SESSION['role']) && $_SESSION['role'] === $role;
}

function redirect($url) {
    header("Location: $url");
    exit();
}

function sendJSON($data, $statusCode = 200) {
    http_response_code($statusCode);
    header('Content-Type: application/json');
    echo json_encode($data);
    exit();
}

function hashPassword($password) {
    return password_hash($password, PASSWORD_DEFAULT);
}

function verifyPassword($password, $hash) {
    return password_verify($password, $hash);
}

function calculateFine($tanggalMasuk, $pdo) {
    $stmt = $pdo->prepare("SELECT setting_value FROM settings WHERE setting_key IN ('denda_per_hari', 'hari_gratis')");
    $stmt->execute();
    $settings = $stmt->fetchAll(PDO::FETCH_KEY_PAIR);

    $dendaPerHari = isset($settings['denda_per_hari']) ? (int)$settings['denda_per_hari'] : 1000;
    $hariGratis = isset($settings['hari_gratis']) ? (int)$settings['hari_gratis'] : 1;

    $now = new DateTime();
    $masuk = new DateTime($tanggalMasuk);

    $workingDays = 0;
    $current = clone $masuk;

    while ($current <= $now) {
        $weekday = $current->format('w');
        $dateStr = $current->format('Y-m-d');

        $stmt = $pdo->prepare("SELECT COUNT(*) FROM holidays WHERE tanggal = ?");
        $stmt->execute([$dateStr]);
        $isHoliday = $stmt->fetchColumn() > 0;

        if ($weekday != 0 && !$isHoliday) {
            $workingDays++;
        }

        $current->modify('+1 day');
    }

    $hariDenda = max(0, $workingDays - $hariGratis);
    return $hariDenda * $dendaPerHari;
}

function formatRupiah($amount) {
    return 'Rp ' . number_format($amount, 0, ',', '.');
}

function logActivity($pdo, $userId, $action, $description, $ipAddress = null) {
    if ($ipAddress === null) {
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? 'unknown';
    }

    try {
        $stmt = $pdo->prepare("
            INSERT INTO activity_logs (user_id, action, description, ip_address)
            VALUES (?, ?, ?, ?)
        ");
        $stmt->execute([$userId, $action, $description, $ipAddress]);
    } catch (PDOException $e) {
        error_log("Log Error: " . $e->getMessage());
    }
}

function sendWhatsAppNotification($phone, $message) {
    error_log("WhatsApp to $phone: $message");
    return true;
}

function sendEmailNotification($email, $subject, $message) {
    error_log("Email to $email: $subject - $message");
    return true;
}

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
    header('Access-Control-Allow-Headers: Content-Type, Authorization');
    exit(0);
}

header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');

?>

-- ============================================
-- DATABASE: SISTEM INFORMASI LOGISTIC CORNER
-- Politeknik Negeri Lampung
-- ============================================

-- Buat Database
CREATE DATABASE IF NOT EXISTS logistic_corner;
USE logistic_corner;

-- ============================================
-- TABEL USERS (Pengguna Sistem)
-- ============================================
CREATE TABLE users (
    id INT PRIMARY KEY AUTO_INCREMENT,
    username VARCHAR(50) UNIQUE NOT NULL,
    password VARCHAR(255) NOT NULL,
    nama_lengkap VARCHAR(100) NOT NULL,
    nim_nip VARCHAR(20) UNIQUE,
    email VARCHAR(100) UNIQUE,
    no_whatsapp VARCHAR(15),
    role ENUM('admin', 'petugas', 'mahasiswa', 'dosen') DEFAULT 'mahasiswa',
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL PACKAGES (Data Paket)
-- ============================================
CREATE TABLE packages (
    id INT PRIMARY KEY AUTO_INCREMENT,
    nomor_resi VARCHAR(50) UNIQUE NOT NULL,
    nama_penerima VARCHAR(100) NOT NULL,
    nim_nip VARCHAR(20) NOT NULL,
    kurir VARCHAR(50) NOT NULL,
    berat_paket DECIMAL(10,2),
    no_whatsapp VARCHAR(15),
    tanggal_masuk DATETIME NOT NULL,
    tanggal_diambil DATETIME,
    catatan TEXT,
    status ENUM('belum_diambil', 'sudah_diambil') DEFAULT 'belum_diambil',
    denda_dibayar INT DEFAULT 0,
    petugas_input INT,
    petugas_serah INT,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    FOREIGN KEY (petugas_input) REFERENCES users(id) ON DELETE SET NULL,
    FOREIGN KEY (petugas_serah) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_resi (nomor_resi),
    INDEX idx_nim (nim_nip),
    INDEX idx_status (status),
    INDEX idx_tanggal_masuk (tanggal_masuk)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL NOTIFICATIONS (Notifikasi)
-- ============================================
CREATE TABLE notifications (
    id INT PRIMARY KEY AUTO_INCREMENT,
    package_id INT NOT NULL,
    user_id INT,
    type ENUM('arrival', 'reminder', 'taken', 'fine') NOT NULL,
    message TEXT NOT NULL,
    is_read BOOLEAN DEFAULT FALSE,
    sent_via ENUM('whatsapp', 'email', 'system') DEFAULT 'system',
    sent_at DATETIME,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (package_id) REFERENCES packages(id) ON DELETE CASCADE,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
    INDEX idx_package (package_id),
    INDEX idx_user (user_id),
    INDEX idx_read (is_read)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL HOLIDAYS (Hari Libur & Tanggal Merah)
-- ============================================
CREATE TABLE holidays (
    id INT PRIMARY KEY AUTO_INCREMENT,
    tanggal DATE NOT NULL UNIQUE,
    keterangan VARCHAR(100) NOT NULL,
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL SETTINGS (Pengaturan Sistem)
-- ============================================
CREATE TABLE settings (
    id INT PRIMARY KEY AUTO_INCREMENT,
    setting_key VARCHAR(50) UNIQUE NOT NULL,
    setting_value TEXT,
    description TEXT,
    updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- TABEL ACTIVITY_LOGS (Log Aktivitas)
-- ============================================
CREATE TABLE activity_logs (
    id INT PRIMARY KEY AUTO_INCREMENT,
    user_id INT,
    action VARCHAR(100) NOT NULL,
    description TEXT,
    ip_address VARCHAR(45),
    created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
    FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL,
    INDEX idx_user (user_id),
    INDEX idx_created (created_at)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4;

-- ============================================
-- INSERT DATA AWAL
-- ============================================

-- Insert Admin Default (password: admin123)
INSERT INTO users (username, password, nama_lengkap, nim_nip, email, role) VALUES
('admin', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Administrator', 'ADM001', 'admin@polinela.ac.id', 'admin'),
('petugas1', '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', 'Petugas Logistik', 'PTG001', 'petugas@polinela.ac.id', 'petugas');

-- Insert Settings Default
INSERT INTO settings (setting_key, setting_value, description) VALUES
('denda_per_hari', '1000', 'Denda keterlambatan per hari (Rupiah)'),
('hari_gratis', '1', 'Jumlah hari gratis sebelum denda'),
('whatsapp_gateway', '', 'API WhatsApp Gateway'),
('email_notif', 'true', 'Aktifkan notifikasi email'),
('wa_notif', 'true', 'Aktifkan notifikasi WhatsApp'),
('jam_operasional', '08:00-16:00', 'Jam operasional Logistic Corner');

-- Insert Hari Libur 2024-2025
INSERT INTO holidays (tanggal, keterangan) VALUES
('2024-01-01', 'Tahun Baru 2024'),
('2024-02-08', 'Isra Mikraj'),
('2024-02-10', 'Tahun Baru Imlek'),
('2024-03-11', 'Hari Suci Nyepi'),
('2024-03-29', 'Wafat Isa Al-Masih'),
('2024-03-31', 'Hari Raya Idul Fitri'),
('2024-04-01', 'Cuti Bersama Idul Fitri'),
('2024-04-10', 'Hari Raya Idul Fitri'),
('2024-04-11', 'Cuti Bersama Idul Fitri'),
('2024-05-01', 'Hari Buruh'),
('2024-05-09', 'Kenaikan Isa Al-Masih'),
('2024-05-23', 'Hari Raya Waisak'),
('2024-06-01', 'Hari Lahir Pancasila'),
('2024-06-17', 'Hari Raya Idul Adha'),
('2024-07-07', 'Tahun Baru Islam 1446H'),
('2024-08-17', 'Hari Kemerdekaan RI'),
('2024-09-16', 'Maulid Nabi Muhammad SAW'),
('2024-12-25', 'Hari Raya Natal'),
('2024-12-26', 'Cuti Bersama Natal'),
('2025-01-01', 'Tahun Baru 2025');

-- Insert Data Paket Sample
INSERT INTO packages (nomor_resi, nama_penerima, nim_nip, kurir, berat_paket, no_whatsapp, tanggal_masuk, status, petugas_input) VALUES
('JNE1234567890', 'Budi Santoso', '2141001', 'JNE', 2.5, '081234567890', DATE_SUB(NOW(), INTERVAL 5 DAY), 'belum_diambil', 1),
('JNTX987654321', 'Siti Aminah', '2141002', 'J&T', 1.0, '082345678901', DATE_SUB(NOW(), INTERVAL 2 DAY), 'belum_diambil', 1),
('SPX555666777', 'Ahmad Fauzi', '2141003', 'Shopee Express', 0.5, '083456789012', DATE_SUB(NOW(), INTERVAL 10 DAY), 'sudah_diambil', 1),
('SICPT111222', 'Dewi Lestari', '2141004', 'SiCepat', 3.2, '084567890123', DATE_SUB(NOW(), INTERVAL 1 DAY), 'belum_diambil', 1),
('ANT888999', 'Eko Prasetyo', '2141005', 'Anteraja', 1.8, '085678901234', NOW(), 'belum_diambil', 1);

-- ============================================
-- VIEWS (Tampilan Data)
-- ============================================

-- View untuk Dashboard Stats
CREATE VIEW v_dashboard_stats AS
SELECT 
    COUNT(*) as total_paket,
    SUM(CASE WHEN status = 'belum_diambil' THEN 1 ELSE 0 END) as belum_diambil,
    SUM(CASE WHEN status = 'sudah_diambil' THEN 1 ELSE 0 END) as sudah_diambil,
    SUM(CASE 
        WHEN status = 'belum_diambil' THEN 
            GREATEST(0, 
                (DATEDIFF(NOW(), tanggal_masuk) - 1) * 
                (SELECT setting_value FROM settings WHERE setting_key = 'denda_per_hari')
            )
        ELSE 0 
    END) as total_denda
FROM packages;

-- View untuk Paket dengan Denda
CREATE VIEW v_packages_with_fine AS
SELECT 
    p.*,
    DATEDIFF(NOW(), p.tanggal_masuk) as lama_hari,
    CASE 
        WHEN p.status = 'belum_diambil' THEN 
            GREATEST(0, 
                (DATEDIFF(NOW(), p.tanggal_masuk) - 1) * 
                (SELECT setting_value FROM settings WHERE setting_key = 'denda_per_hari')
            )
        ELSE p.denda_dibayar
    END as denda
FROM packages p;

-- ============================================
-- STORED PROCEDURES
-- ============================================

DELIMITER //

-- Procedure untuk menghitung denda
CREATE PROCEDURE calculate_fine(
    IN p_package_id INT,
    OUT p_fine INT
)
BEGIN
    DECLARE v_tanggal_masuk DATETIME;
    DECLARE v_status VARCHAR(20);
    DECLARE v_denda_per_hari INT;
    DECLARE v_hari_gratis INT;
    DECLARE v_lama_hari INT;
    
    -- Ambil data paket
    SELECT tanggal_masuk, status INTO v_tanggal_masuk, v_status
    FROM packages WHERE id = p_package_id;
    
    -- Ambil setting denda
    SELECT setting_value INTO v_denda_per_hari 
    FROM settings WHERE setting_key = 'denda_per_hari';
    
    SELECT setting_value INTO v_hari_gratis 
    FROM settings WHERE setting_key = 'hari_gratis';
    
    -- Hitung lama hari
    SET v_lama_hari = DATEDIFF(NOW(), v_tanggal_masuk);
    
    -- Hitung denda
    IF v_status = 'belum_diambil' THEN
        SET p_fine = GREATEST(0, (v_lama_hari - v_hari_gratis) * v_denda_per_hari);
    ELSE
        SELECT denda_dibayar INTO p_fine FROM packages WHERE id = p_package_id;
    END IF;
END //

-- Procedure untuk ambil paket
CREATE PROCEDURE take_package(
    IN p_package_id INT,
    IN p_petugas_id INT,
    OUT p_message VARCHAR(255)
)
BEGIN
    DECLARE v_denda INT;
    
    -- Hitung denda
    CALL calculate_fine(p_package_id, v_denda);
    
    -- Update status paket
    UPDATE packages 
    SET status = 'sudah_diambil',
        tanggal_diambil = NOW(),
        denda_dibayar = v_denda,
        petugas_serah = p_petugas_id
    WHERE id = p_package_id;
    
    SET p_message = CONCAT('Paket berhasil diambil. Denda: Rp ', v_denda);
END //

DELIMITER ;

-- ============================================
-- TRIGGERS
-- ============================================

DELIMITER //

-- Trigger setelah insert paket (kirim notifikasi)
CREATE TRIGGER after_package_insert
AFTER INSERT ON packages
FOR EACH ROW
BEGIN
    INSERT INTO notifications (package_id, type, message, sent_at)
    VALUES (
        NEW.id,
        'arrival',
        CONCAT('Paket dengan nomor resi ', NEW.nomor_resi, ' telah tiba di Logistic Corner.'),
        NOW()
    );
END //

-- Trigger setelah update paket menjadi diambil
CREATE TRIGGER after_package_taken
AFTER UPDATE ON packages
FOR EACH ROW
BEGIN
    IF NEW.status = 'sudah_diambil' AND OLD.status = 'belum_diambil' THEN
        INSERT INTO notifications (package_id, type, message, sent_at)
        VALUES (
            NEW.id,
            'taken',
            CONCAT('Paket dengan nomor resi ', NEW.nomor_resi, ' telah diambil.'),
            NOW()
        );
    END IF;
END //

DELIMITER ;

-- ============================================
-- GRANTS (Opsional - untuk user terpisah)
-- ============================================
-- CREATE USER 'logistic_user'@'localhost' IDENTIFIED BY 'password123';
-- GRANT ALL PRIVILEGES ON logistic_corner.* TO 'logistic_user'@'localhost';
-- FLUSH PRIVILEGES;

-- ============================================
-- SELESAI
-- ============================================
SELECT 'Database logistic_corner berhasil dibuat!' as status;
