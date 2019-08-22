<?php

namespace App;

use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Notifications\RoutesNotifications;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Support\Facades\Storage;
use Intervention\Image\ImageManagerStatic as Image;

class User extends Authenticatable
{
    use SoftDeletes, RoutesNotifications;

    private const REDACTED_STRING = '_DELETED_';

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = [
        'created_at',
        'updated_at',
        'deleted_at'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
        'photo_url'
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array
     */
    protected $appends = ['photo'];

    public function getPhotoAttribute()
    {
        return $this->getProfilePhoto();
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

    public function reports(){
        return $this->hasMany('App\Report','mentor_id');
    }

    public function expense_claims(){
        return $this->hasMany('App\ExpenseClaim','mentor_id');
    }

    public function manager() {
        return $this->belongsTo('App\User','manager_id');
    }

    public function assignedMentors() {
        return $this->hasMany('App\User','manager_id');
    }

    public function mentees() {
        return $this->hasMany('App\Mentee','mentor_id');
    }

    public function processedClaims() {
        return $this->hasMany('App\ExpenseClaim','processed_by_id')->whereIn('status', ['rejected', 'processed']);
    }

    public function hasRole($role) {
        if ($role == "mentor") {
            return !$this->role;
        } else {
            return $this->role && $this->role == $role;
        }
    }

    public function isManager() {
        return $this->hasRole("manager");
    }

    public function isMentor() {
        return $this->hasRole("mentor");
    }

    public function isAdmin() {
        return $this->hasRole("admin");
    }

    public function scopeCanSee($query) {
        if (Auth::user()->isAdmin()) {
            // show all so no restriction
        }
        else if (Auth::user()->isManager()) {
            $query->whereManagerId(Auth::user()->id);
        }
        else if (Auth::user()->isMentor()) {
            $query->find(Auth::user()->id);
        }
        return $query;
    }

    public function scopeMentor($query) {
        $query->whereNull('role');
        return $query;
    }

    public function scopeAdmin($query) {
        $query->whereRole('admin');
        return $query;
    }

    /**
     * Overrides SoftDelete, to instead redact personal details
     * instead of deleting the row.
     */
    public function forceDelete() {
        $this->redactPersonalDetails();
        return parent::delete();
    }

    public function scopeWithDeactivated($query) {
        return $query->withTrashed()->where('permenantly_deleted', false);
    }

    public function getProfilePhoto(){
        $path = $this->photo_url;

        if ($path && Storage::exists($path)) {
            $photo = Storage::get($path);
            return Image::make($photo)->encode('data-url')->encoded;
        } else {
            return 'https://www.gravatar.com/avatar/'.md5(Str::lower($this->email)).'.jpg?s=200&d=mm';
        }
    }

    public function setProfilePhoto($imageRaw) {
        $imageProcessed = $this->formatImage($imageRaw);

        // store photo on disk
        $path = $imageRaw->hashName('profile');
        Storage::put($path, $imageProcessed);

        // delete old photo if possible
        $oldPhotoPath = $this->photo_url;
        Storage::delete($oldPhotoPath);
        
        // store path to new photo against user
        $this->forceFill([
            'photo_url' => $path,
        ])->save();
    }

    public function unsetProfilePhoto() {
        if ($this->photo_url) {
            Storage::delete($this->photo_url);
            $this->photo_url = null;
            $this->save();
        }
    }

    /**
     * Resize an image instance for the given file.
     *
     * @param  \SplFileInfo  $file
     * @return \Intervention\Image\Image
     */
    private function formatImage($file) {
        return (string) Image::make($file->path())->fit(300)->encode();
    }

    /**
     * Redact all personal information from user object
     */
    private function redactPersonalDetails()
    {
        $this->unsetProfilePhoto();

        // some fields are non-nullable hence the _DELETED_
        $this['name'] = self::REDACTED_STRING;
        $this['email'] = $this['id'] . '@example.com';
        $this['password'] = self::REDACTED_STRING;
        $this['remember_token'] = null;
        $this['permenantly_deleted'] = true;

        $this->save();
    }
}
