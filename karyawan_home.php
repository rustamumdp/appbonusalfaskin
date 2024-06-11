<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] != 'karyawan') {
    header("Location: login.php");
    exit();
}

$username = $_SESSION['username'];

$sql = "SELECT k.nip, k.nama_karyawan, j.nama_jabatan, SUM(g.bonus_request + g.bonus_non_request) as total_bonus
        FROM karyawan k
        JOIN jabatan j ON k.id_jabatan = j.id_jabatan
        JOIN master_gaji g ON k.nip = g.nip
        WHERE k.nip = ?
        GROUP BY k.nip, k.nama_karyawan, j.nama_jabatan";
$stmt = $conn->prepare($sql);
$stmt->bind_param("s", $username);
$stmt->execute();
$result = $stmt->get_result();
$karyawan = $result->fetch_assoc();

$stmt->close();
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Home Karyawan</title>
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
    <div class="container mt-5">
        <div class="card">
            <div class="card-body">
                <h2 class="card-title">Selamat datang, <?php echo isset($karyawan['nama_karyawan']) ? $karyawan['nama_karyawan'] : 'User'; ?>!</h2>
                <p class="card-text">Jabatan: <?php echo isset($karyawan['nama_jabatan']) ? $karyawan['nama_jabatan'] : 'Tidak ditemukan'; ?></p>
                <p class="card-text">Total Bonus: Rp <?php echo isset($karyawan['total_bonus']) ? number_format($karyawan['total_bonus'], 2) : '0.00'; ?></p>
                <a href="logout.php" class="btn btn-primary">Logout</a>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.bundle.min.js"></script>
</body>
</html>
