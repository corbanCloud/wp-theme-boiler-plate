# wp-theme-boiler-plate

Contains a number of helpful resources for creating a WP theme.
Directory structre as follows:

- /acf-json Contains a JSON file defining the ACF fields required for the theme
- /lib
	- gulpfile.js  : The themes gulpfile
	- package.json : npm packe file
	- /pub : The *public* theme assets
		- /css : master css file (regular + min)
		- /img : optimized images
		- /js  : js files (regualr + min & uglified)
	- /src : Contains the *source* files used to generate assets 
		- /img  : raw images
		- /js   : JS files
		- /maps : SASS and JS sources maps for debug/dev tools
		- /scss : SCSS Files
			- /modules : modules contain our various SCSS defintions, but no generate styles until called in partials
				- /functions : SCSS Functions, each function returns a value, does not print styles when called
				- /mixins : SCSS Mixins, each mixin prints one or more styles when called
				- /variables : SCSS variable definitions

			- /partials : Each page/page section gets its own partial, partials generate CSS
	
			- /vendor : 3rd party resources.
	- /php : Non template PHP resources, helper classes, functions, etc.
- /parts : wp template parts
