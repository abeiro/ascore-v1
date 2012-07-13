<?php


function ValidateCode($file) {
	$checker = new HTMLSyntaxChecker(file_get_contents($file), "BLOCK");

	if(!$checker->succeeded())
		return array("msg"=>str_replace("\n","","{$checker->getError()}"),"line"=>0);
		
	debug("Validador HTML OK","red");
	return true;

}




/* This software is provided 'as-is', without any express or implied 
 * warranty. In no event will the author be held liable for any 
 * damages arising from the use of this software.
 * 
 * Permission is granted to anyone to use this software for any 
 * purpose, including commercial applications, and to alter it and 
 * redistribute it freely, subject to the following restrictions:
 * 
 * 1. The origin of this software must not be misrepresented; you 
 * must not claim that you wrote the original software. If you use 
 * this software in a product, an acknowledgment in the product 
 * documentation would be appreciated but is not required.
 * 
 * 2. Altered source versions must be plainly marked as such, and 
 * must not be misrepresented as being the original software.
 * 
 * 3. This notice may not be removed or altered from any source 
 * distribution without the copyright holder's permission.
 */

/* Program code Copyright 2001, 2002 Henri Sivonen
 *
 * Attribute and element rules and entity names are based on 
 * data from http://www.w3.org/TR/html401/sgml/dtd.html
 * However, the element and attribute rules don't match 
 * the rules of the DTD exactly.
 *
 * Documentation: http://www.hut.fi/u/hsivonen/HTMLSyntaxChecker
 */

class HTMLSyntaxChecker {
	/* "private" fields */
	var $htmlError;
	var $htmlResult;
	
  /* these really should be static... */
  var $entities;
  var $attlist;
  var $dtd;
  var $empty;
  
  /* public accessors */
	function getResult() {
		return $this->htmlResult;
	}
	
	function getError() {
		return $this->htmlError;
	}

	function succeeded() {
		if($this->htmlError == null) {
			return true;
		} else {
			return false;
		}
	}
  
	function HTMLSyntaxChecker(&$taggedText, $rootModel) {
    /* these really should be in a static initializer... */
  	$this->entities = array(
			'quot' => 1,
			'amp' => 1,
			'lt' => 1,
			'gt' => 1,
			'OElig' => 1,
			'oelig' => 1,
			'Scaron' => 1,
			'scaron' => 1,
			'Yuml' => 1,
			'circ' => 1,
			'tilde' => 1,
			'ensp' => 1,
			'emsp' => 1,
			'thinsp' => 1,
			'zwnj' => 1,
			'zwj' => 1,
			'lrm' => 1,
			'rlm' => 1,
			'ndash' => 1,
			'mdash' => 1,
			'lsquo' => 1,
			'rsquo' => 1,
			'sbquo' => 1,
			'ldquo' => 1,
			'rdquo' => 1,
			'bdquo' => 1,
			'dagger' => 1,
			'Dagger' => 1,
			'permil' => 1,
			'lsaquo' => 1,
			'rsaquo' => 1,
			'euro' => 1,
			'fnof' => 1,
			'Alpha' => 1,
			'Beta' => 1,
			'Gamma' => 1,
			'Delta' => 1,
			'Epsilon' => 1,
			'Zeta' => 1,
			'Eta' => 1,
			'Theta' => 1,
			'Iota' => 1,
			'Kappa' => 1,
			'Lambda' => 1,
			'Mu' => 1,
			'Nu' => 1,
			'Xi' => 1,
			'Omicron' => 1,
			'Pi' => 1,
			'Rho' => 1,
			'Sigma' => 1,
			'Tau' => 1,
			'Upsilon' => 1,
			'Phi' => 1,
			'Chi' => 1,
			'Psi' => 1,
			'Omega' => 1,
			'alpha' => 1,
			'beta' => 1,
			'gamma' => 1,
			'delta' => 1,
			'epsilon' => 1,
			'zeta' => 1,
			'eta' => 1,
			'theta' => 1,
			'iota' => 1,
			'kappa' => 1,
			'lambda' => 1,
			'mu' => 1,
			'nu' => 1,
			'xi' => 1,
			'omicron' => 1,
			'pi' => 1,
			'rho' => 1,
			'sigmaf' => 1,
			'sigma' => 1,
			'tau' => 1,
			'upsilon' => 1,
			'phi' => 1,
			'chi' => 1,
			'psi' => 1,
			'omega' => 1,
			'thetasym' => 1,
			'upsih' => 1,
			'piv' => 1,
			'bull' => 1,
			'hellip' => 1,
			'prime' => 1,
			'Prime' => 1,
			'oline' => 1,
			'frasl' => 1,
			'weierp' => 1,
			'image' => 1,
			'real' => 1,
			'trade' => 1,
			'alefsym' => 1,
			'larr' => 1,
			'uarr' => 1,
			'rarr' => 1,
			'darr' => 1,
			'harr' => 1,
			'crarr' => 1,
			'lArr' => 1,
			'uArr' => 1,
			'rArr' => 1,
			'dArr' => 1,
			'hArr' => 1,
			'forall' => 1,
			'part' => 1,
			'exist' => 1,
			'empty' => 1,
			'nabla' => 1,
			'isin' => 1,
			'notin' => 1,
			'ni' => 1,
			'prod' => 1,
			'sum' => 1,
			'minus' => 1,
			'lowast' => 1,
			'radic' => 1,
			'prop' => 1,
			'infin' => 1,
			'ang' => 1,
			'and' => 1,
			'or' => 1,
			'cap' => 1,
			'cup' => 1,
			'int' => 1,
			'there4' => 1,
			'sim' => 1,
			'cong' => 1,
			'asymp' => 1,
			'ne' => 1,
			'equiv' => 1,
			'le' => 1,
			'ge' => 1,
			'sub' => 1,
			'sup' => 1,
			'nsub' => 1,
			'sube' => 1,
			'supe' => 1,
			'oplus' => 1,
			'otimes' => 1,
			'perp' => 1,
			'sdot' => 1,
			'lceil' => 1,
			'rceil' => 1,
			'lfloor' => 1,
			'rfloor' => 1,
			'lang' => 1,
			'rang' => 1,
			'loz' => 1,
			'spades' => 1,
			'clubs' => 1,
			'hearts' => 1,
			'diams' => 1,
			'nbsp' => 1,
			'iexcl' => 1,
			'cent' => 1,
			'pound' => 1,
			'curren' => 1,
			'yen' => 1,
			'brvbar' => 1,
			'sect' => 1,
			'uml' => 1,
			'copy' => 1,
			'ordf' => 1,
			'laquo' => 1,
			'not' => 1,
			'shy' => 1,
			'reg' => 1,
			'macr' => 1,
			'deg' => 1,
			'plusmn' => 1,
			'sup2' => 1,
			'sup3' => 1,
			'acute' => 1,
			'micro' => 1,
			'para' => 1,
			'middot' => 1,
			'cedil' => 1,
			'sup1' => 1,
			'ordm' => 1,
			'raquo' => 1,
			'frac14' => 1,
			'frac12' => 1,
			'frac34' => 1,
			'iquest' => 1,
			'Agrave' => 1,
			'Aacute' => 1,
			'Acirc' => 1,
			'Atilde' => 1,
			'Auml' => 1,
			'Aring' => 1,
			'AElig' => 1,
			'Ccedil' => 1,
			'Egrave' => 1,
			'Eacute' => 1,
			'Ecirc' => 1,
			'Euml' => 1,
			'Igrave' => 1,
			'Iacute' => 1,
			'Icirc' => 1,
			'Iuml' => 1,
			'ETH' => 1,
			'Ntilde' => 1,
			'Ograve' => 1,
			'Oacute' => 1,
			'Ocirc' => 1,
			'Otilde' => 1,
			'Ouml' => 1,
			'times' => 1,
			'Oslash' => 1,
			'Ugrave' => 1,
			'Uacute' => 1,
			'Ucirc' => 1,
			'Uuml' => 1,
			'Yacute' => 1,
			'THORN' => 1,
			'szlig' => 1,
			'agrave' => 1,
			'aacute' => 1,
			'acirc' => 1,
			'atilde' => 1,
			'auml' => 1,
			'aring' => 1,
			'aelig' => 1,
			'ccedil' => 1,
			'egrave' => 1,
			'eacute' => 1,
			'ecirc' => 1,
			'euml' => 1,
			'igrave' => 1,
			'iacute' => 1,
			'icirc' => 1,
			'iuml' => 1,
			'eth' => 1,
			'ntilde' => 1,
			'ograve' => 1,
			'oacute' => 1,
			'ocirc' => 1,
			'otilde' => 1,
			'ouml' => 1,
			'divide' => 1,
			'oslash' => 1,
			'ugrave' => 1,
			'uacute' => 1,
			'ucirc' => 1,
			'uuml' => 1,
			'yacute' => 1,
			'thorn' => 1,
			'yuml' => 1
		);
 		$attrs = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1
		);
		$quoteattrs = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1,
			'cite' => 1
		);
		
		$this->attlist['p'] = $attrs;
		$this->attlist['h1'] = $attrs;
		$this->attlist['h2'] = $attrs;
		$this->attlist['h3'] = $attrs;
		$this->attlist['h4'] = $attrs;
		$this->attlist['h5'] = $attrs;
		$this->attlist['h6'] = $attrs;
		$this->attlist['ol'] = $attrs;
		$this->attlist['ul'] = $attrs;
		$this->attlist['dl'] = $attrs;
		$this->attlist['div'] = $attrs;
		$this->attlist['blockquote'] = $quoteattrs;
		$this->attlist['address'] = $attrs;
		
		/* list items */
		$this->attlist['li'] = $attrs;
		$this->attlist['dd'] = $attrs;
		$this->attlist['dt'] = $attrs;
		
		/* inline excl big, small, formctrl, various special */
		$this->attlist['tt'] = $attrs;
		$this->attlist['i'] = $attrs;
		$this->attlist['b'] = $attrs;
		$this->attlist['em'] = $attrs;
		$this->attlist['strong'] = $attrs;
		$this->attlist['dfn'] = $attrs;
		$this->attlist['code'] = $attrs;
		$this->attlist['samp'] = $attrs;
		$this->attlist['kbd'] = $attrs;
		$this->attlist['var'] = $attrs;
		$this->attlist['cite'] = $attrs;
		$this->attlist['abbr'] = $attrs;
		$this->attlist['acronym'] = $attrs;
		$this->attlist['a'] = array( 
			'id' => 1, 
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'charset' => 1, 
			'type' => 1, 
			'name' => 1,
			'href' => 1, 
			'hreflang' => 1, 
			'rel' => 1, 
			'rev' => 1, 
			'accesskey' => 1, 
			'shape' => 1, 
			'coords' => 1, 
			'tabindex' => 1
		);
		$this->attlist['img'] = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'src' => 1, 
			'alt' => 1, 
			'longdesc' => 1, 
			'name' => 1, 
			'height' => 1, 
			'width' => 1, 
			'usemap' => 1
		);
		$this->attlist['q'] = $quoteattrs;
		$this->attlist['sub'] = $attrs;
		$this->attlist['sup'] = $attrs;
		$this->attlist['br'] = array();
		
		
		$this->attlist['object'] = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'classid' => 1, 
			'codebase' => 1, 
			'data' => 1, 
			'type' => 1, 
			'codetype' => 1, 
			'archive' => 1, 
			'standby' => 1, 
			'height' => 1, 
			'width' => 1, 
			'usemap' => 1, 
			'name' => 1, 
			'tabindex' => 1
		);
		
		$this->attlist['param'] = array(
			'id' => 1, 
			'name' => 1, 
			'value' => 1, 
			'valuetype' => 1, 
			'type' => 1
		);
		
		$this->attlist['map'] = array(
			'id' => 1, 
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'name' => 1
		);
		
		$this->attlist['area'] = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'shape' => 1, 
			'coords' => 1, 
			'href' => 1, 
			'alt' => 1, 
			'tabindex' => 1, 
			'accesskey' => 1
		);

		/* tables */
		$this->attlist['table'] = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'summary' => 1, 
			'width' => 1, 
			'border' => 1, 
			'frame' => 1, 
			'rules' => 1, 
			'cellspacing' => 1, 
			'cellpadding' => 1
		);
		$this->attlist['tr'] = array(
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'align' => 1, 
			'char' => 1, 
			'charoff' => 1, 
			'valign' => 1
		);
		$this->attlist['td'] = array(
			'id' => 1, 
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'align' => 1, 
			'char' => 1, 
			'charoff' => 1, 
			'valign' => 1, 
			'abbr' => 1, 
			'axis' => 1, 
			'headers' => 1, 
			'scope' => 1,
			'rowspan' => 1, 
			'colspan' => 1
		);
		$this->attlist['th'] = array(
			'id' => 1, 
			'class' => 1, 
	/*		'style' => 1, */
			'title' => 1, 
			'lang' => 1, 
			'align' => 1, 
			'char' => 1, 
			'charoff' => 1, 
			'valign' => 1, 
			'abbr' => 1, 
			'axis' => 1, 
			'headers' => 1, 
			'scope' => 1,
			'rowspan' => 1, 
			'colspan' => 1
		);
	
		/* block excl form, pre, scripting, hr */
		$block = array(
			'p' => 1, 
			'h1' => 1, 
			'h2' => 1, 
			'h3' => 1, 
			'h4' => 1, 
			'h5' => 1, 
			'h6' => 1, 
			'ol' => 1, 
			'ul' => 1, 
			'dl' => 1, 
			'div' => 1, 
			'blockquote' => 1, 
			'address' => 1,
			'table' => 1
		);
		
		/* inline excl big, small, formctrl, various special */
		$inline = array(
			'PCDATA' => 1, 
			'tt' => 1, 
			'i' => 1, 
			'b' => 1, 
			'em' => 1, 
			'strong' => 1, 
			'dfn' => 1, 
			'code' => 1, 
			'samp' => 1, 
			'kbd' => 1, 
			'var' => 1, 
			'cite' => 1, 
			'abbr' => 1, 
			'acronym' => 1, 
			'a' => 1, 
			'img' => 1, 
			'q' => 1, 
			'sub' => 1, 
			'sup' => 1,
			'map' => 1,
			'object' => 1
		);
		
		$inlinebr = array(
			'PCDATA' => 1, 
			'tt' => 1, 
			'i' => 1, 
			'b' => 1, 
			'em' => 1, 
			'strong' => 1, 
			'dfn' => 1, 
			'code' => 1, 
			'samp' => 1, 
			'kbd' => 1, 
			'var' => 1, 
			'cite' => 1, 
			'abbr' => 1, 
			'acronym' => 1, 
			'a' => 1, 
			'img' => 1, 
			'q' => 1, 
			'sub' => 1, 
			'sup' => 1,
			'br' => 1,
			'map' => 1,
			'object' => 1
		);
		
		
		$codekbdsamp = array(
			'PCDATA' => 1, 
			'br' => 1, 
			'i' => 1, 
			'b' => 1, 
			'em' => 1, 
			'strong' => 1, 
			'dfn' => 1, 
			'code' => 1, 
			'samp' => 1, 
			'kbd' => 1, 
			'var' => 1, 
			'cite' => 1, 
			'abbr' => 1, 
			'acronym' => 1, 
			'a' => 1, 
			'q' => 1, 
			'sub' => 1, 
			'sup' => 1
		);
		
		
		$flow = array(
			'PCDATA' => 1, 
			'tt' => 1, 
			'i' => 1, 
			'b' => 1, 
			'em' => 1, 
			'strong' => 1, 
			'dfn' => 1, 
			'code' => 1, 
			'samp' => 1, 
			'kbd' => 1, 
			'var' => 1, 
			'cite' => 1, 
			'abbr' => 1, 
			'acronym' => 1, 
			'a' => 1, 
			'img' => 1, 
			'q' => 1, 
			'sub' => 1, 
			'sup' => 1,
			'p' => 1, 
			'h1' => 1, 
			'h2' => 1, 
			'h3' => 1, 
			'h4' => 1, 
			'h5' => 1, 
			'h6' => 1, 
			'ol' => 1, 
			'ul' => 1, 
			'dl' => 1, 
			'div' => 1, 
			'blockquote' => 1, 
			'address' => 1,
			'map' => 1,
			'object' => 1,
			'table' => 1
		);
		
		$this->empty = array('img' => 1, 'br' => 1, 'param' => 1, 'area' => 1);
		
		
		/* block excl form, pre, scripting, hr */
		$this->dtd['p'] = $inline;
		$this->dtd['h1'] = $inlinebr;
		$this->dtd['h2'] = $inlinebr;
		$this->dtd['h3'] = $inlinebr;
		$this->dtd['h4'] = $inlinebr;
		$this->dtd['h5'] = $inlinebr;
		$this->dtd['h6'] = $inlinebr;
		$this->dtd['ol'] = array('li' => 1);
		$this->dtd['ul'] = array('li' => 1);
		$this->dtd['dl'] = array('dt' => 1, 'dd' => 1);
		$this->dtd['div'] = $flow;
		$this->dtd['blockquote'] = $block;
		$this->dtd['address'] = $inlinebr;
		
		/* list items */
		$this->dtd['li'] = $flow;
		$this->dtd['dd'] = $flow;
		$this->dtd['dt'] = $inline;
		
		/* inline excl big, small, formctrl, various special */
		$this->dtd['tt'] = $inline;
		$this->dtd['i'] = $inline;
		$this->dtd['b'] = $inline;
		$this->dtd['em'] = $inline;
		$this->dtd['strong'] = $inline;
		$this->dtd['dfn'] = $inline;
		$this->dtd['code'] = $codekbdsamp;
		$this->dtd['samp'] = $codekbdsamp;
		$this->dtd['kbd'] = $codekbdsamp;
		$this->dtd['var'] = $inline;
		$this->dtd['cite'] = $inline;
		$this->dtd['abbr'] = $inline;
		$this->dtd['acronym'] = $inline;
		$this->dtd['a'] = $inline; /* a in a handled elsewhere */
		/* $this->dtd['img'] = $inline; */
		$this->dtd['q'] = $inline;
		$this->dtd['sub'] = $inline;
		$this->dtd['sup'] = $inline;
		$this->dtd['map'] = array(
			'p' => 1,
			'h1' => 1,
			'h2' => 1,
			'h3' => 1,
			'h4' => 1,
			'h5' => 1,
			'h6' => 1,
			'ol' => 1,
			'ul' => 1,
			'dl' => 1,
			'div' => 1,
			'blockquote' => 1,
			'address' => 1,
			'table' => 1,
			'area' => 1
		);
		$this->dtd['object'] = array(
			'PCDATA' => 1,
			'tt' => 1,
			'i' => 1,
			'b' => 1,
			'em' => 1,
			'strong' => 1,
			'dfn' => 1,
			'code' => 1,
			'samp' => 1,
			'kbd' => 1,
			'var' => 1,
			'cite' => 1,
			'abbr' => 1,
			'acronym' => 1,
			'a' => 1,
			'img' => 1,
			'q' => 1,
			'sub' => 1,
			'sup' => 1,
			'p' => 1,
			'h1' => 1,
			'h2' => 1,
			'h3' => 1,
			'h4' => 1,
			'h5' => 1,
			'h6' => 1,
			'ol' => 1,
			'ul' => 1,
			'dl' => 1,
			'div' => 1,
			'blockquote' => 1,
			'address' => 1,
			'map' => 1,
			'object' => 1,
			'table' => 1,
			'param' => 1
		);
		
		/* basic tables */
		$this->dtd[table] = array('tr' => 1);
		$this->dtd[tr] = array('td' => 1, 'th' => 1);
		$this->dtd[td] = $flow;
		$this->dtd[th] = $flow;
	
		/* root models */
		$this->dtd['BLOCK'] = $block;
		$this->dtd['INLINE'] = $inline;
		$this->dtd['INLINEBR'] = $inlinebr;
		$this->dtd['FLOW'] = $flow;
		$this->dtd['TEXT'] = array('PCDATA' => 1);
	

		$this->htmlError = null;
		$this->htmlResult = $this->checkHTMLsyntax($taggedText, $rootModel);
	}
  
	function isGoodPCDATA(&$str, $context) {
		$ltindex = strpos($str, '<', 0);
		if(!($ltindex === false)) {
			$this->signalError("&lt; is not allowed here.", ($context . substr($str, 0, $ltindex)), "<");
			return false;
		}
	
		$gtindex = strpos($str, '>', 0);
		if(!($gtindex === false)) {
			$this->signalError("&gt; is not allowed here.", ($context . substr($str, 0, $gtindex)), ">");
			return false;
		}
	
		$startIndex = 0;
		$endIndex = 0;
		for(;;) {
			$startIndex = strpos($str, '&', $endIndex);
			if($startIndex === false) {
				return true;
			}
			$endIndex = strpos($str, ';', $startIndex);
			if($endIndex === false) {
				$this->signalError("Found &amp; but no matching semicolon.", ($context . $str), "");
				return false;
			}
			$startIndex++;
			if($str{$startIndex} == '#') {
				$charnum = 0;
				if($str{$startIndex + 1} == 'x') {
					$charnumstr = substr(
						$str,
						($startIndex + 2),
						($endIndex - $startIndex - 2)
					);
					if (preg_match("/^([0-9]|[A-Z])+\$/i", $charnumstr)) {
						$charnum = intval($charnumstr, 16);
					} else {
						$this->signalError("Bad hexadecimal character reference.", ($context . substr($str, 0, $startIndex)), ("#x" . $charnumstr . ";"));
						return false;
					}
					if(($charnum > 126) && ($charnum < 160)) {
						$this->signalError("Bad reference to a CP1252 character.", ($context . substr($str, 0, $startIndex)), ("#x" . $charnumstr . ";"));
						return false;
					}
				} else {
					$charnumstr = substr(
						$str,
						($startIndex + 1),
						($endIndex - $startIndex - 1)
					);
					if (preg_match("/^[0-9]+\$/", $charnumstr)) {
						$charnum = intval($charnumstr, 10);
					} else {
						$this->signalError("Bad decimal character reference.", ($context . substr($str, 0, $startIndex)), ("#" . $charnumstr . ";"));
						return false;
					}
					if(($charnum > 126) && ($charnum < 160)) {
						$this->signalError("Bad reference to a CP1252 character.", ($context . substr($str, 0, $startIndex)), ("#" . $charnumstr . ";"));
						return false;
					}
				}
			} else {
				$entityName = substr(
					$str,
					$startIndex,
					($endIndex - $startIndex)
				);
				if(!$this->entities[$entityName]) {
					$this->signalError("Bad entity reference.", ($context . substr($str, 0, $startIndex)), ($entityName . ";"));
					return false;
				}
			}
		}
	}
	
	function nextNonIdentifierChar(&$str, $start) {
		$len = strlen($str);
		for($i = $start; $i < $len; $i++) {
			if(	ord($str{$i}) < 48 ||
				(ord($str{$i}) > 57 && ord($str{$i}) < 65) || 
				(ord($str{$i}) > 90 && ord($str{$i}) < 97) ||
				ord($str{$i}) > 122
			) {
				return $i;
			}
		}
		return $i;
	}
	
	function nextNonWhiteSpace(&$str, $start) {
		$len = strlen($str);
		$char = "";
		for($i = $start; $i < $len; $i++) {
			$char = $str{$i};
			if(
				($char != ' ') &&
				(ord($char) != 10) &&
				(ord($char) != 11) &&
				(ord($char) != 13)
			) {
				return $i;
			}
		}
		return $i;
	}
	
	
	function isWhiteSpace(&$str) {
		$len = strlen($str);
		$char = "";
		for($i = 0; $i < $len; $i++) {
			$char = $str{$i};
			if(
				($char != ' ') &&
				(ord($char) != 10) &&
				(ord($char) != 11) &&
				(ord($char) != 13)
			) {
				return false;
			}
		}
		return true;
	}
	
	function checkAttr($tagstr, &$gi, &$context) {
		/* real #REQUIRED check needed. Fix me. */
		if(($gi == "img") && (strpos($tagstr, 'alt=', 0) === false)) {
			$this->signalError("Required attribute alt missing.", $context, $tagstr);
			return null; 
		}
		
		$startIndex = $this->nextNonIdentifierChar($tagstr, 1);
			
		$attrTable = array();
		
		$out = $out = "<" . $gi;
	
		while($startIndex < strlen($tagstr) - 1) {
			$endIndex = $this->nextNonWhiteSpace($tagstr, $startIndex);
			if($tagstr{$endIndex} == '>') {
				break;
			}
			$tmp = strtolower(substr(
				$tagstr,
				$startIndex,
				($endIndex - $startIndex))
			);
			if(!$this->isWhiteSpace($tmp)) {
				$this->signalError("Bogus chars between attributes.", $context, $tagstr);
				return null;
			}
			$out .= $tmp;
			if($tagstr{$endIndex} == '>') {
				break;
			}
			if(strlen($tmp) == 0) {
				$this->signalError("No white space between closing quote and next attribute name.", $context, $tagstr);
				return null;
			}
			$startIndex = $endIndex;
			$endIndex = $this->nextNonIdentifierChar($tagstr, $startIndex);
			$attName = strtolower(substr(
				$tagstr, 
				$startIndex, 
				($endIndex - $startIndex))
			);
			if($attrTable[$attName]) {
				$this->signalError("Duplicate attribute $attName.", $context, $tagstr);
				return null;
			}
			if($this->attlist[$gi][$attName]) {
				$attrTable[$attName] = 1;
			} else {
				$this->signalError("The element $gi has no attribute called $attName.", $context, $tagstr);
				return null;
			}
			$out .= $attName;
			$startIndex = $endIndex;
			$endIndex = strpos($tagstr, '=', $startIndex);
			if($endIndex === false) {
				$this->signalError("= not found.", $context, $tagstr);
				return;
			}
			$tmp = strtolower(substr(
				$tagstr,
				$startIndex,
				($endIndex - $startIndex))
			);
			if(!$this->isWhiteSpace($tmp)) {
				$this->signalError("Non-whitespace between attribute name and =.", $context, $tagstr);
				return null;
			}
			$out .= $tmp;
			$out .= "=";
			$startIndex = $endIndex + 1;
			$endIndex = strpos($tagstr, '"', $startIndex);
			if($endIndex === false) {
				$this->signalError("&quot; not found.", $context, $tagstr);
				return;
			}
			$tmp = strtolower(substr(
				$tagstr,
				$startIndex,
				($endIndex - $startIndex))
			);
			if(!$this->isWhiteSpace($tmp)) {
				$this->signalError("Non-whitespace between = and &quot;.", $context, $tagstr);
				return null;
			}
			$out .= $tmp;
			$out .= '"';
			$startIndex = $endIndex + 1;
			$endIndex = strpos($tagstr, '"', $startIndex);
			if($endIndex === false) {
				$this->signalError("&quot; not found.", $context, $tagstr);
				return;
			}
			$tmp = substr(
				$tagstr,
				$startIndex,
				($endIndex - $startIndex)
			);
			if(!$this->isGoodPCDATA(
				$tmp,
				($context . substr($tagstr, 0, $startIndex))
			)) {
				return null;
			}
			$out .= $tmp;
			$out .= '"';
			$startIndex = $endIndex + 1;
		}
		return $out . ">";
	}
	
	function signalError($message, $context, $problem) {
		$this->htmlError = 
			"<p class=\"syntax-error-message\"><strong>Error: </strong>"
			. $message . "</p>\n" . "<p class=\"syntax-error-context\"><code>"
			. htmlspecialchars($context) . "<em>" . htmlspecialchars($problem)
			. "</em></code></p>";
	}
	
	function checkHTMLsyntax(&$input, $rootModel) {
		$output = "";
		$index = $this->nextNonWhiteSpace($input, 0);
		$temp = 0;
		$tempstr = "";
		
		$stack = array($rootModel);
		
		$anchorInStack = false;
	
		while($index < strlen($input)) {
			if($input{$index} == '<') {
				$temp = strpos($input, '>', $index);
				if($temp === false) {
					$this->signalError("Tag without &gt;!", $output, "");
					return null;
				}
				$temp++;
				$tempstr = substr($input, $index, ($temp - $index));
				if($input{$index + 1} == '/') {
					$nonGiIndex = $this->nextNonIdentifierChar($tempstr, 2);
					$gi = strtolower(substr($tempstr, 2, ($nonGiIndex - 2)));
		
					/* Check that there is only white space between the end of the GI and the > */
					if(!$this->isWhiteSpace(substr($tempstr, $nonGiIndex, (strlen($tempstr - 1) - $nonGiIndex)))) {
						$this->signalError("Bogus chars in closing tag after GI!", $output, $tempstr);
						return null;
					}
					
					if($gi == array_pop($stack)) {
						$output .= strtolower($tempstr);
						if($gi == 'a') {
							$anchorInStack = false;
						}
					} else {
						$this->signalError("Mismatched closing tag ($gi)!", $output, $tempstr);
						return null;
					}			
				} else {
					$nonGiIndex = $this->nextNonIdentifierChar($tempstr, 1);
					$gi = strtolower(substr($tempstr, 1, ($nonGiIndex - 1)));
					
					if(($tempstr = $this->checkAttr($tempstr, $gi, $output)) == null) {
						return null;
					} 
						
					/* nesting check */
					if($gi == 'a' && $anchorInStack) {
						$this->signalError("Can't nest anchors!", $output, $tempstr);
						return null;
					}
					if($this->dtd[$stack[count($stack) - 1]][$gi]) {
						$output .= $tempstr;
						if(!($this->empty[$gi])) {
							array_push($stack, $gi);
							if($gi == 'a') {
								$anchorInStack = true;
							}
						}
					} else {
						$this->signalError("Bad element nesting! (Possible cause: Attempt to put block content where only inline content is allowed or vice versa. Check for missing paragraph opening and closing tags.)", $output, $tempstr);
						return null;
					}
				}
			} else {
				$temp = strpos($input, '<', $index);
				if($temp === false) {
					$temp = strlen($input);
				}
				$tempstr = substr($input, $index, ($temp - $index));
				if($this->isWhiteSpace($tempstr)) {
					$output .= $tempstr;
				} else {
					if($this->dtd[$stack[count($stack) - 1]]['PCDATA']) {
						if(!$this->isGoodPCDATA($tempstr, $output)) {
							return null;
						}
						$output .= $tempstr;
					} else {
						$this->signalError("Can't put char data here! Possible reason: missing opening tag.", $output, $tempstr);
						return null;
					}
				}
			}
			$index = $temp;
		}
		
		if(array_pop($stack) == $rootModel) {
			return $output;
		} else {
			$this->signalError("Unclosed elements left in stack but input ended.", $output, "");
			return null;
		}
		
	}
}

?>


