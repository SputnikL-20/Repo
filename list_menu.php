<?php

	function get_cat() 
	{
		require_once('connect_db.php');
		mysqli_select_db($link, $db);

		$sql = "SELECT `id`, `name`, `alias`, `childrens` FROM `categories`";
		$result = mysqli_query($link, $sql);
		if (!$result) {
			return NULL;
		}
		$arr_cat = array();
		if (mysqli_num_rows($result) !=0) {

			for ($i=0; $i < mysqli_num_rows($result); $i++) {
				$row = mysqli_fetch_array($result, MYSQLI_ASSOC);
				if (empty($arr_cat[$row['childrens']])) {
					$arr_cat[$row['childrens']] = array();
				}
				$arr_cat[$row['childrens']][] = $row;
			}
		}
		return $arr_cat;
		mysqli_free_result($result);
		mysqli_close($link);
	}
		
	$result = get_cat();

	view_cat($result);

	function view_cat($arr, $childrens_id = 0) 	// Вывод каталога с помощью рекурсии
	{
		if(empty($arr[$childrens_id])) {  // Условия выхода из рекурсии
		 return;
		}
		echo '<ul>';
		for($i = 0; $i < count($arr[$childrens_id]);$i++) {  // Перебираем в цикле массив и выводим на экран
		echo '<li><a href="?categories_id='.$arr[$childrens_id][$i]['id'].'&childrens_id='.$childrens_id.'">'.$arr[$childrens_id][$i]['name'].'</a>';
		view_cat($arr,$arr[$childrens_id][$i]['id']);  // Рекурсия - проверяем нет ли дочерних категорий
		 echo '</li>';
		}
		 echo '</ul>';
	}

?>