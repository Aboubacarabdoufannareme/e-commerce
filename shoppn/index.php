<?php
/**
 * index.php — entry point.
 * Loads core, then hands off to the home view.
 */
require_once __DIR__ . '/core/core.php';

require_once __DIR__ . '/views/home.php';
?>