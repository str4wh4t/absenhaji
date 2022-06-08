<?php
use Orm\User;

function generate_activation_code()
{
    $activation_code = rand(10000,99999);
    $user_list = User::where(['stts' => 0, 'activation_code' => $activation_code])->get();
    if ($user_list->isNotEmpty()) {
        $activation_code = generate_activation_code();
    }
    return $activation_code;
}