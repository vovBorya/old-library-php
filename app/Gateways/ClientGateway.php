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
            INSERT INTO person 
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
            UPDATE person
            SET 
                firstname = :firstname,
                lastname  = :lastname,
                firstparent_id = :firstparent_id,
                secondparent_id = :secondparent_id
            WHERE id = :id;
        ";

        try {
            $statement = $this->db->prepare($statement);
            $statement->execute(array(
                'id' => (int) $id,
                'firstname' => $input['firstname'],
                'lastname'  => $input['lastname'],
                'firstparent_id' => $input['firstparent_id'] ?? null,
                'secondparent_id' => $input['secondparent_id'] ?? null,
            ));
            return $statement->rowCount();
        } catch (PDOException $e) {
            exit($e->getMessage());
        }
    }
}