<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index()
    {
        $data = [
            'title' => 'Sistema de Cadastro de Livros',
            'php_version' => PHP_VERSION,
            'server_software' => $_SERVER['SERVER_SOFTWARE'] ?? 'Unknown',
            'ci_version' => \CodeIgniter\CodeIgniter::CI_VERSION,
        ];

        return view('home/index', $data);
    }

}
