<?php

// Set environment variables for static export
putenv('APP_ENV=local');
putenv('APP_DEBUG=true');
putenv('APP_KEY=base64:uS7fV8kK4lG5J6H7n8m9p0q1r2s3t4u5v6w7x8y9z0a=');
putenv('DB_CONNECTION=sqlite');
putenv('DB_DATABASE=:memory:');

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';

use Illuminate\Contracts\Http\Kernel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;

$kernel = $app->make(Kernel::class);

// Bootstrap the application (which sets up Facades, configs, and providers)
$kernel->bootstrap();

echo "⚙️ Running migrations in-memory...\n";
Artisan::call('migrate', ['--force' => true]);
echo Artisan::output() . "\n";

echo "🌱 Running seeders in-memory...\n";
try {
    Artisan::call('db:seed', ['--force' => true]);
    echo Artisan::output() . "\n";
} catch (\Exception $e) {
    echo "❌ Seeding failed: " . $e->getMessage() . "\n";
    exit(1);
}

echo "🌐 Rendering home page...\n";
$request = Request::create('/', 'GET');
$response = $kernel->handle($request);

if ($response->getStatusCode() === 200) {
    $content = $response->getContent();
    
    // Replace localhost URLs with relative paths for correct asset loading on production
    $content = str_replace('http://localhost/', '/', $content);
    $content = str_replace('http://localhost', '/', $content);
    
    file_put_contents(__DIR__.'/public/index.html', $content);
    echo "✅ Exported home page to public/index.html successfully!\n";
} else {
    echo "❌ Failed to render home page. Status code: " . $response->getStatusCode() . "\n";
    echo $response->getContent() . "\n";
    exit(1);
}
