<?php

use App\Adjustment;
use App\Content;
use App\Task;
use App\TaskAdjustment;
use App\User;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;

class CreateActivityLogTable extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        Schema::create('activity_log', function (Blueprint $table) {
            $table->increments('id');
            $table->string('log_name')->nullable();
            $table->string('description');
            $table->integer('subject_id')->nullable();
            $table->string('subject_type')->nullable();
            $table->integer('causer_id')->nullable();
            $table->string('causer_type')->nullable();
            $table->text('properties')->nullable();
            $table->timestamps();

            $table->index('log_name');
        });

        $mergedHistory = collect($this->convertedContentHistory()->toArray())
            ->merge($this->convertedTaskHistory()->toArray())
            ->sort(function($adjustmentA, $adjustmentB) {
                return $adjustmentA['created_at']->lt($adjustmentB['created_at']) ? 1 : -1;
            });

        DB::table('activity_log')->insert($mergedHistory->toArray());
    }

    protected function convertedContentHistory()
    {
        return Adjustment::get()->map(function($adjustment) {
                $newData = json_decode($adjustment->after);
                $oldData = json_decode($adjustment->before);

                return [
                    'log_name' => 'default',
                    'description' => 'update',
                    'subject_id' => $adjustment->content_id,
                    'subject_type' => Content::class,
                    'causer_id' => $adjustment->user_id,
                    'causer_type' => User::class,
                    'properties' => json_encode([
                        'attributes' => $newData,
                        'old' => $oldData,
                    ]),
                    'created_at' => $adjustment->created_at,
                    'updated_at' => $adjustment->updated_at,
                ];
            })
            ->filter(function($adjustment) {
                return $adjustment['causer_id'] !== null && $adjustment['subject_id'] !== null;
            });
    }

    protected function convertedTaskHistory()
    {
        return TaskAdjustment::get()->map(function($adjustment) {
                $newData = json_decode($adjustment->after);
                $oldData = json_decode($adjustment->before);

                return [
                    'log_name' => 'default',
                    'description' => 'update',
                    'subject_id' => $adjustment->task_id,
                    'subject_type' => Task::class,
                    'causer_id' => $adjustment->user_id,
                    'causer_type' => User::class,
                    'properties' => json_encode([
                        'attributes' => $newData,
                        'old' => $oldData,
                    ]),
                    'created_at' => $adjustment->created_at,
                    'updated_at' => $adjustment->updated_at,
                ];
            })
            ->filter(function($adjustment) {
                return $adjustment['causer_id'] !== null && $adjustment['subject_id'] !== null;
            });;
    }

    /**
     * Reverse the migrations.
     */
    public function down()
    {
        Schema::drop('activity_log');
    }
}
