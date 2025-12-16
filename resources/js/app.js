import "./bootstrap";

const sidebar = document.getElementById("sidebar");
const hamburger = document.getElementById("hamburger");
const overlay = document.getElementById("overlay");

hamburger.addEventListener("click", expand);

function expand() {
    if (sidebar.classList.contains("-left-0")) {
        sidebar.classList.remove("-left-0");
        sidebar.classList.add("-left-60");

        overlay.classList.add("hidden");
        overlay.classList.remove("opacity-50");
        overlay.classList.add("opacity-0");

        return;
    }

    sidebar.classList.add("-left-0");
    sidebar.classList.remove("-left-60");

    overlay.classList.remove("hidden");
    overlay.classList.remove("opacity-0");
    overlay.classList.add("opacity-50");
}

overlay.addEventListener("click", collapse);

function collapse() {
    sidebar.classList.remove("-left-0");
    sidebar.classList.add("-left-60");

    overlay.classList.remove("opacity-50");
    overlay.classList.add("opacity-0");
}

const hamburgerSvg = document.getElementById("hamburger-svg");

hamburger.addEventListener("mousedown", () => {
    hamburgerSvg.classList.add("scale-75");

    setTimeout(() => {
        hamburgerSvg.classList.remove("scale-75");
    }, 100);
});
