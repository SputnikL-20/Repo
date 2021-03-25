<?php
	header('Content-Type: text/html; charset=utf-8');
	require_once('connect_db.php');
	mysqli_select_db($link, $db);

	$sql_select = "SELECT * FROM `categories`";
	$query = mysqli_query($link, $sql_select);
	$var_damp = [];
	while ($result = mysqli_fetch_array($query)) {
		$var_damp[] = $result;
	}

	mysqli_free_result($query);
	mysqli_close($link);

	$tree = buildTreeArray($var_damp);

	function buildTreeArray($arItems, $section_id = 'childrens', $element_id = 'id') // Формирование древовидного массива
	{
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

	function getOrderData($datas, $parentid = 0) // Преобразование и сортировка массива
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

	exportTxt($array);

	function exportTxt($array)
	{
		foreach ($array as $value) {
			if ($value['childrens'] == 0) {
				$str = $value['name']." /".$value['alias'];
				$one = " /".$value['alias']."/";
				textWrite($str);
			}
			if ($value['childrens'] == 1) {
				$str = "\t".$value['name'].$one.$value['alias'];
				$two = $value['alias']."/";
				textWrite($str);
			}
			if ($value['childrens'] == 3 ) {
				$str = "\t\t".$value['name'].$one.$two.$value['alias'];
				textWrite($str);
			}
			if ($value['childrens'] == 6) {
				$str = "\t".$value['name'].$one.$value['alias'];
				textWrite($str);
			}
			if ($value['childrens'] == 7 && $value['id'] == 12) {
				$two = $value['alias']."/";
				$str = "\t".$value['name'].$one.$value['alias'];
				textWrite($str);
			} elseif ($value['childrens'] == 7) {
				$str = "\t".$value['name'].$one.$value['alias'];
				textWrite($str);				
			}
			if ($value['childrens'] == 12) {		 
				$str = "\t\t".$value['name'].$one.$two.$value['alias'];
				textWrite($str);
			}
			if ($value['id'] == 14 && $value['childrens'] == 7) {
				$two = $value['alias']."/";
			} elseif ($value['childrens'] == 14) {
				$str = "\t\t".$value['name'].$one.$two.$value['alias'];
				textWrite($str);				
			}
		}			
	}
		

	function textWrite($string) 
	{
		$file = 'type_a.txt'; 
		$f_hd1 = fopen($file, 'a'); 
		fwrite($f_hd1, "$string\r\n");
		fclose($f_hd1);
	}

?>