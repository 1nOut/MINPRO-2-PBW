<?php

require_once 'config.php';

$res_profile  = $conn->query("SELECT * FROM profile LIMIT 1");
$profile      = $res_profile->fetch_assoc();

$res_skills   = $conn->query("SELECT * FROM skills ORDER BY urutan ASC");

$res_ranks    = $conn->query("SELECT * FROM valorant_ranks ORDER BY urutan ASC");

$res_sertif   = $conn->query("SELECT * FROM sertifikat ORDER BY urutan ASC");
$all_sertif   = $res_sertif->fetch_all(MYSQLI_ASSOC);

$slides       = array_chunk($all_sertif, 4);

function render_paragraphs(string $text): string {
    $paras = array_filter(array_map('trim', explode("\n", $text)));
    return implode('', array_map(fn($p) => "<p>$p</p>", $paras));
}

function e(string $s): string {
    return htmlspecialchars($s, ENT_QUOTES, 'UTF-8');
}

function img_src(?string $blob, string $mime = 'image/png'): string {
    if (empty($blob)) return '';
    return 'data:' . $mime . ';base64,' . base64_encode($blob);
}
?>
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Portfolio <?= e($profile['nama']) ?></title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="style.css">
</head>
<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark-custom fixed-top shadow">
    <div class="container">

        <a class="navbar-brand fw-bold text-white" href="#">Portofolio JHA</a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse justify-content-center" id="navbarNav">
            <ul class="navbar-nav position-relative align-items-center">

                <li class="nav-item">
                    <a class="nav-link active" href="#home">Home</a>
                </li>

                <li class="separator"></li>

                <li class="nav-item">
                    <a class="nav-link" href="#about">About Me</a>
                </li>

                <li class="separator"></li>

                <li class="nav-item">
                    <a class="nav-link" href="#certificates">Certificates</a>
                </li>

                <span class="nav-indicator"></span>
            </ul>
        </div>
    </div>
</nav>

<section id="home" class="hero-section">
    <div class="container text-center text-white">

        <div class="photo-frame mb-5">
            <img src="<?= img_src($profile['foto'], $profile['foto_type']) ?>" class="profile-img" alt="Pas Foto">
        </div>

        <h1 class="fw-bold display-4 mb-3"><?= e($profile['nama']) ?></h1>

        <p class="lead hero-desc">
            Mahasiswa <?= e($profile['prodi']) ?> Fakultas Teknik <br>
            <?= e($profile['universitas']) ?> Angkatan <?= e($profile['angkatan']) ?>.
        </p>

    </div>
</section>

<section id="about" class="about-section">
    <div class="container">

        <h2 class="text-center section-title mb-5">About Me</h2>

        <div class="accordion custom-accordion" id="aboutAccordion">

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#desc">
                        Deskripsi Diri
                    </button>
                </h2>
                <div id="desc" class="accordion-collapse collapse"
                    data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <?= render_paragraphs($profile['deskripsi']) ?>
                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#skills">
                        Skills
                    </button>
                </h2>
                <div id="skills" class="accordion-collapse collapse"
                    data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">

                        <?php while ($skill = $res_skills->fetch_assoc()): ?>
                        <div class="skill-item">
                            <div class="skill-info">
                                <span><?= e($skill['nama']) ?></span>
                                <span><?= e($skill['persentase']) ?>%</span>
                            </div>
                            <div class="progress custom-progress">
                                <div class="progress-bar <?= e($skill['css_class']) ?>"></div>
                            </div>
                        </div>
                        <?php endwhile; ?>

                        <div class="skill-item valorant-section">
                            <div class="skill-info">
                                <span>Valorant</span>
                            </div>
                            <div class="valorant-ranks">
                                <?php while ($rank = $res_ranks->fetch_assoc()): ?>
                                <img src="<?= img_src($rank['gambar'], $rank['gambar_type']) ?>"
                                     class="rank-icon <?= e($rank['css_class']) ?>"
                                     alt="Rank <?= e($rank['nama_rank']) ?>">
                                <?php endwhile; ?>
                            </div>
                            <div class="progress custom-progress">
                                <div class="progress-bar valorant-bar"></div>
                            </div>
                        </div>

                    </div>
                </div>
            </div>

            <div class="accordion-item">
                <h2 class="accordion-header">
                    <button class="accordion-button collapsed" type="button"
                        data-bs-toggle="collapse" data-bs-target="#exp">
                        Pengalaman
                    </button>
                </h2>
                <div id="exp" class="accordion-collapse collapse"
                    data-bs-parent="#aboutAccordion">
                    <div class="accordion-body">
                        <ul>
                            <li>Staff dari Professional Skill Development Department - INFORSA</li>
                            <li>Penanggung Jawab pada Kegiatan ISC di Mobile Legend</li>
                            <li>Panitia pada Study Club Cyber Security</li>
                            <li>Anggota Panitia Kegiatan APLIKASI 2025</li>
                            <li>Anggota Panitia Kegiatan INFORSA Competition</li>
                            <li>Anggota Panitia Kegiatan TAROT</li>
                        </ul>
                    </div>
                </div>
            </div>

        </div>
    </div>
</section>


<section id="certificates" class="certificates-section">
    <div class="container text-center">
        <h2 class="section-title mb-5">My Certificates</h2>

        <div id="certificateCarousel" class="carousel slide" data-bs-ride="carousel">

            <div class="carousel-inner">

                <?php foreach ($slides as $index => $slide): ?>
                <div class="carousel-item <?= $index === 0 ? 'active' : '' ?>">
                    <div class="certificate-grid">

                        <?php foreach ($slide as $sertif): ?>
                        <div class="certificate-card">
                            <img src="<?= img_src($sertif['gambar'], $sertif['gambar_type']) ?>" alt="<?= e($sertif['judul']) ?>">
                            <div class="card-body">
                                <h5><?= e($sertif['judul']) ?></h5>
                                <p><?= e($sertif['deskripsi']) ?></p>
                            </div>
                        </div>
                        <?php endforeach; ?>

                    </div>
                </div>
                <?php endforeach; ?>

            </div>

            <button class="carousel-control-prev" type="button" data-bs-target="#certificateCarousel" data-bs-slide="prev">
                <span class="carousel-control-prev-icon"></span>
            </button>

            <button class="carousel-control-next" type="button" data-bs-target="#certificateCarousel" data-bs-slide="next">
                <span class="carousel-control-next-icon"></span>
            </button>

        </div>
    </div>
</section>

<footer class="text-center text-white py-3 bg-dark-custom">
    <p class="mb-0">© 2026 <?= e($profile['nama']) ?> | Portfolio Website</p>
</footer>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

<script>
const sections = document.querySelectorAll("section");
const navLinks = document.querySelectorAll(".nav-link");
const indicator = document.querySelector(".nav-indicator");
const navContainer = document.querySelector(".navbar-nav");

function moveIndicator(element) {
    const linkRect = element.getBoundingClientRect();
    const navRect = navContainer.getBoundingClientRect();

    indicator.style.width = linkRect.width + "px";
    indicator.style.left = (linkRect.left - navRect.left) + "px";
}

window.addEventListener("scroll", () => {
    let current = "";

    sections.forEach(section => {
        const sectionTop = section.offsetTop - 150;
        if (window.scrollY >= sectionTop) {
            current = section.getAttribute("id");
        }
    });

    navLinks.forEach(link => {
        link.classList.remove("active");
        if (link.getAttribute("href") === "#" + current) {
            link.classList.add("active");
            moveIndicator(link);
        }
    });
});

window.addEventListener("load", () => {
    const activeLink = document.querySelector(".nav-link.active");
    if (activeLink) moveIndicator(activeLink);
});
</script>

<?php $conn->close(); ?>
</body>
</html>
