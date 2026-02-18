<?php
echo "<h1>PHP GD Check</h1>";
echo "Loaded Configuration File: " . php_ini_loaded_file() . "<br>";
echo "GD Extension Loaded: " . (extension_loaded('gd') ? 'YES' : 'NO') . "<br>";
if (function_exists('gd_info')) {
    echo "<pre>";
    print_r(gd_info());
    echo "</pre>";
}
echo "<hr>";
phpinfo();
