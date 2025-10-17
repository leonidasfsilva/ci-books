<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\SubjectModel;
use CodeIgniter\HTTP\ResponseInterface;

class SubjectController extends BaseController
{
    protected $subjectModel;

    public function __construct()
    {
        $this->subjectModel = new SubjectModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Gerenciar Assuntos',
            'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
        ];

        return view('subjects/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Adicionar Assunto',
        ];

        if ($this->request->is('post')) {
            $rules = [
                'Descricao' => 'required|min_length[1]|max_length[20]',
            ];

            if ($this->validate($rules)) {
                try {
                    $this->subjectModel->insert(['Descricao' => $this->request->getPost('Descricao')]);
                    return redirect()->to('/subjects')->with('success', 'Assunto criado com sucesso!');
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Erro ao criar assunto: ' . $e->getMessage());
                }
            } else {
                $errors = $this->validator->getErrors();

                // Personalizar mensagens de erro
                $customErrors = [];
                foreach ($errors as $field => $message) {
                    switch ($field) {
                        case 'Descricao':
                            $customErrors[$field] = 'O campo Descrição é obrigatório.';
                            break;
                        default:
                            $customErrors[$field] = $message;
                    }
                }

                return redirect()->back()->withInput()->with('errors', $customErrors);
            }
        }

        return view('subjects/create', $data);
    }

    public function edit($id)
    {
        $subject = $this->subjectModel->find($id);
        if (!$subject) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Editar Assunto',
            'subject' => $subject,
        ];

        if ($this->request->is('post')) {
            $rules = [
                'Descricao' => 'required|min_length[1]|max_length[20]',
            ];

            if ($this->validate($rules)) {
                try {
                    $this->subjectModel->update($id, ['Descricao' => $this->request->getPost('Descricao')]);
                    return redirect()->to('/subjects')->with('success', 'Assunto atualizado com sucesso!');
                } catch (\Exception $e) {
                    return redirect()->back()->withInput()->with('error', 'Erro ao atualizar assunto: ' . $e->getMessage());
                }
            } else {
                $errors = $this->validator->getErrors();

                // Personalizar mensagens de erro
                $customErrors = [];
                foreach ($errors as $field => $message) {
                    switch ($field) {
                        case 'Descricao':
                            $customErrors[$field] = 'O campo Descrição é obrigatório.';
                            break;
                        default:
                            $customErrors[$field] = $message;
                    }
                }

                return redirect()->back()->withInput()->with('errors', $customErrors);
            }
        }

        return view('subjects/edit', $data);
    }

    public function delete($id)
    {
        try {
            $db = \Config\Database::connect();
            $hasBooks = $db->table('Livro_Assunto')
                          ->where('Assunto_codAs', $id)
                          ->countAllResults() > 0;

            if ($hasBooks) {
                return redirect()->to('/subjects')->with('error', 'Não é possível excluir este assunto pois ele está associado a um ou mais livros.');
            }

            $this->subjectModel->delete($id);
            return redirect()->to('/subjects')->with('success', 'Assunto excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->to('/subjects')->with('error', 'Erro ao excluir assunto: ' . $e->getMessage());
        }
    }
}
