<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class Backend extends BaseController
{
    public function index()
    {
    echo view('layouts/header');
    echo view('layouts/navbar');
    echo view('layouts/sidebar');
    echo view('layouts/content');
    echo view('layouts/footer');
    }
}