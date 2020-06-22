const Encore = require('@symfony/webpack-encore');

Encore
    .setOutputPath('public/build/')
    .setPublicPath('/build')
    .cleanupOutputBeforeBuild()
    .enableSingleRuntimeChunk()
    .enableSourceMaps(!Encore.isProduction())
    .enableVersioning(Encore.isProduction())
    .addEntry('app', './assets/app.js')
    .copyFiles({
        from: './assets/img',
        to: Encore.isProduction() ? 'img/[path][name].[hash:8].[ext]' : 'img/[path][name].[ext]',
    })
    .enableSassLoader()
    .enableLessLoader()
    .disableSingleRuntimeChunk()
    .autoProvidejQuery()
    .splitEntryChunks()
;

module.exports = Encore.getWebpackConfig();
