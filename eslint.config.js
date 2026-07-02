const js = require("@eslint/js");

module.exports = [
  js.configs.recommended,
  {
    files: ["assets/js/**/*.js"],
    languageOptions: {
      ecmaVersion: 2021,
      sourceType: "script",
      globals: {
        window: "readonly",
        document: "readonly",
        gtag: "readonly",
        bccConfig: "readonly",
      },
    },
  },
];
