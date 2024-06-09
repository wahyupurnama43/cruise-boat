<?php 
// Baru nih biar lebih rapih dikit
if (! function_exists('base_url')) {
    function base_url($url = '')
    {
        return BASE_URL . "/{$url}";
    }
}