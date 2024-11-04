<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Peta Kabupaten Sleman</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css"
        integrity="sha256-p4NxAoJBhIIN+hmNHrzRCf9tD/miZyoHS5obTRR9BMY=" crossorigin="" />
    <style>
        /* General Styles */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #CCFFFF;
        }

        /* Navbar Styles */
        .navbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            background-color: #00CCFF;
            padding: 10px;
            color: white;
        }

        .navbar a {
            color: white;
            padding: 14px 20px;
            text-decoration: none;
            text-align: center;
            font-weight: bold;
        }

        .navbar a:hover {
            background-color: #ddd;
            color: black;
        }

        /* Title Styling */
        .main-title {
            text-align: center;
            font-size: 2em;
            margin: 20px 0;
            font-weight: bold;
        }

        /* Map Container */
        #map {
            width: 100%;
            height: 600px;
            margin-bottom: 20px;
        }

        /* Table Styles */
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        table, th, td {
            border: 1px solid black;
        }

        th, td {
            padding: 10px;
            text-align: left;
        }

        th {
            background-color: #f2f2f2;
        }

        /* Form Styles */
        .form-container {
            margin: 20px auto;
            max-width: 600px;
            padding: 20px;
            border: 1px solid #ccc;
            background-color: #fff;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
        }

        .form-container h2 {
            text-align: center;
            margin-bottom: 20px;
        }

        .form-container input, .form-container select {
            width: 100%;
            padding: 12px;
            margin: 5px 0;
            border: 1px solid #ccc;
            border-radius: 4px;
            box-sizing: border-box;
        }

        .form-container button {
            width: 100%;
            padding: 12px;
            background-color: #333;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            margin-top: 10px;
        }

        .form-container button:hover {
            background-color: #555;
        }

        /* Footer Style */
        #footer {
            margin-top: 30px;
            padding: 20px;
            background-color: #333;
            color: white;
            text-align: center;
        }

        /* Smooth scrolling */
        html {
            scroll-behavior: smooth;
        }
    </style>
</head>

<body>
    <!-- Navbar Section -->
    <div class="navbar">
        <span class="title">Kabupaten Sleman</span>
        <div>
            <a href="#map">Peta</a>
            <a href="#data-table">Tabel Data</a>
            <a href="#add-data">Tambah Data</a>
            <a href="#footer">Pembuat</a>
        </div>
    </div>

    <h1 class="main-title">Peta Kabupaten Sleman</h1>

    <!-- Map Section -->
    <div id="map"></div>

    <!-- Data Table Section -->
    <h2 id="data-table">Data Penduduk Kabupaten Sleman</h2>

    <?php
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "pgweb_acara8"; 

    $conn = new mysqli($servername, $username, $password, $dbname);

    if ($conn->connect_error) {
        die("Koneksi gagal: " . $conn->connect_error);
    }

    // Retrieve data from database
    $sql = "SELECT * FROM penduduk";
    $result = $conn->query($sql);

    $dataPenduduk = array();

    if ($result->num_rows > 0) {
        echo "<table>
        <tr>
            <th>Kecamatan</th>
            <th>Latitude</th>
            <th>Longitude</th>
            <th>Luas (km²)</th>
            <th>Jumlah Penduduk</th>
            <th>Aksi</th>
        </tr>";
        
        while($row = $result->fetch_assoc()) {
            echo "<tr>
            <td>" . htmlspecialchars($row["kecamatan"], ENT_QUOTES) . "</td>
            <td>" . htmlspecialchars($row["latitude"], ENT_QUOTES) . "</td>
            <td>" . htmlspecialchars($row["longitude"], ENT_QUOTES) . "</td>
            <td>" . htmlspecialchars($row["luas"], ENT_QUOTES) . "</td>
            <td align='right'>" . htmlspecialchars($row["jumlah_penduduk"], ENT_QUOTES) . "</td>
            <td>
                <a href='edit.php?kecamatan=" . urlencode($row["kecamatan"]) . "'>Edit</a>
                |
                <a href='hapus.php?kecamatan=" . urlencode($row["kecamatan"]) . "' onclick='return confirm(\"Apakah Anda yakin ingin menghapus data ini?\")'>Hapus</a>
            </td>
            </tr>";
            
            $dataPenduduk[] = $row;
        }
        echo "</table>";
    } else {
        echo "Tidak ada hasil";
    }

    // Handle adding data
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $kecamatan = $_POST['kecamatan'];
        $latitude = $_POST['latitude'];
        $longitude = $_POST['longitude'];
        $luas = $_POST['luas'];
        $jumlah_penduduk = $_POST['jumlah_penduduk'];

        // Prepare and bind
        $stmt = $conn->prepare("INSERT INTO penduduk (kecamatan, latitude, longitude, luas, jumlah_penduduk) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("ssssi", $kecamatan, $latitude, $longitude, $luas, $jumlah_penduduk);

        if ($stmt->execute()) {
            echo "<p>Data berhasil ditambahkan!</p>";
            echo "<meta http-equiv='refresh' content='0'>"; // Refresh halaman untuk menampilkan data terbaru
        } else {
            echo "<p>Terjadi kesalahan: " . $stmt->error . "</p>";
        }

        $stmt->close();
    }

    $conn->close();
    ?>

    <!-- Form for Adding Data -->
    <div class="form-container" id="add-data" >
        <h2>Tambah Data Penduduk</h2>
        <form action="" method="post">
            <input type="text" name="kecamatan" placeholder="Kecamatan" required>
            <input type="text" name="latitude" placeholder="Latitude" required>
            <input type="text" name="longitude" placeholder="Longitude" required>
            <input type="text" name="luas" placeholder="Luas (km²)" required>
            <input type="number" name="jumlah_penduduk" placeholder="Jumlah Penduduk" required>
            <button type="submit">Tambah Data</button>
        </form>
    </div>

    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"
        integrity="sha256-20nQCchB9co0qIjJZRGuk2/Z9VM+kNiyxNV1lvTlZBo=" crossorigin=""></script>
    <script>
        var map = L.map("map").setView([-7.7163, 110.3554], 11);

        var osm = L.tileLayer("https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png", {
            attribution: '&copy; <a href="https://www.openstreetmap.org/copyright">OpenStreetMap</a> contributors',
        });
        osm.addTo(map);

        var dataPenduduk = <?php echo json_encode($dataPenduduk); ?>;

        dataPenduduk.forEach(function(item) {
            L.marker([item.latitude, item.longitude])
                .bindPopup(`<b>${item.kecamatan}</b><br>Luas: ${item.luas} km²<br>Jumlah Penduduk: ${item.jumlah_penduduk}`)
                .addTo(map);
        });

        L.control.scale({ position: "bottomright" }).addTo(map);
    </script>

    <!-- Footer Section -->
<div id="footer" style="background-color: #66CCFF; color: white;">
    <p>Created by Zahra Khusnul Khotimah</p>
    <p>NIM: 23/522563/SV/223722</p>
</div>
</body>

</html>
