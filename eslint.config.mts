import js from '@eslint/js';
import globals from 'globals';
import tseslint from 'typescript-eslint';
import { defineConfig, globalIgnores } from 'eslint/config';
import eslintConfigPrettier from 'eslint-config-prettier';

export default defineConfig([
  eslintConfigPrettier,
  globalIgnores([
    'app/',
    'build/',
    'builds/',
    'node_modules/',
    'playwright-report/',
    'public/',
    'test-results/',
    'tests/',
    'vendor/',
    'writable/',
    '.github/',
  ]),

  js.configs.recommended,
  ...tseslint.configs.recommended,

  {
    files: ['e2e/**/*.ts'],

    languageOptions: {
      globals: {
        ...globals.node,
      },
    },

    rules: {
      '@typescript-eslint/no-explicit-any': 'off',
      '@typescript-eslint/no-unused-vars': [
        'error',
        {
          argsIgnorePattern: '^_',
          varsIgnorePattern: '^_',
          caughtErrorsIgnorePattern: '^_',
        },
      ],
    },
  },
]);