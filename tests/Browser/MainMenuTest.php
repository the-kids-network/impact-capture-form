<?php

namespace Tests\Browser;

use App\User;
use Tests\DuskTestCase;
use Laravel\Dusk\Browser;
use Tests\Browser\Pages\MainMenu;

use Tests\Browser\Pages\ActivityTypesPage;
use Tests\Browser\Pages\UserManagementAdminPage;
use Tests\Browser\Pages\EmotionalStatePage;
use Tests\Browser\Pages\ExpenseClaimsPage;
use Tests\Browser\Pages\UserManagementManagerPage;
use Tests\Browser\Pages\MenteePage;
use Tests\Browser\Pages\RegisterUserPage;


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
            $availableActions = ['ACTIVITY TYPES', 'MENTEE', 'EMOTIONAL STATE', 'SESSION REPORTS',
                                 'EXPENSE CLAIMS', 'MENTOR', 'MANAGER', 'ADMIN'];
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
            $actions = [new ActivityTypesPage(), new UserManagementAdminPage(), new EmotionalStatePage(), 
                        new ExpenseClaimsPage(), new UserManagementManagerPage(), new MenteePage(),
                        new RegisterUserPage()];

            $user = User::where('email', 'admin@example.com')->firstOrFail();
            $browser->loginAs($user);
            foreach ($actions as $page) {
                $browser->visit(new MainMenu())->clickLink($page->name())->on($page);
            }
        });
    }
}
