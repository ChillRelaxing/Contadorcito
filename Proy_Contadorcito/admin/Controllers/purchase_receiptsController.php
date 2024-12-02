<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/purchase_receipt.php');
require_once('../../conf/funciones.php');

$receipt = new PurchaseReceipt();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $receipt->receiptType = isset($_POST['receiptType']) ? $_POST['receiptType'] : '';
    $receipt->purchase_date = isset($_POST['purchase_date']) ? $_POST['purchase_date'] : '';
    $receipt->total = isset($_POST['total']) ? $_POST['total'] : '';
    $receipt->pdf_path = isset($_POST['pdf_path']) ? $_POST['pdf_path'] : '';
    $receipt->json_path = isset($_POST['json_path']) ? $_POST['json_path'] : '';
    $receipt->supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : '';
    $receipt->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $receipt->company_id = isset($_POST['company_id']) ? $_POST['company_id'] : '';

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "GetCompanys") {
        $company_list = $receipt->list_companies();
        echo json_encode($company_list);
    }

    if ($action == "GetSuppliers") {
        $suppliers_list = $receipt->list_suppliers();
        echo json_encode($suppliers_list);
    }

    if ($action == "GetUsers") {
        $users_list = $receipt->list_users();
        echo json_encode($users_list);
    }

    if ($action == "ListReceipts") {
        $result = $receipt->list_receipts();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['id'] . '</td>';
                $html .= '<td>' . $row['receiptType'] . '</td>';
                $html .= '<td>' . $row['purchase_date'] . '</td>';
                $html .= '<td>' . $row['total'] . '</td>';
                $html .= '<td>' . $row['pdf_path'] . '</td>';
                $html .= '<td>' . $row['json_path'] . '</td>';
                $html .= '<td>' . $row['supplierName'] . '</td>';
                $html .= '<td>' . $row['firstName'] . '</td>';
                $html .= '<td>' . $row['company_name'] . '</td>';
                $html .= '<td>' . $row['created_at'] . '</td>';
                $html .= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['id'] . '"><i class="fa fa-times"></i></a>
                  </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="8">Sin resultados</td>';
            $html .= '</tr>';
        }

        echo $html;
        exit();
    }

    if ($action == "GetReceiptById") {
        $result = $receipt->get_receipt_by_id($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró el recibo de compra";
        }
        exit();
    }

    if ($action == "Create") {
        $result = $receipt->create();
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Recibo de compra agregado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al agregar el recibo de compra'
            ]);
        }
        exit();
    }

    if ($action == "Update") {
        $result = $receipt->update($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Recibo de compra actualizado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al actualizar el recibo de compra'
            ]);
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $receipt->delete($id);
        if ($result) {
            echo "Recibo de compra eliminado con éxito";
        } else {
            echo "Error al eliminar el recibo de compra";
        }
        exit();
    }
}
?>