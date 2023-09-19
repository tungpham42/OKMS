<?php
class Database {
    private $db_host;
    private $db_user;
    private $db_pass;
    private $db_database;

    public $db_prefix;
    public $link;

    public function __construct($host, $user, $pass, $database, $prefix) {
        $this->db_host = $host;
        $this->db_user = $user;
        $this->db_pass = $pass;
        $this->db_database = $database;
        $this->db_prefix = $prefix;
    }

    public function connect() {
        try {
            $this->link = new PDO("mysql:host={$this->db_host};dbname={$this->db_database}", $this->db_user, $this->db_pass);
            $this->link->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $this->link->exec("SET names UTF8");
        } catch (PDOException $e) {
            die("Unable to establish a DB connection: " . $e->getMessage());
        }
    }

    public function fetchRows($stmt) {
        $array = array();
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            $array[] = $row;
        }
        return $array;
    }

    public function array_load_all($table_name) {
        $stmt = $this->link->query("SELECT * FROM {$this->db_prefix}{$table_name}");
        return $this->fetchRows($stmt);
    }

    public function array_load_with_operator($table_name, $identifier, $value, $operator) {
        $stmt = $this->link->prepare("SELECT * FROM {$this->db_prefix}{$table_name} WHERE {$identifier}{$operator} :value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $this->fetchRows($stmt);
    }

    public function array_load_with_two_identifier($table_name, $identifier1, $value1, $identifier2, $value2) {
        $stmt = $this->link->prepare("SELECT * FROM {$this->db_prefix}{$table_name} WHERE {$identifier1}=:value1 AND {$identifier2}=:value2");
        $stmt->bindParam(':value1', $value1);
        $stmt->bindParam(':value2', $value2);
        $stmt->execute();
        return $this->fetchRows($stmt);
    }

    public function array_load_with_two_values($table_name, $identifier, $value1, $value2) {
        $stmt = $this->link->prepare("SELECT * FROM {$this->db_prefix}{$table_name} WHERE {$identifier}=:value1 OR {$identifier}=:value2");
        $stmt->bindParam(':value1', $value1);
        $stmt->bindParam(':value2', $value2);
        $stmt->execute();
        return $this->fetchRows($stmt);
    }

    public function array_load($table_name, $identifier, $value) {
        return $this->array_load_with_operator($table_name, $identifier, $value, '=');
    }

    public function array_single_load($table_name, $identifier, $value) {
        $stmt = $this->link->prepare("SELECT * FROM {$this->db_prefix}{$table_name} WHERE {$identifier}=:value");
        $stmt->bindParam(':value', $value);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
    
    public function array_single_load_with_two_identifier($table_name, $identifier1, $value1, $identifier2, $value2) {
        $stmt = $this->link->prepare("SELECT * FROM {$this->db_prefix}{$table_name} WHERE {$identifier1}=:value1 AND {$identifier2}=:value2");
        $stmt->bindParam(':value1', $value1);
        $stmt->bindParam(':value2', $value2);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function insert_record($array = array(), $table_name) {
        $keys = implode(', ', array_keys($array));
        $values = implode(', ', array_fill(0, count($array), '?'));
        $query = "INSERT INTO {$this->db_prefix}{$table_name} ({$keys}) VALUES ({$values})";
        $stmt = $this->link->prepare($query);
        $stmt->execute(array_values($array));
    }

    public function update_record_with_operator($array = array(), $identifier, $value, $table_name, $operator) {
		$sets = array();
		$params = array(); // To store the parameters for binding
		$i = 1; // Counter for parameter binding
		
		foreach ($array as $key => $val) {
			$sets[] = "{$key}=?";
			$params[] = $val; // Add the parameter value to the array
			$i++; // Increment the counter
		}
	
		$params[] = $value; // Add the WHERE condition parameter
	
		$sets = implode(', ', $sets);
		$query = "UPDATE {$this->db_prefix}{$table_name} SET {$sets} WHERE {$identifier}{$operator}?";
		$stmt = $this->link->prepare($query);
	
		// Bind parameters using a loop
		foreach ($params as $key => $param) {
			$stmt->bindParam($key + 1, $params[$key]); // Bind parameters
		}
	
		$stmt->execute();	
    }

    public function update_record($array = array(), $identifier, $value, $table_name) {
        $this->update_record_with_operator($array, $identifier, $value, $table_name, '=');
    }

    public function delete_record($identifier, $value, $table_name) {
        $query = "DELETE FROM {$this->db_prefix}{$table_name} WHERE {$identifier}=?";
        $stmt = $this->link->prepare($query);
        $stmt->bindParam(1, $value);
        $stmt->execute();
    }

    public function delete_record_with_two_identifier($identifier1, $value1, $identifier2, $value2, $table_name) {
        $query = "DELETE FROM {$this->db_prefix}{$table_name} WHERE {$identifier1}=? AND {$identifier2}=?";
        $stmt = $this->link->prepare($query);
        $stmt->bindParam(1, $value1);
        $stmt->bindParam(2, $value2);
        $stmt->execute();
    }
}