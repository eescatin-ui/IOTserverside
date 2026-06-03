<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Str;

// Create admin user if not exists
$user = User::where('email', 'admin@example.com')->first();
if (!$user) {
    $user = User::create([
        'name' => 'Admin User',
        'email' => 'admin@example.com',
        'password' => 'password',
        'role' => 'admin',
        'api_token' => Str::random(60),
    ]);
    echo "✅ User created!\n";
} else {
    echo "User already exists\n";
}

echo "Email: {$user->email}\n";
echo "Token: {$user->api_token}\n";
echo "Role: {$user->role}\n";