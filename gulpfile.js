const gulp = require('gulp');
const browserSync = require('browser-sync').create();

function serve() {
    browserSync.init({
        proxy: "http://localhost:33/Klitzke/",
        notify: false
    });

    gulp.watch("**/*.css").on('change', browserSync.reload);
    gulp.watch("**/*.js").on('change', browserSync.reload);
    gulp.watch("**/*.php").on('change', browserSync.reload);
    gulp.watch("**/*.html").on('change', browserSync.reload);
}

gulp.task('default', serve);
