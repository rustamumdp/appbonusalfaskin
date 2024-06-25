<?php
session_start();

// Include koneksi.php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = $_POST['password'];

    $sql = "SELECT * FROM admin WHERE username = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            $_SESSION['username'] = $user['username'];
            $_SESSION['role'] = $user['role'];
            // Periksa peran (role) pengguna
            if ($user['role'] == 'admin') {
                header("Location: home.php"); // Jika admin, arahkan ke home.php
            } else if ($user['role'] == 'karyawan') {
                header("Location: karyawan_home.php"); // Jika karyawan, arahkan ke karyawan_home.php
            }
            exit();
        } else {
            echo "<script>alert('Password salah!');</script>";
        }
    } else {
        echo "<script>alert('Username tidak ditemukan!');</script>";
    }

    $stmt->close();
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - Aplikasi Perhitungan Bonus Karyawan Alfa Skin Care</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
    <style>
        body {
            background: url('assets/img/background5.jpeg') no-repeat center center fixed;
            background-size: cover;
            height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
        }
        .login-panel {
            background-color: rgba(211, 247, 205, 0.8); /* Ubah opasitas */
            border-radius: 10px;
            padding: 30px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.9);
            animation: fadeIn 1.5s;
            text-align: center; /* Menengahkan teks */
            max-width: 400px; /* Menentukan lebar maksimum */
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .login-panel img {
            width: 200px; /* Lebar gambar diperbesar */
            height: auto; /* Menyesuaikan tinggi gambar */
            margin-bottom: 15px;
        }
        .login-panel .btn {
            background-color: #28a745;
            border: none;
            max-width: 100%; /* Sesuaikan lebar tombol dengan lebar maksimum */
            padding: 2px 25px; /* Padding untuk tombol */
            font-size: 15px; /* Ukuran font tombol */
            color: whitesmoke; /* Ubah warna tulisan menjadi putih */
            
        }
        .login-panel .btn:hover {
            background-color: #218838;
        }
        .form-label {
            display: flex;
            justify-content: flex-start;
        }
        .form-control {
            display: block;
            width: 100%;
        }
    </style>
</head>
<body>
    <div class="login-panel">
        <img src="assets/img/logo3.png" class="img-circle" alt="Logo">
        <h3>Aplikasi Perhitungan Bonus Karyawan Klinik Kecantikan Alfa Skin Care Palembang</h3>
        <form method="post" action="">
            <div class="mb-3 text-start">
                <label for="username" class="form-label">Username:</label>
                <input type="text" class="form-control" id="username" name="username" required>
            </div>
            <br>
            <div class="mb-3 text-start">
                <label for="password" class="form-label">Password:</label>
                <input type="password" class="form-control" id="password" name="password" required>
            </div>
            <br>
            <button type="submit" class="btn btn-primary w-100">Login</button>
        </form>
        <br>
        <div class="text-center mt-3">
            Belum punya akun? <a href="register.php">Daftar di sini</a>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
    <script>
        // JavaScript untuk menghapus sesi saat tab atau jendela browser ditutup
        window.addEventListener('beforeunload', function () {
            navigator.sendBeacon('logout.php');
        });
    </script>
</body>
</html>
