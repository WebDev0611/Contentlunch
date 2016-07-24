<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateContentsTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('contents', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('content_type_id')->unsigned(); // not negitive
            // - Dedicated Aurthor Table
            $table->date('due_date');
            $table->string('title');
            $table->integer('connection_id')->unsigned(); // - content destination
            // - content template - how does this work?
            // - invite influencers  - no clue what this is, how does this work?
            $table->text('body');
            // - content tags, -         linking table
            // - related content, -     linking talbe
            // - image attachments, - external table
            $table->integer('buying_stage_id')->unsigned()->nullable();
            $table->integer('persona_id')->unsigned()->nullable();
            $table->integer('campaign_id')->unsigned()->nullable();
            $table->string('meta_title')->nullable();
            $table->string('meta_keywords')->nullable();
            $table->string('meta_description')->nullable();
            $table->boolean('archived')->default(0);
            $table->boolean('published')->default(0);
            $table->integer('user_id')->unsigned(); // not negitive
            $table->timestamps();
        });



        // -- Create content_type table
        Schema::create('content_types', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->string('description');
            $table->integer('provider_id')->unsigned(); // not negitive
            $table->timestamps();
        });
        // -- author connection aka to users
        Schema::create('content_user', function (Blueprint $table) {
            $table->integer('content_id')->unsigned(); // not negitive
            $table->integer('user_id')->unsigned(); // not negitive
            $table->timestamps();
        });

        // - Creating Tags Table
        Schema::create('tags', function ($table) {
            $table->increments('id');
            $table->string('tag');
            $table->timestamps();
        });

        // - Creating Buying Stage Table
        Schema::create('buying_stages', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });
        // - Creating Buying Stage Table
        Schema::create('personas', function ($table) {
            $table->increments('id');
            $table->string('name');
            $table->timestamps();
        });

        // -- linking table from CONTENT to Tags
        Schema::create('content_tag', function (Blueprint $table) {
            $table->integer('content_id')->unsigned(); // not negitive
            $table->integer('tag_id')->unsigned(); // not negitive
            $table->timestamps();
        });

        // - Linking to self for related content
        Schema::create('content_related', function ($table) {
            $table->increments('id');
            $table->integer('content_id');
            $table->integer('related_content_id');
            $table->timestamps();
        });

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('contents');
        Schema::drop('content_types');
        Schema::drop('content_user');
        Schema::drop('tags');
        Schema::drop('buying_stages');
        Schema::drop('content_tag');
        Schema::drop('content_related');
    }
}
