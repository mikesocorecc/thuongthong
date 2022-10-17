let mix = require('laravel-mix');
let glob = require('glob');

mix
    .sourceMaps()
    .webpackConfig({ devtool: 'source-map' })
    .options({
        processCssUrls: false,
        clearConsole: true,
        terser: {
            extractComments: false,
        },
        postCss: [
            require('autoprefixer')({
                //browsers: ['last 3 major versions', '>= 0.5%', 'iOS >= 12', 'Firefox ESR', 'not dead'],
                grid: true
            })
        ]
    });

// Run only for themes.
glob.sync('./wp-content/themes/**/webpack.mix.js').forEach(item => require(item));
