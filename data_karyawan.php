<?php
include 'koneksi.php';
session_start();

// Proses update data karyawan
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Ambil data dari form
    $nip = trim($_POST['edit_nip']);
    $nama_karyawan = trim($_POST['edit_nama_karyawan']);
    $jabatan = trim($_POST['edit_jabatan']);
    $alamat = trim($_POST['edit_alamat']);
    $nomor_handphone = trim($_POST['edit_nomor_handphone']);
    $nomor_rekening = trim($_POST['edit_nomor_rekening']);
    $jenis_bank = trim($_POST['edit_jenis_bank']);
    $gaji_pokok = trim($_POST['edit_gaji_pokok']);

    // Validasi input (contoh sederhana)
    if (empty($nip) || empty($nama_karyawan) || empty($jabatan) || empty($alamat) || empty($nomor_handphone) || empty($nomor_rekening) || empty($jenis_bank) || empty($gaji_pokok)) {
        echo "<script>alert('Semua field harus diisi.');</script>";
    } else {
        // Update data karyawan di database
        $sql = "UPDATE karyawan SET nama_karyawan=?, jabatan=?, alamat=?, nomor_handphone=?, nomor_rekening=?, jenis_bank=?, gaji_pokok=? WHERE nip=?";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("sssssssi", $nama_karyawan, $jabatan, $alamat, $nomor_handphone, $nomor_rekening, $jenis_bank, $gaji_pokok, $nip);

        if ($stmt->execute()) {
            echo "<script>alert('Data karyawan berhasil diperbarui');</script>";
        } else {
            echo "<script>alert('Gagal memperbarui data karyawan: " . $stmt->error . "');</script>";
        }

        $stmt->close();
    }
}

// Ambil data karyawan untuk ditampilkan
$sql = "SELECT * FROM karyawan";
$result = $conn->query($sql);
$current_page = basename($_SERVER['PHP_SELF']);

?>

<!doctype html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Data Karyawan</title>
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.1.0/css/all.min.css">
    <!-- Link ke file CSS custom Anda -->
    <link rel="stylesheet" href="assets/css/style.css"> <!-- Sesuaikan path dengan lokasi style.css -->
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
    <h2>Data Karyawan</h2>
    <table class="table table-striped">
        <thead>
            <tr>
                <th>NIP</th>
                <th>Nama Karyawan</th>
                <th>Jabatan</th>
                <th>Alamat</th>
                <th>Nomor Handphone</th>
                <th>Nomor Rekening</th>
                <th>Jenis Bank</th>
                <th>Gaji Pokok</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php
            if ($result->num_rows > 0) {
                while ($row = $result->fetch_assoc()) {
                    echo "<tr>";
                    echo "<td>" . htmlspecialchars($row["nip"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nama_karyawan"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["jabatan"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["alamat"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nomor_handphone"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["nomor_rekening"]) . "</td>";
                    echo "<td>" . htmlspecialchars($row["jenis_bank"]) . "</td>";
                    echo "<td>Rp. " . number_format($row["gaji_pokok"], 0, ',', '.') . "</td>";
                    echo "<td><button class='btn btn-primary edit-btn' data-id='" . htmlspecialchars($row["nip"]) . "' data-toggle='modal' data-target='#editModal'>Edit</button></td>";
                    echo "</tr>";
                }
            } else {
                echo "<tr><td colspan='9'>Tidak ada data</td></tr>";
            }
            ?>
        </tbody>
    </table>
</div>

<!-- Modal Edit -->
<div id="editModal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Edit Data Karyawan</h4>
            </div>
            <div class="modal-body">
                <form id="editForm" method="post" action="">
                    <div class="form-group">
                        <label for="edit_nip">NIP:</label>
                        <input type="text" class="form-control" id="edit_nip" name="edit_nip" readonly>
                    </div>
                    <div class="form-group">
                        <label for="edit_nama_karyawan">Nama Karyawan:</label>
                        <input type="text" class="form-control" id="edit_nama_karyawan" name="edit_nama_karyawan" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jabatan">Jabatan:</label>
                        <select class="form-control" id="edit_jabatan" name="edit_jabatan" required>
                            <option value="Beautician">Beautician</option>
                            <option value="HRD">HRD</option>
                            <!-- Tambahkan opsi jabatan lain di sini -->
                        </select>
                    </div>
                    <div class="form-group">
                        <label for="edit_alamat">Alamat:</label>
                        <input type="text" class="form-control" id="edit_alamat" name="edit_alamat" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nomor_handphone">Nomor Handphone:</label>
                        <input type="text" class="form-control" id="edit_nomor_handphone" name="edit_nomor_handphone" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_nomor_rekening">Nomor Rekening:</label>
                        <input type="text" class="form-control" id="edit_nomor_rekening" name="edit_nomor_rekening" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_jenis_bank">Jenis Bank:</label>
                        <input type="text" class="form-control" id="edit_jenis_bank" name="edit_jenis_bank" required>
                    </div>
                    <div class="form-group">
                        <label for="edit_gaji_pokok">Gaji Pokok:</label>
                        <input type="number" class="form-control" id="edit_gaji_pokok" name="edit_gaji_pokok" required>
                    </div>
                    <button type="submit" class="btn btn-success">Simpan Perubahan</button>
                </form>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-1.12.4.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.4.1/js/bootstrap.min.js"></script>
<script>
    $(document).ready(function(){
        // Isi form modal dengan data karyawan yang dipilih
        $('.edit-btn').click(function(){
            var nip = $(this).data('id');
            $.ajax({
                url: 'fetch_employee.php',
                method: 'POST',
                data: {nip: nip},
                dataType: 'json',
                success: function(data){
                    $('#edit_nip').val(data.nip);
                    $('#edit_nama_karyawan').val(data.nama_karyawan);
                    $('#edit_jabatan').val(data.jabatan);
                    $('#edit_alamat').val(data.alamat);
                    $('#edit_nomor_handphone').val(data.nomor_handphone);
                    $('#edit_nomor_rekening').val(data.nomor_rekening);
                    $('#edit_jenis_bank').val(data.jenis_bank);
                    $('#edit_gaji_pokok').val(data.gaji_pokok);
                },
                error: function(xhr, status, error){
                    alert("Gagal mengambil data karyawan: " + error);
                }
            });
        });
    });
</script>
</body>
</html>

<?php
// Tutup koneksi ke database
$conn->close();
?>
