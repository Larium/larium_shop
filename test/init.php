<?php
require_once 'SplClassLoader.php';
$l = new SplClassLoader('Larium', __DIR__ . '/../src');
$l->register();
require_once __DIR__ . '/FixtureLoader.php';
require_once __DIR__ . '/Hydrator.php';
require_once __DIR__ . '/Larium/RedirectProvider.php';
require_once __DIR__ . '/../vendor/autoload.php';
