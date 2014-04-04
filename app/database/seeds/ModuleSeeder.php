<?php

class ModuleSeeder extends Seeder {

  public function run()
  {
    $data = array(
      array('create', 'CREATE'),
      array('calendar', 'CALENDAR'),
      array('launch', 'LAUNCH'),
      array('measure', 'MEASURE'),
      array('collaborate', 'COLLABORATE'),
      array('consult', 'CONSULT')
    );
    foreach ($data as $row) {
      $module = new Module;
      $module->name = $row[0];
      $module->title = $row[1];
      $module->save();
    }
  }

}
