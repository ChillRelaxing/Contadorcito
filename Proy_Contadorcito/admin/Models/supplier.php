<?php
require_once(__DIR__ . '/../../conf/conf.php');


class Supplier extends Conf {
    public $supplier_id;
    public $supplier_name;
    public $email;
    public $phone;
    public $address;
    public $city;

    public function create(){
        $query = "INSERT INTO suppliers (supplierName, email, phone, address, city) VALUES (:supplierName, :email, :phone, :address, :city)";
        $params = [
            ':supplierName' => $this->supplier_name,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':address' => $this->address,
            ':city' => $this->city
        ];

        return $this->exec_query($query, $params);
    }

    public function get_supplier_by_id($supplier_id){
        $query = "SELECT supplier_id, supplierName, email, phone, address, city FROM suppliers WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_suppliers(){
        $query = "SELECT supplier_id, supplierName, email, phone, address, city, created_at, updated_at FROM suppliers";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($supplier_id){
        $query = "UPDATE suppliers SET
            supplierName = :supplierName,
            email = :email,
            phone = :phone,
            address = :address,
            city = :city
            WHERE supplier_id = :supplier_id";

        $params = [
            ':supplier_id' => $supplier_id,
            ':supplierName' => $this->supplier_name,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':address' => $this->address,
            ':city' => $this->city
        ];

        return $this->exec_query($query,$params);
    }

    public function delete($supplier_id){
        $query = "DELETE FROM suppliers WHERE supplier_id = :supplier_id";
        $params = [':supplier_id' => $supplier_id];

        return $this->exec_query($query,$params);
    }

    public function checkSupplier($supplier_id = null){
        $query = "SELECT COUNT(*) as total FROM suppliers WHERE supplierName = :supplierName";
        $params = [':supplierName' => $this->supplier_name];

        if ($supplier_id){
            $query.= " AND supplier_id != :supplier_id";
            $params[':supplier_id'] = $supplier_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }

    }
}