<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class RemovingUniqueFlagFromEmail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('account_invites', function (Blueprint $table) {
            $schemaBuilder = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableDetails('account_invites');

            if ($schemaBuilder->hasIndex('account_invites_email_unique')) {
                $table->dropIndex('account_invites_email_unique');
            }

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('account_invites', function (Blueprint $table) {
            $schemaBuilder = Schema::getConnection()
                ->getDoctrineSchemaManager()
                ->listTableDetails('account_invites');

            if (!$schemaBuilder->hasIndex('account_invites_email_unique')) {
                $table->unique('email');
            }
        });
    }
}
