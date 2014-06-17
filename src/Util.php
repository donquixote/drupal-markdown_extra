<?php


namespace Drupal\markdown_extra;


class Util {

  /**
   * @param string $textContent
   *
   * @return array|null
   */
  static function textContentExtractAttributes(&$textContent) {
    if (!preg_match('#^{([^{}]+)}#', $textContent, $m)) {
      return NULL;
    }
    list(, $attributesEncoded) = $m;
    $textContent = substr($textContent, strlen($attributesEncoded) + 2);

    return self::textExtractAttributes($attributesEncoded);
  }

  /**
   * @param string $attributesEncoded
   *
   * @return array
   */
  static function textExtractAttributes($attributesEncoded) {
    $attributes = array();
    $classes = array();
    while ('' !== $attributesEncoded) {
      if ('.' === $attributesEncoded{0}) {
        if (!preg_match('#^\.([a-zA-Z0-9\-_]+)(.*)$#', $attributesEncoded, $m)) {
          break;
        }
        list(, $className, $attributesEncoded) = $m;
        $classes[$className] = $className;
      }
      elseif ('#' === $attributesEncoded{0}) {
        if (!preg_match('#^\#([a-zA-Z0-9\-_]+)(.*)$#', $attributesEncoded, $m)) {
          break;
        }
        list(, $id, $attributesEncoded) = $m;
        $attributes['id'] = $id;
      }
      elseif ('[' === $attributesEncoded{0}) {
        if (!preg_match('#^\[([^\[\]]+)\](.*)$#', $attributesEncoded, $m)) {
          break;
        }
        list(, $str, $attributesEncoded) = $m;
        if (!preg_match('#^([[a-zA-Z0-9\-_]+)=(.*)$#', $str, $m)) {
          continue;
        }
        list(, $key, $value) = $m;
        // @todo Support " in attribute values.
        $attributes[$key] = $value;
      }
      else {
        break;
      }
    }
    if (!empty($classes)) {
      $attributes['class'] = implode(' ', $classes);
    }
    return $attributes;
  }

  /**
   * @param \DOMElement $elementToModify
   * @param array $attributes
   */
  static function elementAddAttributes($elementToModify, $attributes) {
    if (!empty($attributes['class'])) {
      $existingClasses = $elementToModify->getAttribute('class');
      if (!empty($existingClasses)) {
        $classes = explode(' ', $attributes['class'] . ' ' . $existingClasses);
        $attributes['class'] = implode(' ', array_unique($classes));
      }
    }
    foreach ($attributes as $key => $value) {
      $elementToModify->setAttribute($key, $value);
    }
  }

} 
