<?php
session_start();
if ($_SESSION['userName'] == "") {
    header("Location: ../../index.php");
    exit();
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/roles.php');
require_once('../../conf/funciones.php');

$role = new Role();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $role->role_name = isset($_POST['roleName']) ? $_POST['roleName'] : '';
    $role->description = isset($_POST['description']) ? $_POST['description'] : '';

    $role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';
    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "ListRoles") {
        $result = $role->list_roles();

        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['role_id'] . '</td>';
                $html .= '<td>' . $row['roleName'] . '</td>';
                $html .= '<td>' . $row['description'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-role_id="' . $row['role_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-role_id="' . $row['role_id'] . '"><i class="fa fa-times"></i></a>
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

    if ($action == "GetRoleById") {
        $result = $role->get_role_by_id($role_id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontro el rol";
        }
        exit();
    }

    if ($action == "Create") {
        $isRoleRegitered = $role->checkRole($role->role_name);
        if ($isRoleRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'El nombre de rol ya esta en uso',
                'data' => [
                    'roleName' => $role->role_name,
                    'description' => $role->description
                ]
            ]);
            exit();
        } else {

            $result = $role->create();
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'success',
                   'message' => 'Rol agregado con exito'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'error',
                   'message' => 'Error al agregar el rol'
                ]);
            }
            exit();
        }
    }

    if ($action == "Update") {
        $isRoleRegitered = $role->checkRole($role->role_name, $role_id);
        if ($isRoleRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
               'status' => 'error',
               'message' => 'El nombre de rol ya esta en uso',
                'data' => [
                    'role_id' => $role_id,
                    'roleName' => $role->role_name,
                    'description' => $role->description
                ]
            ]);
            exit();
        } else {
            $result = $role->update($role_id);
            if ($result) {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'success',
                   'message' => 'Rol actualizado con exito'
                ]);
            } else {
                header('Content-Type: application/json');
                echo json_encode([
                   'status' => 'error',
                   'message' => 'Error al actualizar el rol'
                ]);
            }
            exit();
        }
    }

    if ($action == "Delete") {
        $result = $role->delete($role_id);
        if ($result) {
            echo "Rol eliminado con exito";
        } else {
            echo "Error al eliminar el rol";
        }
        exit();
    }
}
