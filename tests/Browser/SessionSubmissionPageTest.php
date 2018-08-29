<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\MentorPage;
use Tests\Browser\Pages\SessionSubmissionPage;

class SessionSubmissionPageTest extends DuskTestCase
{
    public function testDefaultSubmission()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'mentor1@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new SessionSubmissionPage());

            $browser->assertSelected("@name='rating-id'", 'Poor');
        });
    }

}
