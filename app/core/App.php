<?php

class App
{
	protected $controller = 'Home';
	protected $method = 'index';
	protected $params = [];

	public function __construct()
	{
		$url = $this->parseURL();

		// controller
		// mengecek apakah ada file di controller sesuai di url
		if (isset($url[0]) && file_exists('app/controllers/' . ucwords(strtolower($url[0])) . '.php')) {
			//apakah ada file home.php(dari url) di folder controllers maka timpa dia dengan controller baru
			$this->controller = ucwords(strtolower($url[0]));
			unset($url[0]);
		}
		require_once 'app/controllers/' . $this->controller . '.php';
		$this->controller = new $this->controller;



		// method
		if (isset($url[1])) {
			// mengecek apakah method ada dalam controller
			if (method_exists($this->controller, $url[1])) {
				$this->method = $url[1];
				unset($url[1]);
			}
		}
		//prams
		if (!empty($url)) {
			$this->params = array_values($url);
		}
		//jalankan controller dan method dan kirimkan parameter jika ada
		call_user_func_array([$this->controller, $this->method], $this->params);
	}

	public function parseURL()
	{
		if (isset($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			return $url;
		}
	}
}
