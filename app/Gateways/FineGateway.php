<?php


namespace App\Gateways;


use PDOException;

class FineGateway extends Gateway {
    public function __construct($db) {
        $this->db = $db;
        $this->tableName = "fines";
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO $this->tableName 
                (term_days_count, amount)
            VALUES
                (:term_days_count, :amount);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'amount' => $input['amount'],
                'term_days_count' => $input['termDaysCount'],
            ));

            $lastAddedQuery = "
                SELECT * FROM $this->tableName
                ORDER BY id DESC
                LIMIT 1
            ";
            $lastAddedQuery = $this->db->query($lastAddedQuery);

            return $lastAddedQuery->fetchAll(\PDO::FETCH_ASSOC);
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE $this->tableName
            SET 
                term_days_count = :term_days_count,
                amount = :amount
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'amount' => $input['amount'],
                'term_days_count' => $input['termDaysCount'],
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}