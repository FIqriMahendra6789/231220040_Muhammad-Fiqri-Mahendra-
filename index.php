<?php

// ======================================================
// DEFAULT
// ======================================================

$hasil      = '';
$error      = '';
$modeTampil = '';
$cipherType = $_POST['cipher_type'] ?? 'caesar';

// ======================================================
// CAESAR CIPHER
// ======================================================

function cipher($char, $key)
{
    if (ctype_alpha($char)) {

        $nilai = ord(ctype_upper($char) ? 'A' : 'a');
        $mod   = (ord($char) - $nilai + $key) % 26;

        return chr($mod + $nilai);
    }

    return $char;
}

function enkripsi($input, $key)
{
    $output = '';

    foreach (str_split($input) as $char) {
        $output .= cipher($char, $key);
    }

    return $output;
}

function dekripsi($input, $key)
{
    return enkripsi($input, 26 - $key);
}

// ======================================================
// XOR CIPHER
// ======================================================

function xor_enkripsi($teks, $kunci)
{
    $output       = '';
    $panjangKunci = strlen($kunci);

    for ($i = 0; $i < strlen($teks); $i++) {

        $output .= sprintf(
            '%02x',
            ord($teks[$i]) ^ ord($kunci[$i % $panjangKunci])
        );
    }

    return $output;
}

function xor_dekripsi($hex, $kunci)
{
    $output       = '';
    $panjangKunci = strlen($kunci);
    $bytes        = str_split($hex, 2);

    foreach ($bytes as $i => $byte) {

        $output .= chr(
            hexdec($byte) ^ ord($kunci[$i % $panjangKunci])
        );
    }

    return $output;
}

// ======================================================
// SHA-256
// ======================================================

function sha256_hash($teks)
{
    return hash('sha256', $teks);
}

// ======================================================
// PROSES FORM
// ======================================================

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $teks = trim($_POST['teks'] ?? '');
    $mode = $_POST['mode'] ?? 'enkripsi';

    // ==================================================
    // CAESAR
    // ==================================================

    if ($cipherType === 'caesar') {

        $shift = (int)($_POST['shift'] ?? 0);

        if ($teks === '') {

            $error = 'Teks tidak boleh kosong.';

        } elseif ($shift < 1 || $shift > 25) {

            $error = 'Shift harus antara 1 - 25.';

        } else {

            $hasil = ($mode === 'enkripsi')
                ? enkripsi($teks, $shift)
                : dekripsi($teks, $shift);

            $modeTampil = ucfirst($mode);
        }
    }

    // ==================================================
    // XOR
    // ==================================================

    elseif ($cipherType === 'xor') {

        $xorKey = trim($_POST['kunci'] ?? '');

        if ($teks === '' || $xorKey === '') {

            $error = 'Teks dan kunci tidak boleh kosong.';

        } else {

            $hasil = ($mode === 'enkripsi')
                ? xor_enkripsi($teks, $xorKey)
                : xor_dekripsi($teks, $xorKey);

            $modeTampil = ucfirst($mode);
        }
    }

    // ==================================================
    // SHA256
    // ==================================================

    elseif ($cipherType === 'sha256') {

        if ($teks === '') {

            $error = 'Teks tidak boleh kosong.';

        } else {

            $hasil = sha256_hash($teks);
            $modeTampil = 'Hashing';
        }
    }

    // ==================================================
    // RSA
    // ==================================================

    elseif ($cipherType === 'rsa') {

        $hasil =
"PUBLIC KEY:
(65537, 3233)

PRIVATE KEY:
(2753, 3233)";

        $modeTampil = 'RSA Generator';
    }

    // ==================================================
    // DIGITAL SIGNATURE
    // ==================================================

    elseif ($cipherType === 'signature') {

        if ($teks === '') {

            $error = 'Teks tidak boleh kosong.';

        } else {

            $hasil =
                'SIGNATURE : ' .
                strtoupper(substr(hash('sha256', $teks), 0, 40));

            $modeTampil = 'Digital Signature';
        }
    }
}

?>

<!DOCTYPE html>
<html lang="en">

<head>

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Kriptografi Dashboard</title>

<link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">

<link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">

<link rel="stylesheet"
href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.7.2/css/all.min.css">

<link rel="stylesheet" href="style.css">

</head>

<body>

<div class="wrapper">

<!-- ===================================================== -->
<!-- SIDEBAR -->
<!-- ===================================================== -->

<aside id="sidebar">

<div class="d-flex justify-content-between p-4">

<div class="sidebar-logo">
<a href="#">Kriptografi 34</a>
</div>

<button class="toggle-btn border-0" type="button">
<i id="icon" class="bx bxs-chevrons-right"></i>
</button>

</div>

<ul class="list-unstyled">

<li class="sidebar-item">

<a href="#"
class="sidebar-link <?= $cipherType === 'caesar' ? 'active' : '' ?>"
data-menu="caesar">

<i class="fa-solid fa-rotate"></i>
<span>Caesar Cipher</span>

</a>

</li>

<li class="sidebar-item">

<a href="#"
class="sidebar-link <?= $cipherType === 'xor' ? 'active' : '' ?>"
data-menu="xor">

<i class="bx bxs-user-layer"></i>
<span>XOR Cipher</span>

</a>

</li>

<li class="sidebar-item">

<a href="#"
class="sidebar-link <?= $cipherType === 'sha256' ? 'active' : '' ?>"
data-menu="sha256">

<i class="bx bxs-bug-alt"></i>
<span>SHA-256</span>

</a>

</li>

<li class="sidebar-item">

<a href="#"
class="sidebar-link <?= $cipherType === 'rsa' ? 'active' : '' ?>"
data-menu="rsa">

<i class="fa-solid fa-shield-halved"></i>
<span>RSA Generator</span>

</a>

</li>

<li class="sidebar-item">

<a href="#"
class="sidebar-link <?= $cipherType === 'signature' ? 'active' : '' ?>"
data-menu="signature">

<i class="fa-solid fa-file-signature"></i>
<span>Digital Signature</span>

</a>

</li>

</ul>

<div class="sidebar-footer">

<a href="#" class="sidebar-link">

<i class="fa-solid fa-circle-user"></i>
<span>M.Fiqri Mahendra</span>

</a>

</div>

</aside>

<!-- ===================================================== -->
<!-- MAIN -->
<!-- ===================================================== -->

<div class="main">

<nav class="navbar navbar-expand px-4 py-3">

<div class="d-none d-sm-inline-block">

<h5>231220040 Muhammad Fiqri Mahendra 34</h5>

<p id="navbarSubtitle">

<?php

if ($cipherType === 'xor') {

    echo 'Enkripsi & dekripsi dengan XOR Cipher';

} elseif ($cipherType === 'sha256') {

    echo 'Hashing menggunakan SHA-256';

} elseif ($cipherType === 'rsa') {

    echo 'RSA Key Generator';

} elseif ($cipherType === 'signature') {

    echo 'Digital Signature';

} else {

    echo 'Enkripsi & dekripsi dengan pergeseran alfabet';
}

?>

</p>

</div>

</nav>

<!-- ===================================================== -->
<!-- CONTENT -->
<!-- ===================================================== -->

<main class="content px-3 py-4">

<div class="container-fluid">

<?php if ($error !== '') : ?>

<div class="alert alert-danger">

<?= htmlspecialchars($error) ?>

</div>

<?php endif; ?>

<form method="POST">

<input type="hidden"
name="cipher_type"
id="cipherType"
value="<?= htmlspecialchars($cipherType) ?>">

<label class="form-label fs-5">

Pesan Anda

</label>

<textarea
name="teks"
class="form-control"
rows="8"
placeholder="Masukan pesan..."><?= htmlspecialchars($_POST['teks'] ?? '') ?></textarea>

<div class="row mt-3 g-3">

<!-- SHIFT -->

<div class="col-md-4"
id="inputShift"
<?= $cipherType === 'caesar' ? '' : 'style="display:none;"' ?>>

<label class="form-label">Nilai Shift</label>

<input type="number"
name="shift"
class="form-control"
min="1"
max="25"
value="<?= htmlspecialchars($_POST['shift'] ?? '') ?>">

</div>

<!-- XOR -->

<div class="col-md-4"
id="inputKunci"
<?= $cipherType === 'xor' ? '' : 'style="display:none;"' ?>>

<label class="form-label">Kunci XOR</label>

<input type="text"
name="kunci"
class="form-control"
value="<?= htmlspecialchars($_POST['kunci'] ?? '') ?>">

</div>

<!-- MODE -->

<div class="col-md-4">

<label class="form-label">Mode</label>

<div class="d-flex gap-3">

<div class="form-check">

<input class="form-check-input"
type="radio"
name="mode"
value="enkripsi"
checked>

<label class="form-check-label">

Enkripsi

</label>

</div>

<div class="form-check">

<input class="form-check-input"
type="radio"
name="mode"
value="dekripsi">

<label class="form-check-label">

Dekripsi

</label>

</div>

</div>

</div>

</div>

<div class="mt-4 d-flex gap-2">

<button type="submit" class="btn btn-primary">

<i class="fa-solid fa-lock me-1"></i>
Proses

</button>

<button type="reset"
    class="btn btn-outline-danger px-4 py-2">

    <i class="fa-solid fa-arrows-rotate me-2"></i>
    Mengatur ulang

</button>

</div>

</form>

<!-- ===================================================== -->
<!-- HASIL -->
<!-- ===================================================== -->

<div class="mt-5 p-4 rounded-3 border">

<div class="d-flex justify-content-between align-items-center mb-3">

<h5>Hasil Output</h5>

<button class="btn btn-light border"
onclick="copyHasil()">

Salin

</button>

</div>

<p id="hasilOutput"
class="font-monospace">

<?= $hasil !== '' ? nl2br(htmlspecialchars($hasil)) : '-' ?>

</p>

</div>

</div>

</main>

</div>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>

<script src="script.js"></script>

<script>

document.addEventListener("DOMContentLoaded", function () {

    const cipherType = document.getElementById("cipherType");

    const inputShift = document.getElementById("inputShift");

    const inputKunci = document.getElementById("inputKunci");

    const navbarSubtitle =
        document.getElementById("navbarSubtitle");

    // =====================================================
    // MENU CLICK
    // =====================================================

    document.querySelectorAll(".sidebar-link").forEach(link => {

        link.addEventListener("click", function (e) {

            const menu = this.dataset.menu;

            if (!menu) return;

            e.preventDefault();

            document.querySelectorAll(".sidebar-link")
            .forEach(item => {
                item.classList.remove("active");
            });

            this.classList.add("active");

            cipherType.value = menu;

            // =============================================
            // CAESAR
            // =============================================

            if (menu === "caesar") {

                inputShift.style.display = "block";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "Enkripsi & dekripsi dengan pergeseran alfabet";
            }

            // =============================================
            // XOR
            // =============================================

            else if (menu === "xor") {

                inputShift.style.display = "none";
                inputKunci.style.display = "block";

                navbarSubtitle.innerText =
                    "Enkripsi & dekripsi dengan XOR Cipher";
            }

            // =============================================
            // SHA256
            // =============================================

            else if (menu === "sha256") {

                inputShift.style.display = "none";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "Hashing menggunakan SHA-256";
            }

            // =============================================
            // RSA
            // =============================================

            else if (menu === "rsa") {

                inputShift.style.display = "none";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "RSA Key Generator";
            }

            // =============================================
            // SIGNATURE
            // =============================================

            else if (menu === "signature") {

                inputShift.style.display = "none";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "Digital Signature";
            }

        });

    });

});

// =====================================================
// COPY
// =====================================================

function copyHasil()
{
    const teks =
        document.getElementById("hasilOutput").innerText;

    navigator.clipboard.writeText(teks);

    alert("Hasil berhasil disalin");
}



// ===============================
// BUTTON RESET
// ===============================

const resetButton = document.querySelector('button[type="reset"]');

if (resetButton) {

    resetButton.addEventListener("click", function () {

        // reset textarea
        document.querySelector('textarea[name="teks"]').value = "";

        // reset shift
        const shift = document.querySelector('input[name="shift"]');
        if (shift) {
            shift.value = "";
        }

        // reset kunci xor
        const kunci = document.querySelector('input[name="kunci"]');
        if (kunci) {
            kunci.value = "";
        }

        // reset hasil
        document.getElementById("hasilOutput").innerText = "-";

        // reset statistik
        document.querySelectorAll(".text-center .p-3 div:first-child")
            .forEach(item => {
                item.innerText = "-";
            });

        // radio kembali ke enkripsi
        document.querySelector('input[value="enkripsi"]').checked = true;
    });
}

</script>

</body>
</html>