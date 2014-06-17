<?php

namespace Drupal\markdown_extra\Filter;

use Drupal\markdown_extra\Util;

class MarkdownExtraAttributesFilter {

  /**
   * @param string $text
   *
   * @return string
   */
  function filter($text) {
    $doc = new \DOMDocument();
    $doc->loadXML('<root>' . $text . '</root>');
    $html = '';
    /** @var \DOMNode $root */
    foreach ($doc->childNodes as $root) {
      $this->filterNodeContents($root);
      /** @var \DOMNode $node */
      foreach ($root->childNodes as $node) {
        $html .= $doc->saveXML($node);
      }
    }
    return $html;
  }

  /**
   * @param \DOMNode $root
   */
  private function filterNodeContents($root) {

    /** @var \DOMElement|null $elementToModify */
    $elementToModify = NULL;

    foreach ($root->childNodes as $node) {
      if ($node instanceof \DOMText) {
        if ('{' === $node->textContent{0}) {
          if (isset($elementToModify)) {
            $textContent = $node->textContent;
            $attributes = Util::textContentExtractAttributes($textContent);
            $node->nodeValue = $textContent;
            if (!empty($attributes)) {
              Util::elementAddAttributes($elementToModify, $attributes);
            }
          }
        }
      }
      elseif ($node instanceof \DOMElement) {
        $this->filterNodeContents($node);
        $elementToModify = $node;
      }
      else {
        $elementToModify = NULL;
      }
    }
  }

} 
