<?php

final class RedmineReferenceCustomField extends DifferentialCustomField {

  public function getFieldKey() {
    return 'cantemo:redminereference';
  }

  public function shouldAppearInPropertyView() {
    return true;
  }

  protected function readValueFromRevision(
					   DifferentialRevision $revision) {
    return $revision->getTitle();
  }

  public function renderPropertyViewLabel() {
    return pht('Redmine References');
  }

  private function _values() {
      $title = $this->getObject()->getTitle();
      
      $matches = preg_match_all('/\B#(\d+)/', $title, $out, PREG_SET_ORDER);
      $ret = array();
      foreach ($out as $match) {
	array_push($ret, $match[0]);
      }
      asort($ret);
      return array_unique($ret);
  }
  
  public function renderPropertyViewValue(array $handles) {
    
    $str = join(" ", $this->_values());

    $rendered = preg_replace_callback(
				      '/\B#(\d+)/',
				      array($this, 'markupInlineRedmineLink'),
				      $str);
    
    return phutil_safe_html($rendered);

  }
  public function markupInlineRedmineLink(array $matches) {
    $uri = 'https://vvv.cantemo.com/search?q='.$matches[1];
    $ref = '#'.$matches[1];
    $link = phutil_tag('a', array(
				  'href' => $uri,
				  'style' => 'font-weight: bold;',
				  ), $ref);
    return $link;
    $engine = $this->getObject()->getEngine();
    return $engine->storeText($link);
  }

}
