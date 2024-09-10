<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil informasi pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Dapatkan data pengguna dari database, jika perlu
$query = $conn->prepare("SELECT nama, kelas, organisasi FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

$query->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Form Pelapor - Buku Tamu UKS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <style>
        body {
            background-color: #f0f4f8; /* Warna latar belakang yang lembut */
        }
        .custom-card {
            background-color: #ffffff; /* Warna latar belakang kartu */
            border-radius: 0.75rem; /* Sudut yang lebih halus */
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); /* Bayangan halus */
        }
        .custom-header {
            background-color: #4a76a8; /* Warna header yang lebih cerah */
            color: #ffffff; /* Warna teks header */
        }
        .custom-button {
            background-color: #4a76a8; /* Warna tombol yang serasi dengan header */
            color: #ffffff; /* Warna teks tombol */
        }
    </style>
</head>
<body class="flex items-center justify-center min-h-screen">
    <div class="container mx-auto p-4">
        <div class="custom-card p-6">
            <div class="custom-header p-4 rounded-t-lg mb-4">
                <h2 class="text-2xl font-bold">Selamat Datang, <?php echo htmlspecialchars($user['nama']); ?></h2>
                <p class="text-sm">Kelas: <?php echo htmlspecialchars($user['kelas']); ?> | Organisasi: <?php echo htmlspecialchars($user['organisasi']); ?></p>
            </div>
            
            <div class="p-4">
                <h3 class="text-xl font-semibold mb-4">Form Pelapor - Buku Tamu UKS</h3>
                <form action="proses_pelapor.php" method="POST" class="space-y-4">
                    <div>
                        <label class="block text-gray-700">Nama Tamu</label>
                        <input type="text" name="nama_tamu" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700">Kelas</label>
                        <input type="text" name="kelas_tamu" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-gray-700">Keluhan</label>
                        <textarea name="keluhan" required class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500"></textarea>
                    </div>
                    <button type="submit" class="w-full custom-button py-3 rounded hover:bg-blue-700 transition duration-300">Submit</button>
                </form>
            </div>
        </div>
    </div>
</body>
</html>
