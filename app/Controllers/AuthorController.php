<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\AuthorModel;
use CodeIgniter\HTTP\ResponseInterface;

class AuthorController extends BaseController
{
    protected $authorModel;

    public function __construct()
    {
        $this->authorModel = new AuthorModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gerenciar Autores',
            'authors' => $this->authorModel->orderBy('Nome')->findAll(),
        ];

        return view('authors/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Adicionar Autor',
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'Nome' => 'required|min_length[1]|max_length[40]|is_unique[Autor.Nome]|regex_match[/^[a-zA-Z0-9\s\-.\']+$/]',
            ];

            if ($this->validate($rules)) {
                try {
                    $this->authorModel->insert(['Nome' => $this->request->getPost('Nome')]);
                    return redirect()->to('/authors')->with('success', 'Autor criado com sucesso!');
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Erro ao criar autor: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('authors/create', $data);
    }

    public function edit($id)
    {
        $author = $this->authorModel->find($id);
        if (!$author) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Editar Autor',
            'author' => $author,
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'Nome' => 'required|min_length[1]|max_length[40]|is_unique[Autor.Nome,CodAu,{id}]|regex_match[/^[a-zA-Z0-9\s\-.\']+$/]',
            ];

            if ($this->validate($rules)) {
                try {
                    $this->authorModel->update($id, ['Nome' => $this->request->getPost('Nome')]);
                    return redirect()->to('/authors')->with('success', 'Autor atualizado com sucesso!');
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Erro ao atualizar autor: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('authors/edit', $data);
    }

    public function delete($id)
    {
        try {
            $db = \Config\Database::connect();
            $hasBooks = $db->table('Livro_Autor')
                          ->where('Autor_CodAu', $id)
                          ->countAllResults() > 0;

            if ($hasBooks) {
                return redirect()->to('/authors')->with('error', 'Não é possível excluir este autor pois ele está associado a um ou mais livros.');
            }

            $this->authorModel->delete($id);
            return redirect()->to('/authors')->with('success', 'Autor excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->to('/authors')->with('error', 'Erro ao excluir autor: ' . $e->getMessage());
        }
    }
}
