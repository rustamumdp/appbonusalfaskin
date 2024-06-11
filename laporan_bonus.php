<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$tanggal_mulai = isset($_GET['tanggal_mulai']) ? $_GET['tanggal_mulai'] : '';
$tanggal_selesai = isset($_GET['tanggal_selesai']) ? $_GET['tanggal_selesai'] : '';
$nama_karyawan = isset($_GET['nama_karyawan']) ? $_GET['nama_karyawan'] : '';

$jumlah_treatment = 0;
$total_treatment_all = 0;
$bonus_target = 250000;

if ($tanggal_mulai && $tanggal_selesai) {
    $sql = "SELECT k.nip, k.nama_karyawan, k.id_jabatan, k.status, k.jumlah_anak, 
                   g.tanggal, g.bonus_request, g.bonus_non_request, g.total_treatment
            FROM karyawan k
            JOIN master_gaji g ON k.nip = g.nip
            WHERE g.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";

    if ($nama_karyawan) {
        $sql .= " AND k.nama_karyawan LIKE '%$nama_karyawan%'";
    }

    $result = $conn->query($sql);
    $data = [];
    $monthly_treatments = [];

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            $tanggal = new DateTime($row['tanggal']);
            $month = $tanggal->format('Y-m');

            if (!isset($monthly_treatments[$row['nip']][$month])) {
                $monthly_treatments[$row['nip']][$month] = 0;
            }
            $monthly_treatments[$row['nip']][$month] += $row['total_treatment'];

            $data[] = $row;
        }
    }
}
?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Laporan Bonus Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <style>
        .rekomendasi-container {
            position: relative;
        }
        .rekomendasi-item {
            background-color: beige;
            border: 1px solid #ccc;
            padding: 10px;
            cursor: pointer;
            margin-bottom: 5px;
        }
        .rekomendasi-item:hover {
            background-color: greenyellow;
        }
        .rekomendasi-box {
            position: absolute;
            width: 100%;
            max-height: 150px;
            overflow-y: auto;
            z-index: 1000;
            border: 1px solid #ccc;
            background-color: #fff;
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
                <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
            </ul>
        </div>
    </div>
</nav>
<div class="container">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Laporan Bonus Karyawan</h3>
            </div>
            <div class="panel-body">
                <form method="get" action="laporan_bonus.php">
                    <div class="form-group rekomendasi-container">
                        <label for="nama_karyawan">Nama Karyawan:</label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan">
                        <div id="rekomendasi-nama" class="rekomendasi-box"></div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai:</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai:</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" required>
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<div class="container">
    <div class="col-md-12">
        <div class="panel panel-primary">
            <div class="panel-heading">
                <h3 class="panel-title">Hasil Laporan</h3>
            </div>
            <div class="panel-body">
                <?php if (!empty($data)) : ?>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>NIP</th>
                                <th>Nama Karyawan</th>
                                <th>ID Jabatan</th>
                                <th>Status</th>
                                <th>Jumlah Anak</th>
                                <th>Tanggal</th>
                                <th>Treatment Bonus Request</th>
                                <th>Treatment Bonus Non-Request</th>
                                <th>Total Treatment</th>
                                <th>Bonus (Rp.)</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $total_bonus = 0;
                            foreach ($data as $row) :
                                $nip = $row['nip'];
                                $tanggal = new DateTime($row['tanggal']);
                                $month = $tanggal->format('Y-m');
                                $total_treatment_month = $monthly_treatments[$nip][$month];

                                $bonus = ($total_treatment_month >= 120) ? $bonus_target : 0;
                                $total_bonus += $bonus;
                            ?>
                                <tr>
                                    <td><?php echo $row['nip']; ?></td>
                                    <td><?php echo $row['nama_karyawan']; ?></td>
                                    <td><?php echo $row['id_jabatan']; ?></td>
                                    <td><?php echo $row['status']; ?></td>
                                    <td><?php echo $row['jumlah_anak']; ?></td>
                                    <td><?php echo $row['tanggal']; ?></td>
                                    <td><?php echo $row['bonus_request']; ?></td>
                                    <td><?php echo $row['bonus_non_request']; ?></td>
                                    <td><?php echo $row['total_treatment']; ?></td>
                                    <td><?php echo number_format($bonus, 0, ',', '.'); ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="9" style="text-align:right;"><strong>Total Bonus (Rp.)</strong></td>
                                <td><strong><?php echo number_format($total_bonus, 0, ',', '.'); ?></strong></td>
                            </tr>
                        </tfoot>
                    </table>
                <?php else : ?>
                    <p>Tidak ada data untuk tanggal yang dipilih.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function() {
        $('#nama_karyawan').on('input', function() {
            var nama = $(this).val().toLowerCase();
            var rekomendasi = '';

            <?php foreach ($karyawanList as $karyawan) : ?>
                if ('<?php echo strtolower($karyawan['nama_karyawan']); ?>'.indexOf(nama) !== -1) {
                    rekomendasi += '<div class="rekomendasi-item"><?php echo $karyawan['nama_karyawan']; ?></div>';
                }
            <?php endforeach; ?>

            $('#rekomendasi-nama').html(rekomendasi);

            if (nama === '') {
                $('#rekomendasi-nama').html('');
            }
        });

        $(document).on('click', '.rekomendasi-item', function() {
            var nama = $(this).text();
            $('#nama_karyawan').val(nama);
            $('#rekomendasi-nama').html('');
        });
    });
</script>
</body>
</html>
