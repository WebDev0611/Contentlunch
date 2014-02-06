
module.exports = function(grunt) {

  // JS Files to concat and watch
  var js_files = [
    './bower_components/jquery/jquery.js',
    './bower_components/bootstrap/dist/js/bootstrap.js',
    './bower_components/bootbox/bootbox.js',
    './bower_components/angular/angular.js',
    './bower_components/angular-local-storage/angular-local-storage.js',
    './bower_components/ng-grid/build/ng-grid.js',
    './bower_components/ng-grid/plugins/ng-grid-csv-export.js',
    './bower_components/underscore/underscore.js',
    './app/assets/scripts/{,*/}*.js'
  ];

  grunt.initConfig({

    // Task configuration
    copy: {
      app: {
        files: [
          // Copy bootstrap fonts to assets
          {
            expand: true,
            cwd: './bower_components/bootstrap/dist/fonts/',
            src: '*',
            dest: './public/assets/fonts/',
            filter: 'isFile'
          },
          // Copy fontawesome fonts to assets
          {
            expand: true,
            cwd: './bower_components/font-awesome/fonts/', 
            src: '*', 
            dest: './public/assets/fonts/', 
            filter: 'isFile'
          },
          // Copy angularjs templates
          {
            expand: true,
            cwd: './app/assets/views/',
            src: '*',
            dest: './public/assets/views/',
            filter: 'isFile'
          }
        ]
      }
    },
    concat: {
      options: {
        separator: '',
      },
      app: {
        src: js_files,
        dest: './public/assets/scripts/app.js'
      }
    },
    jshint: {
      options: {
        reporter: require('jshint-stylish')
      },
      all: [
        'Gruntfile.js',
        './app/assets/scripts/{,*/}*.js'
      ]
    },
    less: {
      development: {
        options: {
          compress: true, // minifying the result
        },
        files: {
          // Compile app.less into app.css
          "./public/assets/styles/app.css":"./app/assets/styles/app.less"
        }
      }
    },
    uglify: {
      options: {
        mangle: false // Use if you want the names of your functions and variables unchanged
      },
      app: {
        files: {
          './public/assets/scripts/app.js': './public/assets/scripts/app.js'
        }
      }
    },
    watch: {
      js: {
        files: js_files,
        // tasks to run
        tasks: ['jshint', 'concat:app'],
        // reloads the browser
        options: {
          livereload: true
        }
      },
      less: {
        files: ['./app/assets/styles/*.less'],
        tasks: ['less'],
        options: {
          livereload: true
        }
      },
      views: {
        files: ['./app/assets/views/*.html'],
        tasks: ['copy'],
        options: {
          livereload: true
        }
      }
    }

  });  

  // Plugin loading
  // grunt.loadNpmTasks('grunt-contrib-concat');
  grunt.loadNpmTasks('grunt-contrib-copy');
  grunt.loadNpmTasks('grunt-contrib-watch');
  // grunt.loadNpmTasks('grunt-contrib-less');
  grunt.loadNpmTasks('grunt-contrib-uglify');
  grunt.loadNpmTasks('grunt-contrib-jshint');

  // Task definition
  grunt.registerTask('default', ['jshint', 'copy', 'concat', 'less']);
  grunt.registerTask('development', ['default', 'watch']);

};

/*
module.exports = function(grunt) {
  // Project configuration.
  grunt.initConfig({
    pkg: grunt.file.readJSON('package.json'),
    uglify: {
      options: {
        banner: '! <%= pkg.name %> <%= grunt.template.today("yyyy-mm-dd") %> \n'
      },
      build: {
        src: 'src/<%= pkg.name %>.js',
        dest: 'build/<%= pkg.name %>.min.js'
      }
    }
  });

  // Load the plugin that provides the "uglify" task.
  grunt.loadNpmTasks('grunt-contrib-uglify');

  // Default task(s).
  grunt.registerTask('default', ['uglify']);

};
*/