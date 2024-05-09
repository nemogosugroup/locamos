var path = require("path");
const webpack = require('webpack');
const SVGSpritemapPlugin = require('svg-sprite-loader/plugin');
function resolve(dir) {
    return path.join(__dirname, "/resources/js", dir);
}

module.exports = {
    resolve: {
        extensions: [".*",".wasm",".mjs",".js",".jsx",".json",".vue"],
        alias: {
            "@": path.join(__dirname, "/resources/js"),
            "@style": path.join(__dirname, "/resources/sass"),
            "@image": path.join(__dirname, "/resources/images")
        },
        fallback: {
            "path": require.resolve("path-browserify")
        }
    },
    module: {
      rules: [
        {
          test: /\.vue$/,
          loader: 'vue-loader'
        },
        {
          test: /\.js$/,
          loader: 'babel-loader',
          exclude: /node_modules/
        },
        {
          test: /\.scss$/,
          use: [
            'sass-loader',
          ],
        },
      ]
    },
    plugins: [
        new webpack.DefinePlugin({
          __VUE_PROD_HYDRATION_MISMATCH_DETAILS__: JSON.stringify(true),
        }),
    ],
    output: {
      chunkFilename: 'js/main/[name].js?id=[chunkhash]',
    }
};
