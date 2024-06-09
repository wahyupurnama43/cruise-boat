<?php 

class Url
{
    public static function check()
    {
        if (isset($_GET['url'])) {
			$url = rtrim($_GET['url'], '/');
			$url = filter_var($url, FILTER_SANITIZE_URL);
			$url = explode('/', $url);
			return $url[0];
		}
    }

    public static function checkAll()
    {
         if (isset($_GET['url'])) {
            $url = rtrim($_GET['url'], '/');
            $url = filter_var($url, FILTER_SANITIZE_URL);
            $url = explode('/', $url);
            if (isset($url[1]) && $url[1] !== null) {
                return $url[0] .'/'. $url[1];
            }
            return $url[0];
        }        
    }
}