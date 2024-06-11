<?php
include 'koneksi.php';
session_start();

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip = $_POST['nip'];
    $nama_karyawan = $_POST['nama_karyawan'];
    $id_jabatan = $_POST['id_jabatan'];
    $nomor_rekening = $_POST['nomor_rekening'];
    $jenis_bank = $_POST['jenis_bank'];
    $gaji_pokok = $_POST['gaji_pokok'];

    // Simpan data karyawan
    $sql = "INSERT INTO karyawan (nip, nama_karyawan, id_jabatan, nomor_rekening, jenis_bank, gaji_pokok) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssi", $nip, $nama_karyawan, $id_jabatan, $nomor_rekening, $jenis_bank, $gaji_pokok);

    if ($stmt->execute()) {
        echo "<script>alert('Data karyawan berhasil ditambahkan');</script>";
    } else {
        echo "<script>alert('Gagal menambahkan data karyawan');</script>";
    }

    $stmt->close();
}

// Tutup koneksi ke database
$conn->close();
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Tambah Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
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
        <div class="col-md-6 col-md-offset-3">
            <div class="panel panel-primary">
                <div class="panel-heading">
                    <h3 class="panel-title">Tambah Karyawan</h3>
                </div>
                <div class="panel-body">
                    <form method="post" action="">
                        <div class="form-group">
                            <label for="nip">NIP:</label>
                            <input type="text" class="form-control" id="nip" name="nip" required>
                        </div>
                        <div class="form-group">
                            <label for="nama_karyawan">Nama Karyawan:</label>
                            <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                        </div>
                        <div class="form-group">
                            <label for="id_jabatan">ID Jabatan:</label>
                            <input type="text" class="form-control" id="id_jabatan" name="id_jabatan" required>
                        </div>
                        <div class="form-group">
                            <label for="nomor_rekening">Nomor Rekening:</label>
                            <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" required>
                        </div>
                        <div class="form-group">
                            <label for="jenis_bank">Jenis Bank:</label>
                            <input type="text" class="form-control" id="jenis_bank" name="jenis_bank" required>
                        </div>
                        <div class="form-group">
                            <label for="gaji_pokok">Gaji Pokok:</label>
                            <input type="number" class="form-control" id="gaji_pokok" name="gaji_pokok" required>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
                <div class="panel-footer">
                    <a href="data_karyawan.php"><i class="fas fa-users"></i> Lihat Data Karyawan</a>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
