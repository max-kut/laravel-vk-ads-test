// https://github.com/michael-ciniawsky/postcss-load-config

module.exports = {
  "plugins": {
    "postcss-import": {},
        "postcss-url": {},
        // to edit target browsers: use "browserslist" field in package.json
        autoprefixer: {},
        "css-mqpacker": {},
        "postcss-csso": {},
        "postcss-font-magician": {
            variants: {
                "Roboto Slab": {
                    "400": []
                },
                "PT Sans": {
                    "400": []
                }
            },
            foundries: ["google"]
        }
  }
}
