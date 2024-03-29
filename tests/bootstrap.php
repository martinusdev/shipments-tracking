<?php
declare(strict_types=1);

define('COMPARISIONS', __DIR__ . '/' . 'comparisons');

putenv('PACKETA_API_PASSWORD=API_PASSWORD');

if (is_file('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

function pr($var)
{
    $template = PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg' ? '<pre class="pr">%s</pre>' : "\n%s\n\n";
    printf($template, trim(print_r($var, true)));

    return $var;
}

function dd($var)
{
    pr($var);
    die;
}
