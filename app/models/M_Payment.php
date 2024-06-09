<?php
class M_Payment
{
    private $db;
    public function __construct()
    {
        date_default_timezone_set('Asia/Ujung_Pandang');
        $this->db = new Database();
    }

    public function getAllPayment()
    {
        $sql = "SELECT * FROM tb_payment ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getAllPaymentByINV($id)
    {
        $sql = "SELECT * FROM tb_payment WHERE orderNum = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getPaymentbyBooking($id)
    {
        $sql = "SELECT * FROM tb_payment WHERE bookID = '$id' ORDER BY dateCreate DESC LIMIT 1";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function checkExpired()
    {
        $date = date('Y-m-d H:i:s');
        // return $date;
        $sql = "SELECT bookID, type, model FROM `tb_payment` WHERE (dateExpired < '$date' or dateExpired is NULL) AND status = 'waiting'";
        $this->db->query($sql);
        $data = $this->db->resultSet();
        foreach ($data as $d) {

            $id = $d['bookID'];

            if ($d['model'] !== "ots" and $d['model'] !== "later") { // jika bukan on the spot atau pay later
                $sql = "UPDATE tb_payment SET status=:status WHERE bookID = '$id'";
                $this->db->query($sql);
                $this->db->bind('status', 'expired');
                $this->db->execute();

                $sql4 = "UPDATE tb_book SET status=:status WHERE id = '$id'";
                $this->db->query($sql4);
                $this->db->bind('status', '4');
                $this->db->execute();

                $sql3 = "DELETE FROM tb_manifest WHERE bookID = '$id'";
                $this->db->query($sql3);
                $this->db->execute();
                echo $d['model'];
            } else {
                echo $d['model'];
            }
        }
    }
}