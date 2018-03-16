<?php

use Illuminate\Database\Seeder;
use TCG\Voyager\Models\Operation;
use TCG\Voyager\Models\Role;

class OperationRoleTableSeeder extends Seeder
{
    /**
     * Auto generated seed file.
     *
     * @return void
     */
    public function run()
    {
        $role = Role::where('name', 'admin')->firstOrFail();

        $operations = Operation::all();

        $role->operations()->sync(
            $operations->pluck('id')->all()
        );
    }
}
