<?php


namespace Drupal\markdown_extra\Tests;


use Drupal\markdown_extra\Filter\MarkdownExtraAttributesFilter;

class MarkdownExtraAttributesTestCase extends \PHPUnit_Framework_TestCase {

  function providerMarkdownAttributes() {
    $dir = dirname(__DIR__) . '/fixtures/MarkdownExtraAttributes';
    $pairs = array();
    foreach (scandir($dir) as $candidate) {
      if ('.' === $candidate{0}) {
        continue;
      }
      if (!preg_match('#^(.+)\.(in|out)\.txt$#', $candidate, $m)) {
        continue;
      }
      list(,$name,$type) = $m;
      $file = $dir . '/' . $candidate;
      $pairs[$name][$type] = file_get_contents($file);
    }
    $variations = array();
    foreach ($pairs as $pair) {
      if (isset($pair['in']) && isset($pair['out'])) {
        $variations[] = array($pair['in'], $pair['out']);
      }
    }
    return $variations;
  }

  /**
   * @dataProvider providerMarkdownAttributes
   *
   * @param string $input
   * @param string $expected
   */
  function testMarkdownAttributes($input, $expected) {
    $filter = new MarkdownExtraAttributesFilter();
    $this->assertEquals($expected, $filter->filter($input));
  }

  function testFoo() {
    $this->assertEquals(1, 1);
  }

} 
