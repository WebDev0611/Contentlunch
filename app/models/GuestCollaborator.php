<?php

use LaravelBook\Ardent\Ardent;

class GuestCollaborator extends Ardent {

    protected $table = 'guest_collaborators';

    public $autoHydrateEntityFromInput = false;
    public $forceEntityHydrationFromInput = false;

    protected $guarded = [
        'id',
        'created_at',
        'updated_at',
    ];

    public static $rules = [
        'connection_user_id' => 'required',
        'access_code'        => 'required',
        'name'               => 'required',
        'connection_id'      => 'required',
        'content_id'         => 'required',
        'content_type'       => 'required',
    ];

    public function content()
    {
        return $this->belongsTo('content');
    }

    public function connection()
    {
        return $this->belongsTo('connection');
    }

    public function beforeSave()
    {
        if (is_array($this->settings)) {
            $this->settings = serialize($this->settings);
        }
        return true;
    }

    public function toArray()
    {
        $values = parent::toArray();
        // we may be choosing not to select settings
        if (empty($values['settings'])) return $values;

        if (!is_array($values['settings'])) {
            $values['settings'] = unserialize($values['settings']);
        }
        return $values;
    }

    public function cloneMe()
    {
        $class = get_called_class();
        $clone = new $class($this->toArray());
        unset($clone->id);
        $clone->exists = false;
        return $clone;
    }

    public function canViewContent($contentID)
    {
        return self::guestCanViewContent($contentID, $this);
    }

    static function guestCanViewContent($contentID, $guest = false)
    {
        if (!$guest) $guest = Session::get('guest');
        return $guest && $guest->content_id == $contentID;
    }
}
