<?php
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $tanggal = $_POST['tanggal'];

    // Ambil data karyawan sesuai urutan untuk tanggal tertentu
    $sql = "
        SELECT k.nip, k.nama_karyawan
        FROM karyawan k
        JOIN urutan_karyawan uk ON k.nip = uk.nip
        WHERE uk.tanggal = ?
        ORDER BY uk.urutan ASC";

    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $tanggal);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        while ($row = $result->fetch_assoc()) {
            echo "<option value='" . $row['nip'] . "'>" . $row['nama_karyawan'] . "</option>";
        }
    } else {
        echo "<option value=''>Tidak ada karyawan tersedia untuk tanggal ini</option>";
    }

    $stmt->close();
}
?>
