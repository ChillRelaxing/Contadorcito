<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/purchase_receipt.php');
require_once('../../conf/funciones.php');

$purchaseReceipt = new PurchaseReceipt();

if (isset($_FILES['archivo_pdf']) && isset($_FILES['archivo_json'])) {
    // Ruta de destino para los archivos
    $pdf_dir = "../../uploads/pdfs";
    $json_dir = "../../uploads/jsons";

    // Obtener nombres de los archivos
    $pdf_name = $_FILES['archivo_pdf']['name'];
    $json_name = $_FILES['archivo_json']['name'];

    // Ruta completa de destino
    $pdf_target = $pdf_dir . '/' . basename($pdf_name);
    $json_target = $json_dir . '/' . basename($json_name);

    // Comprobar que los directorios existen o crearlos
    if (!is_dir($pdf_dir)) {
        mkdir($pdf_dir, 0777, true);
    }
    if (!is_dir($json_dir)) {
        mkdir($json_dir, 0777, true);
    }

    // Mover los archivos al directorio correspondiente
    if (move_uploaded_file($_FILES['archivo_pdf']['tmp_name'], $pdf_target) &&
        move_uploaded_file($_FILES['archivo_json']['tmp_name'], $json_target)) {

        // Mostrar las rutas de los archivos
        echo "Ruta PDF: " . $pdf_target . "<br>";
        echo "Ruta JSON: " . $json_target . "<br>";

        // Insertar las rutas en la base de datos
        $stmt = $conn->prepare("INSERT INTO purchase_receipts (pdf_path, json_path) VALUES (?, ?)");
        $stmt->bind_param("ss", $pdf_target, $json_target);

        // Ejecutar la consulta
        if ($stmt->execute()) {
            echo "Archivos subidos y registrados correctamente.";
        } else {
            echo "Error al registrar los archivos en la base de datos: " . $stmt->error;
        }
        $stmt->close();
    } else {
        echo "Error al mover los archivos.";
    }
}


// Procesamiento de archivos PDF
if (isset($_FILES['pdf_path']) && $_FILES['pdf_path']['error'] == UPLOAD_ERR_OK) {
    $pdfTmpPath = $_FILES['pdf_path']['tmp_name'];
    $pdfName = $_FILES['pdf_path']['name'];
    $pdfPath = '../../uploads/pdfs/' . $pdfName; // Especifica el directorio donde guardar el archivo
    
    move_uploaded_file($pdfTmpPath, $pdfPath);
    $purchaseReceipt->pdf_path = $pdfPath; // Guarda la ruta del archivo
}

// Para el archv JSON
if (isset($_FILES['json_path']) && $_FILES['json_path']['error'] == UPLOAD_ERR_OK) {
    $jsonTmpPath = $_FILES['json_path']['tmp_name'];
    $jsonName = $_FILES['json_path']['name'];
    $jsonPath = '../../uploads/jsons/' . $jsonName;

    if (!is_dir('../../uploads/jsons/')) {
        mkdir('../../uploads/jsons/', 0777, true); // Crea directorio si no existe
    }

    if (move_uploaded_file($jsonTmpPath, $jsonPath)) {
        $purchaseReceipt->json_path = $jsonPath; // Guardar la ruta del archivo JSON

    } else {
        echo "Error: No se pudo mover el archivo JSON.";
    }
}


if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $purchaseReceipt->receiptType = isset($_POST['receiptType']) ? $_POST['receiptType'] : '';
    $purchaseReceipt->receiptNumber = isset($_POST['receiptNumber']) ? $_POST['receiptNumber'] : '';
    $purchaseReceipt->purchase_date = isset($_POST['purchase_date']) ? $_POST['purchase_date'] : '';
    $purchaseReceipt->total = isset($_POST['total']) ? $_POST['total'] : '';
    $purchaseReceipt->supplier_id = isset($_POST['supplier_id']) ? $_POST['supplier_id'] : '';
    $purchaseReceipt->company_id = isset($_POST['company_id']) ? $_POST['company_id'] : '';
    $purchaseReceipt->pdf_path = isset($_POST['pdf_path']) ? $_POST['pdf_path'] : '';
    $purchaseReceipt->json_path = isset($_POST['json_path']) ? $_POST['json_path'] : '';

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    // Acción para listar recibos
    if ($action == "ListReceipts") {
        $result = $purchaseReceipt->list_receipts();
    
        $html = '';
    
        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['receipt_id'] . '</td>';
                $html .= '<td>' . $row['receiptType'] . '</td>';
                $html .= '<td>' . $row['receiptNumber'] . '</td>';
                $html .= '<td>' . $row['purchase_date'] . '</td>';
                $html .= '<td>' . $row['total'] . '</td>';
                $html .= '<td>' . $row['supplierName'] . '</td>'; 
                $html .= '<td>' . $row['company_name'] . '</td>';
                $html .= '<td>' . $row['pdf_path'] . '</td>';
                $html .= '<td>' . $row['json_path'] . '</td>';
                $html .= '<td>' . $row['created_at'] . '</td>';
                $html .= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['receipt_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['receipt_id'] . '"><i class="fa fa-times"></i></a>
                    </td>';
                $html .= '</tr>';
            }
        }
    
        echo $html;
        exit();
    }
    

    // Acción para obtener recibo por ID
    if ($action == "GetReceiptById") {
        $result = $purchaseReceipt->get_receipt_by_id($id);

        if (!empty($result)) {
            echo json_encode($result);
        } else {
            echo json_encode([]);
        }
        exit();
    }

    // Acción para crear recibo
    if ($action == "Create") {
        $isReceiptRegistered = $purchaseReceipt->check_receipt($purchaseReceipt->supplier_id, $purchaseReceipt->purchase_date);
        if ($isReceiptRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Ya existe un recibo para el proveedor en la misma fecha',
                'data' => [
                    'receiptNumber' => $purchaseReceipt->receiptNumber
                ]
            ]);
            exit();
        } else {
            $result = $purchaseReceipt->create();

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Recibo creado con éxito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al crear el recibo'
                ]);
            }
        }
        exit();
    }

    // Acción para actualizar recibo
    if ($action == "Update") {
        $isReceiptRegistered = $purchaseReceipt->check_receipt($purchaseReceipt->supplier_id, $purchaseReceipt->purchase_date, $id);
        if ($isReceiptRegistered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Ya existe un recibo con el mismo número para este proveedor',
                'data' => [
                    'id' => $id,
                    'receiptNumber' => $purchaseReceipt->receiptNumber
                ]
            ]);
            exit();
        } else {
            $result = $purchaseReceipt->update($id);

            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Recibo actualizado con éxito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al actualizar el recibo'
                ]);
            }
        }
        exit();
    }

    // Acción para eliminar recibo
    if ($action == "Delete") {
        $result = $purchaseReceipt->delete($id);

        if ($result) {
            echo 'Recibo eliminado con éxito';
        } else {
            echo 'Error al eliminar el recibo';
        }
        exit();
    }

    // Acción para mostrar el modal de creación
    if ($action == "ShowCreateModal") {
        // Obtener proveedores y empresas
        $suppliers = $purchaseReceipt->getAllSuppliers(); 
        $companies = $purchaseReceipt->getAllCompanies(); 

        // Pasar los datos a la vista del modal
        include('../views/purchase_receipts/createModal.php');
        exit();
    }

}
?>
