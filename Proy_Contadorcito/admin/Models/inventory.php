<?php
require_once(__DIR__ . '/../../conf/conf.php');

class Inventory extends Conf {
    public $id;
    public $book_name;
    public $publisher;
    public $stock;
    public $purchase_price;
    public $sale_price;

    public function create() {
        $query = "INSERT INTO inventory (bookName, publisher, stock, purchase_price, sale_price) VALUES (:bookName, :publisher, :stock, :purchase_price, :sale_price)";
        $params = [
            ':bookName' => $this->book_name,
            ':publisher' => $this->publisher,
            ':stock' => $this->stock,
            ':purchase_price' => $this->purchase_price,
            ':sale_price' => $this->sale_price
        ];

        return $this->exec_query($query, $params);
    }

    public function get_inventory_by_id($id) {
        $query = "SELECT id, bookName, publisher, stock, purchase_price, sale_price FROM inventory WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_inventory() {
        $query = "SELECT id, bookName, publisher, stock, purchase_price, sale_price, created_at, updated_at FROM inventory";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id) {
        $query = "UPDATE inventory SET
            bookName = :bookName,
            publisher = :publisher,
            stock = :stock,
            purchase_price = :purchase_price,
            sale_price = :sale_price
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':bookName' => $this->book_name,
            ':publisher' => $this->publisher,
            ':stock' => $this->stock,
            ':purchase_price' => $this->purchase_price,
            ':sale_price' => $this->sale_price
        ];

        return $this->exec_query($query, $params);
    }

    public function delete($id) {
        $query = "DELETE FROM inventory WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query, $params);
    }

    public function checkBook($bookName, $id = null) {
        $query = "SELECT COUNT(*) as total FROM inventory WHERE bookName = :bookName";
        $params = [':bookName' => $bookName];

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
?>
