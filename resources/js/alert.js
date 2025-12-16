const alert = document.getElementById("alert");

if (alert) {
    alert.classList.add("opacity-0");
    setTimeout(() => {
        alert.classList.add("hidden");
    }, 5000);
}
