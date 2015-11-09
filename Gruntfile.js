module.exports = function(grunt) {

// Load multiple grunt tasks using globbing patterns
require('load-grunt-tasks')(grunt);

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),

		// concat
		concat: {
			js: {
				options: {
					separator: ';'
					},
				src: ['assets/js/src/**/*.js'],
				dest: 'assets/js/<%= pkg.name %>.min.js'
				},
			},

		// uglify
		uglify: {
			options: {
				mangle: false
			},
			js: {
				files: {
					'assets/js/<%= pkg.name %>.min.js': ['assets/js/<%= pkg.name %>.min.js']
				}
			}
		},

		// watch our project for changes
		watch: {
			// JS
			js: {
				files: ['assets/js/src/**/*.js'],
				tasks: ['concat:js', 'uglify:js'],
					options: {
				// livereload: true,
				}
			},
		},

		// check missing textdomain
		checktextdomain: {
			options:{
				text_domain: '<%= pkg.pot.textdomain %>',
				create_report_file: true,
				keywords: [
					'__:1,2d',
					'_e:1,2d',
					'_x:1,2c,3d',
					'esc_html__:1,2d',
					'esc_html_e:1,2d',
					'esc_html_x:1,2c,3d',
					'esc_attr__:1,2d',
					'esc_attr_e:1,2d',
					'esc_attr_x:1,2c,3d',
					'_ex:1,2c,3d',
					'_n:1,2,4d',
					'_nx:1,2,4c,5d',
					'_n_noop:1,2,3d',
					'_nx_noop:1,2,3c,4d',
					' __ngettext:1,2,3d',
					'__ngettext_noop:1,2,3d',
					'_c:1,2d',
					'_nc:1,2,4c,5d'
					]
				},
				files: {
					src: [
						'**/*.php', // Include all files
						'!node_modules/**', // Exclude node_modules/
						],
					expand: true
				}
			},

		// makepot
		makepot: {
			target: {
		options: {
			domainPath: '<%= pkg.directories.languages %>', // Where to save the POT file.
				exclude: ['build/.*'],
				mainFile: '<%= pkg.pot.src %>', // Main project file.
				potFilename:  '<%= pkg.pot.textdomain %>' + '.pot', // Name of the POT file.
				potHeaders: {
					poedit: true, // Includes common Poedit headers.
					'x-poedit-keywordslist': true, // Include a list of all possible gettext functions.
					'report-msgid-bugs-to': '<%= pkg.pot.header.bugs %>',
					'last-translator': '<%= pkg.pot.header.last_translator %>',
					'language-team': '<%= pkg.pot.header.team %>',
					'language': 'en_US'
				},
				type: '<%= pkg.pot.type %>', // Type of project (wp-plugin or wp-theme).
				updateTimestamp: true, // Whether the POT-Creation-Date should be updated without other changes.
				updatePoFiles: false, // Whether to update PO files in the same directory as the POT file.
				processPot: function(pot, options) {
					var translation, // Exclude meta data from pot.
						excluded_meta = [
							'Plugin Name of the plugin/theme',
							'Plugin URI of the plugin/theme',
							'Author of the plugin/theme',
							'Author URI of the plugin/theme'
						];
					for (translation in pot.translations['']) {
						if ('undefined' !== typeof pot.translations[''][translation].comments.extracted) {
							if (excluded_meta.indexOf(pot.translations[''][translation].comments.extracted) >= 0) {
								console.log('Excluded meta: ' + pot.translations[''][translation].comments.extracted);
								delete pot.translations[''][translation];
							}
						}
					}
					return pot;
				}
			}
		}
	},

		// create .mo
		potomo: {
			dist: {
				options: {
				poDel: false // Set to true if you want to erase the .po
				},
				files: [{
					expand: true,
					cwd: '<%= pkg.directories.languages %>',
					src: ['*.po'],
					dest: '<%= pkg.directories.languages %>',
					ext: '.mo',
					nonull: true
				}]
			}
		},

		replace: {
			readme_txt: {
				src: [ 'readme.txt' ],
				overwrite: true,
				replacements: [{
					from: /Stable tag: (.*)/,
					to: "Stable tag: <%= pkg.version %>"
				}]
			},
			main_php: {
				src: [ '<%= pkg.pot.src %>' ],
				overwrite: true,
				replacements: [{
					from: / Version:\s*(.*)/,
					to: " Version: <%= pkg.version %>"
				}]
			}
		}
	});

// Default task.
	grunt.registerTask('default', ['concat', 'uglify' ]);

	grunt.registerTask('watch', [ 'watch' ]);

// i18n task.
	grunt.registerTask('build:i18n', ['checktextdomain', 'makepot', 'newer:potomo' ]);
};
