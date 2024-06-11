<?php
include 'koneksi.php';

// Cari data karyawan
$search_name = isset($_GET['search_name']) ? $_GET['search_name'] : '';
$sql_search = "SELECT k.nip, k.nama_karyawan, j.nama_jabatan, k.nomor_rekening, k.jenis_bank, k.gaji_pokok
               FROM karyawan k
               JOIN jabatan j ON k.id_jabatan = j.id_jabatan";
if ($search_name) {
    $sql_search .= " WHERE k.nama_karyawan LIKE ?";
    $search_name = "%" . $search_name . "%";
}

$stmt_search = $conn->prepare($sql_search);
if ($search_name) {
    $stmt_search->bind_param("s", $search_name);
}
$stmt_search->execute();
$result_search = $stmt_search->get_result();

// Tutup koneksi ke database
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Data Karyawan</title>
    <!-- Bootstrap CSS -->
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
        <h2>Data Karyawan</h2>
        <form method="get" action="">
            <div class="form-group">
                <label for="search_name">Cari Nama Karyawan:</label>
                <input type="text" class="form-control" id="search_name" name="search_name" value="<?php echo htmlspecialchars($search_name); ?>">
            </div>
            <button type="submit" class="btn btn-primary">Cari</button>
        </form>
        <br>
        <?php if ($result_search && $result_search->num_rows > 0): ?>
            <table class="table table-bordered">
                <thead>
                    <tr>
                        <th>NIP</th>
                        <th>Nama Karyawan</th>
                        <th>Nama Jabatan</th>
                        <th>Nomor Rekening</th>
                        <th>Jenis Bank</th>
                        <th>Gaji Pokok</th>
                    </tr>
                </thead>
                <tbody>
                    <?php while($row = $result_search->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $row['nip']; ?></td>
                            <td><?php echo $row['nama_karyawan']; ?></td>
                            <td><?php echo $row['nama_jabatan']; ?></td>
                            <td><?php echo $row['nomor_rekening']; ?></td>
                            <td><?php echo $row['jenis_bank']; ?></td>
                            <td><?php echo $row['gaji_pokok']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
        <?php elseif ($result_search): ?>
            <p class="alert alert-warning">Tidak ada data yang ditemukan.</p>
        <?php endif; ?>
    </div>
    <div class="panel-footer">
        <a href="home.php">Kembali ke Home</a>
    </div>
    <!-- Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
</body>
</html>
