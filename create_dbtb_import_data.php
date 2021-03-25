<?php
	require_once('connect_db.php');

	$json = file_get_contents('categories.json');
	$content = json_decode($json, true);

	$array = getOrderData($content);

	function getOrderData($datas, $parentid = 0) // Преобразовать дерево-массив в массив
	{
	    $array = [];
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
	    return $array;
	}

	$query = "CREATE DATABASE IF NOT EXISTS $db";
	if ($result = mysqli_query($link, $query)) {
		echo "База данных успешно создана!";
	} else {
		exit("Ошибка создания, $db");
	}

	mysqli_select_db($link, $db);

	$query_tbl = "CREATE TABLE IF NOT EXISTS `categories` ( `id` INT UNSIGNED NOT NULL AUTO_INCREMENT , `name` VARCHAR(50) NOT NULL , `alias` VARCHAR(50) NOT NULL , `childrens` INT NOT NULL DEFAULT '0' , PRIMARY KEY (`id`)) ENGINE = InnoDB CHARSET=utf8";

	if ($result_crttbl = mysqli_query($link, $query_tbl)) {
		echo "Таблица успешно создана!";
	} else {
		exit("Ошибка создания Таблицы!");
	}

	foreach ($array as $value) {
		$id = $value['id'];
		$name = $value['name'];
		$alias = $value['alias'];
		$childrens = $value['childrens'];
		$query_add = "INSERT INTO `categories` (`id`, `name`, `alias`, `childrens`) VALUES ('$id', '$name', '$alias', '$childrens')";
		if (!empty($result_add = mysqli_query($link, $query_add))) {
			echo "Данные добавлены!";
		} else {
			exit("Ошибка добавления данных в Таблицу Данные УЖЕ СУЩЕСТВУЮТ!");
		}	
	}
	mysqli_close($link);
	
?>