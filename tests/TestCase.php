<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use App\User as AppUser;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Spark;

class User extends AppUser
{
    function __construct($authId, $role) {
        $this->authId = $authId;
        $this->role=$role;
    }

    public function getAuthIdentifier()
    {
        return $this->authId;
    }

}

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;


    function setUp() {
        parent::setUp();

        $this->basicUser = new User(12, NULL);
        $this->managerUser = new User(2, 'manager');
        $this->adminUser = new User(1, 'admin');
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
