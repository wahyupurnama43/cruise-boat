<?php

class Staff extends Controller
{

     public function __construct()
     {
          $this->mail = new PHPMailer\PHPMailer\PHPMailer();
     }

     public function index()
     {
          $data['nm_code']     = $this->model('M_Home')->getCountry();
          $data['schedule']    = $this->model('M_Home')->getsFrom();
          $data['Allschedule'] = $this->model('M_Schedule')->getAllSC();
          $data['boat']        = $this->model('M_Boat')->getAllBoatEnable();
          $data['transport']   = $this->model('M_Transport')->getAllTR();

          if ($_SESSION['session_status'] == 'true' && ($_SESSION['session_login_grade'] == 'staff' || $_SESSION['session_login_grade'] == "administrator")) {
               $sessionId = $_SESSION['session_login_token'];
               $data['user']    = $this->model('M_User')->getUserBy('tb_user', 'id', $sessionId);
               $this->view('hompage/homeStaff', $data);
          }
     }
}
