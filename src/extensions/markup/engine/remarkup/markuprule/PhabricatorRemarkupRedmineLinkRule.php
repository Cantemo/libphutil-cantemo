<?php
  /**
   * Looks for references to GHC Trac issues and links to them as a Remarkup rule.
   */
final class PhabricatorRemarkupRedmineLinkRule
extends PhabricatorRemarkupCustomInlineRule {
  public function getPriority() {
    return 200.0;
  }
  public function apply($text) {
    if ($this->getEngine()->isTextMode()) {
      return $text;
    }
    return preg_replace_callback(
				 '/\B#(\d+)/',
				 array($this, 'markupInlineRedmineLink'),
				 $text);
  }
  public function markupInlineRedmineLink(array $matches) {
    $uri = 'https://vvv2.cantemo.com/search?q='.$matches[1];
    $ref = '#'.$matches[1];
    $link = phutil_tag('a', array(
				  'href' => $uri,
				  'style' => 'font-weight: bold;',
				  ), $ref);
    $engine = $this->getEngine();
    return $engine->storeText($link);
  }
}
