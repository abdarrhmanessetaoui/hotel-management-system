<?php

$dir = new RecursiveDirectoryIterator(__DIR__ . '/resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/\.blade\.php$/', RegexIterator::GET_MATCH);

foreach ($files as $fileList) {
    $file = $fileList[0];
    $content = file_get_contents($file);
    $original = $content;

    // Pattern: replace asset($property) with Str::startsWith support
    $content = preg_replace_callback('/asset\(\s*(\$[a-zA-Z0-9_->]+)\s*\)/', function($m) {
        $var = $m[1];
        return "\\Illuminate\\Support\\Str::startsWith({$var}, 'http') ? {$var} : asset({$var})";
    }, $content);

    if ($content !== $original) {
        file_put_contents($file, $content);
        echo "Fixed asset() check in: $file\n";
    }
}
echo "Done.\n";

