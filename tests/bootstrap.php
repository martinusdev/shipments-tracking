<?php

define('COMPARISIONS', __DIR__ . '/' . 'comparisons');

if (is_file('vendor/autoload.php')) {
    require_once 'vendor/autoload.php';
} else {
    require_once dirname(__DIR__) . '/vendor/autoload.php';
}

function pr($var)
{
    $template = (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') ? '<pre class="pr">%s</pre>' : "\n%s\n\n";
    printf($template, trim(print_r($var, true)));

    return $var;
}
