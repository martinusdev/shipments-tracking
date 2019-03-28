<?php

define('COMPARISIONS', __DIR__ . '/' . 'comparisons');

print_r(COMPARISIONS);

function pr($var)
{

    $template = (PHP_SAPI !== 'cli' && PHP_SAPI !== 'phpdbg') ? '<pre class="pr">%s</pre>' : "\n%s\n\n";
    printf($template, trim(print_r($var, true)));

    return $var;
}
