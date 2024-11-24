<?php
require_once(__DIR__ . '/../../conf/conf.php');

class User extends Conf {
    public $user_id;

    public $firstName;

    public $lastName;

    public $userName;

    public $email;

    public $phone;

    public $password;

    public $role_id;
 

    public function get_user($userName, $password){
        $query = "SELECT A.user_id, A.firstName, A.lastName, A.userName, A.email, B.roleName , A.phone, A.role_id FROM users AS A INNER JOIN roles AS B 
        ON A.role_id = B.role_id WHERE A.userName=:userName && A.password=:password";
        $params = [':userName' => $userName,
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
        $query = "SELECT role_id, roleName FROM roles";

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
            ':firstName' => $this->firstName,
            ':lastName' => $this->lastName,
            ':userName' => $this->userName,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':password' => $this->password,
            ':role_id' => $this->role_id
        ];

        return $this->exec_query($query, $params);
    }

    public function get_user_by_id($user_id){
        $query = "SELECT user_id, firstName, lastName, userName, email, phone, role_id FROM users WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }


    public function list_users(){
        $query = "SELECT A.user_id, A.firstName, A.lastName, A.userName, B.roleName, A.email, A.phone, A.created_at, A.updated_at FROM users AS A INNER JOIN roles AS B 
        ON A.role_id = B.role_id";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function update($user_id){
        $query = "UPDATE users SET
            firstName = :firstName,
            lastName = :lastName, 
            userName = :userName,
            email = :email,
            phone = :phone,
            role_id = :role_id
            WHERE user_id = :user_id";

        $params = [
            ':user_id' => $user_id,
            ':firstName' => $this->firstName,
            ':lastName' => $this->lastName,
            ':userName' => $this->userName,
            ':email' => $this->email,
            ':phone' => $this->phone,
            ':role_id' => $this->role_id
        ];

        return $this->exec_query($query,$params);

    }

    public function checkUser($userName, $email, $user_id  = null){
        if ($user_id  == null){
            $query = "SELECT COUNT(*) AS total FROM users WHERE userName = :userName OR email = :email";
            $params = [':userName' => $userName, ':email' => $email];
        } else {
            $query = "SELECT COUNT(*) AS total FROM users WHERE (userName = :userName OR email = :email) AND user_id != :user_id";
            $params = [':userName' => $userName, ':email' => $email, ':user_id' => $user_id];
        }

        $result = $this->exec_query($query, $params);

        if ($result){
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }

    public function delete($user_id){
        $query = "DELETE FROM users WHERE user_id = :user_id";
        $params = [':user_id' => $user_id];

        return $this->exec_query($query,$params);
    }

}
