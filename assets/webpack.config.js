

const path = require( 'path' );
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' );
const OptimizeCssAssetsPlugin = require( 'optimize-css-assets-webpack-plugin' );
const cssnano = require( 'cssnano' );
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' ); //Automatically removes unused webpack assets on rebuild
const UglifyJsPlugin = require( 'uglifyjs-webpack-plugin' );

const JS_DIR = path.resolve( __dirname, 'src/js' );
const IMG_DIR = path.resolve( __dirname, 'src/img' );
const LIB_DIR = path.resolve( __dirname, 'src/library' );
const BUILD_DIR = path.resolve( __dirname, 'build' );

const entry = {
    main: JS_DIR + '/main.js',
    admin: JS_DIR + '/admin.js',
};

const output = {
    path: BUILD_DIR,
    filename: 'js/[name].js'
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
			'css-loader',
			'sass-loader',
		]
	},
	{
		test: /\.(png|jpg|svg|jpeg|gif|ico)$/,
		use: {
			loader: 'file-loader',
			options: {
				name: '[path][name].[ext]',
				publicPath: 'production' === process.env.NODE_ENV ? '../' : '../../'
			}
		}
	},
	{
		test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/,
		exclude: [ IMG_DIR, /node_modules/ ],
		use: {
			loader: 'file-loader',
			options: {
				name: '[path][name].[ext]',
				publicPath: 'production' === process.env.NODE_ENV ? '../' : '../../'
			}
		}
	}
];

/**
 * Note: argv.mode will return 'development' or 'production'.
 */
const plugins = ( argv ) => [

    new CleanWebpackPlugin( {
		cleanStaleWebpackAssets: ( 'production' === argv.mode  ) // Automatically remove all unused webpack assets on rebuild, when set to true in production.
	    } 
    ),

	new MiniCssExtractPlugin( {
		filename: 'css/[name].css'
	    } 
    ),
];

module.exports = ( env, argv ) => ({
    entry: entry,
    output: output,
    devtool: 'source-map',
    module: {
		rules: rules,
	},
    optimization: {
		minimizer: [
			new OptimizeCssAssetsPlugin( {
				cssProcessor: cssnano
			} ),

			new UglifyJsPlugin( {
				cache: false,
				parallel: true,
				sourceMap: false
			} )
		]
	},

	plugins: plugins( argv ),

	externals: {
		jquery: 'jQuery'
	}
});