/**
 * @package Mix Asset Management
 *
 * @developer Alfredo Flores
 * @email alfredo@xerosoft.com
 */

const path = require("path");
const fs = require("fs-extra");
const mix = require("laravel-mix");
const reload = require("webpack-livereload-plugin");

mix.sass("resources/sass/app.scss", "public/dist/css");
mix.js("resources/js/app.js", "public/dist/js");

mix.disableNotifications();

if (mix.inProduction()) {
  require("laravel-mix-versionhash");
  mix.versionHash();

  // mix.extract() // Disabled until resolved: https://github.com/JeffreyWay/laravel-mix/issues/1889
  // mix.version() // Use `laravel-mix-versionhash` for the generating correct Laravel Mix manifest file.
} else {
  mix.sourceMaps();
}

mix.webpackConfig({
  plugins: [new reload()],
  resolve: {
    alias: {
      "@Components": path.join(__dirname, "./resources/js/components"),
      "@Public": path.join(__dirname, "./public"),
      "@Scripts": path.join(__dirname, "./resources/js"),
      "@Styles": path.join(__dirname, "./resources/sass"),
      "@SDT": path.join(__dirname, "./resources/js/components/datatable/server/index.js")
    }
  },
  output: {
    chunkFilename: "dist/js/[chunkhash].js",
    path: mix.config.hmr ? "/" : path.resolve(__dirname, "./public/build")
  }
});

mix.options({
  processCssUrls: false,
  postCss: [require("autoprefixer")]
});

mix.then(() => {
  if (!mix.config.hmr) {
    process.nextTick(() => publishAseets());
  }
});

function publishAseets() {
  const publicDir = path.resolve(__dirname, "./public");

  if (mix.inProduction()) {
    fs.removeSync(path.join(publicDir, "dist"));
  }

  fs.copySync(path.join(publicDir, "build", "dist"), path.join(publicDir, "dist"));
  fs.removeSync(path.join(publicDir, "build"));
}

// See laracasts episode to understand. SVG Renderer
// https://laracasts.com/series/whatcha-working-on/episodes/38
// https://laracasts.com/series/practical-vue-components/episodes/6
mix.override(config => {
  config.module.rules.find(rule => rule.test.test(".svg")).exclude = /\.svg$/;

  config.module.rules.push({
    test: /\.svg$/,
    use: [{ loader: "html-loader" }]
  });
});
