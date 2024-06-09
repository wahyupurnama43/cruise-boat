<?php

class M_Auth
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function login($data)
    {

        $username = htmlspecialchars(trim($data['username']), ENT_QUOTES);
        $password = htmlspecialchars(trim($data['password']), ENT_QUOTES);

        $sql = "SELECT tb_user.*, tb_grade.grade AS grade_real, tb_grade.akses FROM tb_user LEFT JOIN tb_grade on tb_user.grade = tb_grade.id WHERE userID=:username";
        $this->db->query($sql);
        $this->db->bind('username', $username);
        // $this->db->resultSinggle($username, $password);
        $data = $this->db->single();

        // cek password
        if (password_verify($password, $data['password'])) {
            // password benar
            return $data;
        } else {
            // password salah
            return 0;
        }
    }
}
