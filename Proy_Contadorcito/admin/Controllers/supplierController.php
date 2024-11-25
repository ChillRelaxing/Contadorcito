<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/supplier.php');
require_once('../../conf/funciones.php');

$supplier = new Supplier();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $supplier->supplier_name = isset($_POST['supplierName']) ? $_POST['supplierName'] : '';
    $supplier->email = isset($_POST['email']) ? $_POST['email'] : '';
    $supplier->phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $supplier->address = isset($_POST['address']) ? $_POST['address'] : '';
    $supplier->city = isset($_POST['city']) ? $_POST['city'] : '';

    $supplier_id = isset($_POST['id']) ? $_POST['id'] : '';

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListSuppliers") {
        $result = $supplier->list_suppliers();

        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['supplier_id'] . '</td>';
                $html .= '<td>' . $row['supplierName'] . '</td>';
                $html .= '<td>' . $row['email'] . '</td>';
                $html .= '<td>' . $row['phone'] . '</td>';
                $html .= '<td>' . $row['address'] . '</td>';
                $html .= '<td>' . $row['city'] . '</td>';
                $html.= '<td>' . $row['created_at'] . '</td>';
                $html.= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['supplier_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['supplier_id'] . '"><i class="fa fa-times"></i></a>
                    </td>';
                $html .= '</tr>';
            };
        }

        echo $html;
        exit();
    }

    if ($action == "GetSupplierById") {
        $result = $supplier->get_supplier_by_id($id);

        if (!empty($result)) {
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
        exit();
    }

    if ($action == "Create") {
        $isSupplierRegistered = $supplier->checkSupplier();
        if ($isSupplierRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de proveedor ya esta en uso',
                'data' => [
                    'supplierName' => $supplier->supplier_name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                    'city' => $supplier->city
                ]
            ]);
            exit();
        } else {
            $result = $supplier->create();

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proveedor creado con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al agregar el Proveedor'
                ]);
            }
        }
        exit();
    }

    if ($action == "Update") {
        $isSupplierRegistered = $supplier->checkSupplier($id);
        if ($isSupplierRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de proveedor ya esta en uso',
                'data' => [
                    'id' => $id,
                    'supplierName' => $supplier->supplier_name,
                    'email' => $supplier->email,
                    'phone' => $supplier->phone,
                    'address' => $supplier->address,
                    'city' => $supplier->city
                ]
            ]);
            exit();
        } else {
            $result = $supplier->update($id);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Proveedor actualizado con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al actualizar el Proveedor'
                ]);
            }
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $supplier->delete($id);

        if ($result) {
            echo 'Proveedor eliminado con exito';
        } else {
            echo 'Error al eliminar el Proveedor';
        }
        exit();
    }
}
