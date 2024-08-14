<?php
// File: poster-presentation.php

// Koneksi ke database
$servername = "localhost";
$username = "root"; // Ganti dengan username MySQL Anda
$password = ""; // Ganti dengan password MySQL Anda
$dbname = "db_pci";

// Membuat koneksi ke database
$conn = new mysqli($servername, $username, $password, $dbname);

// Cek koneksi
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Logika Pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Pengaturan Paginasi
$limit = 8; // Jumlah poster per halaman
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
$start = ($page > 1) ? ($page * $limit) - $limit : 0;

// Modifikasi query untuk pencarian dan paginasi
$search_query = "";
if ($search) {
    $search_query = "AND (image_name LIKE '%$search%' OR title LIKE '%$search%')";
}

// Query untuk menghitung jumlah total poster yang sesuai dengan pencarian
$totalResult = $conn->query("SELECT COUNT(*) AS total FROM images WHERE is_active = 1 $search_query");
if(!$totalResult) {
    die("Error in counting query: " . $conn->error);
}

$total = $totalResult->fetch_assoc()['total'];
$pages = ceil($total / $limit);

// Query untuk mengambil data gambar dengan paginasi dan pencarian
$sql = "SELECT image_name, image_data, title FROM images WHERE is_active = 1 $search_query LIMIT $start, $limit";

$result = $conn->query($sql);

if(!$result) {
    die("Error in selection query: " . $conn->error);
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=2160, height=3840, initial-scale=1.0">
    <title>PCI IOSS ISASS AP 2024</title>

    <link href="library/bs/bootstrap.min.css" rel="stylesheet">
    <link href="library/style.css" rel="stylesheet">
    <link href="library/lightbox/lightbox.css" rel="stylesheet">
    <link href="library/animate/animate.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="icon" href="favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link href="library/bootstrap-icons/font/bootstrap-icons.min.css" rel="stylesheet">
</head>

<body style="background-image:url('img/bg_polos.jpg')">

<?php include('components/logo.php'); ?>

<div class="input-group mb-4 searchbox" style="margin-top: 650px; width: 1500px; margin-left: auto; margin-right: auto;">
    <span class="input-group-text" id="inputGroup-sizing-default"><i class="bi bi-search p-5"></i></span>
    <input type="text" class="form-control" aria-label="Search cards" value="<?php echo htmlspecialchars($search); ?>" onkeyup="searchFilter()" id="searchInput">
    <script>
        function searchFilter() {
            const search = document.getElementById('searchInput').value;
            window.location.href = "?search=" + search;
        }
    </script>
</div>

<div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 kontenisi" style="margin-left:auto; margin-right:auto;">
    <?php
    if ($result->num_rows > 0) {
        while($row = $result->fetch_assoc()) {
            $image_name = $row['image_name'];
            $image_data = base64_encode($row['image_data']);
            $title = $row['title'];
    ?>
    <div class="col">
        <div class="card">
            <a href="data:image/jpeg;base64,<?php echo $image_data; ?>" data-lightbox="mygallery" data-title="<?php echo $title; ?>">
                <img src="data:image/jpeg;base64,<?php echo $image_data; ?>" class="card-img-top" alt="<?php echo $title; ?>">
            </a>
            <div class="card-body">
                <h4 class="card-title text-center mt-4"><?php echo $title; ?></h4>
            </div>
        </div>
    </div>
    <?php
        }
    } else {
        echo "No images found.";
    }
    $conn->close();
    ?>
</div>

<!-- Pagination -->
<nav aria-label="Page navigation">
    <ul class="pagination justify-content-center mt-4">
        <?php if ($page > 1): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page - 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Previous">
                    <span aria-hidden="true">&laquo;</span>
                </a>
            </li>
        <?php endif; ?>

        <?php for ($i = 1; $i <= $pages; $i++): ?>
            <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                <a class="page-link" href="?page=<?php echo $i; ?>&search=<?php echo urlencode($search); ?>"><?php echo $i; ?></a>
            </li>
        <?php endfor; ?>

        <?php if ($page < $pages): ?>
            <li class="page-item">
                <a class="page-link" href="?page=<?php echo $page + 1; ?>&search=<?php echo urlencode($search); ?>" aria-label="Next">
                    <span aria-hidden="true">&raquo;</span>
                </a>
            </li>
        <?php endif; ?>
    </ul>
</nav>

<!-- Navigasi Home Back-->
<div class="container-fluid navigasi">
    <div class="align-self-center" style="display: flex; position: absolute; top: 3200px;">
        <a href="index.php"><img src="img/home.png" class="img-link" style="margin-right: 520px;"></a>
        <a id="backButton"><img src="img/back.png" class="img-link"></a>
    </div>
</div>

<!-- Navigasi Kembali -->
<div class="container-fluid d-flex justify-content-center" style="position: absolute; top: 3325px;">
    <a href="freepaperpresentation.php"><img src="img/kiri.png" class="img-link" style="margin-right: 1010px;"></a>
    <a href="index.php"><img src="img/kanan.png" class="img-link"></a>
</div>

<script src="library/lightbox/lightbox-plus-jquery.js"></script>
<script src="library/bs/bootstrap.min.js"></script>
<script src="library/script.js"></script>

</body>

</html>
