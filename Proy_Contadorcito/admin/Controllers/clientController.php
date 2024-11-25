<?php
session_start();
if ($_SESSION['user_name'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/client.php');
require_once('../../conf/funciones.php');

$client = new Client();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $client->firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $client->lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $client->address = isset($_POST['address']) ? $_POST['address'] : '';
    $client->email = isset($_POST['email']) ? $_POST['email'] : '';
    $client->phone = isset($_POST['phone']) ? $_POST['phone'] : '';

    $id = isset($_POST['client_id']) ? $_POST['client_id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListClients") {
        $result = $client->list_clients();
        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['client_id'] . '</td>';
                $html .= '<td>' . $row['firstName'] . '</td>';
                $html .= '<td>' . $row['lastName'] . '</td>';
                $html .= '<td>' . $row['address'] . '</td>';
                $html .= '<td>' . $row['email'] . '</td>';
                $html .= '<td>' . $row['phone'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['client_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['client_id'] . '"><i class="fa fa-times"></i></a>
                  </td>';
                $html .= '</tr>';
            }
        } else {
            $html .= '<tr>';
            $html .= '<td colspan="7">Sin resultados</td>';
            $html .= '</tr>';
        }

        echo $html;
        exit();
    }

    if ($action == "GetClientById") {
        $result = $client->get_client_by_id($id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró el cliente";
        }
        exit();
    }

    if ($action == "Create") {
        $isClientRegitered = $client->checkClient($client->email);
        if ($isClientRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'El email del cliente ya esta en uso',
                'data' => [
                    'email' => $client->email
                ]
            ]);
            exit();
        } else {

            $result = $client->create();
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'success',
                   'message' => 'Cliente agregado con exito'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'error',
                   'message' => 'Error al agregar el cliente'
                ]);
            }
            exit();
        }
    }

    if ($action == "Update") {
        $isClientRegitered = $client->checkClient($client->email, $id);
        if ($isClientRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'El email del cliente ya esta en uso',
                'data' => [
                    'client_id' => $id,
                    'email' => $client->email
                ]
            ]);
            exit();
        } else {
            $result = $client->update($id);
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'success',
                   'message' => 'Cliente actualizado con exito'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'error',
                   'message' => 'Error al actualizar el cliente'
                ]);
            }
            exit();
        }
    }

    if ($action == "Delete") {
        $result = $client->delete($id);
        if ($result) {
            echo "Cliente eliminado con éxito";
        } else {
            echo "Error al eliminar el cliente";
        }
        exit();
    }
}