<?php
// Direktori tempat gambar disimpan
$directory = "img/poster/";

// Mendapatkan parameter pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fungsi untuk mendapatkan file gambar dari direktori
function getImages($directory, $search) {
    $images = array(); // Inisialisasi array kosong

    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                // Hanya menampilkan file dengan ekstensi gambar
                if (preg_match('/\.(jpg|jpeg|png|gif)$/i', $file)) {
                    // Cek apakah ada pencarian dan cocokkan dengan nama file
                    if ($search && stripos($file, $search) === false) {
                        continue;
                    }
                    $images[] = $file; // Tambahkan file ke dalam array
                }
            }
            closedir($dh);
        }
    }

    return $images; // Mengembalikan array gambar, meskipun kosong
}

// Mendapatkan daftar gambar
$images = getImages($directory, $search); // Selalu akan menjadi array, meskipun kosong
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
    <link href="library/animate/animate.min.css" rel="stylesheet">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>

    <link rel="icon" href="favicon/favicon.png">
    <link rel="apple-touch-icon" sizes="180x180" href="favicon/apple-touch-icon.png">
    <link rel="icon" type="image/png" sizes="32x32" href="favicon/favicon-32x32.png">
    <link rel="icon" type="image/png" sizes="16x16" href="favicon/favicon-16x16.png">
    <link rel="manifest" href="favicon/site.webmanifest">
    <link href="library/bs/bootstrap-icons.min.css" rel="stylesheet">

    <style>
    .scrollable-content {
        max-height: 60vh;
        overflow-y: auto;
        padding: 150px;
    }

    .modal-img {
        width: 100%;
        height: auto;
    }

    .modal-lg {
        max-width: 80%;
    }

    .modal-navigation {
        position: absolute;
        top: 50%;
        transform: translateY(-50%);
        background-color: rgba(0, 0, 0, 0.25);
        color: white;
        padding: 80px; /* Diperbesar 4x dari 20px */
        cursor: pointer;
        z-index: 1000;
        font-size: 8rem; /* Diperbesar 4x dari 2rem */
    }

    .modal-navigation:hover {
        background-color: rgba(0, 0, 0, 0.7);
    }

    .modal-navigation.left {
        left: -60px;
    }

    .modal-navigation.right {
        right: -60px;
    }

    .modal-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    /* Tambahan untuk memperbesar tombol close */
    .btn-close {
        width: 2rem; /* Ukuran lebar tombol close diperbesar */
        height: 2rem; /* Ukuran tinggi tombol close diperbesar */
        padding: 0.75rem; /* Tambahan padding untuk memperbesar tombol */
        font-size: 1.5rem; /* Memperbesar ikon "X" di dalam tombol */
    }
</style>

</head>

<body style="background-image:url('img/bg_polos.jpg')">

<?php include('components/logo.php'); ?>

<div class="input-group mb-4 searchbox" style="margin-top: 650px; width: 1500px; margin-left: auto; margin-right: auto;">
    <span class="input-group-text" id="inputGroup-sizing-default"><i class="bi bi-search p-5"></i></span>
    <input type="text" class="form-control" aria-label="Search cards" value="<?php echo htmlspecialchars($search); ?>" oninput="searchFilter()" id="searchInput">
    <script>
        let typingTimer;
        let doneTypingInterval = 500;  // Waktu tunggu dalam milidetik

        const searchInput = document.getElementById('searchInput');

        function searchFilter() {
            clearTimeout(typingTimer);
            typingTimer = setTimeout(function() {
                const search = searchInput.value;
                window.location.href = "?search=" + encodeURIComponent(search);
            }, doneTypingInterval);
        }

        searchInput.addEventListener('keyup', function() {
            clearTimeout(typingTimer);
        });
    </script>
</div>

<div class="scrollable-content">
    <div class="row row-cols-1 row-cols-sm-2 row-cols-md-4 g-4 kontenisi" style="margin-left:auto; margin-right:auto;">
        <?php
        // Menampilkan gambar
        if (is_array($images) && count($images) > 0) {
            foreach ($images as $index => $image) {
                $image_path = $directory . $image;
                ?>
                <div class="col">
                    <div class="card h-100" style="display: flex; flex-direction: column;">
                        <button type="button" class="btn p-0" data-bs-toggle="modal" data-bs-target="#imageModal" data-bs-index="<?php echo $index; ?>" data-bs-image="<?php echo $image_path; ?>" data-bs-title="<?php echo $image; ?>">
                            <img src="<?php echo $image_path; ?>" class="card-img-top" alt="<?php echo $image; ?>">
                        </button>
                        <div class="card-body" style="flex-grow: 1; display: flex; flex-direction: column; justify-content: space-between;">
                            <h4 class="card-title text-center" style="
                                display: -webkit-box;
                                -webkit-line-clamp: 7;
                                -webkit-box-orient: vertical;
                                overflow: hidden;
                                text-overflow: ellipsis;
                                max-height: calc(1.2em * 7);
                                line-height: 1.2em;
                            ">
                                <?php echo $image; ?>
                            </h4>
                        </div>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "No images found.";
        }
        ?>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="imageModalLabel">Title</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center position-relative">
                <span class="modal-navigation left" id="prev-image">&#10094;</span>
                <img id="modalImage" src="" class="modal-img" alt="Image">
                <span class="modal-navigation right" id="next-image">&#10095;</span>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Home Back-->
<div class="container-fluid navigasi">
    <div class="align-self-center" style="display: flex; position: absolute; top: 3300px;">
        <a href="index.php"><img src="img/home.png" class="img-link" style="margin-right: 520px;"></a>
        <a href="callforpaper.php"><img src="img/back.png" class="img-link"></a>
    </div>
</div>

<script src="library/bs/bootstrap.min.js"></script>
<script src="library/script.js"></script>
<script>
    let currentIndex = 0;
    const images = <?php echo json_encode($images); ?>;
    const directory = "<?php echo $directory; ?>";

    const modalImage = document.getElementById('modalImage');
    const modalTitle = document.getElementById('imageModalLabel');
    const prevButton = document.getElementById('prev-image');
    const nextButton = document.getElementById('next-image');

    document.getElementById('imageModal').addEventListener('show.bs.modal', function (event) {
        const button = event.relatedTarget;
        currentIndex = parseInt(button.getAttribute('data-bs-index'));
        updateModalContent();
    });

    prevButton.addEventListener('click', function () {
        currentIndex = (currentIndex - 1 + images.length) % images.length;
        updateModalContent();
    });

    nextButton.addEventListener('click', function () {
        currentIndex = (currentIndex + 1) % images.length;
        updateModalContent();
    });

    function updateModalContent() {
        modalImage.src = directory + images[currentIndex];
        modalTitle.textContent = images[currentIndex];
    }
</script>

</body>

</html>
