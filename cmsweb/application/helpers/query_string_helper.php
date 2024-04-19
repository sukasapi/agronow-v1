<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
function update_query_string($key=NULL, $value=NULL) 
{	
	$key   = strtolower($key);
	$value = strtolower($value);

	$host 		 = $_SERVER['HTTP_HOST'];	// localhost
	$self 		 = $_SERVER['PHP_SELF'];	// /spkoa/index.php/eko/tempat
	$request_uri = $_SERVER['REQUEST_URI'];	// /spkoa/index.php/eko/tempat?q=a&a=3
	

    $parsed_uri = parse_url($request_uri);

    //split query string
    if (isset($parsed_uri['query'])) {
    	/*$query = explode('&',$parsed_uri['query']);
	    $parsedQuery = array();
	    foreach ( $query as $q ) {
	        list($k,$v) = explode('=',$q);
	        $parsedQuery[$k] = $v;
	    }*/
	    parse_str($parsed_uri['query'], $parsedQuery);

	    $parsed_uri['query'] = $parsedQuery;

	    if (array_key_exists($key, $parsed_uri['query'])) {
	    	foreach ($parsed_uri['query'] as $k => $v) {
		    	$k 	   = strtolower($k);
		    	$v 	   = strtolower($v);
		    	if ($key===$k) {
		    		$parsed_uri['query'][$k] = $value;
		    	}
		    }
	    } else {
	    	$parsed_uri['query'][$key] = $value;
	    }
	    
    } else {
    	$parsed_uri['query'][$key] = $value;
    }
    
    $request_uri = '?'.http_build_query($parsed_uri['query']);
    $full_path = $host.$self.'?'.$request_uri;
    
    return $request_uri;
	
    // print_r($request_uri);
}




function search_hidden_query_string() 
{	

	$request_uri = $_SERVER['REQUEST_URI'];	// /spkoa/index.php/eko/tempat?q=a&a=3
	

    $parsed_uri = parse_url($request_uri);

    //split query string
    if (isset($parsed_uri['query'])) {
    	/*$query = explode('&',$parsed_uri['query']);
	    $parsedQuery = array();
	    foreach ( $query as $q ) {
	        list($k,$v) = explode('=',$q);
	        $parsedQuery[$k] = $v;
	    }*/
	    parse_str($parsed_uri['query'], $parsedQuery);
	    
	    $parsed_uri['query'] = $parsedQuery;
    } else {
    	$parsed_uri['query']=NULL;
    }
    
    // print_r($parsed_uri['query']);
	$new_parsed_uri = $parsed_uri['query'];

	if (isset($new_parsed_uri['q'])&&isset($new_parsed_uri['keyword_by'])) {
		unset($new_parsed_uri['q']);
		unset($new_parsed_uri['keyword_by']);
	}

	if (isset($new_parsed_uri['month'])&&isset($new_parsed_uri['year'])) {
		unset($new_parsed_uri['month']);
		unset($new_parsed_uri['year']);
	}

	if (!empty($new_parsed_uri)) {
		foreach ($new_parsed_uri as $k => $v) {
			echo "<input type='hidden' name='".$k."' value='".$v."' />";
		}
	}
}
?>