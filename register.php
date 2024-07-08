<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = $_POST['username'];
    $password = password_hash($_POST['password'], PASSWORD_BCRYPT);
    $nama_lengkap = $_POST['nama_lengkap'];
    $role = $_POST['role']; // Ambil nilai role dari form

    $sql = "INSERT INTO akun_pengguna (username, password, nama_lengkap, role) VALUES (?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $username, $password, $nama_lengkap, $role);

    if ($stmt->execute()) {
        // Registrasi berhasil
        $stmt->close();
        $conn->close();

        // Arahkan ke halaman login.php setelah registrasi berhasil
        header("Location: login.php");
        exit(); // Keluar dari script setelah mengarahkan ke halaman login.php
    } else {
        // Registrasi gagal
        echo "<script>alert('Registrasi gagal! Coba lagi.');</script>";
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Register - Aplikasi Bonus Alfa Skin Care</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
    <style>
        html {
            position: relative;
            min-height: 100%;
        }
        body {
            background: url('assets/img/background5.jpeg') no-repeat center center fixed;
            background-size: cover;
        }
        .container {
            margin-top: 60px;
        }
        .panel {
            animation: fadeIn 1.5s;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-success"> <!-- Ubah panel-primary menjadi panel-success -->
                <div class="panel-heading">
                    <center>
                        <img src="assets/img/logo3.png" alt="Logo" width="150px">
                    </center>
                    <h3 class="panel-title text-center">Register</h3>
                </div>
                <div class="panel-body"> <!-- Tambahkan kelas fadeIn pada panel-body -->
                    <form method="post" action="" class="fadeIn"> <!-- Tambahkan kelas fadeIn pada form -->
                        <div class="form-group">
                            <label for="username">Username:</label>
                            <input type="text" class="form-control" id="username" name="username" required>
                        </div>
                        <div class="form-group">
                            <label for="password">Password:</label>
                            <input type="password" class="form-control" id="password" name="password" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_lengkap">Nama Lengkap:</label>
                            <input type="text" class="form-control" id="nama_lengkap" name="nama_lengkap" required>
                        </div>
                        <div class="form-group">
                            <label for="role">Role:</label>
                            <select class="form-control" id="role" name="role" required>
                                <option value="admin">Admin</option>
                                <option value="karyawan">Karyawan</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-success">Register</button> <!-- Ubah kelas btn-primary menjadi btn-success -->
                    </form>
                </div>
                <div class="panel-footer">
                    Sudah punya akun? <a href="login.php">Login di sini</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
