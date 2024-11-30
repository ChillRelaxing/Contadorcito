<?php
require_once(__DIR__ . '/../../conf/conf.php');

class User extends Conf {
    public $id;

    public $first_name;

    public $last_name;

    public $user_name;

    public $email;

    public $phone;

    public $password;

    public $role_id;
 

    public function get_user($user_name, $password){
        $query = "SELECT A.id, firstName, lastName, userName, email, roleName , phone, role_id FROM users AS A INNER JOIN roles AS B 
        ON A.role_id = B.id WHERE userName=:userName && password=:password";
        $params = [':userName' => $user_name,
                    ':password' => md5($password)
        ];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_roles(){
        $query = "SELECT id, roleName FROM roles";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function create(){
        $query = "INSERT INTO users (firstName, lastName, userName, email, phone, password, role_id) VALUES (:firstName, :lastName, :userName, :email, :phone, :password, :role_id)";
        $params = [
            ':firstName' => $this->first_name,
            ':lastName' => $this->last_name,
            ':userName' => $this->user_name,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':password' => $this->password,
            ':role_id' => $this->role_id
        ];

        return $this->exec_query($query, $params);
    }

    public function get_user_by_id($id){
        $query = "SELECT id, firstName, lastName, userName, email, phone, role_id FROM users WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }


    public function list_users(){
        $query = "SELECT A.id, firstName, lastName, userName, roleName, email, phone, A.created_at, A.updated_at FROM users AS A INNER JOIN roles AS B 
        ON A.role_id = B.id";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($id){
        $query = "UPDATE users SET
            firstName = :firstName,
            lastName = :lastName, 
            userName = :userName,
            email = :email,
            phone = :phone,
            role_id = :role_id
            WHERE id = :id";

        $params = [
            ':id' => $id,
            ':firstName' => $this->first_name,
            ':lastName' => $this->last_name,
            ':userName' => $this->user_name,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':role_id' => $this->role_id
        ];

        return $this->exec_query($query,$params);

    }

    public function checkUser($user_name, $email, $id = null){
        if ($id == null){
            $query = "SELECT COUNT(*) AS total FROM users WHERE userName = :userName OR email = :email";
            $params = [':userName' => $user_name, ':email' => $email];
        } else {
            $query = "SELECT COUNT(*) AS total FROM users WHERE (userName = :userName OR email = :email) AND id != :id";
            $params = [':userName' => $user_name, ':email' => $email, ':id' => $id];
        }

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }

    public function delete($id){
        $query = "DELETE FROM users WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query,$params);
    }

}
