<?php
require_once(__DIR__ . '/../../conf/conf.php');

class SalesReceipt extends Conf {
    public $id;
    public $receiptType;
    public $sale_date;
    public $total;
    public $pdf_path;
    public $json_path;
    public $client_id;
    public $user_id;
    public $company_id;

    // Listar compañías
    public function list_companies() {
        $query = "SELECT id, company_name FROM company";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar clientes
    public function list_clients() {
        $query = "SELECT id, client_name FROM clients";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar usuarios
    public function list_users() {
        $query = "SELECT id, firstName FROM users";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Crear un nuevo recibo de venta
    public function create() {
        $query = "INSERT INTO sales_receipts (receiptType, sale_date, total, pdf_path, json_path, client_id, user_id, company_id) 
                  VALUES (:receiptType, :sale_date, :total, :pdf_path, :json_path, :client_id, :user_id, :company_id)";
        $params = [
            ':receiptType' => $this->receiptType,
            ':sale_date' => $this->sale_date,
            ':total' => $this->total,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path,
            ':client_id' => $this->client_id,
            ':user_id' => $this->user_id,
            ':company_id' => $this->company_id
        ];

        return $this->exec_query($query, $params);
    }

    // Obtener un recibo de venta por ID
    public function get_receipt_by_id($id) {
        $query = "SELECT id, receiptType, sale_date, total, pdf_path, json_path, client_id, user_id, company_id 
                  FROM sales_receipts 
                  WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todos los recibos de venta
    public function list_receipts() {
        $query = "SELECT sr.id, sr.receiptType, sr.sale_date, sr.total, sr.pdf_path, sr.json_path, c.client_name, u.firstName, co.company_name, sr.created_at, sr.updated_at
                  FROM sales_receipts sr
                  INNER JOIN clients c ON sr.client_id = c.id
                  INNER JOIN users u ON sr.user_id = u.id
                  INNER JOIN company co ON sr.company_id = co.id";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Actualizar un recibo de venta
    public function update($id) {
        $query = "UPDATE sales_receipts SET
                  receiptType = :receiptType,
                  sale_date = :sale_date,
                  total = :total,
                  pdf_path = :pdf_path,
                  json_path = :json_path,
                  client_id = :client_id,
                  user_id = :user_id,
                  company_id = :company_id
                  WHERE id = :id";

        $params = [
            ':id' => $id,
            ':receiptType' => $this->receiptType,
            ':sale_date' => $this->sale_date,
            ':total' => $this->total,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path,
            ':client_id' => $this->client_id,
            ':user_id' => $this->user_id,
            ':company_id' => $this->company_id
        ];

        return $this->exec_query($query, $params);
    }

    // Eliminar un recibo de venta
    public function delete($id) {
        $query = "DELETE FROM sales_receipts WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query, $params);
    }

    // Verificar si existe un recibo de venta para una compañía y fecha específica
    public function check_receipt($company_id, $sale_date, $receipt_id = null) {
        $query = "SELECT COUNT(*) as total FROM sales_receipts 
                  WHERE company_id = :company_id AND sale_date = :sale_date";
        $params = [
            ':company_id' => $company_id,
            ':sale_date' => $sale_date
        ];

        if ($receipt_id) {
            $query .= " AND id != :receipt_id";
            $params[':receipt_id'] = $receipt_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}
?>