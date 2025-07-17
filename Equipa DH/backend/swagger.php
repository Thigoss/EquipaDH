<?php
require_once("vendor/autoload.php");
$openapi = \OpenApi\Generator::scan(['/var/www/html/app/Http/Controllers']);
header('Content-Type: application/x-yaml');
echo $openapi->toYaml();
