<?php


namespace App\Gateways;


use PDOException;

class GenreGateway extends Gateway {

    public function __construct($db) {
        $this->db = $db;
        $this->tableName = "genres";
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO $this->tableName 
                (label)
            VALUES
                (:label);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'label' => $input['label'],
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE $this->tableName
            SET 
                label = :label
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'label' => $input['label']
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}