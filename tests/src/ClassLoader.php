<?php


namespace Drupal\markdown_extra\Tests;


class ClassLoader {

  /**
   * @var string[]
   */
  private $namespaces = array();

  function __construct() {
    $this->namespaces['Drupal\markdown_extra\Tests\\'] = __DIR__;
    $this->namespaces['Drupal\markdown_extra\\'] = dirname(dirname(__DIR__)) . '/src';
  }

  /**
   * @param string $class
   */
  function loadClass($class) {
    foreach ($this->namespaces as $namespace => $dir) {
      if (0 === strpos($class, $namespace)) {
        $strlen = strlen($namespace);
        $relativeClassName = substr($class, $strlen);
        $relativePath = str_replace('\\', '/', $relativeClassName) . '.php';
        $file = $dir . '/' . $relativePath;
        if (file_exists($file)) {
          require $file;
        }
      }
    }
  }

} 
