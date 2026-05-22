// =====================================================
// SIDEBAR TOGGLE
// =====================================================

const hamburger = document.querySelector(".toggle-btn");
const toggler = document.querySelector("#icon");

hamburger.addEventListener("click", function () {

    document.querySelector("#sidebar")
        .classList.toggle("expand");

    toggler.classList.toggle("bxs-chevrons-right");
    toggler.classList.toggle("bxs-chevrons-left");

});

// =====================================================
// DOM READY
// =====================================================

document.addEventListener("DOMContentLoaded", function () {

    const cipherType = document.getElementById("cipherType");

    const inputShift = document.getElementById("inputShift");

    const inputKunci = document.getElementById("inputKunci");

    const navbarSubtitle =
        document.getElementById("navbarSubtitle");

    // =================================================
    // SIDEBAR MENU
    // =================================================

    document.querySelectorAll(".sidebar-link")
        .forEach(link => {

        link.addEventListener("click", function (e) {

            const menu = this.dataset.menu;

            // kalau bukan menu utama biarkan
            if (!menu) return;

            e.preventDefault();

            // remove active
            document.querySelectorAll(".sidebar-link")
                .forEach(item => {
                    item.classList.remove("active");
                });

            // active baru
            this.classList.add("active");

            // set hidden input
            cipherType.value = menu;

            // =========================================
            // CAESAR
            // =========================================

            if (menu === "caesar") {

                inputShift.style.display = "block";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "Enkripsi & dekripsi dengan pergeseran alfabet";
            }

            // =========================================
            // XOR
            // =========================================

            else if (menu === "xor") {

                inputShift.style.display = "none";
                inputKunci.style.display = "block";

                navbarSubtitle.innerText =
                    "Enkripsi & dekripsi dengan XOR Cipher";
            }

            // =========================================
            // SHA256
            // =========================================

            else if (menu === "sha256") {

                inputShift.style.display = "none";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "Hashing menggunakan SHA-256";
            }

            // =========================================
            // RSA
            // =========================================

            else if (menu === "rsa") {

                inputShift.style.display = "none";
                inputKunci.style.display = "none";

                navbarSubtitle.innerText =
                    "RSA Generator";
            }

            // =========================================
            // DIGITAL SIGNATURE
            // =========================================

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
// COPY HASIL
// =====================================================

function copyHasil() {

    const teks =
        document.getElementById("hasilOutput").innerText;

    if (teks !== "-") {

        navigator.clipboard.writeText(teks);

        alert("Hasil berhasil disalin");

    }

}