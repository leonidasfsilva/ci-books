<?php

namespace App\Models;

use CodeIgniter\Model;

class BookModel extends Model
{
    protected $table            = 'Livro';
    protected $primaryKey       = 'CodL';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields = ['Titulo', 'Editora', 'Edicao', 'AnoPublicacao', 'Valor'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Titulo' => 'required|min_length[1]|max_length[40]|regex_match[/^[a-zA-Z0-9\s\-.\']+$/]',
        'Editora' => 'permit_empty|min_length[1]|max_length[40]|regex_match[/^[a-zA-Z0-9\s\-.\']+$/]',
        'Edicao' => 'permit_empty|integer|greater_than[0]',
        'AnoPublicacao' => 'permit_empty|exact_length[4]|regex_match[/^\d{4}$/]',
        'Valor' => 'required|decimal|greater_than[0]',
    ];
    protected $validationMessages   = [
        'Titulo' => [
            'required' => 'Título é obrigatório.',
            'min_length' => 'Título deve ter pelo menos 1 caractere.',
            'max_length' => 'Título não pode exceder 40 caracteres.',
            'regex_match' => 'Título contém caracteres inválidos.',
        ],
        'Editora' => [
            'min_length' => 'Editora deve ter pelo menos 1 caractere.',
            'max_length' => 'Editora não pode exceder 40 caracteres.',
            'regex_match' => 'Editora contém caracteres inválidos.',
        ],
        'Edicao' => [
            'integer' => 'Edição deve ser um número inteiro.',
            'greater_than' => 'Edição deve ser maior que zero.',
        ],
        'AnoPublicacao' => [
            'exact_length' => 'Ano de publicação deve ter exatamente 4 dígitos.',
            'regex_match' => 'Ano de publicação deve conter apenas números.',
        ],
        'Valor' => [
            'required' => 'Valor é obrigatório.',
            'decimal' => 'Valor deve ser um número decimal.',
            'greater_than' => 'Valor deve ser maior que zero.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;


    /**
     * Get books with authors and subjects
     */
    public function getBooksWithRelations()
    {
        return $this->select('Livro.*, GROUP_CONCAT(DISTINCT a.Nome ORDER BY a.Nome SEPARATOR \', \') AS authors, GROUP_CONCAT(DISTINCT s.Descricao ORDER BY s.Descricao SEPARATOR \', \') AS subjects')
                    ->join('Livro_Autor ba', 'Livro.CodL = ba.Livro_CodL', 'left')
                    ->join('Autor a', 'ba.Autor_CodAu = a.CodAu', 'left')
                    ->join('Livro_Assunto bs', 'Livro.CodL = bs.Livro_CodL', 'left')
                    ->join('Assunto s', 'bs.Assunto_codAs = s.codAs', 'left')
                    ->groupBy('Livro.CodL')
                    ->orderBy('Livro.Titulo')
                    ->findAll();
    }

    /**
     * Get book with authors and subjects by ID
     */
    public function getBookWithRelations($id)
    {
        $book = $this->select('Livro.*')
                     ->where('Livro.CodL', $id)
                     ->first();

        if ($book) {
            $book['authors'] = $this->db->table('Livro_Autor')->select('Autor_CodAu')->where('Livro_CodL', $id)->get()->getResultArray();
            $book['authors'] = array_column($book['authors'], 'Autor_CodAu');
            $book['subjects'] = $this->db->table('Livro_Assunto')->select('Assunto_codAs')->where('Livro_CodL', $id)->get()->getResultArray();
            $book['subjects'] = array_column($book['subjects'], 'Assunto_codAs');
        }

        return $book;
    }

    // Callbacks
    protected $allowCallbacks = true;
    protected $beforeInsert   = [];
    protected $afterInsert    = [];
    protected $beforeUpdate   = [];
    protected $afterUpdate    = [];
    protected $beforeFind     = [];
    protected $afterFind      = [];
    protected $beforeDelete   = [];
    protected $afterDelete    = [];
}
