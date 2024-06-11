<?php
session_start();
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nip = $_POST['nip'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $tanggal = $_POST['tanggal'];
    $bonus_request = $_POST['bonus_request'];
    $bonus_non_request = $_POST['bonus_non_request'];
    $total_treatment = $_POST['total_treatment'];

    // Check if the record already exists
    $checkSql = "SELECT * FROM master_gaji WHERE nip = '$nip' AND tanggal = '$tanggal'";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        // Record exists, update it
        $sql = "UPDATE master_gaji 
                SET bonus_request = '$bonus_request', bonus_non_request = '$bonus_non_request', total_treatment = '$total_treatment'
                WHERE nip = '$nip' AND tanggal = '$tanggal'";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Data berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    } else {
        // Record does not exist, insert new record
        $sql = "INSERT INTO master_gaji (nip, tanggal, bonus_request, bonus_non_request, total_treatment)
                VALUES ('$nip', '$tanggal', '$bonus_request', '$bonus_non_request', '$total_treatment')";
        if ($conn->query($sql) === TRUE) {
            $_SESSION['message'] = "Data berhasil disimpan.";
        } else {
            $_SESSION['error'] = "Error: " . $sql . "<br>" . $conn->error;
        }
    }
    header('Location: calculate_bonus.php');
    exit();
}

$sql = "SELECT nip, nama_karyawan FROM karyawan";
$result = $conn->query($sql);
$karyawanList = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $karyawanList[] = $row;
    }
}
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Hitung Bonus Karyawan</title>
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
                <h3 class="panel-title">Hitung Bonus Karyawan</h3>
            </div>
            <div class="panel-body">
                <form method="post" action="calculate_bonus.php">
                    <div class="form-group rekomendasi-container">
                        <label for="nama_karyawan">Nama Karyawan:</label>
                        <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                        <div id="rekomendasi-nama" class="rekomendasi-box"></div>
                    </div>
                    <div class="form-group">
                        <label for="nip">NIP:</label>
                        <input type="text" class="form-control" id="nip" name="nip" readonly required>
                    </div>
                    <div class="form-group">
                        <label for="tanggal">Tanggal:</label>
                        <input type="date" class="form-control" id="tanggal" name="tanggal" required>
                    </div>
                    <div class="form-group">
                        <label for="bonus_request">Treatment Bonus Request:</label>
                        <input type="number" class="form-control" id="bonus_request" name="bonus_request" required>
                    </div>
                    <div class="form-group">
                        <label for="bonus_non_request">Treatment Bonus Non-Request:</label>
                        <input type="number" class="form-control" id="bonus_non_request" name="bonus_non_request" required>
                    </div>
                    <div class="form-group">
                        <label for="total_treatment">Total Treatment:</label>
                        <input type="number" class="form-control" id="total_treatment" name="total_treatment" readonly>
                    </div>
                    <button type="submit" class="btn btn-primary">Simpan</button>
                </form>
            </div>
            <div class="panel-footer">
                <a href="home.php">Kembali ke Home</a>
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
                    rekomendasi += '<div class="rekomendasi-item" data-nip="<?php echo $karyawan['nip']; ?>"><?php echo $karyawan['nama_karyawan']; ?></div>';
                }
            <?php endforeach; ?>

            $('#rekomendasi-nama').html(rekomendasi);

            if (nama === '') {
                $('#rekomendasi-nama').html('');
                $('#nip').val('');
            }
        });

        $(document).on('click', '.rekomendasi-item', function() {
            var nama = $(this).text();
            var nip = $(this).attr('data-nip');
            $('#nama_karyawan').val(nama);
            $('#nip').val(nip);
            $('#rekomendasi-nama').html('');
        });

        $('#bonus_request, #bonus_non_request').on('input', function() {
            var bonusRequest = parseInt($('#bonus_request').val()) || 0;
            var bonusNonRequest = parseInt($('#bonus_non_request').val()) || 0;
            var totalTreatment = bonusRequest + bonusNonRequest;
            $('#total_treatment').val(totalTreatment);
        });
    });
</script>
</body>
</html>
