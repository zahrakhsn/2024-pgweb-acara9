<?php
if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['kecamatan'])) {
    $kecamatan = $_GET['kecamatan'];

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pgweb_acara8"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    $stmt = $conn->prepare("SELECT * FROM penduduk WHERE kecamatan = ?");
    $stmt->bind_param("s", $kecamatan);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();
        echo "<form action='update.php' method='post' style='max-width: 500px; margin: auto; padding: 20px; border: 1px solid #ccc; border-radius: 5px;'>
                <h2>Edit Data Kecamatan</h2>
                <div style='margin-bottom: 15px;'>
                    <label for='kecamatan'>Kecamatan:</label>
                    <input type='text' id='kecamatan' name='kecamatan' value='" . htmlspecialchars($row["kecamatan"], ENT_QUOTES) . "' required style='width: 100%; padding: 8px;'>
                </div>
                <div style='margin-bottom: 15px;'>
                    <label for='latitude'>Latitude:</label>
                    <input type='text' id='latitude' name='latitude' value='" . htmlspecialchars($row["latitude"], ENT_QUOTES) . "' required style='width: 100%; padding: 8px;'>
                </div>
                <div style='margin-bottom: 15px;'>
                    <label for='longitude'>Longitude:</label>
                    <input type='text' id='longitude' name='longitude' value='" . htmlspecialchars($row["longitude"], ENT_QUOTES) . "' required style='width: 100%; padding: 8px;'>
                </div>
                <div style='margin-bottom: 15px;'>
                    <label for='luas'>Luas:</label>
                    <input type='text' id='luas' name='luas' value='" . htmlspecialchars($row["luas"], ENT_QUOTES) . "' required style='width: 100%; padding: 8px;'>
                </div>
                <div style='margin-bottom: 15px;'>
                    <label for='jumlah_penduduk'>Jumlah Penduduk:</label>
                    <input type='text' id='jumlah_penduduk' name='jumlah_penduduk' value='" . htmlspecialchars($row["jumlah_penduduk"], ENT_QUOTES) . "' required style='width: 100%; padding: 8px;'>
                </div>
                <input type='submit' value='Update' style='padding: 10px 15px; background-color: #4CAF50; color: white; border: none; border-radius: 5px; cursor: pointer;'>
            </form>";
    } else {
        echo "Data tidak ditemukan.";
    }

    $stmt->close();
    $conn->close();
}
?>
