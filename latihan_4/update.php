<?php
// Koneksi ke database
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "pgweb_acara8";

$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Koneksi gagal: " . $conn->connect_error);
}

// Ambil data dari form
$kecamatan = $_POST['kecamatan'];
$latitude = $_POST['latitude'];
$longitude = $_POST['longitude'];
$luas = $_POST['luas'];
$jumlah_penduduk = $_POST['jumlah_penduduk'];

// Query untuk memperbarui data
$sql = "UPDATE penduduk SET latitude = ?, longitude = ?, luas = ?, jumlah_penduduk = ? WHERE kecamatan = ?";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ddiss", $latitude, $longitude, $luas, $jumlah_penduduk, $kecamatan);

if ($stmt->execute()) {
    // Jika update berhasil, redirect ke halaman tabel
    header("Location: index.php"); // Ganti 'index.php' dengan nama file halaman tabel Anda
    exit(); // Pastikan script berhenti setelah pengalihan
} else {
    echo "Error updating record: " . $conn->error;
}

// Tutup koneksi
$stmt->close();
$conn->close();
?>