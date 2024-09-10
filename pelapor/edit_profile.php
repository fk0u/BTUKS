<?php
session_start();
include '../config.php';

// Periksa apakah pengguna sudah login
if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

// Ambil ID pengguna dari sesi
$user_id = $_SESSION['user_id'];

// Mengambil data pengguna dari database
$query = $conn->prepare("SELECT nama, nisn, nomor_telepon, kelas, organisasi, username FROM users WHERE id = ?");
$query->bind_param("i", $user_id);
$query->execute();
$result = $query->get_result();
$user = $result->fetch_assoc();

// Jangan menutup koneksi di sini
// $conn->close();

if (!$user) {
    die("Pengguna tidak ditemukan.");
}

// Proses pembaruan profil jika formulir disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nama = $_POST['nama'];
    $nisn = $_POST['nisn'];
    $nomor_telepon = $_POST['nomor_telepon'];
    $kelas = $_POST['kelas'];
    $username = $_POST['username'];
    $password = isset($_POST['password']) ? $_POST['password'] : '';

    $error = '';
    $success = '';

    // Validasi input
    if (empty($nama) || empty($nisn) || empty($nomor_telepon) || empty($kelas) || empty($username)) {
        $error = "Semua field harus diisi.";
    } else {
        if ($_SESSION['role'] == 'admin') {
            // Admin dapat mengubah semua field termasuk organisasi
            $organisasi = $_POST['organisasi'];
            $update_query = $conn->prepare("UPDATE users SET nama = ?, nisn = ?, nomor_telepon = ?, kelas = ?, organisasi = ?, username = ? WHERE id = ?");
            $update_query->bind_param("ssssssi", $nama, $nisn, $nomor_telepon, $kelas, $organisasi, $username, $user_id);
        } else {
            // Non-admin tidak dapat mengubah organisasi
            $update_query = $conn->prepare("UPDATE users SET nama = ?, nisn = ?, nomor_telepon = ?, kelas = ?, username = ? WHERE id = ?");
            $update_query->bind_param("sssssi", $nama, $nisn, $nomor_telepon, $kelas, $username, $user_id);
        }

        if ($update_query->execute()) {
            $success = "Profil berhasil diperbarui.";
        } else {
            $error = "Terjadi kesalahan saat memperbarui profil.";
        }

        if (!empty($password)) {
            // Update password jika diberikan
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $update_password_query = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
            $update_password_query->bind_param("si", $password_hash, $user_id);
            if ($update_password_query->execute()) {
                $success .= " Password berhasil diperbarui.";
            } else {
                $error .= " Terjadi kesalahan saat memperbarui password.";
            }
        }

        // Menutup statement setelah selesai
        $update_query->close();
        if (isset($update_password_query)) {
            $update_password_query->close();
        }
    }

    // Menutup koneksi setelah semua operasi selesai
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profil - Buku Tamu UKS</title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">
    <div class="bg-white p-8 rounded-lg shadow-lg w-full max-w-lg">
        <h2 class="text-2xl font-bold mb-6 text-gray-800">Edit Profil</h2>

        <!-- Tampilkan pesan jika ada -->
        <?php if(isset($error)): ?>
            <div class="bg-red-100 text-red-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($error); ?>
            </div>
        <?php elseif(isset($success)): ?>
            <div class="bg-green-100 text-green-700 p-4 rounded mb-4">
                <?php echo htmlspecialchars($success); ?>
            </div>
        <?php endif; ?>

        <form action="" method="post" class="space-y-4">
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="nama">Nama</label>
                <input type="text" name="nama" id="nama" value="<?php echo htmlspecialchars($user['nama']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="nisn">NISN</label>
                <input type="text" name="nisn" id="nisn" value="<?php echo htmlspecialchars($user['nisn']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="nomor_telepon">Nomor Telepon</label>
                <input type="text" name="nomor_telepon" id="nomor_telepon" value="<?php echo htmlspecialchars($user['nomor_telepon']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="kelas">Kelas</label>
                <input type="text" name="kelas" id="kelas" value="<?php echo htmlspecialchars($user['kelas']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <?php if ($_SESSION['role'] == 'admin'): ?>
                <div>
                    <label class="block text-gray-700 text-sm font-semibold mb-2" for="organisasi">Organisasi</label>
                    <input type="text" name="organisasi" id="organisasi" value="<?php echo htmlspecialchars($user['organisasi']); ?>" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>
            <?php endif; ?>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="username">Username</label>
                <input type="text" name="username" id="username" value="<?php echo htmlspecialchars($user['username']); ?>" required class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-semibold mb-2" for="password">Password (Kosongkan jika tidak ingin mengganti)</label>
                <input type="password" name="password" id="password" class="w-full p-3 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
            </div>
            <button type="submit" class="w-full bg-blue-600 text-white font-semibold py-3 rounded-md hover:bg-blue-700 transition duration-300">Simpan Perubahan</button>
        </form>
    </div>
</body>
</html>
