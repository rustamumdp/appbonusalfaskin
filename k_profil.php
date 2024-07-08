<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Query untuk mendapatkan informasi pengguna dari database
$sql = "SELECT * FROM akun_pengguna WHERE username = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
} else {
    echo "Data pengguna tidak ditemukan.";
    exit();
}

$stmt->close();
$conn->close();
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
</head>
<body>
<nav class="navbar navbar-default">
    <div class="container-fluid">
        <div class="navbar-header">
            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                <span class="sr-only">Toggle navigation</span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
                <span class="icon-bar"></span>
            </button>
            <a class="navbar-brand" href="#">Aplikasi Perhitungan Bonus Karyawan Alfa Skin Care</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <!-- Menu Items -->
                <li class="<?php echo ($current_page == 'karyawan_home.php') ? 'active' : ''; ?>"><a href="karyawan_home.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="<?php echo ($current_page == 'k_laporan_bonus.php') ? 'active' : ''; ?>"><a href="k_laporan_bonus.php"><i class="fas fa-file-invoice"></i>Lihat Laporan Bonus</a></li>
                <li class="<?php echo ($current_page == 'k_profil.php') ? 'active' : ''; ?>"><a href="k_profil.php"><i class="fas fa-user"></i> Profil</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>

    <div class="container">
        <div class="row">
            <div class="col-md-6 col-md-offset-3">
                <div class="panel panel-default">
                    <div class="panel-heading">
                        <h3 class="panel-title">Profil Pengguna</h3>
                    </div>
                    <div class="panel-body">
                        <!-- <div class="text-center">
                            <img src="path_to_profile_picture/<?php echo $user['profile_picture']; ?>" class="img-circle" alt="Profil">
                        </div> -->
                        <br>
                        <p><strong>Username:</strong> <?php echo $user['username']; ?></p>
                        <p><strong>Nama Lengkap:</strong> <?php echo $user['nama_lengkap']; ?></p>
                        <p><strong>Role:</strong> <?php echo $user['role']; ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
