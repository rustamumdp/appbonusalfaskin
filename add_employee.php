<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Periksa keberadaan dan nilai kunci 'nama_karyawan' di $_POST
    $nip = isset($_POST['nip']) ? $_POST['nip'] : '';
    $nama = isset($_POST['nama_karyawan']) ? $_POST['nama_karyawan'] : '';
    $jabatan = isset($_POST['jabatan']) ? $_POST['jabatan'] : '';
    $alamat = isset($_POST['alamat']) ? $_POST['alamat'] : '';
    $nomor_handphone = isset($_POST['nomor_handphone']) ? $_POST['nomor_handphone'] : '';
    $nomor_rekening = isset($_POST['nomor_rekening']) ? $_POST['nomor_rekening'] : '';
    $jenis_bank = isset($_POST['jenis_bank']) ? $_POST['jenis_bank'] : '';

    // Periksa apakah nama_karyawan tidak boleh kosong
    if (empty($nama)) {
        echo "Nama karyawan tidak boleh kosong.";
        exit; // Stop execution if 'nama_karyawan' is empty
    }

    // Query INSERT
    $sql = "INSERT INTO karyawan (nip, nama_karyawan, jabatan, alamat, nomor_handphone, nomor_rekening, jenis_bank) VALUES (?, ?, ?, ?, ?, ?, ?)";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("sssssss", $nip, $nama, $jabatan, $alamat, $nomor_handphone, $nomor_rekening, $jenis_bank);

    if ($stmt->execute()) {
        echo "Data karyawan berhasil ditambahkan.";
    } else {
        echo "Error: " . $sql . "<br>" . $conn->error;
    }

    $stmt->close();
}

$conn->close();
// Ambil nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);
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
            <a class="navbar-brand" href="#">Aplikasi Perhitungan Bonus Karyawan Alfa Skin Care</a>
        </div>
        <div id="navbar" class="navbar-collapse collapse">
            <ul class="nav navbar-nav">
                <li class="<?php echo ($current_page == 'home.php') ? 'active' : ''; ?>"><a href="home.php"><i class="fas fa-home"></i> Home</a></li>
                <li class="<?php echo ($current_page == 'transaksi_bonus.php') ? 'active' : ''; ?>"><a href="transaksi_bonus.php"><i class="fas fa-calculator"></i> Transaksi Bonus</a></li>
                <li class="<?php echo ($current_page == 'calculate_bonus.php') ? 'active' : ''; ?>"><a href="calculate_bonus.php"><i class="fas fa-calculator"></i> Hitung Bonus</a></li>
                <li class="<?php echo ($current_page == 'urutan_karyawan.php') ? 'active' : ''; ?>"><a href="urutan_karyawan.php"><i class="fas fa-sort-numeric-down"></i> Urutan Karyawan</a></li>
                <li class="<?php echo ($current_page == 'edit_ketentuan_bonus.php') ? 'active' : ''; ?>"><a href="edit_ketentuan_bonus.php"><i class="fas fa-cog"></i> Edit Ketentuan Bonus</a></li>
                <li class="<?php echo ($current_page == 'add_employee.php') ? 'active' : ''; ?>"><a href="add_employee.php"><i class="fas fa-user-plus"></i> Tambah Karyawan</a></li>
                <li class="<?php echo ($current_page == 'data_karyawan.php') ? 'active' : ''; ?>"><a href="data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                <li class="<?php echo ($current_page == 'laporan_bonus.php') ? 'active' : ''; ?>"><a href="laporan_bonus.php"><i class="fas fa-file-invoice"></i> Laporan Bonus</a></li>
                <li class="<?php echo ($current_page == 'profile.php') ? 'active' : ''; ?>"><a href="profile.php"><i class="fas fa-user"></i> Profil</a></li>
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
            <form method="post" action="add_employee.php">
            <div class="form-group">
                <label for="nip">NIP:</label>
                <input type="text" class="form-control" id="nip" name="nip" required>
            </div>
            <div class="form-group">
                <label for="nama_karyawan">Nama Karyawan:</label>
                <input type="text" class="form-control" id="nama_karyawan" name="nama_karyawan" required>
            </div>
            <div class="form-group">
                <label for="jabatan">Jabatan:</label>
                <select class="form-control" id="jabatan" name="jabatan" required>
                    <option value="">Pilih Jabatan</option>
                    <option value="Beautician">Beautician</option>
                    <option value="HRD">HRD</option>
                </select>
                </div>
                <div class="form-group">
                    <label for="alamat">Alamat:</label>
                    <input type="text" class="form-control" id="alamat" name="alamat" required>
                 </div>
                <div class="form-group">
                    <label for="nomor_handphone">Nomor Handphone:</label>
                    <input type="text" class="form-control" id="nomor_handphone" name="nomor_handphone" required>
                </div>
                <div class="form-group">
                <label for="nomor_rekening">Nomor Rekening:</label>
                <input type="text" class="form-control" id="nomor_rekening" name="nomor_rekening" required>
                </div>
                <div class="form-group">
                      <label for="jenis_bank">Jenis Bank:</label>
                      <input type="text" class="form-control" id="jenis_bank" name="jenis_bank" required>
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
