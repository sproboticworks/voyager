<?php 

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model {

    const ACTIONS = ['INDEX', 'CREATE', 'STORE', 'SHOW', 'EDIT', 'UPDATE', 'DESTROY', 
                    'DELETE', 'GET', 'ACCEPT', 'APPROVE', 'PUBLISH', 'SCHEDULE', 'CANCEL', 'UPLOAD',
                    'LOGIN', 'LOGOUT', 'ENABLE', 'DISABLE', 'INSTALL', 'UNINSTALL', 'ORDER', 'ADD',
                    'POST', 'MOVE', 'REMOVE', 'NEW', 'RENAME', 'CROP'];
    
    public function module() {
        return $this->belongsTo(Module::class);
    }

    public function roles() {
        return $this->belongsToMany(Role::class)->withPivot('menu_item_id')->withTimestamps();
    }
}
