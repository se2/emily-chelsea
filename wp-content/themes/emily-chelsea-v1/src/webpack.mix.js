const path = require("path");
const glob = require("glob");
const mix = require("laravel-mix");
const publicDir = "./dist";

mix.sourceMaps(true, "source-map")
	.sass("assets/scss/base.scss", publicDir + "/css/base.min.css")
	.options({
		processCssUrls: false,
	});

glob.sync("assets/scss/components/*.scss").forEach((file) => {
	const { name } = path.parse(file);
	mix.sourceMaps(true, "source-map")
		.sass(file, `${publicDir}/css/components/${name}.css`)
		.options({
			processCssUrls: false,
		});
});

glob.sync("assets/scss/pages/*.scss").forEach((file) => {
	const { name } = path.parse(file);
	mix.sourceMaps(true, "source-map")
		.sass(file, `${publicDir}/css/pages/${name}.css`)
		.options({
			processCssUrls: false,
		});
});

glob.sync("assets/js/components/*.js").forEach((file) => {
	const { name } = path.parse(file);
	mix.js(file, `${publicDir}/js/components/${name}.js`);
});

glob.sync("assets/js/pages/*.js").forEach((file) => {
	const { name } = path.parse(file);
	mix.js(file, `${publicDir}/js/pages/${name}.js`);
});

mix.copy("assets/img", `${publicDir}/img`);
