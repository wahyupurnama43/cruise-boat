<?php

class M_Transport
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllTR()
    {
        $sql = "SELECT * FROM tb_transport ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function getTransport($id)
    {
        $sql = "SELECT * FROM tb_transport WHERE id = '$id'";
        $this->db->query($sql);
        return $this->db->single();
    }

    public function create_transport($data)
    {

        $transpotName = htmlspecialchars(trim($data['transpotName']), ENT_QUOTES);
        $zone         = htmlspecialchars(trim($data['zone']), ENT_QUOTES);
        $price        = htmlspecialchars(trim($data['price']), ENT_QUOTES);
        $status       = htmlspecialchars(trim($data['status']), ENT_QUOTES);

        $sql = "INSERT INTO tb_transport(transpotName, zone, price, status, createBy) VALUES (:transpotName, :zone, :price, :status, :createBy)";

        $this->db->query($sql);
        $this->db->bind('transpotName', $transpotName);
        $this->db->bind('zone', $zone);
        $this->db->bind('price', $price);
        $this->db->bind('status', $status);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }


    public function edit_transport($data)
    {
        $id           = $_POST['id'];
        $transpotName = htmlspecialchars(trim($data['transpotName']), ENT_QUOTES);
        $zone         = htmlspecialchars(trim($data['zone']), ENT_QUOTES);
        $price        = htmlspecialchars(trim($data['price']), ENT_QUOTES);
        $status       = htmlspecialchars(trim($data['status']), ENT_QUOTES);

        $sql = "UPDATE tb_transport SET transpotName=:transpotName, zone=:zone, price=:price, status=:status, lastUpdateBy=:lastUpdateBy WHERE id = $id";

        $this->db->query($sql);
        $this->db->bind('transpotName', $transpotName);
        $this->db->bind('zone', $zone);
        $this->db->bind('price', $price);
        $this->db->bind('status', $status);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}