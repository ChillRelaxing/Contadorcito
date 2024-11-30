<?php
require_once(__DIR__ . '/../../conf/conf.php');

class Role extends Conf {
    public $id;

    public $role_name;

    public $description;

    public function create(){
        $query = "INSERT INTO roles (roleName, description) VALUES (:roleName, :description)";
        $params = [
            ':roleName' => $this->role_name,
            ':description' => $this->description
        ];

        return $this->exec_query($query, $params);
    }

    public function get_role_by_id($id){
        $query = "SELECT id, roleName, description FROM roles WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }


    public function list_roles(){
        $query = "SELECT id, roleName, description FROM roles";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id){
        $query = "UPDATE roles SET
            roleName = :roleName,
            description = :description 
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':roleName' => $this->role_name,
            ':description' => $this->description
        ];

        return $this->exec_query($query,$params);

    }

    public function delete($id){
        $query = "DELETE FROM roles WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query,$params);
    }


    public function checkRole($role_name, $id=null){
        $query = "SELECT COUNT(*) as total FROM roles WHERE roleName = :roleName";
        $params = [':roleName' => $role_name];

        if ($id){
            $query.= " AND id!= :id";
            $params[':id'] = $id;
        }

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }

}