<?php


class Flasher
{
    public static function setFlash($pesan, $tipe)
    {
        // if(isset($))
        $_SESSION['flash'] = [
            'pesan' => $pesan,
            'tipe' => $tipe
        ];
    }
    public static function flash($ret_obj = false)
    {
        if (isset($_SESSION['flash'])) {
            $data = $_SESSION['flash'];
            unset($_SESSION['flash']);
            if ($ret_obj) return htmlspecialchars(json_encode($data), ENT_QUOTES,  'UTF-8');
            return $data['aksi'];
        }
    }
}
