<?php

namespace App\Models;

use CodeIgniter\Model;

class SubjectModel extends Model
{
    protected $table            = 'Assunto';
    protected $primaryKey       = 'codAs';
    protected $useAutoIncrement = true;
    protected $returnType       = 'array';
    protected $useSoftDeletes   = false;
    protected $protectFields    = true;
    protected $allowedFields    = ['Descricao'];

    protected bool $allowEmptyInserts = false;

    // Dates
    protected $useTimestamps = true;
    protected $dateFormat    = 'datetime';
    protected $createdField  = 'created_at';
    protected $updatedField  = 'updated_at';
    protected $deletedField  = 'deleted_at';

    // Validation
    protected $validationRules      = [
        'Descricao' => 'required|min_length[1]|max_length[20]|is_unique[Assunto.Descricao,codAs,{codAs}]|regex_match[/^[a-zA-Z0-9\s\-.\']+$/]',
    ];
    protected $validationMessages   = [
        'Descricao' => [
            'required' => 'Descrição do assunto é obrigatória.',
            'min_length' => 'Descrição do assunto deve ter pelo menos 1 caractere.',
            'max_length' => 'Descrição do assunto não pode exceder 20 caracteres.',
            'is_unique' => 'Este assunto já existe.',
            'regex_match' => 'Descrição do assunto contém caracteres inválidos.',
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
