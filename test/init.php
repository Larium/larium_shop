<?php
require_once 'SplClassLoader.php';
require_once __DIR__ . '/FixtureLoader.php';

$l = new SplClassLoader('Larium', __DIR__ . '/../src');
$l->register();
