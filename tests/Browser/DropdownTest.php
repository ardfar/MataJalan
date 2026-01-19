<?php

namespace Tests\Browser;

use App\Models\User;
use Laravel\Dusk\Browser;
use Tests\DuskTestCase;

class DropdownTest extends DuskTestCase
{
    /** @test */
    public function dropdown_remains_visible_after_click()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->waitForText($user->name)
                    ->click('button.inline-flex.items-center') // Selector for the dropdown trigger
                    ->pause(500) // Wait for transition
                    ->assertVisible('div[x-show="open"]') // Assert dropdown content is visible
                    ->assertSee('Profile')
                    ->assertSee('Log Out');
        });
    }

    /** @test */
    public function dropdown_closes_on_click_outside()
    {
        $this->browse(function (Browser $browser) {
            $user = User::factory()->create();

            $browser->loginAs($user)
                    ->visit('/dashboard')
                    ->click('button.inline-flex.items-center')
                    ->waitForText('Profile')
                    ->clickAt(0, 0) // Click somewhere else
                    ->pause(500)
                    ->assertMissing('div[x-show="open"]'); // Assert dropdown is hidden (or missing from DOM view)
        });
    }
}
