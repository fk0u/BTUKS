<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Dashboard Admin</title>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?> <!-- Include Navbar -->

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Dashboard Admin</h1>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Card 1: Lihat Laporan -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 flex items-center space-x-4">
                    <div class="bg-green-500 text-white p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 14h-1v-4c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4v4H3c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h1v4c0 2.21 1.79 4 4 4h10c2.21 0 4-1.79 4-4v-4h1c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm-7 6H8v-2h6v2zm-6-4V8c0-1.1.9-2 2-2s2 .9 2 2v8H8zm10 4H12v-2h4v2zm0-4H12V8c0-1.1.9-2 2-2s2 .9 2 2v8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Lihat Laporan</h2>
                        <p class="text-gray-600">Lihat semua laporan kesehatan yang masuk.</p>
                        <a href="lihat_laporan.php" class="text-green-500 hover:text-green-600">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Card 2: Riwayat Kesehatan -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 flex items-center space-x-4">
                    <div class="bg-green-500 text-white p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M21 14h-1v-4c0-2.21-1.79-4-4-4H8c-2.21 0-4 1.79-4 4v4H3c-1.1 0-2 .9-2 2v4c0 1.1.9 2 2 2h1v4c0 2.21 1.79 4 4 4h10c2.21 0 4-1.79 4-4v-4h1c1.1 0 2-.9 2-2v-4c0-1.1-.9-2-2-2zm-7 6H8v-2h6v2zm-6-4V8c0-1.1.9-2 2-2s2 .9 2 2v8H8zm10 4H12v-2h4v2zm0-4H12V8c0-1.1.9-2 2-2s2 .9 2 2v8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Riwayat Kesehatan</h2>
                        <p class="text-gray-600">Lihat riwayat kesehatan siswa dan jumlah kunjungan ke UKS.</p>
                        <a href="riwayat_kesehatan.php" class="text-green-500 hover:text-green-600">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Card 3: Manajemen Pengguna -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 flex items-center space-x-4">
                    <div class="bg-green-500 text-white p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 12c2.21 0 4-1.79 4-4s-1.79-4-4-4-4 1.79-4 4 1.79 4 4 4zm0 2c-3.31 0-6 2.69-6 6v2h12v-2c0-3.31-2.69-6-6-6zm0-8c1.1 0 2 .9 2 2s-.9 2-2 2-2-.9-2-2 .9-2 2-2zm0 12H6v-2c0-2.21 1.79-4 4-4s4 1.79 4 4v2z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Manajemen Pengguna</h2>
                        <p class="text-gray-600">Kelola data pengguna dan akses mereka.</p>
                        <a href="manajemen_pengguna.php" class="text-green-500 hover:text-green-600">Lihat Detail</a>
                    </div>
                </div>
            </div>

            <!-- Card 4: Upload Dokumen -->
            <div class="bg-white shadow-lg rounded-lg overflow-hidden">
                <div class="p-6 flex items-center space-x-4">
                    <div class="bg-green-500 text-white p-4 rounded-full">
                        <svg class="w-8 h-8" fill="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                            <path d="M12 7V1L5 8h7v6h2V8h7L12 1v6zm0 14c-4.41 0-8-3.59-8-8h2c0 3.31 2.69 6 6 6s6-2.69 6-6h2c0 4.41-3.59 8-8 8z"/>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-xl font-semibold text-gray-800">Upload Dokumen</h2>
                        <p class="text-gray-600">Unggah dan kelola dokumen terkait kesehatan siswa.</p>
                        <a href="upload.php" class="text-green-500 hover:text-green-600">Lihat Detail</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
