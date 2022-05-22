const mix = require('laravel-mix')
const path = require('path')

require('./nova.mix')

mix
  .setPublicPath("dist")
  .js("resources/js/field.js", "js")
  .vue({ version: 3 })
  .nova("ctessier/nova-advanced-image-field");
