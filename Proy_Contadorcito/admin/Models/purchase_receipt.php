<?php
require_once(__DIR__ . '/../../conf/conf.php');

class PurchaseReceipt extends Conf {
    public $receipt_id;
    public $receiptType;
    public $receiptNumber;
    public $purchase_date;
    public $total;
    public $supplier_id;
    public $company_id;
    public $pdf_path;
    public $json_path;
    public $created_at;
    public $updated_at;

    // Crear un nuevo recibo de compra
    public function create() {
        $query = "INSERT INTO purchase_receipts (receiptType, receiptNumber, purchase_date, total, supplier_id, company_id, pdf_path, json_path) 
                  VALUES (:receiptType, :receiptNumber, :purchase_date, :total, :supplier_id, :company_id, :pdf_path, :json_path)";
        $params = [
            ':receiptType' => $this->receiptType,
            ':receiptNumber' => $this->receiptNumber,
            ':purchase_date' => $this->purchase_date,
            ':total' => $this->total,
            ':supplier_id' => $this->supplier_id,
            ':company_id' => $this->company_id,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path
        ];

        return $this->exec_query($query, $params);
    }

    // Obtener un recibo de compra por ID
    public function get_receipt_by_id($id) {
        $query = "SELECT receipt_id, receiptType, receiptNumber, purchase_date, total, supplier_id, company_id, pdf_path, json_path, created_at, updated_at 
                  FROM purchase_receipts 
                  WHERE receipt_id = :receipt_id";
        $params = [':receipt_id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todos los recibos de compra
    public function list_receipts() {
        $query = "SELECT pr.receipt_id, pr.receiptType, pr.receiptNumber, pr.purchase_date, pr.total, 
        s.supplierName AS supplierName, c.company_name AS company_name, 
        pr.pdf_path, pr.json_path, pr.created_at, pr.updated_at
        FROM purchase_receipts pr
        INNER JOIN suppliers s ON pr.supplier_id = s.id
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
                  receiptNumber = :receiptNumber,
                  purchase_date = :purchase_date,
                  total = :total,
                  supplier_id = :supplier_id,
                  company_id = :company_id,
                  pdf_path = :pdf_path,
                  json_path = :json_path
                  WHERE receipt_id = :receipt_id";

        $params = [
            ':receipt_id' => $id,
            ':receiptType' => $this->receiptType,
            ':receiptNumber' => $this->receiptNumber,
            ':purchase_date' => $this->purchase_date,
            ':total' => $this->total,
            ':supplier_id' => $this->supplier_id,
            ':company_id' => $this->company_id,
            ':pdf_path' => $this->pdf_path,
            ':json_path' => $this->json_path
        ];

        return $this->exec_query($query, $params);
    }

    // Eliminar un recibo de compra
    public function delete($id) {
        $query = "DELETE FROM purchase_receipts WHERE receipt_id = :receipt_id";
        $params = [':receipt_id' => $id];

        return $this->exec_query($query, $params);
    }

    // Verificar si existe un recibo de compra con el mismo número de recibo para un proveedor en una fecha específica
    public function check_receipt($supplier_id, $purchase_date, $receipt_id = null) {
        $query = "SELECT COUNT(*) as total FROM purchase_receipts 
                  WHERE supplier_id = :supplier_id AND purchase_date = :purchase_date";
        $params = [
            ':supplier_id' => $supplier_id,
            ':purchase_date' => $purchase_date
        ];

        if ($receipt_id) {
            $query .= " AND receipt_id != :receipt_id";
            $params[':receipt_id'] = $receipt_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
    public function getAllSuppliers() {
        $query = "SELECT id, supplierName FROM suppliers"; 
        $result = $this->exec_query($query);
        
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    public function getAllCompanies() {
        $query = "SELECT company_id, company_name FROM company"; // Suponiendo que 'company_name' es el nombre de la empresa
        $result = $this->exec_query($query);
        
        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        }
        return [];
    }

    // Método para obtener todos los comprobantes de compra
    public function getAllReceipts() {
        $sql = "SELECT pr.receipt_id, pr.receiptType, pr.receiptNumber, pr.purchase_date, pr.total, 
                s.supplierName  AS supplier_name, c.name AS company_name, 
                pr.pdf_path, pr.json_path, pr.created_at, pr.updated_at
                FROM purchase_receipts pr
                INNER JOIN suppliers s ON pr.supplier_id = s.id
                INNER JOIN company c ON pr.company_id = c.id";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
    
}
?>
