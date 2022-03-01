<?php

use Hidehalo\Nanoid\Client as NanoidClient;
use Illuminate\Validation\ValidationException;

if (!function_exists('remember_for')) {
    /**
     * @param Int $onset Days in integer to remember the cache
     */
    function remember_for($onset)
    {
        $day = 60 * 60 * 24;

        return $day * $onset;
    }
}

if (!function_exists('constants')) {
    /**
     * @param String $key String to get
     */
    function constants($key)
    {
        return config('constants.' . $key);
    }
}

if (!function_exists('encrypter')) {
    /**
     * @param String $action Choose between 'encrypt' or 'decrypt'
     * @param String $data Data to process
     */
    function encrypter($action, $data)
    {
        $string = gettype($data) == 'array' ? json_encode($data) : $data;
        $output = false;
        $encrypt_method = 'AES-256-CBC';
        $secret_key = env('LCS_ENCRYPTER_KEY');
        $secret_iv = env('LCS_ENCRYPTER_IV');

        // hash
        $key = hash('sha256', $secret_key);

        // encrypt method AES-256-CBC expects 16 bytes 
        $iv = substr(hash('sha256', $secret_iv), 0, 16);

        if ($action == 'encrypt') {
            $output = openssl_encrypt($string, $encrypt_method, $key, 0, $iv);
            $output = base64_encode($output);
        } else if ($action == 'decrypt') {
            $output = openssl_decrypt(base64_decode($string), $encrypt_method, $key, 0, $iv);
            $output = json_decode($output, true);
        }

        return $output;
    }
}

if (!function_exists('verify_permission')) {
    /**
     * @param Object $user User who we will validate the permissions to
     * @param Array $permission Permision to validate
     * @param String $message Optional message for denied request
     */
    function verify_permission($user, $permission, $message = 'Access denied. You don\'t have enough permission to proceed.')
    {
        $permissions = $user->role()->permissions->pluck('slug')->toArray();

        if (!in_array($permission[0], $permissions)) {
            throw ValidationException::withMessages([
                'general' => $message,
                'data'    => []
            ]);
        }

        return;
    }
}

if (!function_exists('nanoid')) {
    /**
     * @param Number $size Specify how many character will generate
     */
    function nanoid($size = 21)
    {
        $nanoid = new NanoidClient();

        return $nanoid->generateId($size);
    }
}
