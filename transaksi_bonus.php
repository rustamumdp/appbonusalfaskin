<?php
include 'koneksi.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $nama_pasien = $_POST['nama_pasien'];
    $jenis_facial = $_POST['jenis_facial'];
    $tanggal = $_POST['tanggal'];
    $nip = null;

    // Validasi input
    if (empty($nama_pasien) || empty($tanggal)) {
        echo "<script>alert('Harap isi semua kolom.');</script>";
    } else {
        if ($jenis_facial == 'request') {
            $nip = $_POST['nama_karyawan'];
            if (empty($nip)) {
                echo "<script>alert('Harap pilih karyawan untuk facial request.');</script>";
            }
        } else {
            // Untuk facial "non-request", pilih karyawan sesuai logika yang telah ditentukan
            $nip = pilihKaryawanNonRequest($conn, $tanggal);
        }

        // Proses penyimpanan transaksi ke database
        if (!empty($nip)) {
            $sql = "INSERT INTO transaksi_bonus (nama_pasien, jenis_facial, tanggal, nip) VALUES (?, ?, ?, ?)";
            $stmt = $conn->prepare($sql);
            $stmt->bind_param("sssi", $nama_pasien, $jenis_facial, $tanggal, $nip);

            if ($stmt->execute()) {
                echo "<script>alert('Transaksi berhasil disimpan.'); window.location.href = 'transaksi_bonus.php';</script>";
            } else {
                echo "<script>alert('Gagal menyimpan transaksi.');</script>";
            }
        }
    }
}

// Fungsi untuk memilih karyawan untuk transaksi non-request
function pilihKaryawanNonRequest($conn, $tanggal) {
    // 1. Cek karyawan yang belum menangani transaksi non-request hari ini
    $sql = "
        SELECT k.nip 
        FROM karyawan k 
        LEFT JOIN transaksi_bonus t 
        ON k.nip = t.nip 
        AND t.jenis_facial = 'non_request' 
        AND t.tanggal = ?
        WHERE t.nip IS NULL 
        ORDER BY k.urutan ASC 
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tanggal);
    $stmt->execute();
    $stmt->bind_result($nip);
    if ($stmt->fetch()) {
        return $nip;
    }
    $stmt->close();

    // 2. Jika semua karyawan sudah menangani transaksi non-request hari ini, pilih yang sudah paling lama tidak menangani
    $sql = "
        SELECT k.nip 
        FROM karyawan k 
        LEFT JOIN transaksi_bonus t 
        ON k.nip = t.nip 
        AND t.jenis_facial = 'non_request' 
        ORDER BY MAX(t.tanggal) ASC, k.urutan ASC 
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($nip);
    $stmt->fetch();
    $stmt->close();

    return $nip;
}

// Ambil data karyawan untuk form request
$sql_karyawan = "SELECT nip, nama_karyawan FROM karyawan"; // Sesuaikan dengan tabel yang benar
$result_karyawan = $conn->query($sql_karyawan);
$current_page = basename($_SERVER['PHP_SELF']);
?>

<!DOCTYPE html>
<html>
<head>
    <title>Transaksi Bonus</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
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
        <h2>Transaksi Bonus</h2>
        <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <div class="form-group">
                <label for="nama_pasien">Nama Pasien:</label>
                <input type="text" class="form-control" id="nama_pasien" name="nama_pasien" required>
            </div>
            <div class="form-group">
                <label for="jenis_facial">Jenis Facial:</label>
                <select class="form-control" id="jenis_facial" name="jenis_facial" required>
                    <option value="" disabled selected>Pilih</option>
                    <option value="request">Facial Request</option>
                    <option value="non_request">Facial Non-Request</option>
                </select>
            </div>

            <div class="form-group">
                <label for="nama_karyawan">Nama Karyawan:</label>
                <select class="form-control" id="nama_karyawan" name="nama_karyawan" required>
                    <option value="" disabled selected>Pilih</option>
                    <?php
                    // Tampilkan pilihan karyawan dari hasil query
                    if ($result_karyawan->num_rows > 0) {
                        while ($row = $result_karyawan->fetch_assoc()) {
                            echo "<option value='" . $row['nip'] . "'>" . $row['nama_karyawan'] . "</option>";
                        }
                    }
                    ?>
                </select>
            </div>

            <div class="form-group">
                <label for="tanggal">Tanggal Transaksi:</label>
                <input type="date" class="form-control" id="tanggal" name="tanggal" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan Transaksi</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script>
        $(document).ready(function() {
            // Set tanggal saat ini secara otomatis
            var now = new Date();
            var day = ("0" + now.getDate()).slice(-2);
            var month = ("0" + (now.getMonth() + 1)).slice(-2);
            var nowFormatted = now.getFullYear() + "-" + month + "-" + day;
            $('#tanggal').val(nowFormatted);

            $('#jenis_facial').change(function() {
                if ($(this).val() == 'non_request') {
                    // Untuk non-request, karyawan dipilih otomatis
                    $('#nama_karyawan').attr('disabled', true);
                    var nipNonRequest = <?php echo json_encode(pilihKaryawanNonRequest($conn, date('Y-m-d'))); ?>;
                    $('#nama_karyawan').val(nipNonRequest);
                } else {
                    // Untuk request, karyawan dipilih oleh user
                    $('#nama_karyawan').attr('disabled', false);
                }
            });

            // Trigger change untuk inisialisasi
            $('#jenis_facial').trigger('change');
        });
    </script>
</body>
</html>
