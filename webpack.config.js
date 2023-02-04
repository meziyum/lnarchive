
const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin");
const TerserPlugin = require("terser-webpack-plugin");
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' );

const JS_DIR = path.resolve( __dirname, 'src/js' );
const IMG_DIR = path.resolve( __dirname, 'src/img' );
const BUILD_DIR = path.resolve( __dirname, 'assets' );

const entry = {
    main: JS_DIR + '/main.js',
    admin: JS_DIR + '/admin.js',
	novel: JS_DIR + '/novel.js',
	post: JS_DIR + '/post.js',
	page: JS_DIR + '/page.js',
	archive: JS_DIR + '/archive.js',
	archive_post: JS_DIR + '/archive-post.js',
	search: JS_DIR + '/search.js',
};

const output = {
    path: BUILD_DIR,
    filename: 'js/[name].js',
};

const rules = [
    {
		test: /\.js$/,
		include: [ JS_DIR ],
		exclude: /node_modules/,
		use: 'babel-loader'
	},
	{
		test: /\.scss$/,
		exclude: /node_modules/,
		use: [
			MiniCssExtractPlugin.loader,
			{ loader: "css-loader", options: { sourceMap: true } },
			{ loader: "postcss-loader", },
          	{ loader: "sass-loader", options: { sourceMap: true } },
		]
	},
	{
		test: /\.(png|jpg|svg|jpeg|gif|ICO)$/,
		type: 'asset/resource',
		generator: {
			filename: 'img/[hash][ext][query]',
		},
	},
	{
		test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
		exclude: [ IMG_DIR, /node_modules/ ],
		type: 'asset/resource',
		generator: {
			filename: 'font/[hash][ext][query]',
		},
	}
];

const plugins = ( argv ) => [
	require('autoprefixer'),

    new CleanWebpackPlugin( {
		cleanStaleWebpackAssets: ( 'production' === argv.mode  )
	    }
    ),

	new MiniCssExtractPlugin( {
		filename: 'css/[name].css'
	    }
    ),
];

module.exports = (env, argv) => ({
    entry: entry,
    output: output,
    devtool: 'inline-source-map',
    module: {
		rules: rules,
	},
    optimization: {
		minimize: argv.mode === 'production',
		minimizer: [
			new CssMinimizerPlugin({
				parallel: true,
			}),
			new TerserPlugin(),
		]
	},

	plugins: plugins(argv),

	externals: {
		jquery: 'jQuery'
	}
});