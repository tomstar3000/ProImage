<?php
/*
UPDATES

    2008-08-01  Fixed CSS comment stripping regex to add PCRE_DOTALL (changed from '/\/\*.*\*\//U' to '/\/\*.*\*\//sU')

*/
// you can extend this to support file input/output or just add new functions to this class!
class Emogrifier {

    private $html = '';
    private $css = '';
    private $unprocessableHTMLTags = array('wbr');

    public function __construct($html = '', $css = '') {
        $this->html = $html;
        $this->css  = $css;
    }

    public function setHTML($html = '') { $this->html = $html; }
    public function setCSS($css = '') { 
			$this->css = $css;
			$this->css = str_replace('\\"','"',$this->css);
			$this->css = str_replace(array('<style>','<style type="text/css">','</style>','<!--','-->','@charset "utf-8";','/* CSS Document */'),"",$this->css);
		}

	// there are some HTML tags that DOMDocument cannot process, and will throw an error if it encounters them.
	// these functions allow you to add/remove them if necessary
    public function addUnprocessableHTMLTag($tag) { $this->unprocessableHTMLTags[] = $tag; }
    public function removeUnprocessableHTMLTag($tag) {
        if (($key = array_search($tag,$this->unprocessableHTMLTags)) !== false)
            unset($this->unprocessableHTMLTags[$key]);
    }

    // applies the CSS you submit to the html you submit. places the css inline
	public function emogrify() {
	    // process the CSS here, turning the CSS style blocks into inline css
		$unprocessableHTMLTags = implode('|',$this->unprocessableHTMLTags);
		$body = preg_replace("/<($unprocessableHTMLTags)[^>]*>/i",'',$this->html);

		$xmldoc = new DOMDocument();
		@$xmldoc -> loadHTML($body);

		$xpath = new DOMXPath($xmldoc);

		// get rid of css comment code
		$re_commentCSS = '/\/\*.*\*\//sU';
		$css = preg_replace($re_commentCSS,'',$this->css);

		// process the CSS file for selectors and definitions
		$re_CSS = '/^\s*([^{]+){([^}]+)}/mis';
		preg_match_all($re_CSS,$css,$matches);

		foreach ($matches[1] as $key => $selectorString) {
		    // if there is a blank definition, skip
		    if (!strlen(trim($matches[2][$key]))) continue;

		    // split up the selector
		    $selectors = explode(',',$selectorString);
		    foreach ($selectors as $selector) {
		        // don't process pseudo-classes
		        if (strpos($selector,':') !== false) continue;

		        // query the body for the xpath selector
				$nodes = $xpath->query($this->translateCSStoXpath(trim($selector)));
				foreach($nodes as $node) {
							// if it has a style attribute, get it, process it, and append (overwrite) new stuff
							if ($node->hasAttribute('style')) {
									$style = $node->getAttribute('style');
									// break it up into an associative array
									$oldStyleArr = $this->cssStyleDefinitionToArray($node->getAttribute('style'));
									$newStyleArr = $this->cssStyleDefinitionToArray($matches[2][$key]);

									// new styles overwrite the old styles (not technically accurate, but close enough)
									$combinedArr = array_merge($oldStyleArr,$newStyleArr);
									$style = '';
									foreach ($combinedArr as $k => $v) $style .= ($k . ':' . $v . ';');
							} else {
									// otherwise create a new style
									$style = trim($matches[2][$key]);
							}
							$node->setAttribute('style',$style);
					}
		    }
		}

		// This removes styles from your email that contain display:none;. You could comment these out if you want.
        $nodes = $xpath->query('//*[contains(translate(@style," ",""),"display:none;")]');
        foreach ($nodes as $node) $node->parentNode->removeChild($node);

		return $xmldoc->saveHTML();

	}

	// right now we only support CSS 1 selectors, but include CSS2/3 selectors are fully possible.
	// http://plasmasturm.org/log/444/
	private function translateCSStoXpath($css_selector) {
	    // returns an Xpath selector
	    $search = array(
	                       '/\s+>\s+/', // Matches any F element that is a child of an element E.
	                       '/(\w+)\s+\+\s+(\w+)/', // Matches any F element that is a child of an element E.
	                       '/\s+/', // Matches any F element that is a descendant of an E element.
	                       '/(\w+)?\#([\w\-]+)/e', // Matches id attributes
	                       '/(\w+)?\.([\w\-]+)/e', // Matches class attributes
	    );
	    $replace = array(
	                       '/',
	                       '\\1/following-sibling::*[1]/self::\\2',
	                       '//',
	                       "(strlen('\\1') ? '\\1' : '*').'[@id=\"\\2\"]'",
	                       "(strlen('\\1') ? '\\1' : '*').'[contains(concat(\" \",@class,\" \"),concat(\" \",\"\\2\",\" \"))]'",
	    );
	    return '//'.preg_replace($search,$replace,trim($css_selector));
	}

	private function cssStyleDefinitionToArray($style) {
	    $definitions = explode(';',$style);
	    $retArr = array();
	    foreach ($definitions as $def) {
    	    list($key,$value) = explode(':',$def);
    	    if (empty($key) || empty($value)) continue;
    	    $retArr[trim($key)] = trim($value);
	    }
	    return $retArr;
	}
}
?>
