<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $kelas = $_POST['kelas'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $username = $_POST['username'];
    $password = $_POST['password']; // Menyimpan password langsung tanpa hashing

    $sql = "INSERT INTO users (nama, nisn, kelas, nomor_telepon, username, password, role) 
            VALUES ('$nama', '$nisn', '$kelas', '$nomor_telepon', '$username', '$password', 'pelapor')";
    if ($conn->query($sql) === TRUE) {
        $success = "Akun pelapor berhasil dibuat";
    } else {
        $error = "Error: " . $conn->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Buat Akun Pelapor</title>
</head>
<body class="bg-gray-100 p-8">
    <div class="container mx-auto">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Buat Akun Pelapor</h1>

        <?php if(isset($success)) { echo "<p class='text-green-500 mb-4'>$success</p>"; } ?>
        <?php if(isset($error)) { echo "<p class='text-red-500 mb-4'>$error</p>"; } ?>

        <form action="" method="post" class="bg-white p-6 rounded-lg shadow-md">
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nama</label>
                <input name="nama" type="text" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">NISN</label>
                <input name="nisn" type="text" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Kelas</label>
                <input name="kelas" type="text" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Nomor Telepon</label>
                <input name="nomor_telepon" type="text" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Username</label>
                <input name="username" type="text" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <div class="mb-4">
                <label class="block text-gray-700 text-sm font-bold mb-2">Password</label>
                <input name="password" type="password" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" required>
            </div>
            <button type="submit" class="w-full bg-green-500 text-white p-3 rounded hover:bg-green-600 transition duration-300">Buat Akun</button>
        </form>
    </div>
</body>
</html>
