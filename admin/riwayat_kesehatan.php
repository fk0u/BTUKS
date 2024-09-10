<?php
include '../config.php';
session_start();
if (!isset($_SESSION['username']) || ($_SESSION['role'] != 'admin' && $_SESSION['role'] != 'pelapor')) {
    header("Location: ../login.php");
    exit();
}

$searchQuery = '';
if (isset($_POST['search'])) {
    $searchQuery = $_POST['search'];
}

function fetchTamuData($conn, $searchQuery) {
    $sql = "SELECT nama_tamu, asal_kelas, COUNT(*) AS jumlah_masuk FROM tamu";
    if ($searchQuery) {
        $sql .= " WHERE nama_tamu LIKE ?";
    }
    $sql .= " GROUP BY nama_tamu, asal_kelas";
    $stmt = $conn->prepare($sql);
    if ($searchQuery) {
        $likeQuery = '%' . $searchQuery . '%';
        $stmt->bind_param("s", $likeQuery);
    }
    $stmt->execute();
    return $stmt->get_result();
}

$result = fetchTamuData($conn, $searchQuery);

// Ambil detail jika ada nama tamu yang dipilih
$detailTamu = [];
if (isset($_GET['nama_tamu'])) {
    $namaTamu = $_GET['nama_tamu'];
    $stmt = $conn->prepare("SELECT nama_tamu, asal_kelas, keluhan, penanganan, created_at FROM tamu WHERE nama_tamu = ?");
    $stmt->bind_param("s", $namaTamu);
    $stmt->execute();
    $riwayatResult = $stmt->get_result();

    $detailTamu = [
        'nama_tamu' => '',
        'asal_kelas' => '',
        'riwayat' => []
    ];

    while ($row = $riwayatResult->fetch_assoc()) {
        if (empty($detailTamu['nama_tamu'])) {
            $detailTamu['nama_tamu'] = $row['nama_tamu'];
            $detailTamu['asal_kelas'] = $row['asal_kelas'];
        }
        $detailTamu['riwayat'][] = [
            'keluhan' => $row['keluhan'],
            'penanganan' => $row['penanganan'],
            'created_at' => $row['created_at']
        ];
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <title>Riwayat Kesehatan</title>
</head>
<body class="bg-gray-100">
    <?php include 'navbar.php'; ?>

    <div class="container mx-auto p-8">
        <h1 class="text-3xl font-bold mb-6 text-gray-800">Riwayat Kesehatan</h1>

        <form method="post" class="mb-6">
            <input type="text" name="search" placeholder="Cari berdasarkan nama tamu..." class="p-2 border rounded w-full" value="<?php echo htmlspecialchars($searchQuery); ?>">
            <button type="submit" class="bg-green-500 text-white p-2 rounded mt-2 hover:bg-green-600">Cari</button>
        </form>

        <?php if (empty($detailTamu)): ?>
            <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden">
                <thead class="bg-green-600 text-white">
                    <tr>
                        <th class="py-2 px-3 text-left text-xs font-medium">Nama Tamu</th>
                        <th class="py-2 px-3 text-left text-xs font-medium">Kelas</th>
                        <th class="py-2 px-3 text-left text-xs font-medium">Jumlah Masuk UKS</th>
                        <th class="py-2 px-3 text-left text-xs font-medium">Aksi</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td class="py-2 px-3"><?php echo htmlspecialchars($row['nama_tamu']); ?></td>
                            <td class="py-2 px-3"><?php echo htmlspecialchars($row['asal_kelas']); ?></td>
                            <td class="py-2 px-3"><?php echo htmlspecialchars($row['jumlah_masuk']); ?></td>
                            <td class="py-2 px-3">
                                <a href="?nama_tamu=<?php echo urlencode($row['nama_tamu']); ?>" class="bg-blue-500 text-white p-2 rounded hover:bg-blue-600">Lihat Detail</a>
                            </td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php else: ?>
            <h2 class="text-2xl font-bold mb-4">Detail Riwayat Kesehatan</h2>
            <div class="bg-white shadow-md rounded-lg p-6 mb-6">
                <p class="font-semibold">Nama Tamu: <span class="font-normal"><?php echo htmlspecialchars($detailTamu['nama_tamu']); ?></span></p>
                <p class="font-semibold">Kelas: <span class="font-normal"><?php echo htmlspecialchars($detailTamu['asal_kelas']); ?></span></p>
                <table class="min-w-full bg-white shadow-md rounded-lg overflow-hidden mt-4">
                    <thead class="bg-green-600 text-white">
                        <tr>
                            <th class="py-2 px-3 text-left text-xs font-medium">Keluhan</th>
                            <th class="py-2 px-3 text-left text-xs font-medium">Penanganan</th>
                            <th class="py-2 px-3 text-left text-xs font-medium">Tanggal Masuk UKS</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($detailTamu['riwayat'] as $riwayat): ?>
                            <tr>
                                <td class="py-2 px-3"><?php echo htmlspecialchars($riwayat['keluhan']); ?></td>
                                <td class="py-2 px-3"><?php echo htmlspecialchars($riwayat['penanganan']); ?></td>
                                <td class="py-2 px-3"><?php echo htmlspecialchars($riwayat['created_at']); ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <a href="cetak_pdf.php?nama_tamu=<?php echo urlencode($detailTamu['nama_tamu']); ?>" target="_blank" class="bg-green-500 text-white p-2 rounded hover:bg-green-600 mt-4 inline-block">Cetak PDF</a>
            </div>
            <a href="riwayat_kesehatan.php" class="bg-gray-500 text-white p-2 rounded hover:bg-gray-600">Kembali</a>
        <?php endif; ?>
    </div>
</body>
</html>
