<?php

namespace Laravel\Spark;

use Illuminate\Support\Str;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use RoutesNotifications;

    private static $REDACTED_STRING = '_DELETED_';

    /**
     * Redact all personal information from user object
     *
     * Idea is that we don't want to actually delete a user because it would corrupt reports.
     * So instead we're removing all personal data from the user row.
     */
    public function redactPersonalDetails()
    {
        // Email address cannot repeat and cannot be empty. So we'll construct a new fake email address
        $newEmail = $this['id'] . '@example.com';
        $personalFields = ['name', 'password', 'remember_token', 'photo_url'];

        foreach ($personalFields as $field)
        {
            $this[$field] = '_DELETED_';
        }

        $this['email'] = $newEmail;
        $this['deleted_at'] = now();
        $this->save();
    }

    /**
     * Get the profile photo URL attribute.
     *
     * @param  string|null  $value
     * @return string|null
     */
    public function getPhotoUrlAttribute($value)
    {
        return empty($value) ? 'https://www.gravatar.com/avatar/'.md5(Str::lower($this->email)).'.jpg?s=200&d=mm' : url($value);
    }

    /**
     * Make the team user visible for the current user.
     *
     * @return $this
     */
    public function shouldHaveSelfVisibility()
    {
        return $this->makeVisible([
            
        ]);
    }

    /**
     * Convert the model instance to an array.
     *
     * @return array
     */
    public function toArray()
    {
        $array = parent::toArray();

        return $array;
    }
}
