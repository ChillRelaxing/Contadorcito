<?php

require_once(__DIR__ . '/../../conf/conf.php');

class Role extends Conf {
    public $role_id;
    public $role_name;
    public $description;

    // Método para crear un rol
    public function create() {
        $query = "INSERT INTO roles (roleName, description) VALUES (:roleName, :description)";
        $params = [
            ':roleName' => $this->role_name,
            ':description' => $this->description
        ];

        return $this->exec_query($query, $params);
    }

    // Método para obtener un rol por su ID
    public function get_role_by_id($role_id) {
        $query = "SELECT role_id, roleName, description FROM roles WHERE role_id = :role_id";
        $params = [':role_id' => $role_id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Método para listar todos los roles
    public function list_roles() {
        $query = "SELECT role_id, roleName, description FROM roles";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Método para actualizar un rol
    public function update($role_id) {
        $query = "UPDATE roles SET
            roleName = :roleName,
            description = :description
            WHERE role_id = :role_id";

        $params = [
            ':role_id' => $role_id,
            ':roleName' => $this->role_name,
            ':description' => $this->description
        ];

        return $this->exec_query($query, $params);
    }

    // Método para eliminar un rol
    public function delete($role_id) {
        $query = "DELETE FROM roles WHERE role_id = :role_id";
        $params = [':role_id' => $role_id];

        return $this->exec_query($query, $params);
    }

    // Método para verificar si un rol ya existe
    public function checkRole($role_name, $role_id = null) {
        $query = "SELECT COUNT(*) as total FROM roles WHERE roleName = :roleName";
        $params = [':roleName' => $role_name];

        if ($role_id) {
            $query .= " AND role_id != :role_id";
            $params[':role_id'] = $role_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}
