<?php
// Include koneksi.php untuk mengakses database
include 'koneksi.php';

// Ambil nilai query dari AJAX
$query = $_POST['query'];

// Buat query SQL untuk mencari karyawan berdasarkan nama
$sql = "SELECT nama_karyawan FROM karyawan WHERE nama_karyawan LIKE '%$query%' LIMIT 10";

$result = $conn->query($sql);

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        echo '<div class="rekomendasi-item">' . $row['nama_karyawan'] . '</div>';
    }
} else {
    echo '<div class="rekomendasi-item">Tidak ada hasil</div>';
}

// Tutup koneksi database
$conn->close();
?>
