<?php
require_once(__DIR__ . '/../../conf/conf.php');

class PurchaseReceipt extends Conf {
    public $receipt_id;
    public $receipt_type;
    public $purchase_date;
    public $total;
    public $pdf_path;
    public $json_path;
    public $supplier_id;
    public $user_id;
    public $company_id;

    public function create(){
        $query = "INSERT INTO purchase_receipts 
                  (receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id) 
                  VALUES 
                  (:receiptType, :purchase_date, :total, :pdf_path, :json_path, :supplier_id, :user_id, :company_id)";
        $params = [
            ':receiptType' => $this->receipt_type,
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

    public function get_receipt_by_id($receipt_id){
        $query = "SELECT receipt_id, receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id 
                  FROM purchase_receipts 
                  WHERE receipt_id = :receipt_id";
        $params = [':receipt_id' => $receipt_id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_receipts(){
        $query = "SELECT receipt_id, receiptType, purchase_date, total, pdf_path, json_path, supplier_id, user_id, company_id, created_at, updated_at 
                  FROM purchase_receipts";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($receipt_id){
        $query = "UPDATE purchase_receipts SET
                    receiptType = :receiptType,
                    purchase_date = :purchase_date,
                    total = :total,
                    pdf_path = :pdf_path,
                    json_path = :json_path,
                    supplier_id = :supplier_id,
                    user_id = :user_id,
                    company_id = :company_id
                  WHERE receipt_id = :receipt_id";

        $params = [
            ':receipt_id' => $receipt_id,
            ':receiptType' => $this->receipt_type,
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

    public function delete($receipt_id){
        $query = "DELETE FROM purchase_receipts WHERE receipt_id = :receipt_id";
        $params = [':receipt_id' => $receipt_id];

        return $this->exec_query($query, $params);
    }

    public function checkReceipt($pdf_path){
        $query = "SELECT COUNT(*) as total FROM purchase_receipts WHERE pdf_path = :pdf_path";
        $params = [':pdf_path' => $pdf_path];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}
