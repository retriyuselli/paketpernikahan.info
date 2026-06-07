import "./bootstrap";
import Alpine from "alpinejs";
window.Alpine = Alpine;
Alpine.start();
import "./capacitor-oauth";
import "./capacitor-push";

// Dark Mode Toggle
document.addEventListener("DOMContentLoaded", function () {
    const themeToggle = document.getElementById("theme-toggle");
    const themeIcon = document.getElementById("theme-icon");
    const htmlElement = document.documentElement;

    // Check for saved theme preference or default to light mode
    const currentTheme = localStorage.getItem("theme") || "light";
    htmlElement.classList.toggle("dark", currentTheme === "dark");
    if (themeIcon) themeIcon.textContent = currentTheme === "dark" ? "☀️" : "🌙";

    // Toggle theme on button click
    if (!themeToggle) return;
    themeToggle.addEventListener("click", function () {
        const isDark = htmlElement.classList.contains("dark");

        if (isDark) {
            htmlElement.classList.remove("dark");
            localStorage.setItem("theme", "light");
            if (themeIcon) themeIcon.textContent = "🌙";
        } else {
            htmlElement.classList.add("dark");
            localStorage.setItem("theme", "dark");
            if (themeIcon) themeIcon.textContent = "☀️";
        }
    });
});
