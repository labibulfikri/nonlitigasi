<?php if (!defined("BASEPATH")) exit("No direct script access allowed");


if (!function_exists('get_csrf_token')) {
    function get_csrf_token()
    {
        $ci = get_instance();
        // return '<meta name="csrf-token" content="' . $ci->security->get_csrf_hash() . '">' . PHP_EOL;
        if (!$ci->session->csrf_token) {
            $ci->session->csrf_token = hash('sha1', time());
        }
        return $ci->session->csrf_token;
    }
}



if (!function_exists('get_csrf_name')) {
    function get_csrf_name()
    {
        return "token";
    }
}

function crsf()
{
    return "<input type='hidden' name='" . get_csrf_name() . "' value='" . get_csrf_token() . "' />";
}

function crsf_ajax()
{
    return "<input id='token' type='hidden' name='" . get_csrf_name() . "' value='" . get_csrf_token() . "' />";
}

if (!function_exists('cek_csrf')) {
    function cek_csrf()
    {
        $ci = get_instance();
        if ($ci->input->post('token') != $ci->session->csrf_token or !$ci->input->post('token') or !$ci->session->csrf_token) {
            $ci->session->unset_userdata('csrf_token');
            $ci->output->set_status_header(403);
            show_error('tidak dapat akses halaman ini');
            return false;
        }
    }
}
function encrypt_url($string)
{

    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */
    $security       = parse_ini_file("security.ini");
    $secret_key     = $security["encryption_key"];
    $secret_iv      = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];

    // hash
    $key    = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv     = substr(hash("sha256", $secret_iv), 0, 16);

    //do the encryption given text/string/number
    $result = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
    $output = base64_encode($result);
    return $output;
}



function decrypt_url($string)
{

    $output = false;
    /*
    * read security.ini file & get encryption_key | iv | encryption_mechanism value for generating encryption code
    */

    $security       = parse_ini_file("security.ini");
    $secret_key     = $security["encryption_key"];
    $secret_iv      = $security["iv"];
    $encrypt_method = $security["encryption_mechanism"];

    // hash
    $key    = hash("sha256", $secret_key);

    // iv – encrypt method AES-256-CBC expects 16 bytes – else you will get a warning
    $iv = substr(hash("sha256", $secret_iv), 0, 16);

    //do the decryption given text/string/number

    $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
    return $output;
}
