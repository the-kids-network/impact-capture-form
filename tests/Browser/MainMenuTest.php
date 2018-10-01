<?php

namespace Tests\Browser;

use Artisan;
use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\Login;
use Tests\Browser\Pages\MainMenu;

use Tests\Browser\Pages\ActivityTypesPage;
use Tests\Browser\Pages\AdminPage;
use Tests\Browser\Pages\EmotionalStatePage;
use Tests\Browser\Pages\ExpenseClaimsPage;
use Tests\Browser\Pages\FinancePage;
use Tests\Browser\Pages\ManagerPage;
use Tests\Browser\Pages\MenteePage;
use Tests\Browser\Pages\MentorPage;
use Tests\Browser\Pages\PhysicalAppearancePage;
use Tests\Browser\Pages\RegisterUserPage;
use Tests\Browser\Pages\SessionReportsPage;


class MainMenuTest extends DuskTestCase
{
    //use DatabaseMigrations;
    //use DatabaseTransactions;

    public function setUp(): void
    {
      parent::setUp();

      //Artisan::call('db:seed');
      //fwrite(STDERR, Artisan::output());
    }

    public function testBasicExample()
    {
        $this->browse(function (Browser $browser) {
            $browser->visit('/')
                    ->assertSee('Session Report Database');
        });
    }

    public function testMentorHome()
    {
        $this->browse(function (Browser $browser) {
            $availableActions = ['SUBMIT A SESSION REPORT', 'SUBMIT AN EXPENSE CLAIM'];
            $user = User::where('email', 'mentor1@example.com')->firstOrFail();

            $pageView = $browser->loginAs($user)->visit(new MainMenu());

            foreach($availableActions as $page) {
                $pageView->assertSee($page);
            }
        });
    }

    public function testAdminHome()
    {
        $this->browse(function (Browser $browser) {
            $testing = [['ONE' => 2]];
            $availableActions = ['ACTIVITY TYPES', 'MENTEE', 'PHYSICAL APPEARANCE', 'EMOTIONAL STATE', 'SESSION REPORTS',
                                 'EXPENSE CLAIMS', 'MENTOR', 'MANAGER', 'FINANCE', 'ADMIN'];
            $user = User::where('email', 'admin@example.com')->firstOrFail();

            $pageView = $browser->loginAs($user)->visit(new MainMenu());

            foreach($availableActions as $page) {
                $pageView->assertSee($page);
            }
        });
    }

    public function testCanFollowLinks()
    {
        $this->browse(function (Browser $browser) {
            $actions = [new ActivityTypesPage(), new AdminPage(), new EmotionalStatePage(), new ExpenseClaimsPage(),
                        new FinancePage(), new ManagerPage(), new MenteePage(), new MentorPage(), new PhysicalAppearancePage(),
                        new RegisterUserPage(), new SessionReportsPage()];

            $user = User::where('email', 'admin@example.com')->firstOrFail();
            $browser->loginAs($user);
            foreach ($actions as $page) {
                $browser->visit(new MainMenu())->clickLink($page->name())->on($page);
            }
        });
    }
}
