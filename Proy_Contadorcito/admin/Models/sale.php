<?php
require_once(__DIR__ . '/../../conf/conf.php');


class Sale extends Conf {
    public $id;
    public $date;
    public $employee_id;
    public $client_id;
    public $total;

    public function create(){
        $query = "INSERT INTO sales (date, employee_id, client_id, total) VALUES (:date, :employee_id, :client_id, :total)";
        $params = [
            ':date' => $this->date,
            ':employee_id' => $this->employee_id,
            ':client_id' => $this->client_id,
            ':total' => $this->total
        ];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $this->conn->lastInsertId();
        } else {
            return false;
        }
    }

    public function list_sales(){
        $query = "SELECT a.id, b.firstName as employee, c.firstName as customer, total,date FROM sales as a INNER JOIN users as b
        on a.employee_id = b.id INNER JOIN clients as c 
        on a.client_id = c.id";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_products(){
        $query = "SELECT id, bookName, sale_price FROM inventory";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_clients(){
        $query = "SELECT id, CONCAT(firstName, ' ', lastName) AS fullName FROM clients";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function get_product_by_id($id){
        $query = "SELECT id, bookName, sale_price, stock FROM inventory WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id){
        $query = "UPDATE sales SET
            date = :date,
            employee_id = :employee_id,
            client_id = :client_id,
            total = :total
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':date' => $this->date,
            ':employee_id' => $this->employee_id,
            ':client_id' => $this->client_id,
            ':total' => $this->total
        ];

        return $this->exec_query($query,$params);
    }

    public function delete($id){
        $query = "DELETE FROM sales WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query,$params);
    }

    public function get_sale_details_id($id) {
        $query = "select b.bookName, b.sale_price, a.quantity, b.sale_price * a.quantity as subtotal from sales_detail as a INNER JOIN inventory as b 
        on a.product_id = b.id where a.sale_id = :id";

        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function add_details($details) {
        $query = "INSERT INTO sales_detail (sale_id, product_id, quantity, unit_price) VALUES (:sale_id, :product_id, :quantity, :unit_price)";

        foreach ($details as $detail) {
            $params = [
                ':sale_id' => $this->id,
                ':product_id' => $detail['product_id'],
                ':quantity' => $detail['quantity'],
                ':unit_price' => $detail['unit_price']
            ];

            // Ejecutar la inserción de cada detalle
            $this->exec_query($query, $params);
        }

        return true;
    }


}