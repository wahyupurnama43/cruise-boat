<?php

class M_Mitra
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function getAllUser()
    {
        $sql = "SELECT * FROM tb_mitra ORDER BY dateCreate DESC";
        $this->db->query($sql);
        return $this->db->resultSet();
    }

    public function create_mitra($data)
    {


        $name_p         = htmlspecialchars(trim($data['name_p']), ENT_QUOTES);
        $wa_p           = htmlspecialchars(trim($data['wa_p']), ENT_QUOTES);
        $nama_lengkap   = htmlspecialchars(trim($data['nama_lengkap']), ENT_QUOTES);
        $alamat_p       = htmlspecialchars(trim($data['alamat_p']), ENT_QUOTES);
        $alamat_website = htmlspecialchars(trim(($data['alamat_website'])), ENT_QUOTES);
        $sosmed         = htmlspecialchars(trim($data['sosmed']), ENT_QUOTES);

        $sql = "INSERT INTO tb_mitra(companyName, companyPhone, pic,companyWeb, companySosmed,companyAddress ,createBy) VALUES (:companyName, :companyPhone, :pic, :companyWeb, :companySosmed,:companyAddress  ,:createBy)";

        $this->db->query($sql);
        $this->db->bind('companyName', $name_p);
        $this->db->bind('companyPhone', $wa_p);
        $this->db->bind('pic', $nama_lengkap);
        $this->db->bind('companyWeb', $alamat_website);
        $this->db->bind('companySosmed', $sosmed);
        $this->db->bind('companyAddress', $alamat_p);
        $this->db->bind('createBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }

    public function edit_mitra($data)
    {

        $id             = $_POST['id'];
        $name_p         = htmlspecialchars(trim($data['name_p']), ENT_QUOTES);
        $wa_p           = htmlspecialchars(trim($data['wa_p']), ENT_QUOTES);
        $nama_lengkap   = htmlspecialchars(trim($data['nama_lengkap']), ENT_QUOTES);
        $alamat_p       = htmlspecialchars(trim($data['alamat_p']), ENT_QUOTES);
        $alamat_website = htmlspecialchars(trim(($data['alamat_website'])), ENT_QUOTES);
        $sosmed         = htmlspecialchars(trim($data['sosmed']), ENT_QUOTES);

        $sql        = "UPDATE tb_mitra SET companyName=:companyName, companyPhone=:companyPhone, pic=:pic,companyWeb=:companyWeb,companySosmed=:companySosmed,companyAddress=:companyAddress,lastUpdateBy=:lastUpdateBy WHERE id = $id";
        $this->db->query($sql);
        $this->db->bind('companyName', $name_p);
        $this->db->bind('pic', $nama_lengkap);
        $this->db->bind('companyPhone', $wa_p);
        $this->db->bind('companyWeb', $alamat_website);
        $this->db->bind('companySosmed', $sosmed);
        $this->db->bind('companyAddress', $alamat_p);
        $this->db->bind('lastUpdateBy', $_SESSION['session_login_id']);
        $this->db->execute();
        return $this->db->rowCount();
    }
}
