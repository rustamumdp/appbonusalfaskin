<?php
session_start();
include 'koneksi.php';

// Cek apakah koneksi database berhasil
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil ketentuan bonus dari database
$sql_bonus = "SELECT bonus_request, bonus_non_request FROM ketentuan_bonus WHERE id = 1";
$result_bonus = $conn->query($sql_bonus);
$ketentuan_bonus = $result_bonus ? $result_bonus->fetch_assoc() : ['bonus_request' => 0, 'bonus_non_request' => 0];

// Ambil data karyawan jika ada parameter NIP dan tanggal
$nip = filter_input(INPUT_GET, 'nip', FILTER_SANITIZE_STRING);
$tanggal_dari = filter_input(INPUT_GET, 'tanggal_dari', FILTER_SANITIZE_STRING);
$tanggal_sampai = filter_input(INPUT_GET, 'tanggal_sampai', FILTER_SANITIZE_STRING);
$karyawan = null;
$transaksi = null;
$gaji_pokok = null;

if ($nip && $tanggal_dari && $tanggal_sampai) {
    // Persiapkan dan eksekusi statement untuk mengambil data karyawan
    $sql_karyawan = "SELECT nip, nama_karyawan, gaji_pokok FROM karyawan WHERE nip = ?";
    if ($stmt_karyawan = $conn->prepare($sql_karyawan)) {
        $stmt_karyawan->bind_param("s", $nip);
        $stmt_karyawan->execute();
        $result_karyawan = $stmt_karyawan->get_result();
        $karyawan = $result_karyawan->fetch_assoc();
        $stmt_karyawan->close();
    }

    // // Ambil gaji pokok dari tabel karyawan
    // $sql_gaji_pokok = "SELECT gaji_pokok FROM karyawan WHERE nip = ?";
    // if ($stmt_gaji_pokok = $conn->prepare($sql_gaji_pokok)) {
    //     $stmt_gaji_pokok->bind_param("s", $nip);
    //     $stmt_gaji_pokok->execute();
    //     $result_gaji_pokok = $stmt_gaji_pokok->get_result();
    //     $gaji_pokok = $result_gaji_pokok->fetch_assoc()['gaji_pokok'];
    //     $stmt_gaji_pokok->close();
    // }
    // Ambil gaji pokok dari tabel karyawan
$sql_gaji_pokok = "SELECT gaji_pokok FROM karyawan WHERE nip = ?";
if ($stmt_gaji_pokok = $conn->prepare($sql_gaji_pokok)) {
    $stmt_gaji_pokok->bind_param("s", $nip);
    $stmt_gaji_pokok->execute();
    $result_gaji_pokok = $stmt_gaji_pokok->get_result();
    $gaji_pokok_row = $result_gaji_pokok->fetch_assoc();
    $gaji_pokok = $gaji_pokok_row ? $gaji_pokok_row['gaji_pokok'] : 0; // Pengecekan disini
    $stmt_gaji_pokok->close();
} else {
    $gaji_pokok = 0; // Default jika statement gagal
}


    // Persiapkan dan eksekusi statement untuk mengambil data transaksi bonus
    $sql_transaksi = "SELECT 
                        tanggal,
                        nama_pasien,
                        SUM(CASE WHEN jenis_facial = 'request' THEN 1 ELSE 0 END) AS bonus_request,
                        SUM(CASE WHEN jenis_facial = 'non_request' THEN 1 ELSE 0 END) AS bonus_non_request
                      FROM transaksi_bonus
                      WHERE nip = ? AND tanggal BETWEEN ? AND ?
                      GROUP BY tanggal, nama_pasien";
    if ($stmt_transaksi = $conn->prepare($sql_transaksi)) {
        $stmt_transaksi->bind_param("sss", $nip, $tanggal_dari, $tanggal_sampai);
        $stmt_transaksi->execute();
        $result_transaksi = $stmt_transaksi->get_result();
        $transaksi = [];
        while ($row = $result_transaksi->fetch_assoc()) {
            $row['total_facial'] = $row['bonus_request'] + $row['bonus_non_request']; // Hitung total facial
            $transaksi[] = $row;
        }
        $stmt_transaksi->close();
    }
}

// Ambil daftar semua karyawan
$sql = "SELECT nip, nama_karyawan FROM karyawan";
$result = $conn->query($sql);
$karyawanList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $karyawanList[] = $row;
    }
}
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!doctype html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hitung Bonus Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
    <style>
        @media print {
            body * {
                visibility: hidden;
            }
            #printableArea, #printableArea * {
                visibility: visible;
            }
            #printableArea {
                position: absolute;
                left: 0;
                top: 0;
            }
            .navbar, .btn {
                display: none !important;
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
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Lihat Laporan Bonus Karyawan</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="">
                    <div class="form-group">
                        <label for="nip">Nama Karyawan:</label>
                        <select class="form-control" id="nip" name="nip" required>
                            <option value="">Pilih Karyawan</option>
                            <?php foreach ($karyawanList as $k): ?>
                                <option value="<?php echo $k['nip']; ?>" <?php echo ($k['nip'] == $nip) ? 'selected' : ''; ?>>
                                    <?php echo $k['nama_karyawan']; ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_dari">Dari Tanggal:</label>
                        <input type="date" class="form-control" id="tanggal_dari" name="tanggal_dari" value="<?php echo htmlspecialchars($tanggal_dari); ?>" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_sampai">Sampai Tanggal:</label>
                        <input type="date" class="form-control" id="tanggal_sampai" name="tanggal_sampai" value="<?php echo htmlspecialchars($tanggal_sampai); ?>" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan</button>
                    <a href="k_laporan_bonus.php" class="btn btn-default">Reset</a>
                   
                </form>
            </div>
        </div>

        <div id="printableArea" class="panel panel-info">
            <div class="panel-heading">
                <h3 class="panel-title">Hasil Perhitungan Bonus</h3>
            </div>
            <div class="panel-body">
                <?php if ($karyawan && $transaksi): ?>
                <h3>Bonus untuk <?php echo htmlspecialchars($karyawan['nama_karyawan']); ?> (NIP: <?php echo htmlspecialchars($karyawan['nip']); ?>)</h3>
                <h3>Periode: dari <?php echo htmlspecialchars($tanggal_dari); ?> sampai <?php echo htmlspecialchars($tanggal_sampai); ?></h3>
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>Tanggal</th>
                            <th>Nama Pasien</th>
                            <th>Facial Request</th>
                            <th>Facial Non-Request</th>
                            <th>Total Facial</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $total_facial_request = 0;
                        $total_facial_non_request = 0;
                        foreach ($transaksi as $data) {
                            $facial_request = $data['bonus_request'];
                            $facial_non_request = $data['bonus_non_request'];
                            $total_facial_request += $facial_request;
                            $total_facial_non_request += $facial_non_request;
                            
                            // Konversi format tanggal dari Y-m-d H:i:s ke format yang diinginkan
                            $tanggal = new DateTime($data['tanggal']);
                            $tanggal_formatted = $tanggal->format('d-m-Y');
                            
                            echo "<tr>";
                            echo "<td>" . htmlspecialchars($tanggal_formatted) . "</td>";
                            echo "<td>" . htmlspecialchars($data['nama_pasien']) . "</td>";
                            echo "<td>" . htmlspecialchars($facial_request) . "</td>";
                            echo "<td>" . htmlspecialchars($facial_non_request) . "</td>";
                            echo "<td>" . htmlspecialchars($data['total_facial']) . "</td>";
                            echo "</tr>";
                        }
                        ?>
                        <tr>
                            <td colspan="2"><strong>Total Facial</strong></td>
                            <td><strong><?php echo $total_facial_request; ?></strong></td>
                            <td><strong><?php echo $total_facial_non_request; ?></strong></td>
                            <td><strong><?php echo $total_facial_request + $total_facial_non_request; ?></strong></td>
                        </tr>
                    </tbody>
                </table>
                <h4>Total Bonus:</h4>
                <p>Facial Request: <?php echo $total_facial_request; ?> x Rp<?php echo number_format($ketentuan_bonus['bonus_request']); ?> = Rp<?php echo number_format($total_facial_request * $ketentuan_bonus['bonus_request']); ?></p>
                <p>Facial Non-Request: <?php echo $total_facial_non_request; ?> x Rp<?php echo number_format($ketentuan_bonus['bonus_non_request']); ?> = Rp<?php echo number_format($total_facial_non_request * $ketentuan_bonus['bonus_non_request']); ?></p>
                <p><strong>Total Bonus Keseluruhan: Rp<?php echo number_format(($total_facial_request * $ketentuan_bonus['bonus_request']) + ($total_facial_non_request * $ketentuan_bonus['bonus_non_request'])); ?></strong></p>
                <p><strong>Gaji Pokok: Rp<?php echo number_format($gaji_pokok); ?></strong></p>
                <p><strong>Total Gaji dan Bonus: Rp<?php echo number_format($gaji_pokok + ($total_facial_request * $ketentuan_bonus['bonus_request']) + ($total_facial_non_request * $ketentuan_bonus['bonus_non_request'])); ?></strong></p>
                <?php else: ?>
                <p>Silakan pilih nama karyawan dan rentang tanggal untuk melihat bonus.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>


<?php
// Tutup koneksi
$conn->close();
?>
