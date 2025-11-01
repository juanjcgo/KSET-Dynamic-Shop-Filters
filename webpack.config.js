const path = require('path');

module.exports = {
    mode: 'development',
    entry: {
        frontend: './src/frontend/index.js',
        backend: './src/backend/index.js'
    },
    output: {
        path: path.resolve(__dirname, 'assets/js/dist'),
        filename: '[name]/bundle.js'
    },
    module: {
        rules: [
            {
                test: /\.jsx?$/,
                exclude: /node_modules/,
                use: {
                    loader: 'babel-loader',
                    options: {
                        presets: [
                            '@babel/preset-env',
                            '@babel/preset-react'
                        ],
                        plugins: [
                            '@babel/plugin-transform-class-properties'
                        ]
                    }
                }
            },
            {
                test: /\.css$/,  // Agrega esta regla para manejar archivos CSS
                use: ['style-loader', 'css-loader']
            }
        ]
    },
    resolve: {
        extensions: ['.js', '.jsx']
    }
};