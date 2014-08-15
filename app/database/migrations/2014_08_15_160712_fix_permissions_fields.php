<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class FixPermissionsFields extends Migration {

    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Permission::where('name', 'content_delete')->update([
            'name'   => 'create_execute_content_delete',
            'module' => 'create',
            'type'   => 'execute',
        ]);
        Permission::where('name', 'collaborate_delete_tasks')->update([
            'name' => 'collaborate_execute_tasks_delete',
            'type' => 'execute',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Do nothing on down because we changed the original migration. This should be a permanent 1-way change
    }

}
