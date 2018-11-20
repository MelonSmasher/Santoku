<?php
/**
 * Created by PhpStorm.
 * User: melon
 * Date: 11/19/18
 * Time: 11:06 AM
 */

use App\DeploySession;

/**
 * @param int $min_length
 * @param int $max_length
 * @return string
 */
function generateDeploySessionToken($min_length = 8, $max_length = 64)
{
    do {
        $exists = false;
        $length = (int)rand($min_length, $max_length);
        $token = strtoupper(Illuminate\Support\Str::random($length)); // Generate a token with the chosen length
        $token_exist = DeploySession::where('token', $token)->first(); // Get any Models with that token
        if (!empty($token_exist)) $exists = true;
    } while ($exists);
    return $token;
}