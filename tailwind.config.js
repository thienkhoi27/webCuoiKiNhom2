/** @type {import('tailwindcss').Config} */
export default {
    content: [
        "./resources/**/*.blade.php",
        "./resources/**/*.js",
        "./resources/**/*.vue",
    ],
    theme: {
        extend: {
            fontFamily: {
                Nunito: ["Nunito", "sans-serif"],
            },
            colors: {
                pinkSoft: "#ff69b4",   
                yellowSoft: "#f7d154", 
                blueSoft: "#4a90e2",  
            }
        },
    },
    plugins: [],
};
