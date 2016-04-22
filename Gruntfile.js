module.exports = function (grunt) {
    grunt.initConfig({
        pkg: grunt.file.readJSON('package.json'),
        bowercopy: {
            options: {
                srcPrefix: 'bower_components',
                destPrefix: 'web/assets'
            },
            scripts: {
                files: {
                    'js/jquery.js': 'jquery/dist/jquery.js',
                    'js/bootstrap.js': 'bootstrap/dist/js/bootstrap.js',
                    'js/moment.min.js': 'moment/min/moment.min.js',
                    'js/bootstrap-datetimepicker.min.js': 'eonasdan-bootstrap-datetimepicker/build/js/bootstrap-datetimepicker.min.js'
                }
            },
            stylesheets: {
                files: {
                    'css/bootstrap.css': 'bootstrap/dist/css/bootstrap.css',
                    'css/font-awesome.css': 'font-awesome/css/font-awesome.css',
                    'css/bootstrap-datetimepicker.css': 'eonasdan-bootstrap-datetimepicker/build/css/bootstrap-datetimepicker.css'
                }
            },
            fonts: {
                files: {
                    'fonts': ['font-awesome/fonts', 'bootstrap/dist/fonts']
                }
            }
        }
    });

    grunt.loadNpmTasks('grunt-bowercopy');
    grunt.registerTask('default', ['bowercopy']);
};