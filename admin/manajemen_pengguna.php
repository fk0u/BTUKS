<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Jika ada request delete
if (isset($_GET['delete_id'])) {
    $delete_id = $_GET['delete_id'];
    $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
    $stmt->bind_param("i", $delete_id);
    if ($stmt->execute()) {
        echo "<script>alert('Pengguna berhasil dihapus'); window.location.href='manajemen_pengguna.php';</script>";
    } else {
        echo "<script>alert('Gagal menghapus pengguna');</script>";
    }
    $stmt->close();
}

// Mengambil data pengguna dari tabel users
$query = "SELECT * FROM users";
$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Manajemen Pengguna</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@2.8.2/dist/alpine.min.js" defer></script>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?> <!-- Include Navbar -->

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Manajemen Pengguna</h1>

        <!-- Tombol Tambah Pengguna -->
        <div x-data="{ open: false }">
            <button @click="open = true" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 transition duration-300 mb-6">Tambah Pengguna</button>

            <!-- Popup Form -->
            <div x-show="open" @keydown.escape.window="open = false" class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white p-6 rounded-lg shadow-lg w-full max-w-lg">
                    <h2 class="text-2xl font-bold mb-4">Tambah Akun</h2>
                    <form action="tambah_pengguna.php" method="post">
                        <div class="mb-4">
                            <label for="nama" class="block text-gray-700">Nama</label>
                            <input type="text" id="nama" name="nama" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="nisn" class="block text-gray-700">NISN</label>
                            <input type="text" id="nisn" name="nisn" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="kelas" class="block text-gray-700">Kelas</label>
                            <input type="text" id="kelas" name="kelas" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="nomor_telepon" class="block text-gray-700">Nomor Telepon</label>
                            <input type="text" id="nomor_telepon" name="nomor_telepon" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="username" class="block text-gray-700">Username</label>
                            <input type="text" id="username" name="username" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="password" class="block text-gray-700">Password</label>
                            <input type="password" id="password" name="password" class="w-full p-2 border rounded" required>
                        </div>
                        <div class="mb-4">
                            <label for="role" class="block text-gray-700">Role</label>
                            <select id="role" name="role" class="w-full p-2 border rounded" required>
                                <option value="admin">Admin</option>
                                <option value="pelapor">Pelapor</option>
                            </select>
                        </div>
                        <div class="flex justify-between">
                            <button type="submit" class="bg-green-500 text-white p-2 rounded hover:bg-green-600">Tambah</button>
                            <button type="button" @click="open = false" class="bg-red-500 text-white p-2 rounded hover:bg-red-600">Batal</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <!-- Tabel Pengguna -->
        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="py-2 px-3 text-left text-xs font-medium">Nama</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">NISN</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Kelas</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Nomor Telepon</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Username</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Role</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Aksi</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['nama']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['nisn']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['kelas']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['nomor_telepon']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['username']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['role']); ?></td>
                        <td class="py-2 px-3 flex">
                            <a href="edit_pengguna.php?id=<?php echo $row['id']; ?>" class="bg-yellow-500 text-white p-2 rounded mr-2 hover:bg-yellow-600">Edit</a>
                            <a href="manajemen_pengguna.php?delete_id=<?php echo $row['id']; ?>" class="bg-red-500 text-white p-2 rounded hover:bg-red-600" onclick="return confirm('Apakah Anda yakin ingin menghapus pengguna ini?')">Hapus</a>
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
