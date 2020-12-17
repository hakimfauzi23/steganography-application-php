<?php
namespace App\Controllers;
use CodeIgniter\Controller;
class Backend extends BaseController
{
    public function index()
    {
    echo view('backend/landing_page');
    }
}