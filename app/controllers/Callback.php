<?php

class Callback extends Controller
{
	public function __construct()
	{
		$this->mail = new PHPMailer\PHPMailer\PHPMailer();
	}

	public function index()
	{

		if ($_POST['status'] == 'berhasil') {
			$data['inv']    = $_POST['sid'];
			$data['status'] = $_POST['status'];
			$this->model('M_Home')->callback($data);
			// $this->model('M_Home')->loadForCreateImg();
			// $this->model('M_Home')->tesCallback($data);
		}
	}

	public function success()
	{
		$this->view('hompage/success');
	}

	public function error()
	{
		$this->view('hompage/error');
	}
}
