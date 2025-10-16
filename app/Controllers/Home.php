<?php

namespace App\Controllers;

class Home extends BaseController
{
    public function index(): string
    {
        $data = [
            'title' => 'Sistema de Cadastro de Livros',
        ];

        return view('home/index', $data);
    }
}
