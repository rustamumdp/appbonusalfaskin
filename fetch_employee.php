<?php
include 'koneksi.php';

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $nip = $_POST['nip'];

    // Fetch data karyawan termasuk gaji pokok berdasarkan NIP
    $sql = "SELECT nip, nama_karyawan, jabatan, alamat, nomor_handphone, nomor_rekening, jenis_bank, gaji_pokok FROM karyawan WHERE nip = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $nip);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $data = $result->fetch_assoc();
        echo json_encode($data);
    } else {
        echo json_encode([]);
    }

    $stmt->close();
}

// Tutup koneksi ke database
$conn->close();
?>
