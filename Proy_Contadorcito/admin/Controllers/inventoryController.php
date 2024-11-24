<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/inventory.php');
require_once('../../conf/funciones.php');

$inventory = new Inventory();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $inventory->book_name = isset($_POST['bookName']) ? $_POST['bookName'] : '';
    $inventory->publisher = isset($_POST['publisher']) ? $_POST['publisher'] : '';
    $inventory->stock = isset($_POST['stock']) ? $_POST['stock'] : 0;
    $inventory->purchase_price = isset($_POST['purchase_price']) ? $_POST['purchase_price'] : 0.00;
    $inventory->sale_price = isset($_POST['sale_price']) ? $_POST['sale_price'] : 0.00;

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : '';

    if ($action == "ListInventory") {
        $result = $inventory->list_inventory();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['id'] . '</td>';
                $html .= '<td>' . $row['bookName'] . '</td>';
                $html .= '<td>' . $row['publisher'] . '</td>';
                $html .= '<td>' . $row['stock'] . '</td>';
                $html .= '<td>' . $row['purchase_price'] . '</td>';
                $html .= '<td>' . $row['sale_price'] . '</td>';
                $html .= '<td>' . $row['created_at'] . '</td>';
                $html .= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-times"></i></a>
                    </td>';
                $html .= '</tr>';
            }
        }

        echo $html;
        exit();
    }

    if ($action == "GetInventoryById") {
        $result = $inventory->get_inventory_by_id($id);

        if (!empty($result)) {
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
        exit();
    }

    if ($action == "Create") {
        $isBookRegistered = $inventory->checkBook($inventory->book_name);
        if ($isBookRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre del libro ya está en uso',
                'data' => [
                    'bookName' => $inventory->book_name,
                    'publisher' => $inventory->publisher,
                    'stock' => $inventory->stock,
                    'purchase_price' => $inventory->purchase_price,
                    'sale_price' => $inventory->sale_price
                ]
            ]);
            exit();
        } else {
            $result = $inventory->create();

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Libro creado con éxito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al agregar el libro'
                ]);
            }
        }
        exit();
    }

    if ($action == "Update") {
        $isBookRegistered = $inventory->checkBook($inventory->book_name, $id);
        if ($isBookRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre del libro ya está en uso',
                'data' => [
                    'id' => $id,
                    'bookName' => $inventory->book_name,
                    'publisher' => $inventory->publisher,
                    'stock' => $inventory->stock,
                    'purchase_price' => $inventory->purchase_price,
                    'sale_price' => $inventory->sale_price
                ]
            ]);
            exit();
        } else {
            $result = $inventory->update($id);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Libro actualizado con éxito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al actualizar el libro'
                ]);
            }
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $inventory->delete($id);

        if ($result) {
            echo 'Libro eliminado con éxito';
        } else {
            echo 'Error al eliminar el libro';
        }
        exit();
    }
}
?>
