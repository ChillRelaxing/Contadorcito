<?php
require_once(__DIR__ . '/../../conf/conf.php');

class PurchaseReceipt extends Conf {
    public $id;
    public $receiptType;
    public $purchase_date;
    public $total;
    public $pdf_path;
    public $json_path;
    public $supplier_id;
    public $user_id;
    public $company_id;


    public function list_companies(){
        $query = "SELECT id, company_name FROM company";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_suppliers(){
        $query = "SELECT id, supplier_name FROM suppliers";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_users(){
        $query = "SELECT id, firstName FROM users";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Crear un nuevo recibo de compra
    public function create() {
        $query = "INSERT INTO purchase_receipts (receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id) 
                  VALUES (:receiptType, :purchase_date, :total, :pdf_path, :json_path, :supplier_id, :user_id, :company_id)";
        $params = [
            ':receiptType' => $this->receiptType,
            ':purchase_date' => $this->purchase_date,
            ':total' => $this->total,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path,
            ':supplier_id' => $this->supplier_id,
            ':user_id' => $this->user_id,
            ':company_id' => $this->company_id
        ];

        return $this->exec_query($query, $params);
    }

    // Obtener un recibo de compra por ID
    public function get_receipt_by_id($id) {
        $query = "SELECT id, receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id 
                  FROM purchase_receipts 
                  WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todos los recibos de compra
    public function list_receipts() {
        $query = "SELECT pr.id, pr.receiptType, pr.purchase_date, pr.total, pr.pdf_path, pr.json_path, s.supplierName, u.firstName, c.company_name, pr.created_at, pr.updated_at
                  FROM purchase_receipts pr
                  INNER JOIN suppliers s ON pr.supplier_id = s.id
                  INNER JOIN users u ON pr.user_id = u.id
                  INNER JOIN company c ON pr.company_id = c.id";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Actualizar un recibo de compra
    public function update($id) {
        $query = "UPDATE purchase_receipts SET
                  receiptType = :receiptType,
                  purchase_date = :purchase_date,
                  total = :total,
                  pdf_path = :pdf_path,
                  json_path = :json_path,
                  supplier_id = :supplier_id,
                  user_id = :user_id,
                  company_id = :company_id
                  WHERE id = :id";

        $params = [
            ':id' => $id,
            ':receiptType' => $this->receiptType,
            ':purchase_date' => $this->purchase_date,
            ':total' => $this->total,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path,
            ':supplier_id' => $this->supplier_id,
            ':user_id' => $this->user_id,
            ':company_id' => $this->company_id
        ];

        return $this->exec_query($query, $params);
    }

    // Eliminar un recibo de compra
    public function delete($id) {
        $query = "DELETE FROM purchase_receipts WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query, $params);
    }

    // Verificar si existe un recibo de compra para una compañía y fecha específica
    public function check_receipt($company_id, $purchase_date, $receipt_id = null) {
        $query = "SELECT COUNT(*) as total FROM purchase_receipts 
                  WHERE company_id = :company_id AND purchase_date = :purchase_date";
        $params = [
            ':company_id' => $company_id,
            ':purchase_date' => $purchase_date
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