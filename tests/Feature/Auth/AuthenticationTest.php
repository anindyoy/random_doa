<?php

use App\Models\User;

test('users can authenticate with remember me', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
        'remember' => true,
    ]);

    $response
        ->assertSessionHasNoErrors()
        ->assertRedirect('/');

    $this->assertAuthenticated();

    // Verify remember token is set
    $this->assertNotNull(auth()->user()->getRememberToken());
});

test('users can not authenticate with invalid password', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasErrors('email');
    $response->assertRedirect(); // back redirect

    $this->assertGuest();
});

test('email field is required for login', function () {
    $response = $this->post(route('login'), [
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('password field is required for login', function () {
    $response = $this->post(route('login'), [
        'email' => 'test@example.com',
    ]);

    $response->assertSessionHasErrors('password');
    $this->assertGuest();
});

test('email must be valid email format', function () {
    $response = $this->post(route('login'), [
        'email' => 'not-an-email',
        'password' => 'password',
    ]);

    $response->assertSessionHasErrors('email');
    $this->assertGuest();
});

test('users can logout', function () {
    $user = User::factory()->create();

    $response = $this->actingAs($user)->post(route('logout'));

    $response->assertRedirect('/');

    $this->assertGuest();
});

test('session is regenerated on login', function () {
    $user = User::factory()->create();

    // Start a session
    $this->get(route('login'));
    $oldSessionId = session()->getId();

    $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'password',
    ]);

    // Session should be regenerated
    $newSessionId = session()->getId();
    $this->assertNotEquals($oldSessionId, $newSessionId);
});

test('session is invalidated on logout', function () {
    $user = User::factory()->create();

    $this->actingAs($user);
    $sessionId = session()->getId();

    $this->post(route('logout'));

    // Verify we can't use the old session
    $this->assertGuest();
});

test('email input is preserved on failed login', function () {
    $user = User::factory()->create();

    $response = $this->post(route('login'), [
        'email' => $user->email,
        'password' => 'wrong-password',
    ]);

    $response->assertSessionHasInput('email', $user->email);
});