<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$tahun = isset($_GET['tahun']) ? $_GET['tahun'] : date('Y');
$bulan = isset($_GET['bulan']) ? $_GET['bulan'] : date('m');

$tanggal_mulai = $tahun . '-' . $bulan . '-01';
$tanggal_selesai = date("Y-m-t", strtotime($tanggal_mulai));

$sql = "SELECT k.nip, k.nama_karyawan, k.id_jabatan, k.status, k.jumlah_anak, 
               g.tanggal, g.bonus_request, g.bonus_non_request
        FROM karyawan k
        JOIN master_gaji g ON k.nip = g.nip
        WHERE g.tanggal BETWEEN ? AND ?
        ORDER BY g.tanggal";

$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $tanggal_mulai, $tanggal_selesai);
$stmt->execute();
$result = $stmt->get_result();

$total_treatment_request = 0;
$total_treatment_non_request = 0;

while ($row = $result->fetch_assoc()) {
    $total_treatment_request += $row['bonus_request'];
    $total_treatment_non_request += $row['bonus_non_request'];
}

$total_treatment = $total_treatment_request + $total_treatment_non_request;
$bonus_target = ($total_treatment >= 20) ? 100000 : 0;

$conn->close();
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bonus Bulanan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            .print-container, .print-container * {
                visibility: visible;
            }
            .print-container {
                position: absolute;
                left: 0;
                top: 0;
                width: 100%;
            }
        }
    </style>
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
            <a class="navbar-brand" href="#">Aplikasi Bonus Alfa Skin Care</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Tambah Karyawan</a></li>
                <li><a href="data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                <li><a href="calculate_bonus.php"><i class="fas fa-calculator"></i> Hitung Bonus</a></li>
                <li><a href="laporan_bonus.php"><i class="fas fa-file-invoice"></i> Laporan Bonus</a></li>
                <li><a href="laporan_bonus_bulanan.php"><i class="fas fa-file-invoice"></i> Laporan Bonus Bulanan</a></li>
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Laporan Bonus Bulanan</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="">
                    <div class="form-group">
                        <label for="tahun">Tahun:</label>
                        <input type="number" class="form-control" id="tahun" name="tahun" value="<?php echo htmlspecialchars($tahun); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="bulan">Bulan:</label>
                        <input type="number" class="form-control" id="bulan" name="bulan" value="<?php echo htmlspecialchars($bulan); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Cari</button>
                </form>
                <br>
                <div class="print-container">
                    <h4>Total Treatment: <?php echo number_format($total_treatment, 0, ',', '.'); ?></h4>
                    <?php if ($total_treatment >= 20): ?>
                        <h4>Bonus Target: <?php echo number_format($bonus_target, 0, ',', '.'); ?></h4>
                    <?php endif; ?>
                </div>
                <button class="btn btn-default" onclick="window.print()">Print Laporan</button>
            </div>
            <div class="panel-footer">
                <a href="home.php">Kembali ke Home</a>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
