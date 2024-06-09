<?php

class M_Boat
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllBoat()
    {
        $sql = "SELECT * FROM tb_boat ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getAllBoatEnable()
    {
        $sql = "SELECT * FROM tb_boat WHERE boatStatus = 'enable' ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getImg($tb, $id)
    {
        $sql = "SELECT * FROM tb_images WHERE imgTable = '$tb' AND imgTableID ='$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function imgBoat($id)
    {
        $sql = "SELECT * FROM tb_images WHERE imgTableID = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function first()
    {
        $sql = "SELECT id FROM tb_boat ORDER BY id DESC limit 1";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function getBoat($id)
    {
        $sql = "SELECT * FROM tb_boat WHERE id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function create_boat($data, $img)
    {

        $captain     = htmlspecialchars(trim(implode(',', $data['boatCap'])), ENT_QUOTES);
        $crew        = htmlspecialchars(trim(implode(',', $data['boatCrew'])), ENT_QUOTES);
        $boatName    = htmlspecialchars(trim($data['boatName']), ENT_QUOTES);
        $boatSeat    = htmlspecialchars(trim($data['boatSeat']), ENT_QUOTES);
        $boatSeatVIP = htmlspecialchars(trim($data['boatSeatVIP']), ENT_QUOTES);
        $boatSpec    = htmlspecialchars(trim($data['boatSpec']), ENT_QUOTES);
        $boatStatus  = htmlspecialchars(trim($data['boatStatus']), ENT_QUOTES);


        $sql = "INSERT INTO tb_boat(boatName, boatSeat,boatSeatVIP, boatSpec, boatStatus, captain, crew ,  createBy) VALUES (:boatName,:boatSeat,:boatSeatVIP,:boatSpec,:boatStatus, :captain, :crew,:createBy)";

        $this->db->query($sql);
        $this->db->bind('boatName', $boatName);
        $this->db->bind('boatSeat', $boatSeat);
        $this->db->bind('boatSeatVIP', $boatSeatVIP);
        $this->db->bind('boatSpec', $boatSpec);
        $this->db->bind('boatStatus', $boatStatus);
        $this->db->bind('captain', $captain);
        $this->db->bind('crew', $crew);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();

        $boat = $this->first();

        $sql = "INSERT INTO tb_images(imgTable, imgTableID, imgDirectory, imgName, imgFormat, createBy) VALUES (:imgTable, :imgTableID, :imgDirectory, :imgName, :imgFormat, :createBy)";

        $this->db->query($sql);
        $this->db->bind('imgTable', 'tb_boat');
        $this->db->bind('imgTableID', $boat['id']);
        $this->db->bind('imgDirectory', $img['imgDirectory']);
        $this->db->bind('imgName', $img['name']);
        $this->db->bind('imgFormat', $img['ext']);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();

        return $this->db->rowCount();
    }

    public function edit_boat($data, $img)
    {

        $captain     = htmlspecialchars(trim(implode(',', array_filter($data['boatCap'], 'strlen'))), ENT_QUOTES);
        $crew        = htmlspecialchars(trim(implode(',', array_filter($data['boatCrew'], 'strlen'))), ENT_QUOTES);
        $id         = $_POST['id'];
        $boatName   = htmlspecialchars(trim($data['boatName']), ENT_QUOTES);
        $boatSeat   = htmlspecialchars(trim($data['boatSeat']), ENT_QUOTES);
        $boatSpec   = htmlspecialchars(trim($data['boatSpec']), ENT_QUOTES);
        $boatSeatVIP = htmlspecialchars(trim($data['boatSeatVIP']), ENT_QUOTES);
        $boatStatus = htmlspecialchars(trim($data['boatStatus']), ENT_QUOTES);

        $sql = "UPDATE tb_boat SET boatName=:boatName,boatSeat=:boatSeat,boatSeatVIP=:boatSeatVIP,boatSpec=:boatSpec,boatStatus=:boatStatus,captain=:captain, crew=:crew,lastUpdateBy=:lastUpdateBy WHERE id=$id";

        $this->db->query($sql);
        $this->db->bind('boatName', $boatName);
        $this->db->bind('boatSeat', $boatSeat);
        $this->db->bind('boatSeatVIP', $boatSeatVIP);
        $this->db->bind('boatSpec', $boatSpec);
        $this->db->bind('captain', $captain);
        $this->db->bind('crew', $crew);
        $this->db->bind('boatStatus', $boatStatus);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();

        if ($img !== "") {
            $sql2 = "UPDATE tb_images SET imgDirectory=:imgDirectory, imgName=:imgName, imgFormat=:imgFormat, lastUpdateBy=:lastUpdateBy WHERE imgTableID = '$id' AND imgTable = 'tb_boat'";

            $this->db->query($sql2);
            $this->db->bind('imgDirectory', $img['imgDirectory']);
            $this->db->bind('imgName', $img['name']);
            $this->db->bind('imgFormat', $img['ext']);
            $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
            $this->db->execute();
        }


        return $this->db->rowCount();
    }

    public function deleteIMG($tb, $id)
    {
        $sql = "DELETE FROM tb_images WHERE imgTable='$tb' AND imgTableID='$id'";
        $this->db->query($sql);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function getDisableSeat($seat, $boat)
    {
        $sql = "SELECT * FROM tb_seatDisable WHERE seat = '$seat' AND id_boat ='$boat'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function disable_seat($seat, $boat)
    {
        $sql = "SELECT * FROM tb_seatDisable WHERE id_boat = '$boat' AND seat = '$seat'";
        $this->db->query($sql);
        $data = $this->db->single();

        if ($data) {
            $sql = "DELETE FROM tb_seatDisable WHERE id =:id";
            $this->db->query($sql);
            $this->db->bind('id', $data['id']);
            $this->db->execute();
            return $this->db->rowCount();
        } else {
            $sql = "INSERT INTO tb_seatDisable(seat, id_boat) VALUES (:seat, :id_boat)";
            $this->db->query($sql);
            $this->db->bind('seat', $seat);
            $this->db->bind('id_boat', $boat);
            $this->db->execute();
            return $this->db->rowCount();
        }
    }
}
