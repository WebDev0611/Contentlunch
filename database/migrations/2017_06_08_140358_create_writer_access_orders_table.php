<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWriterAccessOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('writer_access_orders', function (Blueprint $table) {
            $table->increments('id');

            $table->integer('approvedwords')->nullable();
            $table->integer('approvedrating')->nullable();
            $table->integer('project_id')->unsigned();
            $table->integer('complexity')->nullable();
            $table->integer('writertype')->nullable();
            $table->integer('minwords')->unsigned()->nullable();
            $table->integer('maxwords')->unsigned()->nullable();
            $table->integer('hourstoexpire')->nullable();
            $table->integer('hourstocomplete')->nullable();
            $table->integer('hourstoapprove')->nullable();
            $table->integer('hourstorevise')->nullable();
            $table->integer('paidreview')->nullable();

            $table->string('order_id')->unique();
            $table->string('status');
            $table->string('title');
            $table->string('approved')->nullable();

            $table->text('instructions');
            $table->text('special');
            $table->text('required');
            $table->text('optional');
            $table->text('seo');
            $table->text('preview_text')->nullable();

            $table->boolean('autoapproved')->nullable();
            $table->boolean('allowhtml');
            $table->boolean('lovelist');

            $table->float('maxcost');

            // Category
            $table->integer('category_id')->unsigned()->nullable();
            $table->string('category_name')->nullable();

            // Asset
            $table->integer('asset_id')->unsigned()->nullable();
            $table->string('asset_name')->nullable();

            // Expertise
            $table->integer('expertise_id')->unsigned()->nullable();
            $table->string('expertise_name')->nullable();

            // FKs
            $table->string('targetwriter_id')->nullable();
            $table->string('writer_id')->nullable();
            $table->string('editor_id')->nullable();

            // Timestamps
            $table->timestamps();
        });

        Schema::table('writer_access_orders', function($table) {
            $table->foreign('targetwriter_id')
                ->references('writer_id')
                ->on('writer_access_writers')
                ->onDelete('set null');

            $table->foreign('writer_id')
                ->references('writer_id')
                ->on('writer_access_writers')
                ->onDelete('set null');

            $table->foreign('editor_id')
                ->references('writer_id')
                ->on('writer_access_writers')
                ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('writer_access_orders');
    }
}
