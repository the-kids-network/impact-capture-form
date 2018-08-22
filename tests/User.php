<?php

namespace Tests;

use App\User as AppUser;

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
