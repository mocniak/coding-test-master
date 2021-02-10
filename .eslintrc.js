module.exports = {
    "ignorePatterns": [
        'vendor',
        'node_modules',
        'public',
        'jest.config.js',
        'webpack.config.js',
        'postcss.config.js',
    ],
    root: true,
    parser: '@typescript-eslint/parser',
    parserOptions: {
        tsconfigRootDir: __dirname,
        project: './tsconfig.json',
    },
    plugins: ['react-hooks'],
    extends: [
        'eslint:recommended',
        'plugin:@typescript-eslint/recommended',
        'plugin:@typescript-eslint/recommended-requiring-type-checking',
        'plugin:react/recommended',
        'plugin:jest/recommended',
        'plugin:jest/style',
        'plugin:prettier/recommended',
        'prettier/@typescript-eslint',
        'prettier/react',
    ],
    rules: {
        'react/prop-types': 'off',
        '@typescript-eslint/explicit-module-boundary-types': 'off',
        '@typescript-eslint/unbound-method': 'off',
        'react-hooks/rules-of-hooks': 'error',
        'react-hooks/exhaustive-deps': 'warn',
        "no-restricted-imports": ["error", "react-redux/useSelector", "react-redux/useDispatch"]
    },
    settings: {
        react: {
            version: 'latest'
        }
    }
};
