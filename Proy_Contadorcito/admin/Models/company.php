<?php
require_once(__DIR__ . '/../../conf/conf.php');

class Company extends Conf {
    public $id;
    public $company_name;
    public $company_type;
    public $address;
    public $phone;
    public $email;
    public $representative;

    // Método para crear una nueva compañía
    public function create() {
        $query = "INSERT INTO company (company_name, company_type, address, phone, email, representative) 
                  VALUES (:company_name, :company_type, :address, :phone, :email, :representative)";
        $params = [
            ':company_name' => $this->company_name,
            ':company_type' => $this->company_type,
            ':address' => $this->address,
            ':phone' => $this->phone,
            ':email' => $this->email,
            ':representative' => $this->representative
        ];

        return $this->exec_query($query, $params);
    }

    // Obtener una compañía por ID
    public function get_company_by_id($id) {
        $query = "SELECT company_id, company_name, company_type, address, phone, email, representative 
                  FROM company 
                  WHERE company_id = :company_id";
        $params = [':company_id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todas las compañías
    public function list_companies() {
        $query = "SELECT company_id, company_name, company_type, address, phone, email, representative FROM company";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Actualizar información de una compañía
    public function update($id) {
        $query = "UPDATE company SET
                  company_name = :company_name,
                  company_type = :company_type,
                  address = :address,
                  phone = :phone,
                  email = :email,
                  representative = :representative
                  WHERE company_id = :company_id";

        $params = [
            ':company_id' => $id,
            ':company_name' => $this->company_name,
            ':company_type' => $this->company_type,
            ':address' => $this->address,
            ':phone' => $this->phone,
            ':email' => $this->email,
            ':representative' => $this->representative
        ];

        return $this->exec_query($query, $params);
    }

    // Eliminar una compañía
    public function delete($id) {
        $query = "DELETE FROM company WHERE company_id = :company_id";
        $params = [':company_id' => $id];

        return $this->exec_query($query, $params);
    }

    // Verificar si existe una compañía con el mismo email
    public function checkCompany($email, $id = null) {
        $query = "SELECT COUNT(*) as total FROM company WHERE email = :email";
        $params = [':email' => $email];

        if ($id) {
            $query .= " AND company_id != :company_id";
            $params[':company_id'] = $id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}