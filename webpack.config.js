const path = require('path')

module.exports = {
    module: {
        rules: [{
            test: /\.pug$/,
            loader: 'vue-pug-loader'
        },{
            test: /\.scss$/,
            use: [{
                loader: "sass-loader",
                options: {
                    additionalData: `@import "@/sass/global";`
                },
            }]
        }]
    },
    resolve: {
        extensions: ['.*'],
        alias: {
        '@': path.resolve(__dirname, './resources/'),
         'jQuery': path.resolve(__dirname, '/node_modules/jquery/dist/jquery.js')
        }
    }
}