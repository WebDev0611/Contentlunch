<?php

use App\Campaign;
use App\Content;
use App\Task;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Log;

class AddsAccountIdToActivityLog extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->integer('account_id')->after('log_name')->unsigned()->nullable();
        });

        collect(DB::table('activity_log')->get())->each(function($log) {
            try {
                DB::table('activity_log')
                    ->where('activity_log.id', $log->id)
                    ->update([
                        'account_id' => $this->getAccountId($log->subject_type, $log->subject_id)
                    ]);
            } catch (\Exception $e) {
                // We're trying to get the account ID using the content/task/campaign account id. If
                // the exception is thrown, it means the record wasn't found and the log is most
                // likely a 'delete' event. In this case, there's no reliable way to infer the
                // activity account log.
            }
        });
    }

    protected function getAccountId($type, $id)
    {
        switch ($type) {
            case Task::class: $query = DB::table('tasks'); break;
            case Content::class: $query = DB::table('contents'); break;
            case Campaign::class: $query = DB::table('campaigns'); break;
        }

        return $query->find($id)->account_id;
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('activity_log', function (Blueprint $table) {
            $table->dropColumn([ 'account_id' ]);
        });
    }
}
