<?php

class TemplatesComponent extends Object {
    
    private $controller = false;
    private $settings = array();
    
    function initialize(&$controller, $settings = array()) {
        $this->controller =& $controller;
        $this->settings = $settings;
    }
    
    function renderTemplate($page) {
        
        $tmpl_idx = $page['Page']['template'];
        
        $tmpls = $this->controller->Page->getPageTemplates();
        
        $m = $this->controller->Mustache;
        
	//$page['Page']['meta-description'] = 'HEY HOW!';
	
	
	
	$vv = $this->controller->viewVars;
	$View = ClassRegistry::init('View');
	$View->viewVars = $vv;
	
	
	$tmpl_params = array(
	  'Page' => $page['Page'],
	  'Site' => array(
	    'ga-account' => $this->controller->Setting->getSetting('ga-account')
	  ),
	  'ViewVars' => $vv
	);
	
        $tmpl = file_get_contents(ROOT . '/page-templates/' . $tmpls[$tmpl_idx]);
	
	$comment_open_rgx = '(\/\/|\/\*|<!--)';
	$comment_close_rgx = '(\/\/|\*\/|-->)?';
	
	$rgx = '/' . $comment_open_rgx . '\s*HEADER:(?<name>[^:]+):\s*"?(?<value>.+?)"?$' . $comment_close_rgx . '/';
	
	$tmpl_lines = split("\n", $tmpl);
	$end_of_headers = false;
	$tmpl_ar = array();
	
	
	foreach ($tmpl_lines as $idx => $line) {
	    if ($end_of_headers) {
		$tmpl_ar[] = $line;
	    } else {
		if (preg_match($rgx, $line, $matches)) {
		    $this->controller->header($matches['name'] . ': ' . $matches['value']);
		} else {
		    $end_of_headers = true;
		    $tmpl_ar[] = $line;
		}
	    }
	}
	
	//pr($tmpl_params); exit(0);
	
	
	$tmpl = join("\n", $tmpl_ar);
	
	$output = $m->render($tmpl, $tmpl_params);
	
	if (isset($vv['_EMBEDS'])) {
	  foreach ($vv['_EMBEDS'] as $e) {
	    $embed = 'embeds/emb-' . $e['type'];
	    if (file_exists(ROOT . '/app/views/elements/' . $embed . '.ctp')) {
	      $embed_markup = $View->element($embed, array('params'=>$e['params'],'request'=>$vv['_PARAMS'],'page'=>&$page));
	      //print "***** $embed_markup *****\n";
	      $output = str_replace($e['key'], $embed_markup, $output);
	    } else {
	      $output = str_replace($e['key'], "<!-- Embed failed for type: \"{$e['type']}\".  Unknown embed type. -->\n", $output);
	    }
	  }
	}
	
	print $output;
        exit(0);
        
    }
}

?>