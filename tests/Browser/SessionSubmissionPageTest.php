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

    public function testReportSubmission()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'mentor2@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new SessionSubmissionPage());

            $browser->value('[name=session_date]', '08/29/2018');
            $browser->value('[name=length_of_session]', '1');
            $browser->value('[name=location]', 'Home');
            $browser->value('[name=meeting_details]', 'Here are the meeting details');

            $browser->element('[name=report_submit]')->click();

            $adminUser = User::where('email', 'admin@example.com')->firstOrFail();
            $browser->loginAs($adminUser)->visit("report/3");

            $browser->assertSee('Mentor Two');
            $browser->assertSee('Mentee Two Name');
            $browser->assertSee('Aug 29, 2018');
            $browser->assertSee('Poor');
            $browser->assertSee('1');
            $browser->assertSee('Basketball');
            $browser->assertSee('Home');
            $browser->assertSee('No');
            $browser->assertSee('Strong');
            $browser->assertSee('Happy');
            $browser->assertSee('Here are the meeting details');
        });
    }

}
