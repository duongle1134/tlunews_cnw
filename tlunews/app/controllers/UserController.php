<?php
class UserController
{
    public function login()
    {
        include_once('./app/views/user/login.php');
    }

    public function register()
    {
        include_once('./app/views/user/register.php');
    }

    public function profile()
    {
        include_once('./app/views/user/profile.php');
    }
}
