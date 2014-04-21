<?php

use Woodling\Woodling;

Woodling::seed('AccountContentSettings', function ($blueprint) {
  $rand_array = function () {
    $array = array(
      'foo' => 'bar',
      'bar' => '2',
      'baz' => 'crux',
      'alpha' => 'beta',
      'beta' => '4',
      'chuck' => 'norris',
    );
    $array = array_where($array, function ($key, $value) {
      return rand(0, 1) == 1;
    });
    return $array;
  };
  $blueprint->include_name = $rand_array();
  $blueprint->allow_edit_date = $rand_array();
  $blueprint->keyword_tags = $rand_array();
  $blueprint->persona_columns = array('enabled' => 1, 'content_types' => array('case_study', 'blog_post'));
  $blueprint->personas = $rand_array();
  $blueprint->publishing_guidelines = "Lorum ipsum";
  // Should be overriden
  $blueprint->account_id = null;
});
