/** @type {import('tailwindcss').Config} */
export default {
  content: [
    "./resources/**/*.blade.php",
    "./resources/**/*.js",
    "./resources/**/*.vue",
  ],
  theme: {
    extend: {
      colors: {
        'doc-text': '#170c0d',
        'doc-bg': '#f1faee',
        'doc-primary': '#1d3557',
        'doc-secondary': '#a8dadc',
        'doc-accent': '#457b9d',
      },
      fontFamily: {
        'ubuntu': ['Ubuntu', 'sans-serif'],
      },
    },
  },
  plugins: [],
}