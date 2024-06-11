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
                <!-- Tombol untuk membuka/menutup navbar di layar kecil -->
                <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar" aria-expanded="false" aria-controls="navbar">
                    <!-- Label tombol -->
                    <span class="sr-only">Toggle navigation</span>
                    <!-- Ikon hamburger -->
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </button>
                <!-- Judul navbar -->
                <a class="navbar-brand" href="#">Aplikasi Bonus Alfa Skin Care</a>
            </div>
            <!-- Bagian isi navbar -->
            <div id="navbar" class="navbar-collapse collapse">
                <ul class="nav navbar-nav">
                    <li><a href="profile.php"><span class="glyphicon glyphicon-user"></span> Profil</a></li>
                    <!-- Menu tambah karyawan -->
                    <li><a href="add_employee.php"><i class="fas fa-user-plus"></i> Tambah Karyawan</a></li>
                    <!-- Menu hitung bonus -->
                    <li><a href="calculate_bonus.php"><i class="fas fa-calculator"></i> Hitung Bonus</a></li>
                    <!-- Menu laporan bonus -->
                    <li><a href="laporan_bonus.php"><i class="fas fa-file-invoice"></i> Laporan Bonus</a></li>
                    <!-- Menu data karyawan -->
                    <li><a href="data_karyawan.php"><i class="fas fa-users"></i> Data Karyawan</a></li>
                    <!-- Menu logout -->
                    <li><a href="logout.php"><i class="fas fa-sign-out-alt"></i> Logout</a></li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Konten utama -->
    <div class="container">
        <div class="jumbotron">
            <h1>Selamat Datang di Aplikasi Bonus Alfa Skin Care</h1>
            <p>Silakan pilih menu di atas untuk memulai.</p>
        </div>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
