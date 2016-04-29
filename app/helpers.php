<?php


use App\User;

function getUserFromToken($token){
    $user = \JWTAuth::setToken($token)->authenticate();
    return $user;
}