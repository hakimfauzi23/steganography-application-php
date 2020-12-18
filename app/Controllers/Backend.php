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
    echo view('dashboard/dashboard');
    echo view('layouts/footer');
    }

    public function stego()
    {
        echo view('layouts/header');
        echo view('layouts/navbar');
        echo view('layouts/sidebar');
        echo view('stego/index');
        echo view('layouts/footer');
     
    }
}