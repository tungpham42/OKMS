<?php
class Database {
	var $db_host;
	var $db_user;
	var $db_pass;
	var $db_database;
	var $db_prefix;
	var $link;
	public function __construct($host,$user,$pass,$database,$prefix) {
		$this->db_host = $host;
		$this->db_user = $user;
		$this->db_pass = $pass;
		$this->db_database = $database;
		$this->db_prefix = $prefix;
	}
	public function connect() {
		$this->link = mysqli_connect($this->db_host,$this->db_user,$this->db_pass) or die('Unable to establish a DB connection');
		mysqli_select_db($this->link, $this->db_database);
		mysqli_query($this->link, "SET names UTF8");
	}
	public function array_load_all($table_name) { //Put all table records into an array
		$array = array();
		$result = mysqli_query($this->link, "SELECT * FROM ".$this->db_prefix.$table_name);
		if ($result) {
			while($row = mysqli_fetch_assoc($result)) {
				$array[] = $row;
			}
		}
		return $array;
	}
	public function array_load_with_operator($table_name,$identifier,$value,$operator) { //Put specific table records according to condition,  > < = into an array
		$array = array();
		$result = mysqli_query($this->link, "SELECT * FROM ".$this->db_prefix.$table_name." WHERE ".$identifier.$operator."'".$value."'");
		if ($result) {
			while($row = mysqli_fetch_assoc($result)) {
				$array[] = $row;
			}
		}
		return $array;
	}
	public function array_load_with_two_identifier($table_name,$identifier1,$value1,$identifier2,$value2) { //Load array from database with 2 identifiers
		$array = array();
		$result = mysqli_query($this->link, "SELECT * FROM ".$this->db_prefix.$table_name." WHERE ".$identifier1."='".$value1."' AND ".$identifier2."='".$value2."'");
		if ($result) {
			while($row = mysqli_fetch_assoc($result)) {
				$array[] = $row;
			}
		}
		return $array;
	}
	public function array_load_with_two_values($table_name,$identifier,$value1,$value2) { //Load array from database with 1 identifier and 2 values
		$array = array();
		$result = mysqli_query($this->link, "SELECT * FROM ".$this->db_prefix.$table_name." WHERE ".$identifier."='".$value1."' OR ".$identifier."='".$value2."'");
		if ($result) {
			while($row = mysqli_fetch_assoc($result)) {
				$array[] = $row;
			}
		}
		return $array;
	}
	public function array_load($table_name,$identifier,$value) { //Load array from database with 1 identifier and 1 value
		$array = $this->array_load_with_operator($table_name,$identifier,$value,'=');
		return $array;
	}
	public function insert_record($array = array(), $table_name) { //Insert table record
		$keys = array_keys($array);
		$values = array_values($array);
		$query = "";
		$query .= "INSERT INTO ".$this->db_prefix.$table_name."(";
		for ($k = 0; $k < count($keys); $k++) {
			$query .= $keys[$k].(($k < (count($keys) - 1)) ? ",": "");
		}
		$query .= ") VALUES(";
		for ($i = 0; $i < count($values); $i++) {
			$query .= "'".$values[$i]."'".(($i < (count($values) - 1)) ? ",": "");
		}
		$query .= ")";
		mysqli_query($this->link, $query);
	}
	public function update_record_with_operator($array = array(), $identifier, $value, $table_name, $operator) { //Update table record
		$keys = array_keys($array);
		$values = array_values($array);
		$query = "";
		$query .= "UPDATE ".$this->db_prefix.$table_name." SET ";
		for ($i = 0; $i < count($array); $i++) {
			$query .= $keys[$i]."='".$values[$i]."'".(($i < (count($array) - 1)) ? ",": "");
		}
		$query .= " WHERE ".$identifier.$operator."'".$value."'";
		mysqli_query($this->link, $query);
	}
	public function update_record($array = array(), $identifier, $value, $table_name) { //Update table record
		$this->update_record_with_operator($array, $identifier, $value, $table_name, '=');
	}
	public function delete_record($identifier, $value, $table_name) { //Delete table records with 1 identifier
		mysqli_query($this->link, "DELETE FROM ".$this->db_prefix.$table_name." WHERE ".$identifier."='".$value."'");
	}
	public function delete_record_with_two_identifier($identifier1, $value1, $identifier2, $value2, $table_name) { //Delete table records with 2 identifiers
		mysqli_query($this->link, "DELETE FROM ".$this->db_prefix.$table_name." WHERE ".$identifier1."='".$value1."' AND ".$identifier2."='".$value2."'");
	}
}