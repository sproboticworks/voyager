<?php 

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;

class Operation extends Model {

    const ACTIONS = ['INDEX', 'CREATE', 'STORE', 'SHOW', 'EDIT', 'UPDATE', 'DESTROY', 'DELETE', 'GET', 'ACCEPT', 'APPROVE', 'PUBLISH', 'SCHEDULE', 'CANCEL', 'UPLOAD'];
    
    public function module() {
        return $this->belongsTo(Module::class);
    }
}
