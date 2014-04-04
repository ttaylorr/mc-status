module.exports = function(grunt) {
	grunt.initConfig({
    mkdir: {
      all: {
        options: {
          mode: 0700,
          create: ['tmp']
        }
      }
    },
    clean: {
      short: ['./tmp/']
    },
    php: {
      dist: {
        options: {
          port: 8080,
          base: 'web',
          open: true,
          keepalive: true
        }
      }
    },
    phplint: {
      options: {
        swapPath: './tmp'
      },
      all: ['php/*.php']
    },
    uglify: {
      build: {
        src: ['js/app.js'],
        dest: 'js/app.min.js'
      }
    }
  });

  grunt.loadNpmTasks('grunt-mkdir');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-php');
  grunt.loadNpmTasks('grunt-phplint');
  grunt.loadNpmTasks('grunt-contrib-uglify');

  grunt.registerTask('default', ['mkdir:all', 'phplint:all', 'uglify', 'clean']);
}
