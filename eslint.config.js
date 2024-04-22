import f3veEslintConfig from '@f3ve/eslint-config';

export default [
  ...f3veEslintConfig({
    vue: true,
    typescript: true,
    browser: true,
    prettier: true,
  }),
];
