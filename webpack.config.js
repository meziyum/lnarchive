
import path from 'path';
import MiniCssExtractPlugin from 'mini-css-extract-plugin';
import CssMinimizerPlugin from 'css-minimizer-webpack-plugin';
import TerserPlugin from 'terser-webpack-plugin';
import {CleanWebpackPlugin} from 'clean-webpack-plugin';

const JS_DIR = path.resolve('./src/js/pages');
const IMG_DIR = path.resolve('./src/img');
const BUILD_DIR = path.resolve('./assets');

const entry = {
    admin: `${JS_DIR}/admin/admin.js`,
    novel: `${JS_DIR}/novel/novel.tsx`,
    post: `${JS_DIR}/post/post.js`,
    page: `${JS_DIR}/page/page.js`,
    library: `${JS_DIR}/library/library.js`,
    blog: `${JS_DIR}/blog/blog.js`,
    default: `${JS_DIR}/default.js`,
    calendar: `${JS_DIR}/calendar/calendar.js`,
    profile: `${JS_DIR}/profile/profile.tsx`,
    add_data: `${JS_DIR}/add_data/add_data.js`,
    reading_list: `${JS_DIR}/reading_list/reading_list`,
    login: `${JS_DIR}/login/login.js`,
};

const output = {
    path: BUILD_DIR,
    filename: 'js/[name].js',
};

const rules = [
    {
        test: /\.(js|jsx|ts|tsx)$/,
        exclude: /node_modules/,
        use: {
            loader: 'babel-loader',
            options: {
                presets: ['@babel/preset-env', '@babel/preset-react', '@babel/preset-typescript'],
            },
        },
    },
    {
        test: /\.scss$/,
        exclude: /node_modules/,
        use: [
            MiniCssExtractPlugin.loader,
            {loader: 'css-loader', options: {
                sourceMap: true,
            }},
            {loader: 'postcss-loader'},
            {loader: 'sass-loader', options: {
                sourceMap: true,
            }},
        ],
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
        exclude: [IMG_DIR, /node_modules/],
        type: 'asset/resource',
        generator: {
            filename: 'font/[hash][ext][query]',
        },
    },
];

const plugins = (argv) => [
    new CleanWebpackPlugin({
        cleanStaleWebpackAssets: 'production' === argv.mode,
    }),

    new MiniCssExtractPlugin({
        filename: 'css/[name].css',
    }),
];

export default (env, argv) => ({
    entry,
    output,
    resolve: {
        extensions: ['.js', '.jsx', '.ts', '.tsx'],
    },
    module: {
        rules,
    },
    optimization: {
        minimize: argv.mode === 'production',
        minimizer: [
            new CssMinimizerPlugin({
                parallel: true,
            }),
            new TerserPlugin(),
        ],
    },

    plugins: plugins(argv),

    externals: {
        jquery: 'jQuery',
    },
});
