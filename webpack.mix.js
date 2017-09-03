let mix = require('laravel-mix');
let fs = require('fs');
let prod = mix.config.inProduction;
const argv = process.env.npm_config_argv;

mix.browserSync({
    proxy: 'l5.dev',
    open: false
});


/*************************************
 JAVASCRIPT
 *************************************/

/*
Vendor Libraries
 */
mix.scripts([
        'node_modules/jquery/dist/jquery.js',
        'node_modules/popper.js/dist/umd/popper.js',
        'node_modules/bootstrap/dist/js/bootstrap.js',
        'node_modules/jquery-form/dist/jquery.form.min.js',
        'node_modules/toastr/build/toastr.min.js',
        'node_modules/malihu-custom-scrollbar-plugin/jquery.mCustomScrollbar.js',
        'node_modules/sweetalert2/dist/sweetalert2.js',
        'resources/assets/js/vendor/jquery-ui.min.js',
        'resources/assets/js/vendor/formvalidation/formValidation.popular.min.js',
        'resources/assets/js/vendor/formvalidation/bootstrap4.min.js',
        'resources/assets/js/vendor/jquery.dataTables.js',
        'resources/assets/js/vendor/dataTables.bootstrap4.min.js',
        'resources/assets/js/vendor/jquery.simpler-sidebar.js',
        'resources/assets/js/vendor/purl.js'
    ],
    'public/assets/js/vendor.' + (prod ? 'min.' : '') + 'js'
);

/*
App Specific
 */
// core js
mix.js('resources/assets/js/core.js', 'public/assets/js/core.' + (prod ? 'min.' : '') + 'js');
// js modules
fs.readdirSync('resources/assets/js/modules/').forEach(function(file) {
    let outputFile = prod ? file.replace('.js', '.min.js') : file;
    mix.babel('resources/assets/js/modules/' + file, 'public/assets/js/modules/' + outputFile);
});


/*************************************
 CSS
 *************************************/

/*
 App Specific
 */
// core css
//mix.sass('resources/assets/sass/core.scss', 'assets/css/core.' + (prod ? 'min.' : '') + 'css');
// css skins
if ( argv.match(/skins/) === null ) {
    mix.sass('resources/assets/sass/skins/blue.scss', 'assets/css/skins/blue.' + (prod ? 'min.' : '') + 'css');
} else {
    fs.readdirSync('resources/assets/sass/skins/').forEach(function(file) {
        let outputFile = file.replace('.scss', '.css');
        outputFile = prod ? outputFile.replace('.css', '.min.css') : outputFile;
        mix.sass('resources/assets/sass/skins/' + file, 'public/assets/css/skins/' + outputFile);
    });
}

// css modules
/*fs.readdirSync('resources/assets/sass/modules/').forEach(function(file) {
    let outputFile = file.replace('.scss', '.css');
    outputFile = prod ? outputFile.replace('.css', '.min.css') : outputFile;
    mix.sass('resources/assets/sass/modules/' + file, 'public/assets/css/modules/' + outputFile);
});*/


/*************************************
 Copy Files
 *************************************/

mix.copyDirectory('resources/assets/images/', 'public/assets/images/');