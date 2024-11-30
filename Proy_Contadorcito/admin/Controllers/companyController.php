<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/company.php');
require_once('../../conf/funciones.php');

$company = new Company();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $company->company_name = isset($_POST['company_name']) ? $_POST['company_name'] : '';
    $company->company_type = isset($_POST['company_type']) ? $_POST['company_type'] : '';
    $company->address = isset($_POST['address']) ? $_POST['address'] : '';
    $company->phone = isset($_POST['phone']) ? $_POST['phone'] : '';
    $company->email = isset($_POST['email']) ? $_POST['email'] : '';
    $company->representative = isset($_POST['representative']) ? $_POST['representative'] : '';

    $id = isset($_POST['id']) ? $_POST['id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListCompanies") {
        $result = $company->list_companies();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['id'] . '</td>';
                $html .= '<td>' . $row['company_name'] . '</td>';
                $html .= '<td>' . $row['company_type'] . '</td>';
                $html .= '<td>' . $row['address'] . '</td>';
                $html .= '<td>' . $row['phone'] . '</td>';
                $html .= '<td>' . $row['email'] . '</td>';
                $html .= '<td>' . $row['representative'] . '</td>';
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

    if ($action == "GetCompanyById") {
        $result = $company->get_company_by_id($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró la compañía";
        }
        exit();
    }

    if ($action == "Create") {
        $result = $company->create();
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Compañía agregada con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al agregar la compañía'
            ]);
        }
        exit();
    }

    if ($action == "Update") {
        $result = $company->update($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'success',
                'message' => 'Compañía actualizada con éxito'
            ]);
        } else {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'Error al actualizar la compañía'
            ]);
        }
        exit();
    }

    if ($action == "Delete") {
        $result = $company->delete($id);
        if ($result) {
            echo "Compañía eliminada con éxito";
        } else {
            echo "Error al eliminar la compañía";
        }
        exit();
    }
}
