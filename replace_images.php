<?php
$dir = new RecursiveDirectoryIterator('resources/views');
$ite = new RecursiveIteratorIterator($dir);
$files = new RegexIterator($ite, '/.*\.blade\.php$/', RegexIterator::GET_MATCH);

$updatedFiles = 0;

foreach($files as $file) {
    $path = $file[0];
    $content = file_get_contents($path);
    
    // Replace src="..."
    $newContent = preg_replace_callback('/(<img[^>]*src=["\'])([^"\']+)(["\'][^>]*>)/i', function($matches) {
        $src = $matches[2];
        if (strpos($src, 'http') === 0 || strpos($src, 'picsum.photos') !== false) {
            return $matches[0];
        }
        $random = rand(1, 1000);
        return $matches[1] . 'https://picsum.photos/seed/' . $random . '/800/600' . $matches[3];
    }, $content);
    
    // Replace style="background-image: url(...)"
    $newContent = preg_replace_callback('/(url\([\'"]?)([^)\'"]+)([\'"]?\))/i', function($matches) {
        $src = $matches[2];
        if (strpos($src, 'http') === 0 || strpos($src, 'picsum.photos') !== false) {
            return $matches[0];
        }
        $random = rand(1, 1000);
        return $matches[1] . 'https://picsum.photos/seed/' . $random . '/1920/1080' . $matches[3];
    }, $newContent);

    if ($newContent !== $content) {
        file_put_contents($path, $newContent);
        echo "Updated $path\n";
        $updatedFiles++;
    }
}
echo "Done. Updated $updatedFiles files.\n";
