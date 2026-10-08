<?php
header('Content-Type: text/plain');
echo "upload_tmp_dir        = " . ini_get('upload_tmp_dir') . "\n";
echo "upload_max_filesize   = " . ini_get('upload_max_filesize') . "\n";
echo "post_max_size         = " . ini_get('post_max_size') . "\n";
echo "file_uploads          = " . ini_get('file_uploads') . "\n";
echo "open_basedir          = " . ini_get('open_basedir') . "\n";
echo "sys_get_temp_dir      = " . sys_get_temp_dir() . "\n";
echo "whoami (php)          = " . (function_exists('posix_getpwuid') ? (posix_getpwuid(posix_geteuid())['name'] ?? 'unknown') : 'n/a') . "\n";

$dir = __DIR__ . '/images/products';
echo "products dir          = " . $dir . "\n";
echo "exists                = " . (is_dir($dir) ? 'yes' : 'no') . "\n";
echo "writable              = " . (is_writable($dir) ? 'yes' : 'no') . "\n";
echo "perms                 = " . substr(sprintf('%o', fileperms($dir)), -4) . "\n";
echo "owner uid             = " . fileowner($dir) . "\n";
echo "current uid (php)     = " . getmyuid() . "\n";