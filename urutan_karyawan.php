<?php
session_start();
include 'koneksi.php';

// Fungsi untuk memilih karyawan untuk transaksi non-request yang akan mendapatkan giliran
function getCurrentTurnNIP($conn) {
    $tanggal = date('Y-m-d'); // Gunakan tanggal hari ini

    // 1. Cek karyawan yang belum menangani transaksi non-request hari ini
    $sql = "
        SELECT k.nip 
        FROM karyawan k 
        LEFT JOIN (
            SELECT nip 
            FROM transaksi_bonus 
            WHERE jenis_facial = 'non_request' 
            AND tanggal = ?
            GROUP BY nip
        ) t 
        ON k.nip = t.nip 
        WHERE t.nip IS NULL 
        ORDER BY k.urutan ASC 
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tanggal);
    $stmt->execute();
    $stmt->bind_result($nip);
    if ($stmt->fetch()) {
        $stmt->close();
        return $nip;
    }
    $stmt->close();

    // 2. Jika semua karyawan sudah menangani transaksi non-request hari ini, pilih yang sudah paling lama tidak menangani
    $sql = "
        SELECT k.nip 
        FROM karyawan k 
        LEFT JOIN (
            SELECT nip, MAX(tanggal) as last_date
            FROM transaksi_bonus 
            WHERE jenis_facial = 'non_request' 
            GROUP BY nip
        ) t 
        ON k.nip = t.nip 
        ORDER BY t.last_date ASC, k.urutan ASC 
        LIMIT 1";

    $stmt = $conn->prepare($sql);
    $stmt->execute();
    $stmt->bind_result($nip);
    $stmt->fetch();
    $stmt->close();

    return $nip;
}

// Dapatkan NIP karyawan yang sedang dalam giliran
$currentTurnNIP = getCurrentTurnNIP($conn);

// Mengambil data karyawan dan mengurutkannya berdasarkan urutan jika ada, jika tidak, urutkan berdasarkan nama
$sql = "SELECT nip, nama_karyawan, urutan FROM karyawan ORDER BY urutan ASC, nama_karyawan ASC"; 
$result = $conn->query($sql);

// Proses pengurutan karyawan saat form disubmit
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['nip']) && is_array($_POST['nip'])) {
        // Inisialisasi array untuk menyimpan urutan baru
        $newOrder = [];
        $allNIPs = $_POST['nip'];
        
        // Memindahkan NIP pertama ke akhir array
        $firstNIP = array_shift($allNIPs);
        array_push($allNIPs, $firstNIP);
        
        // Menyimpan urutan baru ke database
        $stmt = $conn->prepare("UPDATE karyawan SET urutan = ? WHERE nip = ?");
        foreach ($allNIPs as $index => $nip) {
            $urutan = $index + 1; // Urutan dimulai dari 1
            $stmt->bind_param("is", $urutan, $nip);
            $stmt->execute();
        }
        $stmt->close();
        
        // Redirect dengan pesan sukses
        $_SESSION['message'] = "Urutan karyawan berhasil diperbarui.";

        header("Location: " . $_SERVER['PHP_SELF']);
        exit();
    }
}

$current_page = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Urutan Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
    <style>
        .drag-handle {
            cursor: move;
        }
        .current-turn {
            background-color: #d9edf7; /* Biru terang */
            font-weight: bold;
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
    <div class="container">
        <h2>Urutan Karyawan</h2>

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

        <form method="post" action="">
            <table class="table table-striped" id="karyawan-table">
                <thead>
                    <tr>
                        <th>Urutan</th>
                        <th>NIP</th>
                        <th>Nama Karyawan</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    while ($row = $result->fetch_assoc()) {
                        // Tambahkan class "current-turn" jika NIP karyawan saat ini sesuai dengan $currentTurnNIP
                        $class = ($row['nip'] == $currentTurnNIP) ? 'current-turn' : '';
                        echo "<tr class='$class'>";
                        echo "<td class='drag-handle'>" . htmlspecialchars($row["urutan"]) . "</td>";
                        echo "<td><input type='hidden' name='nip[]' value='" . htmlspecialchars($row["nip"]) . "'>" . htmlspecialchars($row["nip"]) . "</td>";
                        echo "<td>" . htmlspecialchars($row["nama_karyawan"]) . "</td>";
                        echo "</tr>";
                    }
                    ?>
                </tbody>
            </table>
            <button type="submit" class="btn btn-primary">Update Urutan</button>
        </form>
    </div>
    <script src="https://code.jquery.com/jquery-3.5.1.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/js/all.min.js"></script>
    <script>
        $(document).ready(function(){
            // Menggunakan jQuery UI untuk mengaktifkan fitur drag and drop pada tabel
            $("#karyawan-table tbody").sortable({
                handle: '.drag-handle',
                placeholder: 'sortable-placeholder',
                update: function(event, ui) {
                    // Perbarui urutan input tersembunyi setelah elemen di drag and drop
                    $('#karyawan-table tbody tr').each(function(index){
                        $(this).find('input[name="nip[]"]').val($(this).index() + 1);
                    });
                }
            }).disableSelection();
        });
    </script>
</body>
</html>
