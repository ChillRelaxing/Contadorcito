<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/SalesReceipt.php');
require_once('../../conf/funciones.php');

$salesReceipt = new SalesReceipt();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $salesReceipt->receiptType = isset($_POST['receiptType']) ? $_POST['receiptType'] : '';
    $salesReceipt->sale_date = isset($_POST['sale_date']) ? $_POST['sale_date'] : '';
    $salesReceipt->total = isset($_POST['total']) ? $_POST['total'] : 0;
    $salesReceipt->pdf_path = isset($_POST['pdf_path']) ? $_POST['pdf_path'] : '';
    $salesReceipt->json_path = isset($_POST['json_path']) ? $_POST['json_path'] : '';
    $salesReceipt->client_id = isset($_POST['client_id']) ? $_POST['client_id'] : '';
    $salesReceipt->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';
    $salesReceipt->company_id = isset($_POST['company_id']) ? $_POST['company_id'] : '';

    $id = isset($_POST['sale_receipt_id']) ? $_POST['sale_receipt_id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListReceipts") {
        $result = $salesReceipt->list_receipts();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['sale_receipt_id'] . '</td>';
                $html .= '<td>' . $row['receiptType'] . '</td>';
                $html .= '<td>' . $row['sale_date'] . '</td>';
                $html .= '<td>' . $row['total'] . '</td>';
                $html .= '<td>' . $row['pdf_path'] . '</td>';
                $html .= '<td>' . $row['json_path'] . '</td>';
                $html .= '<td>' . $row['client_id'] . '</td>';
                $html .= '<td>' . $row['user_id'] . '</td>';
                $html .= '<td>' . $row['company_id'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['sale_receipt_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['sale_receipt_id'] . '"><i class="fa fa-times"></i></a>
                  </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="10">Sin resultados</td>';
            $html .= '</tr>';
        }

        echo $html;
        exit();
    }

    if ($action == "GetReceiptById") {
        $result = $salesReceipt->get_receipt_by_id($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró el recibo de venta";
        }
        exit();
    }

    if ($action == "Create") {
        $result = $salesReceipt->create();
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Recibo de venta agregado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al agregar el recibo de venta'
            ]);
        }
        exit();
    }

    if ($action == "Update") {
        $result = $salesReceipt->update($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Recibo de venta actualizado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al actualizar el recibo de venta'
            ]);
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $salesReceipt->delete($id);
        if ($result) {
            echo "Recibo de venta eliminado con éxito";
        } else {
            echo "Error al eliminar el recibo de venta";
        }
        exit();
    }
}
?>