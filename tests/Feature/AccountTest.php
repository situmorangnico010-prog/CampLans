<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AccountTest extends TestCase
{
    use RefreshDatabase;

    public function test_account_page_is_displayed(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->get('/account');

        $response->assertOk();
    }

    public function test_account_information_can_be_updated(): void
    {
        $user = User::factory()->create();

        $response = $this
            ->actingAs($user)
            ->post('/account', [
                'name' => 'Test User Updated',
                'password' => 'newpassword123',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();

        $this->assertSame('Test User Updated', $user->name);
        $this->assertTrue(\Hash::check('newpassword123', $user->password));
    }

    public function test_account_information_can_be_updated_without_password(): void
    {
        $user = User::factory()->create();
        $oldPassword = $user->password;

        $response = $this
            ->actingAs($user)
            ->post('/account', [
                'name' => 'Test User Updated 2',
            ]);

        $response->assertSessionHasNoErrors();
        $response->assertRedirect();

        $user->refresh();

        $this->assertSame('Test User Updated 2', $user->name);
        $this->assertSame($oldPassword, $user->password);
    }
}
