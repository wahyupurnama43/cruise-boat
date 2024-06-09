<?php

class Login extends Controller
{
    public function index()
    {
        if ($_SESSION['status'] == 'true' or $_SESSION['session_login_token'] !== null or $_SESSION['session_login'] !== null or $_SESSION['session_login_id'] !== null or $_SESSION['session_login_grade'] !== null) {
            header('Location: ' . BASEURL . 'dashboard');
        } else {
            $this->view('dashboard/login');
        }
    }

    public function auth()
    {
        $data = $this->model('M_Auth')->login($_POST);

        if ($data !== 0) {

            // ini update booking yg statusnya 2 dan 5 menjadi finish
            $this->model('M_Book')->updateStatusFinished();

            $_SESSION['session_status'] = 'true';
            $_SESSION['session_login'] = $data['userName'];
            $_SESSION['session_login_id'] = $data['userID'];
            $_SESSION['session_login_token'] = $data['id'];
            $_SESSION['session_login_grade'] = $data['grade'];
            $_SESSION['session_login_grade_real'] = $data['grade_real'];
            $_SESSION['method'] = $data['akses'];

            if ($data['grade'] == 'syahbandar') {
                header('Location: ' . BASEURL . 'dashboard/profile');
            } else {
                header('Location: ' . BASEURL . 'dashboard');
            }
        } else {
            Flasher::setFlash('Mohon Masukkan Username / Password Dengan Benar ', 'error');
            header('Location: ' . BASEURL . 'login');
        }
    }

    public function logout()
    {
        session_unset();
        session_destroy();
        header('Location: ' . BASEURL . 'login');
    }
}
