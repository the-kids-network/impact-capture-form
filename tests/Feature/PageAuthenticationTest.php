<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

use App\User;

class PageAuthenticationTest extends TestCase
{
    public function testBasicPageAuthentication()
    {
        // Test authentication via status codes on various pages:
        //                      Page                            , Guest , Mentor, Manager   , Admin
        $this->multiplePages([['/'                              , 200   , 200   , 200       , 200],

                              ['/login'                         , 200   , 302   , 302       , 302],
                              ['/register'                      , 302   , 401   , 401       , 200],

                              ['/home'                          , 302   , 200   , 200       , 200],
                         //   ['/delete-all'                    , 302   , 401   , 401       , 200],
                              ['/expense-claim/new'             , 302   , 200   , 401       , 401],
                              ['/calendar'                      , 302   , 200   , 200       , 200],

                              ['/activity-type'                 , 302   , 401   , 401       , 200],
                              ['/emotional-state'               , 302   , 401   , 401       , 200],
                              ['/physical-appearance'           , 302   , 401   , 401       , 200],

                              ['/finance/expense-claim/export'  , 302   , 401   , 401       , 200],
                              ['/finance/process-expense-claims', 302   , 401   , 401       , 200],

                              ['/mentee'                        , 302   , 401   , 401       , 200],

                              ['/report'                        , 302   , 200   , 200       , 200],
                              ['/report/new'                    , 302   , 200   , 401       , 401],
                              ['/report/export'                 , 302   , 200   , 200       , 200],

                              ['/expense-claim'                 , 302   , 401   , 200       , 200],
                         //   ['/expense-claim/1'                 , 302   , 401   , 401       , 200],
                              ['/expense-claim/export'          , 302   , 401   , 200       , 200],
                              ['/receipt/download-all'          , 302   , 401   , 404       , 404],
                         //   ['/receipt/1'                     , 302   , 401   , 200       , 200],

                              ['/reporting/mentor'              , 302   , 401   , 302       , 302],
                              ['/reporting/mentor'.
                                '?start_date=01-01-1970'.
                                  '&end_date=16-05-2019'        , 302   , 401   , 200       , 200],
                              ['/reporting/mentor/export'       , 302   , 401   , 302       , 302],
                              ['/reporting/mentor'.
                                  '?start_date=01-01-1970'.
                                  '&end_date=16-05-2019'        , 302   , 401   , 200       , 200],

                              ['/roles/mentor'                  , 302   , 401   , 401       , 200],
                              ['/roles/manager'                 , 302   , 401   , 401       , 200],
                              ['/roles/admin'                   , 302   , 401   , 401       , 200],
                              
                              //TODO: Manager and mentor roles
                              //TODO: DELETE ALL - Admin only
                              //TODO: View individual reports, add items, etc
                             ]);
    }
}

