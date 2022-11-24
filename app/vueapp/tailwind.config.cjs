/** @type {import('tailwindcss').Config} */
const colors = require('tailwindcss/colors')
module.exports = {
  darkMode: 'class',
  corePlugins: {
    preflight: false,
  },
  content: [
    "./index.html",
    "./src/**/*.{vue,js,ts,jsx,tsx}",
  ],
  theme: {
    screens: {
      xs: '480px',
      sm: '640px',
      md: '768px',
      lg: '1000px',
      xl: '1024px'
    },
    fontFamily: {
      serif: ['Garamond, Baskerville, Baskerville Old Face, Hoefler Text, Times New Roman, serif'],
      sans: ['Helvetica Neue, Helvetica, Arial, sans-serif'],
    },
    extend: {
      // range: {
      //   sm: ''
      // },
      colors: {
        transparent: 'transparent',
        current: 'currentColor',
        primary: '#5c6ac4',
        secondary: '#ecc94b',
        neutral: colors.gray,
        black: colors.black,
        white: colors.white,
        gray: colors.gray,
        emerald: colors.emerald,
        indigo: colors.indigo,
        yellow: colors.yellow,
        'midnight': '#121063',
        'metal': '#565584',
        'tahiti': '#3ab7bf',
        'silver': '#ecebff',
        'bubble-gum': '#ff77e9',
        'bermuda': '#78dcca',
        'gray-light': '#ccc',
        'blue': {
          500: 'steelblue'
        },
        'gold': {
          gold: '#ffe484',
          100: 'rgb(251,217,93)', // '#fbd95d'
          200: 'rgb(250,211,68)', // '#fad344'
          300: 'rgb(250,206,43)', // '#face2b'
          400: 'rgb(249,200,19)', // '#f9c813'
          500: 'rgb(236,187,6)', // '#ecbb06',
          600: 'rgb(211,167,5)', // '#d3a705',
          700: 'rgb(186,148,5)', // '#ba9405',
          800: 'rgb(161,128,4)', // '#a18004',
          900: 'rgb(136,108,3)', // '#886c03'
        },
        'purple': {
          50: 'rgb(250, 249, 251)',
          100: 'rgb(241, 236, 243)',
          200: 'rgb(223, 211, 228)',
          300: 'rgb(204, 185, 212)',
          400: 'rgb(185, 160, 197)',
          500: '#8c629f',
          600: 'rgb(117, 81, 133)',
          700: 'rgb(106, 74, 120)',
          800: 'rgb(95, 66, 108)',
          900: 'rgb(83, 58, 95)',
        },
        success: '#d4edda',
        error: '#f8d7da',
        warning: '#fff3cd',
        info: '#d1ecf1',
      },
    },
  },
  plugins: [],
}
