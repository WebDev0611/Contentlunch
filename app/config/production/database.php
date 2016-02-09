<?php

return array(
  'default' => 'mysql',
  'connections' => array(
    'mysql' => array(
      'driver'    => 'mysql',
      'host'      => 'contentlaunch-prod.ciijdncmo3aq.us-east-1.rds.amazonaws.com',
      'database'  => 'cl_prod',
      'username'  => 'clprod',
      'password'  => 'h*Le8vE7%TF',
      'charset'   => 'utf8',
      'collation' => 'utf8_unicode_ci',
      'prefix'    => '',
    ),
    'redis' => array(
        'cluster' => false,
        'default' => array('host' => 'contentlaunch-prod.i9zt5p.0001.use1.cache.amazonaws.com', 'port' => 6379),
    ),
  )
);
