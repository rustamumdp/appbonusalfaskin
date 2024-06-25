<?php
// Ambil nama file saat ini
$current_page = basename($_SERVER['PHP_SELF']);
?>


<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Home</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Font Awesome CSS -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <!-- CSS custom -->
    <style>
        /* Membuat menu navbar tidak muncul di layar kecil */
        .navbar-nav > li {
            display: none;
        }
        /* Membuat menu navbar muncul di layar lebih besar dari 768px */
        @media (min-width: 768px) {
            .navbar-nav > li {
                display: block;
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
    <!-- Konten utama -->
    <div class="container">
    <div class="jumbotron" style="background-color: #f8f9fa;">
        <h1 class="display-4" style="font-size: 2.5rem;">Selamat Datang di Aplikasi Perhitungan Bonus Karyawan</h1>
        <p class="lead" style="font-size: 1.25rem;">Klinik Kecantikan Alfa Skin Care Palembang</p>
        <hr class="my-4">
        <p style="font-size: 1.1rem;">Silakan pilih menu di atas untuk memulai.</p>
    </div>
</div>

    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
