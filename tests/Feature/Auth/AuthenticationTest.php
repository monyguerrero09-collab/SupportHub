<?php

namespace Tests\Feature\Auth;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AuthenticationTest extends TestCase
{
    use RefreshDatabase;

    public function test_login_screen_can_be_rendered(): void
    {
        $response = $this->get('/login');

        $response->assertStatus(200);
    }

    public function test_users_can_authenticate_using_the_login_screen(): void
    {
        $rol = \App\Models\Role::firstOrCreate(['nombre' => 'Operador']);
        $user = User::factory()->create(['rol_id' => $rol->id]);

        $response = $this->followingRedirects()->post('/login', [
            'codigo_acceso' => $user->codigo_acceso,
            'selected_role' => 'Operador',
        ]);

        $this->assertAuthenticated();
        $response->assertStatus(200);
    }

    public function test_users_can_not_authenticate_with_invalid_password(): void
    {
        $rol = \App\Models\Role::firstOrCreate(['nombre' => 'Operador']);
        $user = User::factory()->create(['rol_id' => $rol->id]);

        $this->post('/login', [
            'codigo_acceso' => 'wrong-code',
            'selected_role' => 'Operador',
        ]);

        $this->assertGuest();
    }

    public function test_users_can_logout(): void
    {
        $user = User::factory()->create();

        $response = $this->actingAs($user)->post('/logout');

        $this->assertGuest();
        $response->assertRedirect('/');
    }
}
