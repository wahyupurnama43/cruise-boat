<?php

class M_Schedule
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllSC()
    {
        $sql = "SELECT tb_schedule.*,tb_boat.boatName FROM tb_schedule INNER JOIN tb_boat ON tb_schedule.boatID = tb_boat.id ORDER BY tb_schedule.dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getSchedule($id)
    {
        $sql = "SELECT tb_schedule.*, tb_boat.boatName,tb_boat.captain, tb_boat.crew FROM tb_schedule INNER JOIN tb_boat ON tb_schedule.boatID = tb_boat.id WHERE tb_schedule.id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function create_schedule($data)
    {
        $boatID                     = htmlspecialchars(trim($data['boatID']), ENT_QUOTES);
        $sFrom                      = htmlspecialchars(trim($data['sFrom']), ENT_QUOTES);
        $sTo                        = htmlspecialchars(trim($data['sTo']), ENT_QUOTES);
        $sDay                       = htmlspecialchars(trim($data['sDay']), ENT_QUOTES);
        $sTime                      = htmlspecialchars(trim($data['sTime']), ENT_QUOTES);
        $priceDomestic              = htmlspecialchars(trim($data['priceDomestic']), ENT_QUOTES);
        $priceDomesticVIP           = htmlspecialchars(trim($data['priceDomesticVIP']), ENT_QUOTES);
        $priceInternational         = htmlspecialchars(trim($data['priceInternational']), ENT_QUOTES);
        $priceInternationalVIP      = htmlspecialchars(trim($data['priceInternationalVIP']), ENT_QUOTES);
        $childPriceDomestic         = htmlspecialchars(trim($data['childPriceDomestic']), ENT_QUOTES);
        $childPriceDomesticVIP      = htmlspecialchars(trim($data['childPriceDomesticVIP']), ENT_QUOTES);
        $childPriceInternational    = htmlspecialchars(trim($data['childPriceInternational']), ENT_QUOTES);
        $childPriceInternationalVIP = htmlspecialchars(trim($data['childPriceInternationalVIP']), ENT_QUOTES);

        $sql = "INSERT INTO tb_schedule(boatID, sFrom, sTo, sDay, sTime, priceDomestic, priceInternational, priceDomesticVIP, priceInternationalVIP,child_priceDomestic, child_priceInternational, child_priceDomesticVIP, child_priceInternationalVIP,  createBy) VALUES(:boatID,:sFrom,:sTo,:sDay,:sTime,:priceDomestic,:priceInternational,:priceDomesticVIP,:priceInternationalVIP,:child_priceDomestic, :child_priceInternational, :child_priceDomesticVIP, :child_priceInternationalVIP,:createBy)";

        $this->db->query($sql);
        $this->db->bind('boatID', $boatID);
        $this->db->bind('sFrom', $sFrom);
        $this->db->bind('sTo', $sTo);
        $this->db->bind('sDay', $sDay);
        $this->db->bind('sTime', $sTime);
        $this->db->bind('priceDomestic', $priceDomestic);
        $this->db->bind('priceInternational', $priceInternational);
        $this->db->bind('priceDomesticVIP', $priceDomesticVIP);
        $this->db->bind('priceInternationalVIP', $priceInternationalVIP);

        $this->db->bind('child_priceDomestic', $childPriceDomestic);
        $this->db->bind('child_priceInternational', $childPriceInternational);
        $this->db->bind('child_priceDomesticVIP', $childPriceDomesticVIP);
        $this->db->bind('child_priceInternationalVIP', $childPriceInternationalVIP);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function edit_schedule($data)
    {
        $id                    = $_POST['id'];
        $boatID                = htmlspecialchars(trim($data['boatID']), ENT_QUOTES);
        $sFrom                 = htmlspecialchars(trim($data['sFrom']), ENT_QUOTES);
        $sTo                   = htmlspecialchars(trim($data['sTo']), ENT_QUOTES);
        $sDay                  = htmlspecialchars(trim($data['sDay']), ENT_QUOTES);
        $sTime                 = htmlspecialchars(trim($data['sTime']), ENT_QUOTES);
        $priceDomestic         = htmlspecialchars(trim($data['priceDomestic']), ENT_QUOTES);
        $priceDomesticVIP      = htmlspecialchars(trim($data['priceDomesticVIP']), ENT_QUOTES);
        $priceInternational    = htmlspecialchars(trim($data['priceInternational']), ENT_QUOTES);
        $priceInternationalVIP = htmlspecialchars(trim($data['priceInternationalVIP']), ENT_QUOTES);
        $childPriceDomestic         = htmlspecialchars(trim($data['childPriceDomestic']), ENT_QUOTES);
        $childPriceDomesticVIP      = htmlspecialchars(trim($data['childPriceDomesticVIP']), ENT_QUOTES);
        $childPriceInternational    = htmlspecialchars(trim($data['childPriceInternational']), ENT_QUOTES);
        $childPriceInternationalVIP = htmlspecialchars(trim($data['childPriceInternationalVIP']), ENT_QUOTES);

        $sql = "UPDATE tb_schedule SET boatID=:boatID,sFrom=:sFrom,sTo=:sTo,sDay=:sDay,sTime=:sTime,priceDomestic=:priceDomestic,priceInternational=:priceInternational,priceDomesticVIP=:priceDomesticVIP,priceInternationalVIP=:priceInternationalVIP, child_priceDomestic=:child_priceDomestic, child_priceInternational=:child_priceInternational, child_priceDomesticVIP=:child_priceDomesticVIP, child_priceInternationalVIP=:child_priceInternationalVIP,lastUpdateBy=:lastUpdateBy WHERE id = $id";

        $this->db->query($sql);
        $this->db->bind('boatID', $boatID);
        $this->db->bind('sFrom', $sFrom);
        $this->db->bind('sTo', $sTo);
        $this->db->bind('sDay', $sDay);
        $this->db->bind('sTime', $sTime);
        $this->db->bind('priceDomestic', $priceDomestic);
        $this->db->bind('priceInternational', $priceInternational);
        $this->db->bind('priceDomesticVIP', $priceDomesticVIP);
        $this->db->bind('priceInternationalVIP', $priceInternationalVIP);
        $this->db->bind('child_priceDomestic', $childPriceDomestic);
        $this->db->bind('child_priceInternational', $childPriceInternational);
        $this->db->bind('child_priceDomesticVIP', $childPriceDomesticVIP);
        $this->db->bind('child_priceInternationalVIP', $childPriceInternationalVIP);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getSchedulebyDate($date)
    {
        $day = date('l', strtotime($date));

        $sql = "SELECT tb_schedule.*, tb_boat.boatName FROM tb_schedule LEFT JOIN tb_boat ON tb_boat.id=tb_schedule.boatID WHERE sDay LIKE '%" . $day . "%' ORDER BY sTime ASC ";
        $this->db->query($sql);
        return $this->db->resultSet();
    }
}
