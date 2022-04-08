const mix = require('laravel-mix')
const path = require('path')

require('./mix')

mix
  .setPublicPath("dist")
  .js("resources/js/field.js", "js")
  .vue({ version: 3 })
  .webpackConfig({
    resolve: {
      alias: {
        '@': path.resolve(__dirname, 'resources/js/'),
      },
    },
  })
  .nova("ctessier/nova-advanced-image-field");
