<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_FILES['dokumen'])) {
    $targetDir = "uploads/";
    $targetFile = $targetDir . basename($_FILES['dokumen']['name']);
    if (move_uploaded_file($_FILES['dokumen']['tmp_name'], $targetFile)) {
        echo "File successfully uploaded.";
    } else {
        echo "Error uploading file.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upload Dokumen</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">
    <!-- Navbar -->
    <nav class="bg-green-600 p-4 text-white">
        <div class="container mx-auto flex justify-between items-center">
            <a href="dashboard.php" class="text-2xl font-bold">Admin Dashboard</a>
            <a href="logout.php" class="bg-red-500 p-2 rounded hover:bg-red-600">Logout</a>
        </div>
    </nav>

    <div class="container mx-auto p-6">
        <h1 class="text-3xl font-bold mb-6">Upload Dokumen</h1>

        <form action="upload.php" method="post" enctype="multipart/form-data" class="bg-white p-6 rounded-lg shadow-md">
            <input type="file" name="dokumen" class="p-2 border rounded mb-4" required>
            <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600">Upload</button>
        </form>
    </div>
</body>
</html>
