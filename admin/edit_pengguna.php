<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Mendapatkan data pengguna berdasarkan ID
if (isset($_GET['id'])) {
    $id = $_GET['id'];
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
    $stmt->close();
}

// Update data pengguna
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $kelas = $_POST['kelas'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $username = $_POST['username'];
    $password = $_POST['password'];
    $role = $_POST['role'];
    $id = $_POST['id'];

    $stmt = $conn->prepare("UPDATE users SET nama = ?, nisn = ?, kelas = ?, nomor_telepon = ?, username = ?, password = ?, role = ? WHERE id = ?");
    $stmt->bind_param("sssssssi", $nama, $nisn, $kelas, $nomor_telepon, $username, $password, $role, $id);

    if ($stmt->execute()) {
        header("Location: manajemen_pengguna.php?message=updated");
    } else {
        echo "Error: " . $stmt->error;
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
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Edit Pengguna</title>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?> <!-- Include Navbar -->

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Edit Pengguna</h1>
        <form action="edit_pengguna.php" method="post">
            <input type="hidden" name="id" value="<?php echo $user['id']; ?>">
            <div class="mb-4">
                <label for="nama" class="block text-gray-700">Nama</label>
                <input type="text" id="nama" name="nama" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['nama']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="nisn" class="block text-gray-700">NISN</label>
                <input type="text" id="nisn" name="nisn" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['nisn']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="kelas" class="block text-gray-700">Kelas</label>
                <input type="text" id="kelas" name="kelas" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['kelas']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="nomor_telepon" class="block text-gray-700">Nomor Telepon</label>
                <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['nomor_telepon']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="username" class="block text-gray-700">Username</label>
                <input type="text" id="username" name="username" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['username']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="password" class="block text-gray-700">Password</label>
                <input type="password" id="password" name="password" class="w-full p-2 border rounded" value="<?php echo htmlspecialchars($user['password']); ?>" required>
            </div>
            <div class="mb-4">
                <label for="role" class="block text-gray-700">Role</label>
                <select id="role" name="role" class="w-full p-2 border rounded" required>
                    <option value="admin" <?php if ($user['role'] == 'admin') echo 'selected'; ?>>Admin</option>
                    <option value="pelapor" <?php if ($user['role'] == 'pelapor') echo 'selected'; ?>>Pelapor</option>
                </select>
            </div>
            <div class="flex justify-between">
                <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600">Simpan</button>
                <a href="manajemen_pengguna.php" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600">Batal</a>
            </div>
        </form>
    </div>
</body>
</html>
