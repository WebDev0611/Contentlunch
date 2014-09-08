<?php

use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ContentTypesFixMultipleProductDesc extends Migration {

	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		// Remove one of the 2 instances of product description content type
		// If content belongs to the content type,
		// change it to the other product description
		$type1 = ContentType::where('key', 'product-description')->orderBy('id', 'desc')->first();
		$type2 = ContentType::where('key', 'product-description')->orderBy('id', 'asc')->first();
		$content = Content::where('content_type_id', $type1->id)->get();
		if ($content) {
			foreach ($content as $c) {
				$c->content_type_id = $type2->id;
				$c->updateUniques();
			}
		}
		$type1->delete();
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		//
	}

}
