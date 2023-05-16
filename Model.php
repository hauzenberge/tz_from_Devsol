<?php

class Model
{
    private $db;
    private $table;

    public function __construct($table)
    {
        $this->table = $table;

        $host = "localhost";
        $dbname = "test";
        $username = "root";
        $password = "";
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8mb4";
        $options = [
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ];
        $this->db = new PDO($dsn, $username, $password, $options);
    }

    public function create($data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');
      
        $columns = implode(", ", array_keys($data));
        $values = implode(", :", array_keys($data));
        $sql = "INSERT INTO $this->table ($columns) VALUES (:$values)";

      
        $stmt = $this->db->prepare($sql);
        $stmt->execute($data);
    }

    public function update($id, $data)
    {
        $data['created_at'] = date('Y-m-d H:i:s');
        $data['updated_at'] = date('Y-m-d H:i:s');

        $set = [];
        foreach ($data as $key => $value) {
            $set[] = "$key = :$key";
        }
        $set = implode(", ", $set);
        $sql = "UPDATE $this->table SET $set WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute($data);
    }

    public function delete($id)
    {
   
        $sql = "DELETE FROM $this->table WHERE id = :id";

        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(":id", $id, PDO::PARAM_INT);
        $stmt->execute();
    }

    public function getAll()
    {
        $sql = "SELECT * FROM $this->table";

        $stmt = $this->db->query($sql);
        return $stmt->fetchAll();
    }

    public function getHistory($id)
    {
        $query = "SELECT * FROM item_history WHERE item_id = :id";
        $stmt = $this->conn->prepare($query);
        $stmt->bindParam(':id', $id);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    protected function getLastInsertedId() {
        return $this->db->lastInsertId();
      }
}
?>