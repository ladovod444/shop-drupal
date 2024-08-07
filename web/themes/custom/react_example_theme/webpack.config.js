const path = require('path');
const ReactRefreshWebpackPlugin = require('@pmmmwh/react-refresh-webpack-plugin');
const isDevMode = process.env.NODE_ENV !== 'production';
const { EnvironmentPlugin } = require('webpack');

const Dotenv = require('dotenv-webpack');
const PROXY = 'https://shop-site.ddev.site/';
const PUBLIC_PATH = '/themes/react_example_theme/js/dist_dev/';

const config = {
  entry: {
    main: ["./js/src/index.jsx"]
  },
  devtool: (isDevMode) ? 'source-map' : false,
  mode: (isDevMode) ? 'development' : 'production',
  output: {
    path: isDevMode ? path.resolve(__dirname, "js/dist_dev") : path.resolve(__dirname, "js/dist"),
    filename: '[name].min.js',
    publicPath: PUBLIC_PATH
  },
  resolve: {
    extensions: ['.js', '.jsx'],
    fallback: { "crypto": false, "os": false, "path": false }
  },
  module: {
    rules: [
      {
        test: /\.jsx?$/,
        loader: 'babel-loader',
        exclude: /node_modules/,
        include: path.join(__dirname, 'js/src'),
        options: {
          // This is a feature of `babel-loader` for webpack (not Babel itself).
          // It enables caching results in ./node_modules/.cache/babel-loader/
          // directory for faster rebuilds.
          cacheDirectory: true,
          plugins: [
            isDevMode && require.resolve('react-refresh/babel')
          ].filter(Boolean),
        },
      }
    ],
  },

  // plugins: [
  //   // ...
  //   // new EnvironmentPlugin({
  //   //   NODE_ENV: 'development',
  //   //   REACT_APP_SHOP_URL: 'https://shop-site.ddev.site'
  //   // })
  //   ////new EnvironmentPlugin((require('./js/process.env')) )
  // ]
  plugins: [
    // new EnvironmentPlugin({
    //   // Make a global `process` variable that points to the `process` package,
    //   // because the `util` package expects there to be a global variable named `process`.
    //   // Thanks to https://stackoverflow.com/a/65018686/14239942
    //   process: 'process/browser'
    // })
    new Dotenv({
      path: './js/.env', // Path to .env file (this is the default)
      safe: false, // load .env.example (defaults to "false" which does not use dotenv-safe)
    }),

    isDevMode && new ReactRefreshWebpackPlugin(),
  ].filter(Boolean),
  devServer: {
    port: 8182,
    hot: true,
    headers: { 'Access-Control-Allow-Origin': '*' },
    devMiddleware: {
      writeToDisk: true,
    },
    // Settings for http-proxy-middleware.
    proxy: [
      {
        index: '',
        context: ['/'],
        target: PROXY,
        publicPath: PUBLIC_PATH,
        secure: false,
        // These settings allow Drupal authentication to work, so you can sign
        // in to your Drupal site via the proxy. They require some corresponding
        // configuration in Drupal's settings.php.
        changeOrigin: true,
        xfwd: true
      }
    ]
  },
};

module.exports = config;
