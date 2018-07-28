<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function setUp() {
        parent::setUp();

        $this->basicUser = factory(\App\User::class)->create();
        $this->managerUser = factory(\App\User::class)->states('manager')->create();
        $this->adminUser = factory(\App\User::class)->states('admin')->create();
    }
/**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    function allRoleTest($page, $guestStatusCode, $mentorStatusCode, $managerStatusCode, $adminStatusCode) {
        $this->basicUserTest($page, [
            array('user' => $this->basicUser    , 'status' => $mentorStatusCode),
            array('user' => $this->managerUser  , 'status' => $managerStatusCode),
            array('user' => $this->adminUser    , 'status' => $adminStatusCode)
        ]);
    }

    function multiplePages($fullPageList) {
        foreach ($fullPageList as $pageObject) {
            $page = $pageObject[0];
            $response = $this->get($page);
            $this->assertEquals($pageObject[1], $response->getStatusCode(), "$page request failed for guest");
        }

        foreach ($fullPageList as $pageObject) {
            $this->allRoleTest(...$pageObject);
        }
    }

    /**
    * @runInSeparateProcess
    * @preserveGlobalState disabled
    */
    function basicUserTest($page, $expectedStatusCodes)
    {
        foreach ($expectedStatusCodes as $expected) {
            $user = $expected['user'];
            if ($user)
            {
                $this->be($user);
                $response = $this->actingAs($user)->get($page);
            }
            else
            {
                $response = $this->get($page);
            }

            $this->assertEquals($expected['status'], $response->getStatusCode(), "$page request failed for $user");
        }
    }
}
