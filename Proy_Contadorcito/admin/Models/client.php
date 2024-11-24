<?php
require_once(__DIR__ . '/../../conf/conf.php');

class Client extends Conf {
    public $id;
    public $firstName;
    public $lastName;
    public $address;
    public $email;
    public $phone;

    public function create() {
        $query = "INSERT INTO clients (firstName, lastName, address, email, phone) VALUES (:firstName, :lastName, :address, :email, :phone)";
        $params = [
            ':firstName' => $this->firstName,
            ':lastName' => $this->lastName,
            ':address' => $this->address,
            ':email' => $this->email,
            ':phone' => $this->phone
        ];

        return $this->exec_query($query, $params);
    }

    public function get_client_by_id($id) {
        $query = "SELECT id, firstName, lastName, address, email, phone FROM clients WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_clients() {
        $query = "SELECT id, firstName, lastName, address, email, phone FROM clients";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id) {
        $query = "UPDATE clients SET
            firstName = :firstName,
            lastName = :lastName,
            address = :address,
            email = :email,
            phone = :phone 
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':firstName' => $this->firstName,
            ':lastName' => $this->lastName,
            ':address' => $this->address,
            ':email' => $this->email,
            ':phone' => $this->phone
        ];

        return $this->exec_query($query, $params);
    }

    public function delete($id) {
        $query = "DELETE FROM clients WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query, $params);
    }

    public function checkClient($email, $id = null) {
        $query = "SELECT COUNT(*) as total FROM clients WHERE email = :email";
        $params = [':email' => $email];

        if ($id) {
            $query .= " AND id != :id";
            $params[':id'] = $id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}