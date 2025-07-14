/** @type {import('tailwindcss').Config} */
module.exports = {
  content: [
    "./app/views/**/*.php", // Scan all PHP files in the views directory
    "./public/js/**/*.js"   // Scan JavaScript files if they manipulate classes
  ],
  theme: {
    extend: {
      colors: {
        // 'neon-purple': '#C026D3', // Example Neon Purple
        // 'brand-dark': '#1a1a1a',  // A very dark gray, near black
        // 'brand-gray': '#2d2d2d', // A slightly lighter dark gray
      }
    },
  },
  plugins: [],
}
