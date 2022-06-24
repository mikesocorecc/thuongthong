/** */
let mix = require('laravel-mix');
mix.webpackConfig({
    resolve: {
        // options for resolving module requests
        // (does not apply to resolving of loaders)
        modules: [__dirname, 'node_modules']
    },
    stats: {
        children: false,
        warnings: false
    },
    externals: {
        // require("jquery") is external and available
        //  on the global var jQuery
        jquery: 'jQuery'
    },
    devtool: 'source-map'
});

mix.disableNotifications()
    .options({
        processCssUrls: false,
        postCss: [
            require('autoprefixer')({
                // Browserslist’s default browsers (> 0.5%, last 2 versions, Firefox ESR, not dead).
                //browsers: ['defaults'],
                grid: true
            })
        ]
    });

// mix.copyDirectory('resources/img', 'themes/img')
//     .copyDirectory('resources/fonts/SVN-Poppins', 'themes/fonts/SVN-Poppins')
//     .copyDirectory('resources/fonts/fontawesome/webfonts', 'themes/webfonts')
//     .copyDirectory('resources/js/plugins', 'themes/js/plugins');

mix.setPublicPath('themes')
    .sourceMaps()

    //.js('resources/js/draggable.js', 'js/plugins')
    .js('resources/js/app.js', 'js')

    //.sass('resources/sass/fonts.scss', 'css')
    .sass('resources/sass/plugins.scss', 'css')
    .sass('resources/sass/app.scss', 'css');