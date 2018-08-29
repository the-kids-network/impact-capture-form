<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\MentorPage;
use Tests\Browser\Pages\SessionSubmissionPage;

use \PHPUnit\Framework\Assert as PHPUnit;

class SessionSubmissionPageTest extends DuskTestCase
{
    public function testDefaultSubmission()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'mentor1@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new SessionSubmissionPage());

            $browser->assertSelected('rating_id', 2); // Poor
            $browser->assertSelected('activity_type_id', 1); // Basketball
            $browser->assertSelected('safeguarding_concern', 0); // No
            $browser->assertSelected('physical_appearance_id', 1); // Strong
            $browser->assertSelected('emotional_state_id', 1); // Happy
        });
    }

}
