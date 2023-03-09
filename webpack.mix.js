/* ---
 Docs: https://www.npmjs.com/package/mati-mix/
 --- */
const mix = require( 'mati-mix' );

// Settings
mix.js( 'assets-src/js/app.js', 'assets/dist/app.js' );
mix.sass( 'assets-src/scss/app.scss', 'assets/dist/app.css' );

mix.mix.webpackConfig(
	{
		externals: {
			"@wordpress/i18n": [ "wp", "i18n" ]
		}
	}
);
