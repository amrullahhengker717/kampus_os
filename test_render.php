<?php

require __DIR__.'/vendor/autoload.php';

$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Http\Kernel::class);

$request = Illuminate\Http\Request::create('/dashboard', 'GET');
$user = App\Models\User::where('email', 'student@campusos.com')->first();
$app['auth']->login($user);

$response = $kernel->handle($request);
file_put_contents('test_output.html', $response->getContent());
echo "Done";
