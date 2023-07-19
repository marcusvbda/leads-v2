/** @type {import('tailwindcss').Config} */
module.exports = {
    darkMode: 'class',
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.vue",
        "./vendor/marcusvbda/vstack/**/*.vue",
        "./vendor/marcusvbda/vstack/**/*.blade.php",
    ],
    theme: {
        extend: { },
    },
    plugins: [],
}