<?php
session_start();
include 'koneksi.php';

// Cek apakah pengguna sudah login
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

// Mendapatkan nilai bonus saat ini dari database
$sql = "SELECT * FROM ketentuan_bonus WHERE id = 1"; 
$result = $conn->query($sql);
$current_bonus = $result->fetch_assoc();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $bonus_request = filter_input(INPUT_POST, 'bonus_request', FILTER_VALIDATE_INT);
    $bonus_non_request = filter_input(INPUT_POST, 'bonus_non_request', FILTER_VALIDATE_INT);

    // Validasi input
    if ($bonus_request === false || $bonus_non_request === false) {
        $_SESSION['error'] = "Nilai bonus harus berupa angka valid.";
    } else {
        // Mengupdate ketentuan bonus di database
        $updateSql = "UPDATE ketentuan_bonus SET bonus_request = ?, bonus_non_request = ? WHERE id = 1";
        $stmt = $conn->prepare($updateSql);
        $stmt->bind_param("ii", $bonus_request, $bonus_non_request);

        if ($stmt->execute()) {
            $_SESSION['message'] = "Ketentuan bonus berhasil diperbarui.";
        } else {
            $_SESSION['error'] = "Terjadi kesalahan saat memperbarui ketentuan bonus: " . $stmt->error;
        }
    }

    header("Location: edit_ketentuan_bonus.php");
    exit();
}
$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Ketentuan Bonus</title>
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
        <h2>Edit Ketentuan Bonus</h2>

        <?php if (isset($_SESSION['message'])): ?>
            <div class="alert alert-success">
                <?php 
                echo $_SESSION['message']; 
                unset($_SESSION['message']); 
                ?>
            </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['error'])): ?>
            <div class="alert alert-danger">
                <?php 
                echo $_SESSION['error']; 
                unset($_SESSION['error']); 
                ?>
            </div>
        <?php endif; ?>

        <form method="post" action="edit_ketentuan_bonus.php">
            <div class="form-group">
                <label for="bonus_request">Jumlah Facial Request:</label>
                <input type="number" class="form-control" id="bonus_request" name="bonus_request" value="<?php echo htmlspecialchars($current_bonus['bonus_request']); ?>" required>
            </div>
            <div class="form-group">
                <label for="bonus_non_request">Jumlah Facial Non-Request:</label>
                <input type="number" class="form-control" id="bonus_non_request" name="bonus_non_request" value="<?php echo htmlspecialchars($current_bonus['bonus_non_request']); ?>" required>
            </div>
            <button type="submit" class="btn btn-primary">Simpan</button>
        </form>
    </div>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
