<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class PageAuthenticationTest extends TestCase
{
    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    public function testBasicPageAuthentication()
    {
        // Test authentication via status codes on various pages:
        //                      Page                            , Guest , Mentor, Manager   , Admin
        $this->multiplePages([['/'                              , 200   , 200   , 200       , 200],
                              ['/home'                          , 302   , 200   , 200       , 200],
                              ['/login'                         , 200   , 302   , 302       , 302],
                              ['/report'                        , 302   , 302   , 302       , 200],

                              ['/activity-type'                 , 302   , 302   , 302       , 200],
                              ['/mentee'                        , 302   , 302   , 302       , 200],
                              ['/physical-appearance'           , 302   , 302   , 302       , 200],
                              ['/emotional-state'               , 302   , 302   , 302       , 200],
                              ['/expense-claim'                 , 302   , 302   , 302       , 200],
                              ['/roles/mentor'                  , 302   , 302   , 302       , 200],
                              ['/roles/manager'                 , 302   , 302   , 302       , 200],
                              ['/roles/finance'                 , 302   , 302   , 302       , 200],
                              ['/roles/admin'                   , 302   , 302   , 302       , 200],
                              ['/register'                      , 302   , 302   , 302       , 200],
                              ['/my-reports'                    , 302   , 200   , 404       , 404],
                              ['/my-expense-claims'             , 302   , 200   , 404       , 404],
                              ['/manager/review-claims'         , 404   , 404   , 200       , 200],
                              ['/manager/report/export'         , 404   , 404   , 200       , 200],
                              ['/manager/expense-claim/export'  , 404   , 404   , 200       , 200]
                              //TODO: Manager and mentor roles
                              //TODO: DELETE ALL - Admin only
                              //TODO: View individual reports, add items, etc
                             ]);
    }
}
