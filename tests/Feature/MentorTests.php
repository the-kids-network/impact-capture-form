<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class MentorTests extends TestCase
{
    public function testListOfMentors() {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMenteesAppearInMentorList() {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testCanCancelMentorDeletion() {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMentorDeletionApiForAdmin() {
        // Check the database to make sure that it contains the correct value (including redacted data and deleted_at)
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMentorDeletionApiForMentor() {
        // Shouldn't work
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testMentorDeletionApiForUnauthenticatedUser() {
        // Shouldn't work
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testDeletedUserNotInMentorList() {
        // Can just set deleted_at to correct value to test
        $this->markTestIncomplete('This test has not been implemented yet.');
    }

    public function testCanViewReportsFromDeletedMentors() {
        $this->markTestIncomplete('This test has not been implemented yet.');
    }
}
