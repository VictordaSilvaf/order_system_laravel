<?php

use Illuminate\Support\Facades\Auth;

test('Application is running', function () {
    $this->get(route('ping'))
        ->assertStatus(200)
        ->assertJson(['message' => 'pong']);
});

test('User registration', function () {
    $userData = [
        'name' => 'Test User',
        'email' => 'test@example.com',
        'password' => 'password',
        'password_confirmation' => 'password',
    ];

    // Register the user
    $this->post(route('v1.register'), $userData)
        ->assertStatus(201)
        ->assertJsonStructure(['access_token', 'token_type']);
});

test('User login', function () {
    \App\Models\User::factory()->create([
        'email' => 'test@email.com',
        'password' => bcrypt('password'),
    ]);

    $loginData = [
        'email' => 'test@email.com',
        'password' => 'password',
    ];

    $this->post(route('v1.login'), $loginData)
        ->assertStatus(200)
        ->assertJsonStructure(['access_token', 'token_type']);
});

test('User logout', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'test@email.com',
        'password' => bcrypt('password'),
    ]);

    $token = $user->createToken('auth_token')->plainTextToken;

    $this->withHeaders([
        'Authorization' => 'Bearer ' . $token,
    ])->post(route('v1.logout'))
        ->assertStatus(200);
});
