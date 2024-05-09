const mix = require("laravel-mix");
const config = require("./webpack.config");
var path = require("path");
/*
 |--------------------------------------------------------------------------
 | Mix Asset Management
 |--------------------------------------------------------------------------
 |
 | Mix provides a clean, fluent API for defining some Webpack build steps
 | for your Laravel application. By default, we are compiling the Sass
 | file for the application as well as bundling up all the JS files.
 |
 */

mix.js("resources/js/app.js", "public/js")
    .vue()
    .sass("resources/sass/app.scss", "public/css", {
        sourceMap: true,
    })
    .postCss("resources/css/app.css", "public/css", [
        //
    ])
    .copy('resources/js/assets/images/logo', 'public/images/logo')
    .webpackConfig(config)