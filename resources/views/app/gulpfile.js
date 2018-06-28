const gulp = require("gulp");
const replace = require('gulp-replace');
const rename = require('gulp-rename');


gulp.task('blade', () => {
    'use strict';
    gulp
        .src('./dist/index.html')
        .pipe(replace(/<!--context-->/i, function () {
            return `
    <script>
    try {
      window.__CONTEXT__='{!! $context !!}';
    } catch (e){}
    </script>`;
        }))
        .pipe(replace(/<title>.*<\/title>/i, `<title>{{ $title or '' }}</title>`))
        .pipe(replace(/(<script.*)(<\/body>)/i, function (str, s1, s2) {
            return `
  @if(env('THEME_DEVELOPMENT'))
    <script src="http://localhost:8090/app.js"></script>
  @else
    ${s1}
  @endif
    ${s2}
`;
        }))
        .pipe(replace(/(<link.*)(<\/head>)/i, function (str, s1, s2) {
            return `
  @if(!env('THEME_DEVELOPMENT'))
    ${s1}
  @endif
    ${s2}
`;
        }))
        .pipe(rename('index.blade.php'))
        .pipe(gulp.dest("./templates/"));
});

gulp.task('test', () => {
    'use strict'
    gulp
        .src('./templates/index.blade.php')
        .pipe(replace(/(<link.*)(<\/head>)/i, function (str, s1, s2) {
            // console.log(s1);
            // console.log(s2);
            return `
          @if(!env('THEME_DEVELOPMENT'))
            ${s1}
          @endif
          ${s2}
          `
        }))
});

gulp.task('default', ['blade']);
