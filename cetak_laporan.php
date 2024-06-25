<?php
include 'koneksi.php';

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
$sql = "SELECT k.nip, k.nama_karyawan, k.jabatan, g.tanggal, g.bonus_request, g.bonus_non_request
        FROM karyawan k
        JOIN master_gaji g ON k.nip = g.nip";

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
$monthly_gaji_pokok = []; // Array untuk menyimpan gaji pokok per bulan
$total_gaji_keseluruhan = 0; // Variabel untuk menyimpan total gaji keseluruhan

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $tanggal = new DateTime($row['tanggal']);
        $month = $tanggal->format('Y-m');
        $nip = $row['nip'];

        // Inisialisasi gaji pokok bulanan jika belum diatur
        if (!isset($monthly_gaji_pokok[$nip][$month])) {
            // Tentukan gaji pokok berdasarkan jabatan
            $monthly_gaji_pokok[$nip][$month] = isset($gaji_pokok_mapping[$row['jabatan']]) ? $gaji_pokok_mapping[$row['jabatan']] : 0;
        }

        // Menghitung total treatment
        $total_treatment = $row['bonus_request'] + $row['bonus_non_request'];

        // Menghitung bonus request dan non-request
        $bonus_request = $row['bonus_request'] * 20000;
        $bonus_non_request = $row['bonus_non_request'] * 15000;

        // Menghitung total gaji
        $total_gaji = $monthly_gaji_pokok[$nip][$month] + $bonus_request + $bonus_non_request;

        // Menyimpan data dalam array untuk ditampilkan di tabel
        $row['gaji_pokok'] = 'Rp ' . number_format($monthly_gaji_pokok[$nip][$month], 0, ',', '.');
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
    <title>Cetak Laporan Bonus Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <style>
        .table {
            width: 100%;
            margin-bottom: 20px;
            max-width: none;
            border-collapse: collapse !important;
            border-spacing: 0 !important;
            border: 1px solid #ccc;
        }
        .table-bordered th, .table-bordered td {
            border: 1px solid #ccc !important;
        }
        .table th, .table td {
            padding: 8px;
            line-height: 1.42857143;
            vertical-align: top;
            border-top: 1px solid #ddd;
        }
        .text-center {
            text-align: center;
        }
        .text-right {
            text-align: right;
        }
        .logo {
            height: 70px;
        }
    </style>
</head>
<body>
    
    <div class="container">
        <div class="row">
            <div class="col-xs-12 text-center">
                <img src="assets\img\logo3.png" alt="Logo" class="logo">
                <h2>Laporan Bonus Karyawan</h2>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <p><strong>Periode:</strong> <?php echo ($tanggal_mulai && $tanggal_selesai) ? date('d-m-Y', strtotime($tanggal_mulai)) . ' s/d ' . date('d-m-Y', strtotime($tanggal_selesai)) : 'Semua periode'; ?></p>
                <p><strong>Nama Karyawan:</strong> <?php echo $nama_karyawan ?: 'Semua karyawan'; ?></p>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12">
                <table class="table table-bordered">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>NIP</th>
                            <th>Nama Karyawan</th>
                            <th>Jabatan</th>
                            <th>Tanggal</th>
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
                            <td><?php echo $row['nip']; ?></td>
                            <td><?php echo $row['nama_karyawan']; ?></td>
                            <td><?php echo $row['jabatan']; ?></td>
                            <td><?php echo date('d-m-Y', strtotime($row['tanggal'])); ?></td>
                            <td><?php echo $row['gaji_pokok']; ?></td>
                            <td><?php echo $row['bonus_request_amount']; ?></td>
                            <td><?php echo $row['bonus_non_request_amount']; ?></td>
                            <td><?php echo $row['total_treatment']; ?></td>
                            <td><?php echo $row['total_gaji']; ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <th colspan="9" style="text-align: right;">Total Gaji Keseluruhan:</th>
                            <th><?php echo 'Rp ' . number_format($total_gaji_keseluruhan, 0, ',', '.'); ?></th>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
    <script>
window.onload = function() {
    window.print();

    // Setelah pencetakan selesai, kembali ke halaman laporan_bonus.php
    window.onafterprint = function() {
        window.location.href = 'laporan_bonus.php';
    };
};
</script>


</body>
</html>
