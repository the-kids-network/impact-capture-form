<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Tests\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Spark\Spark;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    function setUp() {
        parent::setUp();

        $this->usersForRoles = [
            'mentor'=>new User(12, NULL),
            'manager'=>new User(13, 'manager'),
            'admin'=>new User(14, 'admin')
        ];
    }

    function multiplePages($testDataSet) {
        // map to something easier to work with
        $data = array();
        foreach ($testDataSet as $testDataRow) {
            array_push($data, [$testDataRow[0], 'guest', $testDataRow[1]]);
            array_push($data, [$testDataRow[0], 'mentor', $testDataRow[2]]);
            array_push($data, [$testDataRow[0], 'manager', $testDataRow[3]]);
            array_push($data, [$testDataRow[0], 'admin', $testDataRow[4]]);
        }

        // check all pages as guest (unauthenticated)
        // the framework needs to do this way as once authenticated, 
        // in the next section, there is no way to log out
        foreach ($data as $row) {
            if ($row[1] == "guest") {
                $this->check(...$row);
            }
        }

        // check all pages non-guest
        foreach ($data as $row) {
            if ($row[1] != "guest") {
                $this->check(...$row);
            }
        }
    }

    function check($pageUrl, $userRole, $expectedStatusCode) {
        if ($userRole == 'guest') {
            $response = $this->get($pageUrl);
            $this->assertEquals($expectedStatusCode, $response->getStatusCode(), "$pageUrl request failed for guest");
        } else {
            $user = $this->usersForRoles[$userRole];
            $this->be($user);
            $response = $this->actingAs($user)->get($pageUrl);
            $this->assertEquals($expectedStatusCode, $response->getStatusCode(), "$pageUrl request failed for $user");
        }
    }
}