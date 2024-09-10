<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || $_SESSION['role'] != 'admin') {
    header("Location: ../login.php");
    exit();
}

// Mengambil data dari tabel tamu dan users
$query = "
    SELECT tamu.*, users.nama AS nama_pelapor, users.kelas AS kelas_pelapor
    FROM tamu 
    LEFT JOIN users ON tamu.pelapor_id = users.id
";

$result = $conn->query($query);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Lihat Laporan</title>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?> <!-- Include Navbar -->

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Lihat Laporan</h1>

        <!-- Tombol Kembali -->
        <a href="dashboard.php" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600 transition duration-300 mb-6 inline-block">Kembali ke Dashboard</a>

        <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
            <thead class="bg-green-600 text-white">
                <tr>
                    <th class="py-2 px-3 text-left text-xs font-medium">Nama Tamu</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Asal Kelas</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Keluhan</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Penanganan</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Tanggal</th>
                    <th class="py-2 px-3 text-left text-xs font-medium">Pelapor</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php while ($row = $result->fetch_assoc()): ?>
                    <tr>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['asal_kelas']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['keluhan']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['penanganan']); ?></td>
                        <td class="py-2 px-3"><?php echo htmlspecialchars($row['created_at']); ?></td>
                        <td class="py-2 px-3">
                            <?php echo htmlspecialchars($row['nama_pelapor']); ?> 
                            (<?php echo htmlspecialchars($row['kelas_pelapor']); ?>)
                        </td>
                    </tr>
                <?php endwhile; ?>
            </tbody>
        </table>
    </div>
</body>
</html>
