<?php
include 'config.php'; // File konfigurasi untuk koneksi database
session_start(); // Memulai sesi PHP

// Inisialisasi variabel pesan untuk menampilkan pesan sukses atau error
$success = '';
$error = '';

// Mengamankan input pengguna
function secureInput($data) {
    return htmlspecialchars(stripslashes(trim($data)));
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = secureInput($_POST['username']);
    $password = secureInput($_POST['password']);

    // Query untuk mengambil user berdasarkan username
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();

        // Verifikasi password tanpa hash
        if ($password === $user['password']) {
            // Set session jika login berhasil
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['username'] = $user['username'];

            // Redirect berdasarkan role user
            if ($user['role'] == 'admin') {
                $success = "Login berhasil. Mengarahkan ke dashboard admin...";
                header("refresh:2;url=admin/dashboard.php"); // Redirect setelah 2 detik
            } else {
                $success = "Login berhasil. Mengarahkan ke halaman pelapor...";
                header("refresh:2;url=pelapor/dashboard_pelapor.php"); // Redirect setelah 2 detik
            }
        } else {
            // Jika password salah
            $error = "Password salah. Silakan coba lagi.";
        }
    } else {
        // Jika username tidak ditemukan
        $error = "Username tidak ditemukan. Silakan coba lagi.";
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
    <title>Login</title>
</head>
<body class="flex items-center justify-center h-screen bg-gradient-to-r from-blue-500 to-purple-600">
    <div class="w-full max-w-md p-8 bg-white rounded-lg shadow-lg">
        <h2 class="text-2xl font-bold mb-8 text-center text-gray-800">Login Aplikasi</h2>

        <!-- Tampilkan pesan jika ada -->
        <?php if ($error): ?>
            <p class="text-red-500 mb-4"><?php echo $error; ?></p>
        <?php elseif ($success): ?>
            <p class="text-green-500 mb-4"><?php echo $success; ?></p>
        <?php endif; ?>

        <form action="" method="post" class="space-y-6">
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="username">Username</label>
                <input name="username" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" id="username" type="text" placeholder="Username" required>
            </div>
            <div>
                <label class="block text-gray-700 text-sm font-bold mb-2" for="password">Password</label>
                <div class="relative">
                    <input name="password" class="w-full p-3 border rounded focus:outline-none focus:ring focus:border-blue-500" id="password" type="password" placeholder="Password" required>
                    <button type="button" onclick="togglePassword()" class="absolute inset-y-0 right-0 px-3 flex items-center text-gray-600">
                        <svg id="eyeIcon" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                    </button>
                </div>
            </div>
            <button class="w-full bg-blue-600 text-white font-bold py-3 rounded hover:bg-blue-700 transition duration-300" type="submit">
                Login
            </button>
        </form>
    </div>

    <script>
        function togglePassword() {
            const passwordInput = document.getElementById('password');
            const eyeIcon = document.getElementById('eyeIcon');
            const isPassword = passwordInput.type === 'password';

            if (isPassword) {
                passwordInput.type = 'text';
                eyeIcon.innerHTML = '<path d="M13.875 18.825c-2.7 0-4.875-2.175-4.875-4.875s2.175-4.875 4.875-4.875 4.875 2.175 4.875 4.875-2.175 4.875-4.875 4.875zM13.875 2.625c-6.225 0-11.25 5.025-11.25 11.25s5.025 11.25 11.25 11.25 11.25-5.025 11.25-11.25-5.025-11.25-11.25-11.25z"></path>';
            } else {
                passwordInput.type = 'password';
                eyeIcon.innerHTML = '<path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>';
            }
        }
    </script>
</body>
</html>
