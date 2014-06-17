<?php

require_once __DIR__ . '/src/ClassLoader.php';

spl_autoload_register(
  array(
    new \Drupal\markdown_extra\Tests\ClassLoader(),
    'loadClass'
  ));

require_once dirname(__DIR__) . '/markdown_extra.module';
