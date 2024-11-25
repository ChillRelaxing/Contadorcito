<?php
require_once(__DIR__ . '/../../conf/conf.php');

class SalesReceipt extends Conf {
    public $sale_receipt_id;
    public $receiptType;
    public $sale_date;
    public $total;
    public $pdf_path;
    public $json_path;
    public $client_id;
    public $user_id;
    public $company_id;

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
        $query = "SELECT sale_receipt_id, receiptType, sale_date, total, pdf_path, json_path, client_id, user_id, company_id 
                  FROM sales_receipts 
                  WHERE sale_receipt_id = :sale_receipt_id";
        $params = [':sale_receipt_id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todos los recibos de venta
    public function list_receipts() {
        $query = "SELECT sale_receipt_id, receiptType, sale_date, total, pdf_path, json_path, client_id, user_id, company_id 
                  FROM sales_receipts";

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
                  WHERE sale_receipt_id = :sale_receipt_id";

        $params = [
            ':sale_receipt_id' => $id,
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
        $query = "DELETE FROM sales_receipts WHERE sale_receipt_id = :sale_receipt_id";
        $params = [':sale_receipt_id' => $id];

        return $this->exec_query($query, $params);
    }

    // Verificar si existe un recibo de venta en una fecha específica para un cliente
    public function check_receipt($client_id, $sale_date, $sale_receipt_id = null) {
        $query = "SELECT COUNT(*) as total FROM sales_receipts 
                  WHERE client_id = :client_id AND sale_date = :sale_date";
        $params = [
            ':client_id' => $client_id,
            ':sale_date' => $sale_date
        ];

        if ($sale_receipt_id) {
            $query .= " AND sale_receipt_id != :sale_receipt_id";
            $params[':sale_receipt_id'] = $sale_receipt_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}