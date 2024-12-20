const mix = require('laravel-mix');
const path = require("path");

mix.webpackConfig({
    resolve: {
        alias: {
            "@": path.resolve(__dirname, "resources/js"),
        },
    },
});

mix.js('resources/js/app.js', 'public/js')
   .vue({ version: 2 })  // Ensure Vue 2 is used
   .sass('resources/sass/app.scss', 'public/css');
