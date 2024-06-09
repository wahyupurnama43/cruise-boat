<?php

class M_Report
{
    private $db;
    public function __construct()
    {
        date_default_timezone_set('Asia/Ujung_Pandang');
        $this->db = new Database();
    }

    public function getReportBook($date_start, $date_end, $user, $pay, $status)
    {
        $start = (string)$date_start . " 00:00:00";
        $end = (string)$date_end . " 23:59:59";

        $sql_search = "";

        if ($user !== 'all') {
            if ($user != "guest") {
                $sql_search = $sql_search . " AND grade = '" . $user . "'";
            } elseif ($user == "guest") {
                $sql_search = $sql_search . " AND tb_book.createBy = '0'";
            }
        }

        if ($status !== 'all') {
            $sql_search = $sql_search . " AND tb_payment.status = '" . $status . "'";
        }

        if ($pay !== 'all') {
            if ($pay == "va") {
                $sql_search =  $sql_search . " AND model != 'cc' AND model != 'ots' AND model != 'later' AND model != 'qris_manual' AND model != 'cash'  AND model != 'bon'  AND model != 'edc' ";
            } else {
                $sql_search =  $sql_search . " AND model = '" . $pay . "'";
            }
        }

        $sql = "SELECT tb_book.*, tb_book.emailBook as user_agent ,tb_payment.orderNum,tb_payment.status as Pstatus ,type, nominal, tb_book.createBy, grade, (SELECT grade FROM tb_user WHERE tb_user.userID = tb_book.emailBook  ) as relat_agent FROM tb_book LEFT JOIN tb_payment ON tb_book.id=tb_payment.bookID LEFT JOIN tb_user ON tb_book.createBy=tb_user.userID  WHERE tb_book.dateCreate >= '" . $start . "' AND tb_book.dateCreate <= '" . $end . "' " . $sql_search . " ORDER BY tb_book.dateCreate DESC;";

        $this->db->query($sql);
        // return $sql;

        return $this->db->resultSet();
    }

    public function getReportAgent($date_start, $date_end, $user, $pay)
    {
        $start = (string)$date_start . " 00:00:00";
        $end   = (string)$date_end . " 23:59:59";

        $sql_search = "";


        if ($user !== 'all') {
            if ($user != "guest" && $user != "administrator"  && $user != "staff" && $user != "syahbandar" && $user != "accounting") {
                $sql_search = $sql_search . " AND tb_book.emailBook = '" . $user . "' AND tb_book.createBy != '0'";
            } else if ($user == "guest" && $user == "administrator"  && $user == "staff" && $user == "syahbandar" && $user == "accounting") {
                $sql_search = $sql_search . " AND tb_book.createBy = '" . $user . "'";
            } elseif ($user == "guest") {
                $sql_search = $sql_search . " AND tb_book.createBy = '0'";
            }
        } else {
            $sql_search = $sql_search . " AND grade != 'administrator' AND grade != 'syahbandar'  AND grade != 'accounting' ";
        }

        if ($pay !== 'all') {
            if ($pay == "va") {
                $sql_search =  $sql_search . " AND model != 'cc' AND model != 'ots' AND model != 'later' AND model != 'qris_manual' AND model != 'cash'  AND model != 'bon'  AND model != 'edc' ";
            } else {
                $sql_search =  $sql_search . " AND model = '" . $pay . "'";
            }
        }

        $sql = "SELECT tb_book.*, tb_payment.orderNum,tb_payment.status as Pstatus ,type, nominal, tb_book.createBy, grade FROM tb_book LEFT JOIN tb_payment ON tb_book.id=tb_payment.bookID LEFT JOIN tb_user ON tb_book.createBy=tb_user.userID  WHERE tb_book.dateCreate >= '" . $start . "' AND tb_book.dateCreate <= '" . $end . "' " . $sql_search . " AND tb_payment.status != 'expired' ORDER BY tb_book.dateCreate DESC;";
        $this->db->query($sql);
        // return $sql;

        return $this->db->resultSet();
    }


    public function setStatus($s)
    {
        if ($s == 'waiting') {
            $status = "danger";
            $text = "Waiting Payment";
        } elseif ($s == 'paid') {
            $status = "success";
            $text = "Paid";
        } elseif ($s == 'expired') {
            $status = "secondary";
            $text = "Expired";
        }

        $data['status'] = $status;
        $data['text'] = $text;
        return $data;
    }

    public function getReportMitra($date, $scheduleID)
    {

        $search_date = date('Y-m-d', strtotime($date));

        $sql_search = "";

        $sql = "SELECT tb_manifest.* FROM tb_manifest 
                LEFT JOIN tb_book ON tb_book.id = tb_manifest.bookID 
                WHERE tb_book.depart = '" . $search_date . "' AND tb_manifest.scheduleID = '" . $scheduleID . "'
                UNION
                SELECT tb_manifest.* FROM tb_manifest 
                LEFT JOIN tb_book ON tb_book.id = tb_manifest.bookID 
                WHERE tb_book.datereturn = '" . $search_date . "' AND tb_manifest.scheduleID = '" . $scheduleID . "' ORDER BY seatNumber ASC";

        $this->db->query($sql);
        // return $sql;

        return $this->db->resultSet();
    }

    public function getUserBy()
    {
        $sql   = "SELECT * FROM tb_user INNER JOIN tb_mitra ON tb_mitra.id = tb_user.mitraCompanyID WHERE grade = 'verified_agent'";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getAgentBy()
    {
        $sql   = "SELECT * FROM tb_user INNER JOIN tb_mitra ON tb_mitra.id = tb_user.mitraCompanyID";
        $this->db->query($sql);
        return $this->db->resultSet();
    }


    public function getManifest($id)
    {
        $sql   = "SELECT * FROM tb_manifest WHERE bookID = '$id' AND seatReturn = 0 ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSchedule($dateid)
    {
        $sql   = "SELECT * FROM tb_schedule WHERE id = '$dateid'";
        $this->db->query($sql);
        return $this->db->single();
    }
}
