<?php
require_once(__DIR__ . '/../../conf/conf.php');

class Report extends Conf {
    public $report_id;
    public $report_type;
    public $start_date;
    public $end_date;
    public $status;
    public $company_id;
    public $user_id;


    public function list_company(){
        $query = "SELECT id, company_name FROM company";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    public function list_users(){
        $query = "SELECT id, firstName FROM users";

        $result = $this->exec_query($query);

        if ($result){
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Crear un nuevo reporte
    public function create() {
        $query = "INSERT INTO reports (report_type, start_date, end_date, status, company_id, user_id) 
                  VALUES (:report_type, :start_date, :end_date, :status, :company_id, :user_id)";
        $params = [
            ':report_type' => $this->report_type,
            ':start_date' => $this->start_date,
            ':end_date' => $this->end_date,
            ':status' => $this->status,
            ':company_id' => $this->company_id,
            ':user_id' => $this->user_id
        ];

        return $this->exec_query($query, $params);
    }

    // Obtener un reporte por ID
    public function get_report_by_id($id) {
        $query = "SELECT id, report_type, start_date, end_date, status, company_id, user_id 
                  FROM reports 
                  WHERE id = :id";
        $params = [':id' => $id];

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Listar todos los reportes
    public function list_reports() {
        $query = "SELECT r.id, r.report_type, r.start_date, r.end_date, r.status, c.company_name, u.firstName 
                  FROM reports r
                  INNER JOIN company c ON r.company_id = c.id
                  INNER JOIN users u ON r.user_id = u.id";

        $result = $this->exec_query($query);

        if ($result) {
            return $result->fetchAll(PDO::FETCH_ASSOC);
        } else {
            return [];
        }
    }

    // Actualizar un reporte
    public function update($id) {
        $query = "UPDATE reports SET
                  report_type = :report_type,
                  start_date = :start_date,
                  end_date = :end_date,
                  status = :status,
                  company_id = :company_id,
                  user_id = :user_id
                  WHERE id = :id";

        $params = [
            ':id' => $id,
            ':report_type' => $this->report_type,
            ':start_date' => $this->start_date,
            ':end_date' => $this->end_date,
            ':status' => $this->status,
            ':company_id' => $this->company_id,
            ':user_id' => $this->user_id
        ];

        return $this->exec_query($query, $params);
    }

    // Eliminar un reporte
    public function delete($id) {
        $query = "DELETE FROM reports WHERE id = :id";
        $params = [':id' => $id];

        return $this->exec_query($query, $params);
    }





    
    // Verificar si existe un reporte en un rango de fechas específico para una compañía
    public function check_report($company_id, $start_date, $end_date, $report_id = null) {
        $query = "SELECT COUNT(*) as total FROM reports 
                  WHERE company_id = :company_id AND start_date = :start_date AND end_date = :end_date";
        $params = [
            ':company_id' => $company_id,
            ':start_date' => $start_date,
            ':end_date' => $end_date
        ];

        if ($report_id) {
            $query .= " AND report_id != :report_id";
            $params[':report_id'] = $report_id;
        }

        $result = $this->exec_query($query, $params);

        if ($result) {
            return $result->fetch(PDO::FETCH_ASSOC)['total'];
        } else {
            return 0;
        }
    }
}