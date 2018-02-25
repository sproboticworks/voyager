<?php

namespace TCG\Voyager\Models;

use Illuminate\Database\Eloquent\Model;

class Module extends Model
{
	
	public function parent() {
		return $this->belongsTo(Module::class, 'parent_module_id');
	}

	public function children() {
        return $this->hasMany(Module::class, 'parent_module_id');
    }
	
	public function operations() {
        return $this->hasMany(Operation::class, 'module_id');
    }
}

