/** @type {import('tailwindcss').Config} */
export const content = ["php"];
export const theme = {
  extend: {},
};
export const plugins = [
  require("@tailwindcss/forms")({
    strategy: 'base',
    strategy: 'class', // only generate classes
  }),
];

