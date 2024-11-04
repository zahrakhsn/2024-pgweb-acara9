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

// Ambil kecamatan dari URL
$kecamatan = $_GET['kecamatan'];

// Query untuk hapus data
$sql = "DELETE FROM penduduk WHERE kecamatan='$kecamatan'";

if ($conn->query($sql) === TRUE) {
    echo "Data berhasil dihapus";
    header("Location: index.php");
} else {
    echo "Error: " . $sql . "<br>" . $conn->error;
}

$conn->close();
?>