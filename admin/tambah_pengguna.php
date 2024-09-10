<?php
include '../config.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Ambil data dari form
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $kelas = $_POST['kelas'];
    $nomorTelepon = !empty($_POST['nomor_telepon']) ? $_POST['nomor_telepon'] : null; // Menangani input kosong
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];

    // Validasi input
    if (empty($nama) || empty($nisn) || empty($kelas) || empty($username) || empty($password) || empty($role)) {
        echo "Semua field harus diisi.";
        exit();
    }

    // Query untuk menambahkan pengguna
    $stmt = $conn->prepare("INSERT INTO users (nama, nisn, kelas, nomor_telepon, username, password, role) VALUES (?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssssss", $nama, $nisn, $kelas, $nomorTelepon, $username, $password, $role);

    if ($stmt->execute()) {
        echo "Akun berhasil ditambahkan!";
    } else {
        echo "Terjadi kesalahan: " . $conn->error;
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Pengguna</title>
</head>
<body>
    <h1>Tambah Pengguna</h1>
    <form method="post" action="">
        <label>Nama:</label>
        <input type="text" name="nama" required><br>
        <label>NISN:</label>
        <input type="text" name="nisn" required><br>
        <label>Kelas:</label>
        <input type="text" name="kelas" required><br>
        <label>Nomor Telepon:</label>
        <input type="text" name="nomor_telepon"><br>
        <label>Username:</label>
        <input type="text" name="username" required><br>
        <label>Password:</label>
        <input type="password" name="password" required><br>
        <label>Role:</label>
        <select name="role" required>
            <option value="admin">Admin</option>
            <option value="pelapor">Pelapor</option>
        </select><br>
        <button type="submit">Tambah Pengguna</button>
    </form>
    <a href="manajemen_pengguna.php">Kembali ke Manajemen Pengguna</a>
</body>
</html>
