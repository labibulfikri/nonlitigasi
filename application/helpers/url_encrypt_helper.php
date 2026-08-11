<?php
defined('BASEPATH') or exit('No direct script access allowed');

if (!function_exists('encrypt_url')) {
    function encrypt_url($id)
    {
        $CI = &get_instance();
        $CI->load->library('encryption');
        $encrypted = $CI->encryption->encrypt($id);
        // Ubah karakter agar aman digunakan di URL
        return str_replace(array('+', '/', '='), array('-', '_', '~'), base64_encode($encrypted));
    }
}

if (!function_exists('decrypt_url')) {
    function decrypt_url($string)
    {
        $CI = &get_instance();
        $CI->load->library('encryption');
        $decoded = base64_decode(str_replace(array('-', '_', '~'), array('+', '/', '='), $string));
        return $CI->encryption->decrypt($decoded);
    }
}
