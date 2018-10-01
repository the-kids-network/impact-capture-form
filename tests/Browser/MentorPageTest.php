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

            $this->assertMentorCount($browser, 10, 1);

            $browser->element('.expand-all')->click();
            $browser->waitFor('.mentor-table .mentee.name-row');
            $browser->assertSeeIn('.mentor-table .mentee.name-row', 'Mentee');

            $browser->assertSelectHasOptions('@mentee-list', [1, 2]);
        });
    }

    public function testCanCancelDeleteMentor()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10, 1);
            $browser->element('.mentor-table .mentor.delete-row:nth-child(30) form input[type="submit"]')->click();
            $browser->dismissDialog();

            $this->assertMentorCount($browser, 10, 1);
        });
    }

    public function testCanDeleteMentor()
    {
        $this->browse(function (Browser $browser) {
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $browser->loginAs($user)->visit(new MentorPage());

            $this->assertMentorCount($browser, 10, 1);
            $browser->element('.mentor-table .mentor.delete-row:nth-child(30) form input[type="submit"]')->click();
            $browser->acceptDialog();

            $this->assertMentorCount($browser, 9, 1);

            $browser->visit(new SessionReportsPage());
            $this->assertSessionReportRow($browser, 2, 1, '_DELETED_', 'Mentee One Name');
            $this->assertSessionReportRow($browser, 1, 2, 'Mentor Nine', 'Mentee One Name');
        });
    }

    private function assertMentorCount($browser, $mentorCount, $menteeCount)
    {
        $browser->pause(1000);
        $browser->assertElementsCountIs($mentorCount, '.mentor-table .mentor.name-row')
                ->assertElementsCountIs($menteeCount, '.mentor-table .mentee.name-row');
    }

    private function assertSessionReportRow($browser, $tableIndex, $reportId, $mentorName, $menteeName)
    {
        $browser->assertSeeIn("tr:nth-child($tableIndex) td.report-id", $reportId);
        $browser->assertSeeIn("tr:nth-child($tableIndex) td.mentor-name", $mentorName);
        $browser->assertSeeIn("tr:nth-child($tableIndex) td.mentee-name", $menteeName);
    }
}
