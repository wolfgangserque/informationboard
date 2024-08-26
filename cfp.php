<?php
// Direktori tempat file PDF disimpan
$directory = "paper/";

// Mendapatkan parameter pencarian
$search = isset($_GET['search']) ? $_GET['search'] : '';

// Fungsi untuk mendapatkan file PDF dari direktori
function getPdfs($directory, $search) {
    $pdfs = array();

    if (is_dir($directory)) {
        if ($dh = opendir($directory)) {
            while (($file = readdir($dh)) !== false) {
                // Hanya menampilkan file dengan ekstensi .pdf
                if (preg_match('/\.pdf$/i', $file)) {
                    // Cek apakah ada pencarian dan cocokkan dengan nama file
                    if ($search && stripos($file, $search) === false) {
                        continue;
                    }
                    $pdfs[] = $file;
                }
            }
            closedir($dh);
        }
    }

    return $pdfs;
}

// Mendapatkan daftar PDF
$pdfs = getPdfs($directory, $search);
?>

<!DOCTYPE html>
<html lang="id">

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
        body {
            background-color: #000;
            color: white;
        }

        a {
            color: white;
            text-decoration: none;
            transition: transform 0.3s ease;
        }

        h5 {
            color: black;
            transition: transform 0.3s ease;
        }

        a:hover h5 {
            transform: scale(1.05);
        }

        .thumbnail {
            width: 100%;
            height: auto;
            max-height: 300px;
            object-fit: cover;
            border: 2px solid #ccc;
            border-radius: 8px;
            transition: transform 0.3s ease;
        }

        .thumbnail:hover {
            transform: scale(1.05);
        }

        .scrollable-content {
            max-height: 60vh;
            overflow-y: auto;
            padding: 150px;
            margin-top: 700px;
        }

        .pdf-gallery {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            gap: 20px;
            padding: 20px;
        }

        .pdf-gallery .pdf-item {
            flex: 1 1 300px;
            max-width: 300px;
            text-align: center;
        }

        /* Kustomisasi ukuran modal agar lebih besar */
        .modal-4k {
            max-width: 90%;
            width: 90%;
        }

        .modal-body iframe {
            height: 90vh;
        }
    </style>
</head>

<body style="background-image:url('img/bg_polos.jpg')">

<?php include('components/logo.php'); ?>

<div class="scrollable-content pdf-gallery">
    <div class="container">
        <div class="row">
        <?php
        if (count($pdfs) > 0) {
            foreach ($pdfs as $pdf) {
                $thumbnail_path = "img/thumbnails/" . pathinfo($pdf, PATHINFO_FILENAME) . "-thumb.png";
                ?>
                <div class="col-md-3 col-sm-6 mb-4">
                    <div class="pdf-item">
                        <a href="javascript:void(0);" data-bs-toggle="modal" data-bs-target="#pdfModal" data-bs-pdf="<?php echo $directory . $pdf; ?>">
                            <img src="<?php echo $thumbnail_path; ?>" class="thumbnail" alt="<?php echo $pdf; ?>">
                            <h5 class="mt-2"><?php echo pathinfo($pdf, PATHINFO_FILENAME); ?></h5>
                        </a>
                    </div>
                </div>
                <?php
            }
        } else {
            echo "<p>File PDF tidak ditemukan.</p>";
        }
        ?>
        </div>
    </div>
</div>

<!-- Modal Bootstrap -->
<div class="modal fade" id="pdfModal" tabindex="-1" aria-labelledby="pdfModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-4k modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pdfModalLabel">Tampilan PDF</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <!-- Tampilkan PDF di sini -->
                <iframe id="pdfIframe" src="" frameborder="0" width="100%"></iframe>
            </div>
        </div>
    </div>
</div>

<!-- Navigasi Home Back -->
<div class="container-fluid navigasi">
    <div class="align-self-center" style="display: flex; position: absolute; top: 3200px;">
        <a href="index.php"><img src="img/home.png" class="img-link" style="margin-right: 520px;"></a>
        <a href="freepaperpresentation.php"><img src="img/back.png" class="img-link"></a>
    </div>
</div>

<script src="library/bs/bootstrap.min.js"></script>
<script>
    // Tangkap event ketika modal terbuka
    document.getElementById('pdfModal').addEventListener('show.bs.modal', function (event) {
        var button = event.relatedTarget; // Tombol yang memicu modal
        var pdfUrl = button.getAttribute('data-bs-pdf'); // Ambil URL PDF dari atribut data-bs-pdf
        var modalIframe = document.getElementById('pdfIframe');
        modalIframe.src = pdfUrl; // Set URL PDF ke dalam iframe
    });

    // Bersihkan URL iframe ketika modal ditutup untuk optimisasi
    document.getElementById('pdfModal').addEventListener('hidden.bs.modal', function () {
        document.getElementById('pdfIframe').src = '';
    });
</script>

</body>

</html>
