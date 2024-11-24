<?php
session_start();
if ($_SESSION['userName'] == "") {
    header("Location: ../../index.php");
    exit();
}
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once('../Models/user.php');
require_once('../../conf/funciones.php');

$user = new User();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $user->firstName = isset($_POST['firstName']) ? $_POST['firstName'] : '';
    $user->lastName = isset($_POST['lastName']) ? $_POST['lastName'] : '';
    $user->userName = isset($_POST['userName']) ? ($_POST['userName']) : '';
    $user->email = isset($_POST['email']) ? $_POST['email'] : '';
    $user->phone = isset($_POST['phone']) ? intval($_POST['phone']) : '';
    $user->password = isset($_POST['password']) ?  md5($_POST['password']) : '';
    $user->role_id = isset($_POST['role_id']) ? $_POST['role_id'] : '';

    $user_id = isset($_POST['user_id']) ? $_POST['user_id'] : '';

    $action = isset($_POST['action']) ? $_POST['action'] : "";

    if ($action == "GetRoles") {
        $roles_list = $user->list_roles();
        echo json_encode($roles_list);
    }

    if ($action == "ListUsers") {
        $result = $user->list_users();

        $html = '';

        if (!empty($result)) {
            foreach ($result as $row) {
                $html .= '<tr>';
                $html .= '<td>' . $row['user_id'] . '</td>';
                $html .= '<td>' . $row['firstName'] . '</td>';
                $html .= '<td>' . $row['lastName'] . '</td>';
                $html .= '<td>' . $row['userName'] . '</td>';
                $html .= '<td>' . $row['roleName'] . '</td>';
                $html .= '<td>' . $row['email'] . '</td>';
                $html .= '<td>' . $row['phone'] . '</td>';
                $html .= '<td>' . $row['created_at'] . '</td>';
                $html .= '<td>' . $row['updated_at'] . '</td>';
                $html .= '<td>
                    <a href="#" class="btn btn-sm btn-warning" data-bs-toggle="modal" data-bs-target="#updateModal" data-bs-id="' . $row['user_id'] . '"><i class="fa fa-edit"></i></a>
                    <a href="#" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal" data-bs-id="' . $row['user_id'] . '"><i class="fa fa-times"></i></a>
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

    if ($action == "GetUserById") {
        $result = $user->get_user_by_id($user_id);
        if ($result) {
            header('Content-Type: application/json');
            echo json_encode($result);
        } else {
            header('Content-Type: application/json');
            echo "No se encontró el usuario";
        }
        exit();
    }

    if ($action == "Create") {
        $isUserRegitered = $user->checkUser($user->userName, $user->email);
        if ($isUserRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de usuario o correo electronico ya esta en uso',
                'data' => [
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'userName' => $user->userName,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'password' => "",
                    'role_id' => $user->role_id
                ]
            ]);
            exit();
        } else {
            $result = $user->create();
            if ($result) {
                echo json_encode([
                    'status' => 'success',
                    'message' => 'Usuario agregado con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                    'message' => 'Error al agregar el usuario'
                ]);
            }
            exit();
        }
    }

    if ($action == "Update") {
        $isUserRegitered = $user->checkUser($user->userName, $user->email, $user_id);
        if ($isUserRegitered > 0) {
            header('Content-Type: application/json');
            echo json_encode([
                'status' => 'error',
                'message' => 'El nombre de usuario o correo electronico ya esta en uso',
                'data' => [
                    'user_id' => $user_id,
                    'firstName' => $user->firstName,
                    'lastName' => $user->lastName,
                    'userName' => $user->userName,
                    'email' => $user->email,
                    'phone' => $user->phone,
                    'role_id' => $user->role_id
                ]
            ]);
            exit();
        } else {
            $result = $user->update($user_id);
            if ($result) {
                echo json_encode([
                    'status' =>'success',
                   'message' => 'Usuario actualizado con exito'
                ]);
            } else {
                echo json_encode([
                    'status' => 'error',
                   'message' => 'Error al actualizar el usuario'
                ]);
            }
            exit();
        }
    }

    if ($action == "Delete") {
        $result = $user->delete($user_id);
        if ($result) {
            echo "Usuario eliminado con exito";
        } else {
            echo "Error al eliminar el usuario";
        }
        exit();
    }
}
