<?php


class Change_password extends Controller
{
     public function index($id)
     {
          $datas = $this->model('M_User')->getUserBy('tb_user', 'userID', $id);

          if (isset($_SESSION['session_status']) && $_SESSION['session_status'] == 'true') {
               session_destroy();
          }

          if ($datas['changepass_status'] == 0) {

               if (isset($_POST['submit'])) {
                    if ($_POST['confirm_password'] == $_POST['password']) {
                         $status = $this->model('M_User')->change_password($id, $_POST);
                         if ($status > 0) {
                              Flasher::setFlash('Password Change', 'success');
                              header('Location: ' . BASEURL . '/login');
                         } else {
                              Flasher::setFlash('Access Denied', 'error');
                              header('Location: ' . BASEURL . '/login');
                         }
                    }
               } else {
                    $this->view('hompage/change_pass');
               }
          } else {
               Flasher::setFlash('Access Denied', 'error');
               header('Location: ' . BASEURL . '/login');
          }
     }
}
