<?php

namespace Tests\Browser;

use App\Models\User;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class LoginTest extends DuskTestCase
{
    use DatabaseMigrations;

    /** @test */
    public function a_user_cannot_login_with_invalid_credentials()
    {
        $user = User::factory()->create(['email' => 'user@user.com']);
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->visit('/login')
                ->assertSee('Returning Customer')
                ->type('email', 'user@user.com')
                ->type('password', 'password2')
                ->press('Login')
                ->assertPathIs('/login')
                ->assertSee('credentials do not match');
        });
    }

    /** @test */
    public function a_user_can_login_with_valid_credentials()
    {
        $user = User::factory()->create(['email' => 'user@user.com']);
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                ->visit('/login')
                ->assertSee('Returning Customer')
                ->type('email', 'user@user.com')
                ->type('password', 'password')
                ->press('Login')
                ->assertPathIs('/');
        });
    }
}
