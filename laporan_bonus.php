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

// Mapping gaji pokok berdasarkan jabatan
$gaji_pokok_mapping = [
    'Beautician' => 1500000,
    'HRD' => 5000000,
    'Customer Service' => 1500000
];

// Query untuk mengambil data dari database
$sql = "SELECT k.nip, k.nama_karyawan, k.jabatan, g.tanggal, g.bonus_request, g.bonus_non_request, t.nama_pasien
        FROM karyawan k
        JOIN master_gaji g ON k.nip = g.nip
        JOIN transaksi_bonus t ON t.nip = k.nip";

// Menambahkan kondisi query jika ada filter
$conditions = [];
if ($tanggal_mulai && $tanggal_selesai) {
    $conditions[] = "g.tanggal BETWEEN '$tanggal_mulai' AND '$tanggal_selesai'";
}
if ($nama_karyawan) {
    $conditions[] = "k.nama_karyawan LIKE '%$nama_karyawan%'";
}

if (count($conditions) > 0) {
    $sql .= " WHERE " . implode(" AND ", $conditions);
}

$result = $conn->query($sql);
$data = [];
$total_gaji_keseluruhan = 0; // Variabel untuk menyimpan total gaji keseluruhan

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $nip = $row['nip'];
        $tanggal = new DateTime($row['tanggal']);
        $month = $tanggal->format('Y-m');

        // Hitung gaji pokok berdasarkan jabatan
        $gaji_pokok = isset($gaji_pokok_mapping[$row['jabatan']]) ? $gaji_pokok_mapping[$row['jabatan']] : 0;

        // Menghitung total treatment
        $total_treatment = $row['bonus_request'] + $row['bonus_non_request'];

        // Menghitung bonus request dan non-request
        $bonus_request = $row['bonus_request'] * 20000;
        $bonus_non_request = $row['bonus_non_request'] * 15000;

        // Menghitung total gaji
        $total_gaji = $gaji_pokok + $bonus_request + $bonus_non_request;

        // Menyimpan data dalam array untuk ditampilkan di tabel
        $row['gaji_pokok_formatted'] = 'Rp ' . number_format($gaji_pokok, 0, ',', '.');
        $row['bonus_request_amount'] = 'Rp ' . number_format($bonus_request, 0, ',', '.');
        $row['bonus_non_request_amount'] = 'Rp ' . number_format($bonus_non_request, 0, ',', '.');
        $row['total_treatment'] = $total_treatment;
        $row['total_gaji'] = 'Rp ' . number_format($total_gaji, 0, ',', '.');

        // Tambahkan ke array data
        $data[] = $row;

        // Akumulasi total gaji keseluruhan
        $total_gaji_keseluruhan += $total_gaji;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
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
        @media print {
            .page-header {
                text-align: center;
                margin-bottom: 20px;
            }
            .page-header img {
                height: 50px;
                vertical-align: middle;
                margin-right: 10px;
            }
            .page-header h1 {
                display: inline-block;
                font-size: 24px;
                margin: 0;
                vertical-align: middle;
            }
            .panel-body {
                margin-bottom: 20px;
            }
            .btn {
                display: none;
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
                    <li><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                    <li><a href="transaksi_bonus.php"><i class="fas fa-calculator"></i> Transaksi Bonus</a></li>
                    <li><a href="calculate_bonus.php"><i class="fas fa-calculator"></i> Hitung Bonus</a></li>
                    <li><a href="urutan_karyawan.php"><i class="fas fa-sort-numeric-down"></i> Urutan Karyawan</a></li>
                    <li><a href="edit_ketentuan_bonus.php"><i class="fas fa-cog"></i> Edit Ketentuan Bonus</a></li>
                    <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Tambah Karyawan</a></li>
                    <li><a href="data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                    <li class="active"><a href="laporan_bonus.php"><i class="fas fa-file-invoice"></i> Laporan Bonus</a></li>
                    <li><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
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
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" value="<?php echo htmlspecialchars($nama_karyawan); ?>">
                        <div id="rekomendasi-nama" class="rekomendasi-box"></div>
                    </div>
                    <div class="form-group">
                        <label for="tanggal_mulai">Tanggal Mulai:</label>
                        <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" value="<?php echo htmlspecialchars($tanggal_mulai); ?>">
                    </div>
                    <div class="form-group">
                        <label for="tanggal_selesai">Tanggal Selesai:</label>
                        <input type="date" class="form-control" id="tanggal_selesai" name="tanggal_selesai" value="<?php echo htmlspecialchars($tanggal_selesai); ?>">
                    </div>
                    <button type="submit" class="btn btn-primary">Tampilkan Laporan</button>
                    <a href="cetak_laporan.php?nama_karyawan=<?php echo urlencode($nama_karyawan); ?>&tanggal_mulai=<?php echo $tanggal_mulai; ?>&tanggal_selesai=<?php echo $tanggal_selesai; ?>" class="btn btn-success" id="btnCetakLaporan">Cetak Laporan</a>
                </form>
            </div>
            <?php if ($result->num_rows > 0): ?>
                <div class="panel-body">
                    <h4>Hasil Laporan:</h4>
                    <div class="table-responsive">
                    <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIP</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
                            <th>Nama Pasien</th>
                            <th>Gaji Pokok</th>
                            <th>Bonus Request</th>
                            <th>Bonus Non-Request</th>
                            <th>Total Treatment</th>
                            <th>Total Gaji</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $no = 1;
                        foreach ($data as $row):
                        ?>
                        <tr>
                            <td><?php echo $no++; ?></td>
                            <td><?php echo htmlspecialchars($row['nip']); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_karyawan']); ?></td>
                            <td><?php echo htmlspecialchars($row['jabatan']); ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo htmlspecialchars($row['nama_pasien']); ?></td>
                            <td><?php echo $row['gaji_pokok_formatted']; ?></td>
                            <td><?php echo $row['bonus_request_amount']; ?></td>
                            <td><?php echo $row['bonus_non_request_amount']; ?></td>
                            <td><?php echo $row['total_treatment']; ?></td>
                            <td><?php echo $row['total_gaji']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="10" style="text-align: right;">Total Gaji Keseluruhan:</th>
                            <th><?php echo 'Rp ' . number_format($total_gaji_keseluruhan, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
                    </div>
                </div>
            <?php else: ?>
                <div class="panel-body">
                    <p>Tidak ada data yang sesuai dengan filter yang diberikan.</p>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
    $(document).ready(function() {
        $('#nama_karyawan').keyup(function() {
            var query = $(this).val();
            if (query != '') {
                $.ajax({
                    url: "search.php",
                    method: "POST",
                    data: {
                        query: query
                    },
                    success: function(data) {
                        $('#rekomendasi-nama').fadeIn();
                        $('#rekomendasi-nama').html(data);
                    }
                });
            } else {
                $('#rekomendasi-nama').fadeOut();
            }
        });

        $(document).on('click', '.rekomendasi-item', function() {
            $('#nama_karyawan').val($(this).text());
            $('#rekomendasi-nama').fadeOut();
        });
    });
</script>
<script>
document.getElementById('btnCetakLaporan').addEventListener('click', function(event) {
    event.preventDefault();
    var url = this.getAttribute('href');
    window.open(url, '_blank');
});
</script>

</body>
</html>
