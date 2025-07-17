<?php

namespace App\Helpers;

use Exception;

class EncryptHelper
{
    public static function encrypt ($value) {
        $encrypKey = config('encrypt')['key_crypt'];
        $encrypMethod = config('encrypt')['method_crypt'];
        $encryptVi = config('encrypt')['vi_crypt'];

        return openssl_encrypt($value, $encrypMethod, $encrypKey, 0, $encryptVi);
    }
    public static function decrypt ($value) {
        $encrypKey = config('encrypt')['key_crypt'];
        $encrypMethod = config('encrypt')['method_crypt'];
        $encryptVi = config('encrypt')['vi_crypt'];

        return openssl_decrypt($value, $encrypMethod, $encrypKey, 0, $encryptVi);
    }
}
