import fs from "fs";

import esbuild from "esbuild";
import browserslistToEsbuild from "browserslist-to-esbuild";
import { sassPlugin } from "esbuild-sass-plugin";
import autoprefixer from "autoprefixer";
import postcss from "postcss";

const prod = process.argv[2] === "production";

const entryTS = ["home-page", "form", "create-ad-page"];
const entrySASS = [
  "page",
  "home",
  "item",
  "icon",
  "form",
  "login-register",
  "create-ad",
  "ol",
  "item-page",
];

//TODO: uglify mangle
esbuild
  .build({
    entryPoints: [
      ...entryTS.map((file) => `src/script/${file}.ts`),
      ...entrySASS.map((file) => `src/styles/${file}.sass`),
    ],
    bundle: true,
    external: ["/fonts/*", "/images/*"],
    format: "esm",
    watch: !prod,
    target: browserslistToEsbuild(["cover 95%", "not dead"]),
    logLevel: "info",
    sourcemap: prod ? false : "inline",
    minify: prod,
    treeShaking: true,
    outdir: "src/php/public",
    plugins: [
      sassPlugin({
        transform: async (source) => {
          const { css } = await postcss([autoprefixer]).process(source, {
            from: undefined,
          });
          return css;
        },
      }),
    ],
  })
  .catch((reason) => {
    console.log(reason);
    process.exit(1);
  });
