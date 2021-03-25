<?php
	header('Content-Type: text/html; charset=utf-8');
	require_once('connect_db.php');
	mysqli_select_db($link, $db);

	$sql_select = "SELECT * FROM `categories`";
	$query = mysqli_query($link, $sql_select);
	//$var_damp = [];
	while ($result = mysqli_fetch_assoc($query)) {
		$var_damp[] = $result;
	}

	mysqli_free_result($query);
	mysqli_close($link);

	$tree = buildTreeArray($var_damp);

	function buildTreeArray($arItems, $section_id = 'childrens', $element_id = 'id') {
	    $childs = array();
	    if(!is_array($arItems) || empty($arItems)) {
	        return array();
	    }
	    foreach($arItems as &$item) {
	        if(!$item[$section_id]) {
	            $item[$section_id] = 0;
	        }
	        $childs[$item[$section_id]][] = &$item;
	    }
	    unset($item);
	    foreach($arItems as &$item) {
	        if (isset($childs[$item[$element_id]])) {
	            $item['childrens'] = $childs[$item[$element_id]];
	        }
	    }
	    return $childs[0];
	}

	$array = getOrderData($tree);

	function getOrderData($datas, $parentid = 0)
	{
	    $array = [];
	    if (is_array($datas)) {
		    foreach ($datas as $val) {
		        $indata = array("id" => $val["id"], "name" => $val["name"], "alias" => $val["alias"], "childrens" => $parentid);
		        $array[] = $indata;
		        if (isset($val["childrens"])) {
		            $children = getOrderData($val["childrens"], $val["id"]);
		            if ($children) {
		                $array = array_merge($array, $children);
		            }
		        }
		    }
	    }
	    return $array;
	}

	exportText($array);

	function exportText($arr)
	{
		foreach ($arr as $val) {
			if ($val['childrens'] == 0) {
				$str = $val['name'];
				textWrite($str);
			}
			if ($val['childrens'] == 1) {
				$str = "\t".$val['name'];
				textWrite($str);
			}
			if ($val['childrens'] == 6) {
				$str = "\t".$val['name'];
				textWrite($str);
			}
			if ($val['childrens'] == 7) {
				$str = "\t".$val['name'];
				textWrite($str);
			}
		}	
	}


	function textWrite($string)
	{
		$file = 'type_b.txt';
		$f_hd = fopen($file, 'a');
		fwrite($f_hd, "$string\r\n");
		fclose($f_hd);
	}

?>