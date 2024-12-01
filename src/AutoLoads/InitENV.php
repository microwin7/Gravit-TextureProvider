<?php

use Microwin7\PHPUtils\Rules\Regex;

require_once(__DIR__ . '/../../vendor/autoload.php');

$http_env_vendor = getenv('ENV_VENDOR');
if ($http_env_vendor !== false && !empty($http_env_vendor) && $http_env_vendor !== 'null')
    $env_filename = '.env.' . $http_env_vendor;
else $env_filename = '.env';

$filter = function (string $ENV_KEY): bool {
    if (
        (
            $ENV_KEY === 'APP_URL' ||
            $ENV_KEY === 'LAUNCH_SERVER_ECDSA256_PUBLIC_KEY_BASE64'
        ) && ($ENV_VAL = getenv("PROXY_" . $ENV_KEY)) !== false
    ) {
        if ($ENV_VAL !== "" && $ENV_VAL !== "null") {
            return putenv($ENV_KEY . "=" . $ENV_VAL);
        }
    }
    if ($ENV_KEY === 'SCRIPT_PATH' && ($ENV_VAL = getenv("PROXY_" . $ENV_KEY)) !== false) {
        if ($ENV_VAL !== "null") {
            return putenv($ENV_KEY . "=" . $ENV_VAL);
        }
    }
    if ($ENV_KEY === 'BEARER_TOKEN' && ($ENV_VAL = getenv("PROXY_" . $ENV_KEY)) !== false) {
        if ($ENV_VAL !== "") {
            return putenv($ENV_KEY . "=" . $ENV_VAL);
        }
    }
    return false;
};

if (($env_lines = file(__DIR__ . '/../../' . $env_filename, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES)) !== false) {
    $finalLines = array_filter($env_lines, function ($line) {
        return Regex::valid_with_pattern($line, '/^([A-Z0-9\_]+)=(.*?)$/');
    });
    foreach ($finalLines as $line) {
        preg_match('/^([A-Z0-9\_]+)=.*?$/', $line, $matches);
        if (getenv($matches[1]) === false && !$filter($matches[1])) {
            putenv($matches[0]);
        }
    }
}
