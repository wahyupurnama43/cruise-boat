<?php

class M_Setting
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getSetting()
    {
        $sql = "SELECT * FROM tb_setting";
        $this->db->query($sql);
        return $this->db->resultSet();
    }


    public function edit_setting($data, $gambar)
    {

        if ($gambar == '') {
            $value = htmlspecialchars(trim($data['value']), ENT_QUOTES);
        } else {
            $value =  $gambar;
        }

        $name  = htmlspecialchars(trim($data['name']), ENT_QUOTES);
        $id    = htmlspecialchars(trim($data['id']), ENT_QUOTES);

        $sql = "UPDATE tb_setting SET name=:name,value=:value,lastUpdateBy=:lastUpdateBy WHERE id = '$id'";

        $this->db->query($sql);
        $this->db->bind('name', $name);
        $this->db->bind('value', $value);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
