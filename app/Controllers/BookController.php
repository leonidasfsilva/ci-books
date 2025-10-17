<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\BookModel;
use App\Models\AuthorModel;
use App\Models\SubjectModel;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Database\ConnectionInterface;

class BookController extends BaseController
{
    protected $bookModel;
    protected $authorModel;
    protected $subjectModel;
    protected $db;

    public function __construct()
    {
        $this->bookModel = new BookModel();
        $this->authorModel = new AuthorModel();
        $this->subjectModel = new SubjectModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Gerenciar Livros',
            'books' => $this->bookModel->getBooksWithRelations(),
            'authors' => $this->authorModel->orderBy('Nome')->findAll(),
            'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
        ];

        return view('books/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Adicionar Livro',
            'authors' => $this->authorModel->orderBy('Nome')->findAll(),
            'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'titulo' => 'required|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
                'editora' => 'permit_empty|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
                'edicao' => 'required|integer|greater_than[0]',
                'ano_publicacao' => 'permit_empty|exact_length[4]|regex_match[/^\d{4}$/]',
                'valor' => 'required|decimal|greater_than[0]',
                'authors' => 'required',
            ];

            if ($this->validate($rules)) {
                $this->db->transStart();

                try {
                    $bookData = [
                        'Titulo' => $this->request->getPost('titulo'),
                        'Editora' => $this->request->getPost('editora') ?: null,
                        'Edicao' => $this->request->getPost('edicao') ?: null,
                        'AnoPublicacao' => $this->request->getPost('ano_publicacao') ?: null,
                        'Valor' => $this->request->getPost('valor'),
                    ];

                    $bookId = $this->bookModel->insert($bookData);

                    // Associate authors
                    $authors = $this->request->getPost('authors') ?? [];
                    foreach ($authors as $authorId) {
                        $this->db->table('Livro_Autor')->insert([
                            'Livro_CodL' => $bookId,
                            'Autor_CodAu' => $authorId,
                        ]);
                    }

                    // Associate subjects
                    $subjects = $this->request->getPost('subjects') ?? [];
                    foreach ($subjects as $subjectId) {
                        $this->db->table('Livro_Assunto')->insert([
                            'Livro_CodL' => $bookId,
                            'Assunto_codAs' => $subjectId,
                        ]);
                    }

                    $this->db->transComplete();

                    return redirect()->to('/books')->with('success', 'Livro criado com sucesso!');
                } catch (\Exception $e) {
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('error', 'Erro ao criar livro: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('books/index', $data);
    }

    public function edit($id)
    {
        $book = $this->bookModel->getBookWithRelations($id);
        if (!$book) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound();
        }

        $data = [
            'title' => 'Editar Livro',
            'book' => $book,
            'authors' => $this->authorModel->orderBy('Nome')->findAll(),
            'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
        ];

        if ($this->request->getMethod() === 'post') {
            $rules = [
                'titulo' => 'required|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
                'editora' => 'permit_empty|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
                'edicao' => 'permit_empty|integer|greater_than[0]',
                'ano_publicacao' => 'permit_empty|exact_length[4]|regex_match[/^\d{4}$/]',
                'valor' => 'required|decimal|greater_than[0]',
                'authors' => 'required',
            ];

            if ($this->validate($rules)) {
                $this->db->transStart();

                try {
                    $bookData = [
                        'Titulo' => $this->request->getPost('titulo'),
                        'Editora' => $this->request->getPost('editora') ?: null,
                        'Edicao' => $this->request->getPost('edicao') ?: null,
                        'AnoPublicacao' => $this->request->getPost('ano_publicacao') ?: null,
                        'Valor' => $this->request->getPost('valor'),
                    ];

                    $this->bookModel->update($id, $bookData);

                    // Remove existing associations
                    $this->db->table('Livro_Autor')->where('Livro_CodL', $id)->delete();
                    $this->db->table('Livro_Assunto')->where('Livro_CodL', $id)->delete();

                    // Add new associations
                    $authors = $this->request->getPost('authors') ?? [];
                    foreach ($authors as $authorId) {
                        $this->db->table('Livro_Autor')->insert([
                            'Livro_CodL' => $id,
                            'Autor_CodAu' => $authorId,
                        ]);
                    }

                    $subjects = $this->request->getPost('subjects') ?? [];
                    foreach ($subjects as $subjectId) {
                        $this->db->table('Livro_Assunto')->insert([
                            'Livro_CodL' => $id,
                            'Assunto_codAs' => $subjectId,
                        ]);
                    }

                    $this->db->transComplete();

                    return redirect()->to('/books')->with('success', 'Livro atualizado com sucesso!');
                } catch (\Exception $e) {
                    $this->db->transRollback();
                    return redirect()->back()->withInput()->with('error', 'Erro ao atualizar livro: ' . $e->getMessage());
                }
            } else {
                return redirect()->back()->withInput()->with('errors', $this->validator->getErrors());
            }
        }

        return view('books/index', $data);
    }

    public function delete($id)
    {
        try {
            $this->bookModel->delete($id);
            return redirect()->to('/books')->with('success', 'Livro excluído com sucesso!');
        } catch (\Exception $e) {
            return redirect()->to('/books')->with('error', 'Erro ao excluir livro: ' . $e->getMessage());
        }
    }

    public function getBook($id)
    {
        $book = $this->bookModel->getBookWithRelations($id);
        if (!$book) {
            return $this->response->setStatusCode(404)->setJSON(['error' => 'Book not found']);
        }

        return $this->response->setJSON($book);
    }

    public function errorMsg()
    {
        // Método de teste para validação de mensagens de erro
        // Suporta tanto GET (para visualização) quanto POST (para validação)

        if ($this->request->getMethod() === 'GET') {
            // Retorna a view da listagem de livros para teste
            $data = [
                'title' => 'Teste de Validação - Gerenciar Livros',
                'books' => $this->bookModel->getBooksWithRelations(),
                'authors' => $this->authorModel->orderBy('Nome')->findAll(),
                'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
                'endpoint_info' => [
                    'endpoint' => 'errorMsg',
                    'description' => 'Endpoint de teste para validação de mensagens de erro',
                    'methods' => ['GET', 'POST'],
                    'usage' => [
                        'GET' => 'Retorna a view da listagem de livros para teste',
                        'POST' => 'Envia dados para validação e retorna erros se houver'
                    ],
                    'required_fields' => [
                        'titulo', 'editora', 'edicao', 'ano_publicacao', 'valor', 'authors', 'subjects'
                    ]
                ]
            ];

            return view('books/index', $data);
        }

        // Método POST - valida os dados
        $rules = [
            'titulo' => 'required|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
            'editora' => 'required|min_length[1]|max_length[40]|regex_match[/^[a-zA-ZÀ-ÿ0-9\s\-.\'&]+$/]',
            'edicao' => 'required|integer|greater_than[0]',
            'ano_publicacao' => 'required|exact_length[4]|regex_match[/^\d{4}$/]',
            'valor' => 'required|decimal|greater_than[0]',
            'authors' => 'required',
            'subjects' => 'required',
        ];

        if (!$this->validate($rules)) {
            $errors = $this->validator->getErrors();

            // Return to the view with errors instead of JSON
            return redirect()->back()->withInput()->with('errors', $errors);
        }

        return $this->response->setJSON([
            'success' => true,
            'message' => 'Validação passou!'
        ]);
    }

    public function successMsg()
    {
        // Método de teste para mensagens de sucesso
        // Suporta tanto GET (para visualização) quanto POST (para simular sucesso)

        if ($this->request->getMethod() === 'GET') {
            // Retorna a view da listagem de livros para teste
            $data = [
                'title' => 'Teste de Mensagens de Sucesso - Gerenciar Livros',
                'books' => $this->bookModel->getBooksWithRelations(),
                'authors' => $this->authorModel->orderBy('Nome')->findAll(),
                'subjects' => $this->subjectModel->orderBy('Descricao')->findAll(),
                'endpoint_info' => [
                    'endpoint' => 'successMsg',
                    'description' => 'Endpoint de teste para mensagens de sucesso',
                    'methods' => ['GET', 'POST'],
                    'usage' => [
                        'GET' => 'Retorna a view da listagem de livros para teste',
                        'POST' => 'Simula operação bem-sucedida e retorna mensagem de sucesso'
                    ]
                ]
            ];

            return view('books/index', $data);
        }

        // Método POST - simula uma operação bem-sucedida
        // Em um cenário real, aqui seria a lógica de salvar dados

        // Simula processamento bem-sucedido
        return redirect()->back()->with('success', 'Operação realizada com sucesso! Dados foram salvos corretamente.');
    }
}
