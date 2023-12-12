module.exports = {
  content: ["./src/**/*.{js,jsx,ts,tsx}"],
  theme: {
    fontFamily: {
      sans: ['"Roboto"', "sans-serif"],
    },
  },
  variants: {
    extend: {
      opacity: ["disabled"],
      border: ["disabled"],
    },
  },
  plugins: [require("@tailwindcss/line-clamp")],
};
