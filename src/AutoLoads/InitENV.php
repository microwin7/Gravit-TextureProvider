<?php

use Microwin7\PHPUtils\Rules\Regex;

require_once(__DIR__ . '/../../vendor/autoload.php');

$http_env_vendor = getenv('ENV_VENDOR');
if ($http_env_vendor !== false && !empty($http_env_vendor) && $http_env_vendor !== 'null')
    $env_filename = '.env.' . $http_env_vendor;
else $env_filename = '.env';

if (($env_lines = file(__DIR__ . '/../../' . $env_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) !== false) {
    $finalLines = array_filter($env_lines, function ($line) {
        return Regex::valid_with_pattern($line, '/^([A-Z0-9\_]+)=(.*?)$/');
    });
    foreach ($finalLines as $line) {
        preg_match('/^([A-Z0-9\_]+)=.*?$/', $line, $matches);
        if (getenv($matches[1]) === false) putenv($matches[0]);
    }
}
