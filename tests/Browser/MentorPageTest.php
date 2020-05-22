<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\MentorPage;

class MentorPageTest extends DuskTestCase
{
    public function testDefaultMentors()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10);

            $browser->element('.expand-all')->click();
            $browser->waitFor('.mentors-list .mentee.row');
            $browser->assertSeeIn('.mentors-list .mentee .name', 'Mentee One');

            $browser->assertSelectHasOptions('.mentee-select', [1, 2, 3]);
        });
    }

    private function assertMentorCount($browser, $mentorCount)
    {
        $browser->pause(1000);
        $browser->assertElementsCountIs($mentorCount, '.mentors-list .mentor.row');
    }

    private function assertMenteeCount($browser, $menteeCount)
    {
        $browser->pause(1000);
        $browser->assertElementsCountIs($menteeCount, '.mentors-list .mentee.row');
    }
}
