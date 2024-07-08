<?php
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
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
    <!-- CSS custom -->
    
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
