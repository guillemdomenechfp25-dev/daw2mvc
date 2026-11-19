<?php

class adminController
{

    public function index()
    {
        $view = "admin/admin.php";
        include_once 'views/main.php';
    }
}