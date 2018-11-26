const { mix } = require('laravel-mix');


mix.js('resources/assets/js/app.js', 'public/js')
   .sass('resources/assets/sass/app.scss', 'public/css');


mix.js('resources/assets/js/hrms/hrms.js', 'public/js')
    .js('resources/assets/js/hrms/salaryInfo.js', 'public/js')
	.js('resources/assets/js/hrms/settings.js', 'public/js')
    .js('resources/assets/js/hrms/unit.js', 'public/js')
    .js('resources/assets/js/setup/module.js', 'public/js')
    .js('resources/assets/js/setup/setupMenu.js', 'public/js')
    .js('resources/assets/js/hrms/leaveType.js', 'public/js')
    .js('resources/assets/js/hrms/branch.js', 'public/js');


mix.combine([
    'resources/assets/css/auth/css.css',
    'resources/assets/css/auth/icons.css',
    'resources/assets/css/auth/main.css',
    'resources/assets/css/auth/voyager.css',
], 'public/css/auth.css');

mix.combine([
    // 'resources/assets/css/bootstrap.min.css',
    'resources/assets/css/hrms/theme.css',
], 'public/css/hrms.css');


