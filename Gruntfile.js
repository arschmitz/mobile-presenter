module.exports = function(grunt) {

	grunt.initConfig({
		concat: {
			css: {
				src: [ "css/*.css" ],
				dest: "dist/main.css"
			},
			js: {
				src: [ "js/!(slave|master).js" ],
				dest: "dist/main.js"
			}
		},
		uglify: {
			dist: {
				src: "dist/main.js",
				dest: "dist/main.min.js"
			}
		},
		cssmin: {
			dist: {
				src: "dist/main.css",
			dest: "dist/main.min.css"
			}
		},
		watch: {
			scripts: {
				files: [ "css/*.css", "js/*.js" ],
				tasks: [ "build" ],
				options: {
					interrupt: true
				}
			}
		}
	});

	grunt.loadNpmTasks("grunt-contrib-concat");
	grunt.loadNpmTasks("grunt-contrib-uglify");
	grunt.loadNpmTasks("grunt-contrib-cssmin");
	grunt.loadNpmTasks("grunt-contrib-watch");
	grunt.registerTask("build", ["concat", "uglify", "cssmin"]);
};