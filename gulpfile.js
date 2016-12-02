let gulp = require('gulp');
let $ = require('gulp-load-plugins')();

let runner = options => command => $.run(command.join(' '), options).exec();
let run = runner({verbosity: 3});

let phpunit = args => run(['./vendor/bin/phpunit'].concat(args));

gulp.task('test', () => phpunit());

gulp.task('watch', () => {
  gulp.watch('./tests/**/*Test.php').on('change', event => {
    console.log('[gulp.watch] ' + event.path);
    phpunit(event.path).on('error', () => {});
  });
});
