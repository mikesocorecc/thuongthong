let mix = require('laravel-mix');
//const purgeCss = require('@fullhuman/postcss-purgecss');

const path = require('path');
let directory = path.basename(path.resolve(__dirname));

const dir = 'wp-content/themes/' + directory;
const assets = dir + '/assets';

mix
    .disableNotifications()

    // .copyDirectory(dir + '/resources/img', assets + '/assets/img')
    // .copyDirectory(dir + '/resources/fonts/SVN-Poppins', assets + '/assets/fonts/SVN-Poppins')
    // .copyDirectory(dir + '/resources/fonts/fontawesome/webfonts', assets + '/assets/webfonts')
    // .copyDirectory(dir + '/resources/js/plugins', assets + '/assets/js/plugins')

    //.sass(dir + '/resources/sass/fonts.scss', assets + '/css')
    //.sass(dir + '/resources/sass/admin.scss', assets + '/css')
    //.sass(dir + '/resources/sass/editor-style.scss', assets + '/css')
    .sass(dir + '/resources/sass/plugins.scss', assets + '/css')
    .sass(dir + '/resources/sass/app.scss', assets + '/css')

    //.js(dir + '/resources/js/login.js', assets + '/js')
    //.js(dir + '/resources/js/admin.js', assets + '/js')
    //.js(dir + '/resources/js/draggable.js', assets + '/js/plugins')
    //.js(dir + '/resources/js/parallax-scroll.js', assets + '/js/plugins')
    .js(dir + '/resources/js/app.js', assets + '/js');
