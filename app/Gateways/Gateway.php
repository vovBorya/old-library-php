<?php


namespace App\Gateways;

use PDO;
use PDOException;

class Gateway
{
    protected $db = null;
    protected $tableName = null;

    public function find($id) {
        $statement = "SELECT * FROM $this->tableName WHERE id = :id";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function findAll()
    {
        $statement = "SELECT * FROM $this->tableName";

        try {
            $statement = $this->db->query($statement);
            return $statement->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function delete($id)
    {
        $statement = "
            DELETE FROM $this->tableName
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array('id' => $id));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}
