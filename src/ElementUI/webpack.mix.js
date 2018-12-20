
let mix = require('laravel-mix')

mix.setPublicPath('dist')
  .js('resources/js/nova-element-ui.js', 'js')
  .sass('resources/sass/nova-element-ui.scss', 'css')

  
