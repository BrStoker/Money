const mix = require('laravel-mix');
const config = require('./webpack.config')

mix.webpackConfig(config)
    

mix.js('resources/js/app.js', 'public/js').vue()
    .sass('resources/sass/app.scss', 'public/css')
    .copy('node_modules/jquery/dist/jquery.min.js', 'public/js');
    
if (mix.inProduction()) {
    
    mix.copyDirectory('resources/fonts', 'public/fonts')
        .copyDirectory('resources/svg', 'public/svg')
        .copyDirectory('resources/image', 'public/image')
        .copyDirectory('resources/html', 'public/html');

}

mix.version();