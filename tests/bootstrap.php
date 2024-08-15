<?php
declare(strict_types=1);

define('COMPARISIONS', __DIR__ . '/' . 'comparisons');

putenv('PACKETA_API_PASSWORD=API_PASSWORD');

putenv('PPL_API_CLIENT_ID=API_CLIENT_ID');
putenv('PPL_API_CLIENT_SECRET=API_CLIENT_SECRET');

//if (is_file('vendor/autoload.php')) {
//    require_once 'vendor/autoload.php';
//} else {
//    require_once dirname(__DIR__) . '/vendor/autoload.php';
//}

if (!function_exists('pr')) {
    function pr($var)
    {
        $template = PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ? '<pre class="pr">%s</pre>' : "\n%s\n\n";
        printf($template, trim(print_r($var, true)));

        return $var;
    }
}

if (!function_exists('dd')) {
    function dd($var)
    {
        pr($var);
        die;
    }
}
