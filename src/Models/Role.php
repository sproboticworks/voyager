<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;
use TCG\Voyager\Facades\Voyager;
use TCG\Voyager\Traits\HasRelationships;

class Role extends Model
{
    use HasRelationships;

    protected $guarded = [];

    public function users()
    {
        return $this->hasMany(Voyager::modelClass('User'));
    }

    public function permissions()
    {
        return $this->belongsToMany(Voyager::modelClass('Permission'));
    }

    public function operations() {
        return $this->belongsToMany(Operation::class)->withPivot('menu_item_id')->withTimestamps();
    }

    public function menuItems() {
        return $this->belongsToMany(MenuItem::class, 'operation_role')->withPivot('operation_id')->withTimestamps();
    }
}
