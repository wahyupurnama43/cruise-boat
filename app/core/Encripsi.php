<?php


class Encripsi
{
    static function encode($encrypt_decrypt, $string)
    {
        $password = 'ZIGNATIC';
        $method = 'aes256';
        $iv = substr(hash('sha256', $password), 0, 16);
        $output = '';
        if ($encrypt_decrypt == 'encrypt') {
            $output = openssl_encrypt($string, $method, $password, 0, $iv);
            $output = base64_encode($output);
        } else if ($encrypt_decrypt == 'decrypt') {
            $output = base64_decode($string);
            $output = openssl_decrypt($output, $method, $password, 0, $iv);
        }
        return $output;
    }
}
