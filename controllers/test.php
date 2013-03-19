<?php
	class test extends mfw {
		function db($q){
			$query = $this->db->query(urldecode($q))->assoc();
			var_dump($query);
		}
	}
