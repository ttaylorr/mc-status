module.exports = function(grunt) {
  grunt.initConfig({
    cache: false,
    mkdir: {
      all: {
        options: {
          mode: 0700,
          create: ['./tmp']
        }
      }
    },
    clean: {
      short: ['tmp/']
    },
    phplint: {
      options: {
        swapPath: 'tmp/'
      },
      all: ['php/*.php']
    },
    uglify: {
      build: {
        src: ['js/*.js'],
        dest: 'js/min/app.min.js'
      }
    },
    watch: {
      scripts: {
        files: '**/js/*.js',
        tasks: ['uglify'],
        options: {
          spawn: false
        }
      }
    }
  });

  grunt.loadNpmTasks('grunt-mkdir');
  grunt.loadNpmTasks('grunt-contrib-clean');
  grunt.loadNpmTasks('grunt-phplint');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-composer');
  grunt.loadNpmTasks('grunt-contrib-watch');

  grunt.registerTask('default', ['mkdir:all', 'phplint:all', 'uglify', 'clean']);
  grunt.registerTask('make', ['uglify']);
}
