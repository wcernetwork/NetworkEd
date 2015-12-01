module.exports = function(grunt) {

	grunt.initConfig({
		pkg: grunt.file.readJSON('package.json'),
		paths: {
			assets: {
				js: './resources/assets/js/',
				less: './resources/assets/less/',
				vendor: './vendor/'
			},
			js: './public/assets/js/',
			css: './public/assets/css/',
			fonts: './public/assets/fonts/'
		},
		// Environment Settings
    	ngconstant: {
      		// Options for all targets
      		options: {
        		space: '  ',
        		wrap: '"use strict";\n\n {%= __ngModule %}',
        		name: 'config',
      		},
     		// Environment targets
      		development: {
        		options: {
          			dest: '<%= paths.assets.js %>config.js'
        		},
        		constants: {
          			ENV: {
            			name: 'development',
            			apiEndpoint: 'http://LOCALPATH/public/api/',
            			baseUrl: 'http://LOCALPATH/public/',
            			googleMapsKey: 'GOOGLE_MAPS_KEY'
          			}
        		}
      		},
     		production: {
        		options: {
          			dest: '<%= paths.assets.js %>config.js'
        		},
        		constants: {
          			ENV: {
            			name: 'production',
            			apiEndpoint: 'http://PRODPATH',
            			baseUrl: 'http://PRODPATH',
            			googleMapsKey: 'GOOGLE_MAPS_KEY'
          			}
        		}
      		},
      		staging: {
      			options: {
          			dest: '<%= paths.assets.js %>config.js'
        		},
        		constants: {
          			ENV: {
            			name: 'staging',
            			apiEndpoint: 'http://STAGINGPATH/api/',
            			baseUrl: 'http://STAGINGPATH',
            			googleMapsKey: 'GOOGLE_MAPS_KEY'
          			}
        		}
      		}
    	},
		copy: {
			main: {
				expand: true,
				cwd: '<%= paths.assets.vendor %>fonts/',
				src: '**',
				dest: '<%= paths.fonts %>',
				flatten: true,
				filter: 'isFile',
			},
			variables: {
				expand: true,
				cwd: '<%= paths.assets.vendor %>bootstrap/less/',
				src: 'variables.less',
				dest: '<%= paths.assets.css %>',
				flatten: true,
				filter: 'isFile',
			}
		},
		concat: {
			options: {
				separator: ';'
			},
			frontend: {
				src: [
					'<%= paths.assets.vendor %>components/jquery/jquery.js',
					'<%= paths.assets.vendor %>bower_components/moment/min/moment.min.js',
					'<%= paths.assets.vendor %>twitter/bootstrap/dist/js/bootstrap.js',
					'<%= paths.assets.vendor %>modernizr/modernizr.js',
					'<%= paths.assets.vendor %>angular/angular.js',
					'<%= paths.assets.vendor %>bower_components/angular-google-maps/dist/angular-google-maps.js',
					'<%= paths.assets.vendor %>bower_components/eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js',
					'<%= paths.assets.vendor %>angular/angular-animate.js',
					'<%= paths.assets.vendor %>angular/angular-ui-router.min.js',
					'<%= paths.assets.vendor %>bower_components/textAngular/src/textAngularSetup.js',
					'<%= paths.assets.vendor %>bower_components/textAngular/src/textAngular-sanitize.js',
					'<%= paths.assets.vendor %>bower_components/textAngular/src/textAngular.js',
					'<%= paths.assets.vendor %>bower_components/lodash/dist/lodash.js',
					'<%= paths.assets.vendor %>bower_components/ng-file-upload/angular-file-upload-shim.js',
					'<%= paths.assets.vendor %>bower_components/ng-file-upload/angular-file-upload.js',
					'<%= paths.assets.vendor %>bower_components/angular-loading-bar/build/loading-bar.js',
					'<%= paths.assets.vendor %>bower_components/ngInfiniteScroll/build/ng-infinite-scroll.js',
					'<%= paths.assets.vendor %>bower_components/angular-bootstrap/ui-bootstrap-tpls.js',
					'<%= paths.assets.vendor %>bower_components/bootbox/bootbox.js',
					'<%= paths.assets.vendor %>bower_components/ngBootbox/dist/ngBootbox.js',
					'<%= paths.assets.js %>**/*.js',
					'!<%= paths.assets.js %>admin.js'
				],
				dest: '<%= paths.js %>all.js',
			},
			admin: {
				src: [
					'<%= paths.assets.vendor %>bower_components/tinymce/tinymce.js',
					'<%= paths.assets.vendor %>bower_components/angular-ui-tinymce/src/tinymce.js',
					'<%= paths.assets.js %>**/admin.js'
				],
				dest: '<%= paths.js %>admin.js',
			}
		},
		less: {
			development: {
				options: {
					compress: false,
				},
				files: {
					"<%= paths.css %>all.css" : "<%= paths.assets.less %>bootstrap.less"
				}
			},
			production: {
				options: {
					cleancss: true,
					compress: true,
				},
				files: {
					"<%= paths.css %>all.min.css" : "<%= paths.assets.less %>bootstrap.less"
				}
			}
		},
		uglify: {
			options: {
				mangle: false,
				preserveComments: false,
				sourceMap: true
			},
			frontend: {
				files: {
					'<%= paths.js %>all.min.js' : '<%= paths.js %>all.js',
					'<%= paths.js %>admin.min.js' : '<%= paths.js %>admin.js',
				}
			},
		},
		cachebreaker: {
			dev: {
				options: {
					match: ['all.min.js'],
					replacement: 'md5',
					src: {
						path: 'public/assets/js/all.min.js'
					}
				},
				files: {
					src: ['app/views/includes/head.blade.php']
				}
			}
		},
		phpunit: {
			classes: {
				dir: 'tests/'
			},
			options: {
				bin: 'vendor/bin/phpunit',
				colors: true
			}
		},
		autoprefixer: {
			options: {
    			browsers: ['last 2 versions']
  			},
			dist: {
				files: {
					"<%= paths.css %>all.css": "<%= paths.css %>all.css",
					"<%= paths.css %>all.min.css": "<%= paths.css %>all.min.css"
				}
			}
		},
		watch : {
			less: {
				files: ['<%= paths.assets.less %>*.less', '<%= paths.assets.less %>less-files/*.less'],
				tasks: ['less', 'autoprefixer'],
				options: {
					livereload: true
				}
			},
			scripts: {
		    	files: ['<%= paths.assets.js %>/**/*.js'],
		    	tasks: ['jshint'],
		    	options: {
					livereload: true,
		      		spawn: false,
		    	},
				tasks: ['concat','uglify']
		  	},
			tests: {
				files: ['app/views/*.php', 'resources/views/**/*']
				//tasks: ['phpunit']
			}
		}
	});

	// Load Plugins
	grunt.loadNpmTasks('grunt-contrib-concat');
	grunt.loadNpmTasks('grunt-contrib-watch');
	grunt.loadNpmTasks('grunt-contrib-less');
	grunt.loadNpmTasks('grunt-contrib-uglify');
	grunt.loadNpmTasks('grunt-contrib-copy');
	grunt.loadNpmTasks('grunt-ng-constant');
	grunt.loadNpmTasks('grunt-phpunit');
	grunt.loadNpmTasks('grunt-autoprefixer');
	grunt.loadNpmTasks('grunt-cache-breaker');

	// Task list	
	grunt.registerTask('prod', ['ngconstant:production','copy','less','concat','uglify','autoprefixer','cachebreaker']);
	grunt.registerTask('stage', ['ngconstant:staging','copy','less','concat','uglify','autoprefixer','cachebreaker']);
	grunt.registerTask('dev', ['ngconstant:development','concat','uglify','watch']);
};