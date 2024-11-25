<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/Report.php');
require_once('../../conf/funciones.php');

$report = new Report();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $report->report_type = isset($_POST['report_type']) ? $_POST['report_type'] : '';
    $report->start_date = isset($_POST['start_date']) ? $_POST['start_date'] : '';
    $report->end_date = isset($_POST['end_date']) ? $_POST['end_date'] : '';
    $report->status = isset($_POST['status']) ? $_POST['status'] : 'Pendiente';
    $report->company_id = isset($_POST['company_id']) ? $_POST['company_id'] : '';
    $report->user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

    $id = isset($_POST['report_id']) ? $_POST['report_id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListReports") {
        $result = $report->list_reports();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['report_id'] . '</td>';
                $html .= '<td>' . $row['report_type'] . '</td>';
                $html .= '<td>' . $row['start_date'] . '</td>';
                $html .= '<td>' . $row['end_date'] . '</td>';
                $html .= '<td>' . $row['status'] . '</td>';
                $html .= '<td>' . $row['company_id'] . '</td>';
                $html .= '<td>' . $row['user_id'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['report_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['report_id'] . '"><i class="fa fa-times"></i></a>
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

    if ($action == "GetReportById") {
        $result = $report->get_report_by_id($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró el reporte";
        }
        exit();
    }

    if ($action == "Create") {
        $result = $report->create();
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Reporte agregado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al agregar el reporte'
            ]);
        }
        exit();
    }

    if ($action == "Update") {
        $result = $report->update($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'success',
               'message' => 'Reporte actualizado con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'Error al actualizar el reporte'
            ]);
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $report->delete($id);
        if ($result) {
            echo "Reporte eliminado con éxito";
        } else {
            echo "Error al eliminar el reporte";
        }
        exit();
    }
}
?>