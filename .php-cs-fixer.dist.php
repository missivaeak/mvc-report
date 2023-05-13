<?php

declare(strict_types=1);

/**
 * Execute the command like this:
 *  php-cs-fixer --config=.php-cs-fixer.dist.php fix src tests
 */
require_once __DIR__.'/tools/php-cs-fixer/vendor/autoload.php';

use PhpCsFixer\Finder;
use PhpCsFixer\Config;

$finder = Finder::create();

$config = new Config();
$config->setFinder($finder);

return $config;
