<?php
include '../config.php';
session_start(); // Mulai sesi

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$user_id = $_SESSION['user_id']; // Ambil ID pengguna dari sesi

$query = $conn->prepare("SELECT nama, kelas, organisasi FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $nama = $user['nama'];
    $kelas = $user['kelas'];
    $organisasi = $user['organisasi']; // Mengambil data organisasi jika kolomnya ada
} else {
    echo "Pengguna tidak ditemukan.";
    exit();
}

$query->close();
$conn->close();

// Fungsi untuk mendapatkan salam berdasarkan waktu
function getGreeting() {
    $hour = date('H');
    if ($hour >= 5 && $hour < 12) {
        return "Selamat Pagi";
    } elseif ($hour >= 12 && $hour < 18) {
        return "Selamat Siang";
    } elseif ($hour >= 18 && $hour < 22) {
        return "Selamat Sore";
    } else {
        return "Selamat Malam";
    }
}

// Daftar kutipan acak
$quotes = [
    "Kesehatan adalah harta yang paling berharga.",
    "Hidup sehat dimulai dari kebiasaan kecil.",
    "Jaga kesehatan, jaga masa depan.",
    "Kesuksesan terbesar adalah hidup sehat dan bahagia.",
    "Pikiran yang sehat ada pada tubuh yang sehat."
];
$random_quote = $quotes[array_rand($quotes)]; // Pilih kutipan acak
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard Pelapor</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100">

    <!-- Header -->
    <header class="bg-white shadow">
        <div class="container mx-auto px-4 py-4 flex justify-between items-center">
            <h1 class="text-2xl font-bold text-gray-800">Dashboard Pelapor</h1>
            <div>
                <a href="../logout.php" class="text-gray-600 hover:text-red-600">Logout</a>
            </div>
        </div>
    </header>

    <!-- Content -->
    <div class="container mx-auto mt-6 px-4">
        <!-- Salam dan Kutipan -->
        <div class="bg-blue-100 p-6 rounded-lg shadow-md mb-6">
            <h2 class="text-xl font-semibold text-gray-700"><?php echo getGreeting(); ?>, <?php echo htmlspecialchars($nama); ?>!</h2>
            <p class="text-gray-600 italic mt-2">"<?php echo htmlspecialchars($random_quote); ?>"</p>
        </div>

        <!-- Navigasi dengan Card -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
            <!-- Buku Tamu UKS -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-blue-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v16a1 1 0 01-1 1H4a1 1 0 01-1-1V4z" /></svg>
                    <h3 class="text-lg font-semibold text-gray-800">Buku Tamu UKS</h3>
                </div>
                <p class="text-gray-600 mt-2">Lihat dan tambahkan laporan kesehatan di Buku Tamu UKS.</p>
                <a href="pelapor_form.php" class="block mt-4 text-blue-600 hover:underline">Akses Buku Tamu UKS</a>
            </div>

            <!-- Edit Profile -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-green-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.5a2.5 2.5 0 00-2.5 2.5v2a2.5 2.5 0 005 0v-2a2.5 2.5 0 00-2.5-2.5zM12 14a7 7 0 00-7 7v1h14v-1a7 7 0 00-7-7z" /></svg>
                    <h3 class="text-lg font-semibold text-gray-800">Edit Profile</h3>
                </div>
                <p class="text-gray-600 mt-2">Perbarui informasi profil Anda di sini.</p>
                <a href="edit_profile.php" class="block mt-4 text-green-600 hover:underline">Edit Profil</a>
            </div>

            <!-- Tambahan Fitur Lainnya -->
            <div class="bg-white p-6 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-200">
                <div class="flex items-center">
                    <svg class="w-8 h-8 text-yellow-600 mr-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-3-3v6m-9 4h18M3 3l18 18"></path></svg>
                    <h3 class="text-lg font-semibold text-gray-800">Fitur Tambahan</h3>
                </div>
                <p class="text-gray-600 mt-2">Ini adalah fitur tambahan yang bisa Anda gunakan.</p>
                <a href="#" class="block mt-4 text-yellow-600 hover:underline">Lihat Fitur</a>
            </div>
        </div>
    </div>

</body>
</html>
