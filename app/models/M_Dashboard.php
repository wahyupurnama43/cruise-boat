<?php


class M_Dashboard
{
    private $db;

    public function __construct()
    {
        date_default_timezone_set('Asia/Ujung_Pandang');
        $this->db = new Database();
    }

    public function getPersentaseBooks()
    {
        $dateKemarin = date('Y-m-d', strtotime('-1 days'));

        if ($_SESSION['session_login_grade'] !== "administrator") {
            $by = "AND createBy = '$_SESSION[session_login_id]'";
        }

        $sql2 = "SELECT * FROM tb_book WHERE dateCreate  < '$dateKemarin' AND dateCreate  > '$dateKemarin' " . $by;
        $this->db->query($sql2);
        $dataKemarin = count($this->db->resultSet());

        $date = date('Y-m-d');
        $sql = "SELECT * FROM tb_book WHERE dateCreate  > '$date' " . $by;
        $this->db->query($sql);
        $data = count($this->db->resultSet());

        $persentase = (($data - $dataKemarin) / ($dataKemarin == 0) ? 1 : $dataKemarin) * 100;
        return $persentase;
    }

    public function getTotal($tb)
    {
        $date = date('Y-m-d');
        if ($_SESSION['session_login_grade'] !== "administrator") {
            $by = "AND createBy = '$_SESSION[session_login_id]'";
        }
        $sql2 = "SELECT * FROM $tb WHERE dateCreate  >= '$date' " . $by;
        $this->db->query($sql2);
        return count($this->db->resultSet());
    }


    public function getTotalUser($tb)
    {


        $sql2 = "SELECT * FROM $tb";
        $this->db->query($sql2);
        return count($this->db->resultSet());
    }

    public function getTotalPrice($tb)
    {

        if ($_SESSION['session_login_grade'] !== "administrator") {
            $by = "WHERE tb_book.createBy = '$_SESSION[session_login_id]'";
        }

        $sql2 = "SELECT sum(nominal) AS totalPrice FROM $tb  INNER JOIN tb_book ON $tb.bookID = tb_book.id  " . $by . " AND tb_payment.status ='paid'";
        $this->db->query($sql2);
        return $this->db->single();
    }


    public function getUser($id)
    {

        $sql2 = "SELECT * FROM tb_user WHERE id = '$id'";
        $this->db->query($sql2);
        return $this->db->single();
    }
}
