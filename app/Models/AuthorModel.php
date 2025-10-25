<?php

namespace App\Models;

use CodeIgniter\Model;

class AuthorModel extends Model
{
    protected $table            = 'Autor';
    protected $primaryKey       = 'CodAu';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Nome'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = false;

    // Validation
    protected $validationRules      = [
        'Nome' => 'required|min_length[1]|max_length[40]|is_unique[Autor.Nome]|regex_match[/^[a-zA-ZÀ-ÿ\s]+$/]',
    ];
    protected $validationMessages   = [
        'Nome' => [
            'required' => 'Nome do autor é obrigatório.',
            'min_length' => 'Nome do autor deve ter pelo menos 1 caractere.',
            'max_length' => 'Nome do autor não pode exceder 40 caracteres.',
            'is_unique' => 'Este autor já existe.',
            'regex_match' => 'Nome do autor contém caracteres inválidos.',
        ],
    ];
    protected $skipValidation       = false;
    protected $cleanValidationRules = true;

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
