<?php

class tender {
	function add($params) {

		$answer = "";

		if (empty($_POST["code"]) or empty($_POST["number"]) or empty($_POST["name"]) or empty($_POST["status"]) or empty($_POST["date"])) {
			$answer = '{"response":"Data error"}';
		} else {
			$code = addslashes(strip_tags($_POST["code"]));
			$number = addslashes(strip_tags($_POST["number"]));
			$name = addslashes(strip_tags($_POST["name"]));
			$status = addslashes(strip_tags($_POST["status"]));
			$date = addslashes(strip_tags($_POST["date"]));

			$database = new database();
			$db = $database->opendb();

			if (!$db) {
				$answer = '{"response":"DB error"}';
			} else {
				$query = mysqli_query($db, "SELECT code FROM test_task_data WHERE code = $code");
				$ans = mysqli_fetch_array($query);

				if (!empty($ans["code"])) {
					$answer = '{"response":"Row is exist"}';
				} else {
					$query = mysqli_query($db,"INSERT into test_task_data (code,number,name,status,d) VALUES ($code,'$number','$name','$status','$date')");
					if ($query) {
						$answer = '{"response":"Success"}';
					} else {
						$answer = '{"response":"DB error"}';
					}
				}
			}

		}

		return $answer;

}

	function get($params) {

		$answer = "";
		$num = "0,100";

		foreach ($params as $key => $value) {

			$value = strip_tags($value);
			$value = addslashes($value);

			switch ($key) {
			case "code":
				$code = $value;
				break;
			case "name":
				$name = $value;
				break;
			case "date":
				$name = $value;
				break;
			case "num":
				$num = $value;
				break;
			}
		}

		$database = new database();
		$db = $database->opendb();

		if (!$db) {
			$answer = '{"response":"DB error"}';
		} else {

			if (!empty($code)) {
				$query = "SELECT * FROM test_task_data WHERE code = $code LIMIT 1";
			} else {
				$query = "SELECT * FROM test_task_data";
				if (!empty($date)) {
					$query = $query." WHERE d = '$date'";
					if (!empty($name)) {
						$query = $query." or name = '$name'";
					}
				} else {
					if (!empty($name)) {
						$query = $query." WHERE name = '$name'";
					}
				}
				$query = $query." LIMIT $num";
			}

			$query = mysqli_query($db, $query);
			$arr = [];
			if ($query) {
				while ($ans = mysqli_fetch_array($query)) {
					$arr[] = $ans;
				} 
				$answer = '{"response":'.json_encode($arr).'}';
			} else {
				$answer = '{"response":"Data error"}';
			}
		}

		return $answer;
	}
}

?>