<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddsCorrectContentAuthorToContentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        collect(DB::table('contents')->get())->each(function($content) {
            DB::table('contents')
                ->where('contents.id', $content->id)
                ->update([ 'user_id' => $this->getAuthorID($content) ]);
        });
    }

    protected function getAuthorID($content)
    {
        $contentUser = DB::table('content_user')
            ->where('content_id', $content->id)
            ->orderBy('id', 'asc')
            ->first();

        if (!$contentUser) {
            $contentUser = DB::table('account_user')
                ->where('account_id', $content->account_id)
                ->orderBy('id', 'asc')
                ->first();
        }

        return $contentUser->user_id;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {

    }
}
