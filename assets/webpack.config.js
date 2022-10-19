
const path = require( 'path' ); //Main Path
const MiniCssExtractPlugin = require( 'mini-css-extract-plugin' ); //Plugin to extract a css file for each js file. Supports On-demand loading of CSS and Source Maps
const CssMinimizerPlugin = require("css-minimizer-webpack-plugin"); //Plugin to optimize and minify css
const { CleanWebpackPlugin } = require( 'clean-webpack-plugin' ); //Automatically removes unused webpack assets on rebuild

const JS_DIR = path.resolve( __dirname, 'src/js' ); //Javascript Directory
const IMG_DIR = path.resolve( __dirname, 'src/img' ); //Images Directory
const BUILD_DIR = path.resolve( __dirname, 'build' ); //Build Directory

const entry = { //Entrypoint Constant JSON
    main: JS_DIR + '/main.js', //Main.js Entry Point
    admin: JS_DIR + '/admin.js', //Admin.js Entry Point
};

const output = { //Endpoint Constant JSON
    path: BUILD_DIR, //Path
    filename: 'js/[name].js', //Output Filename for JS files
};

const rules = [ //Rules
    { //Javascript Test Case
		test: /\.js$/, //Extensions
		include: [ JS_DIR ], //Include Directory
		exclude: /node_modules/, //Exclude Directory
		use: 'babel-loader' //Babel Loader
	},
	{ //SASS Test Case
		test: /\.scss$/, //Extension
		exclude: /node_modules/, //Exclude Directory
		use: [
			MiniCssExtractPlugin.loader, //MiniCssExtractPlugin.loader,
			'css-loader', //CSS Loader
			'sass-loader', //SASS Loader
		]
	},
	{ //Images Test Case
		test: /\.(png|jpg|svg|jpeg|gif|ico)$/, //Extensions
		type: 'asset/resource', //Use the Asset Resource Module
		generator: {
			filename: 'img/[hash][ext][query]', //Filename for the Output
		},
	},
	{ //Font Test Case
		test: /\.(ttf|otf|eot|svg|woff(2)?)(\?[a-z0-9]+)?$/, //Extensions
		exclude: [ IMG_DIR, /node_modules/ ], //Exclude
		type: 'asset/resource', //Use the Asset Resource Module
		generator: {
			filename: 'font/[hash][ext][query]', //Filename for the Output
		},
	}
];

/**
 * Note: argv.mode will return 'development' or 'production'.
 */

const plugins = ( argv ) => [ //Plugins

    new CleanWebpackPlugin( { //CleanWebpack Plugin
		cleanStaleWebpackAssets: ( 'production' === argv.mode  )
	    } 
    ),

	new MiniCssExtractPlugin( { //MiniCssExtractPlugin
		filename: 'css/[name].css' //Directory
	    }
    ),
];

module.exports = ( env, argv ) => ({
    entry: entry, //Entry Point
    output: output, //End Point
    devtool: 'source-map', //Source Map Style
    module: { //Modules
		rules: rules, //Rules
	},
    optimization: { //Optimization
		minimizer: [ //Minification
			new CssMinimizerPlugin(), //Css Minification Plugin
		]
	},

	plugins: plugins( argv ), //Plugins

	externals: { //Externals
		jquery: 'jQuery' //jQuery
	}
});