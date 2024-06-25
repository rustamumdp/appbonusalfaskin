<?php
session_start();
include 'koneksi.php';

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

// Query untuk mendapatkan informasi pengguna dari database
$sql = "SELECT * FROM admin WHERE username = ?";
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
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
</head>
<body>
    <nav class="navbar navbar-default">
        <div class="container-fluid">
            <div class="navbar-header">
                <a class="navbar-brand" href="#">Aplikasi Perhitungan Bonus Karyawan Alfa Skin Care</a>
            </div>
                <ul class="nav navbar-nav navbar-right">
                <li class="active"><a href="karyawan_home.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="active"><a href="k_profile.php"><span class="glyphicon glyphicon-user"></span> Profil</a></li>
                <li class="active"><a href="logout.php"><span class="glyphicon glyphicon-log-out"></span> Logout</a></li>
            </ul>
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
