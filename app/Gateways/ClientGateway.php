<?php


namespace App\Gateways;

use PDOException;

class ClientGateway extends Gateway {

    public function __construct($db) {
        $this->db = $db;
        $this->tableName = "clients";
    }

    public function insert(Array $input)
    {
        $statement = "
            INSERT INTO $this->tableName 
                (last_name, first_name, birth_day, address, phone_number, email)
            VALUES
                (:last_name, :first_name, :birth_day, :address, :phone_number, :email);
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'last_name' => $input['lastName'],
                'first_name'  => $input['firstName'],
                'birth_day' => $input['birthDay'] ?? null,
                'address' => $input['address'] ?? null,
                'phone_number' => $input['phoneNumber'] ?? null,
                'email' => $input['email'] ?? null,
            ));
            return $statement->rowCount();
        } catch (\PDOException $e) {
            exit($e->getMessage());
        }
    }

    public function update($id, Array $input)
    {
        $statement = "
            UPDATE $this->tableName SET 
                last_name = :last_name,
                first_name  = :first_name,
                birth_day = :birth_day,
                address = :address,
                phone_number = :phone_number,
                email = :email
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'last_name' => $input['lastName'],
                'first_name'  => $input['firstName'],
                'birth_day' => $input['birthDay'] ?? null,
                'address' => $input['address'] ?? null,
                'phone_number' => $input['phoneNumber'] ?? null,
                'email' => $input['email'] ?? null,
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}