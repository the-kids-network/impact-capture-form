<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Illuminate\Foundation\Testing\DatabaseMigrations;

use Tests\Browser\Pages\MentorPage;
use Tests\Browser\Pages\SessionReportsPage;

class MentorPageTest extends DuskTestCase
{
    public function testDefaultMentors()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10);

            $browser->element('.expand-all')->click();
            $browser->waitFor('.mentor-table .mentee.name-row');
            $browser->assertSeeIn('.mentor-table .mentee.name-row', 'Mentee');

            $browser->assertSelectHasOptions('@mentee-list', [1, 2, 3]);
        });
    }

    public function testCanCancelDeleteMentor()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10);
            $browser->element('.mentor-table .mentor.delete-row:nth-child(odd) form input[type="submit"]')->click();
            $browser->dismissDialog();

            $this->assertMentorCount($browser, 10);
        });
    }

    public function testCanDeleteMentor()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10);
            $browser->element('.mentor-table .mentor.delete-row:nth-child(odd) form input[type="submit"]')->click();
            $browser->acceptDialog();

            $this->assertMentorCount($browser, 9);
        });
    }

    private function assertMentorCount($browser, $mentorCount)
    {
        $browser->pause(1000);
        $browser->assertElementsCountIs($mentorCount, '.mentor-table .mentor.name-row');
    }

    private function assertMenteeCount($browser, $menteeCount)
    {
        $browser->pause(1000);
        $browser->assertElementsCountIs($menteeCount, '.mentor-table .mentee.name-row');
    }
}
