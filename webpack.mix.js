let mix = require("laravel-mix");
const path = require("path");

mix.disableNotifications();
mix.version();

mix
    .js("resources/panel/assets/js/theme-mode.js", "build/panel/js")
    .copy("resources/panel/assets/js/map.js", "public/build/panel/js/map.js")
    .copy("resources/panel/assets/js/moment.min.js", "public/build/panel/js/moment.min.js")
    .copy("resources/panel/assets/js/cropper.js", "public/build/panel/js/cropper.js")
    .copy("resources/panel/assets/js/common.js", "public/build/panel/js/common.js")
    .copy("resources/panel/assets/css/common.css", "public/build/panel/css/common.css")
    .copy("resources/panel/assets/css/cropper.css", "public/build/panel/css/cropper.css")
    .copyDirectory("resources/panel/assets/images", "public/build/panel/images");
mix.copyDirectory("resources/panel/assets/mv", "public/build/panel/vendors");

mix.copy("node_modules/@fancyapps/ui/dist/fancybox/fancybox.css", "public/build/vendor/fancybox");
mix.copy("node_modules/@fancyapps/ui/dist/fancybox/fancybox.umd.js", "public/build/vendor/fancybox");
