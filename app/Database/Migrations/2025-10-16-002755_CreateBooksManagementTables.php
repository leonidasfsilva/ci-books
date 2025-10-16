<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreateBooksManagementTables extends Migration
{
    public function up()
    {
        if (!$this->db->tableExists('Livro')) {
            $this->forge->addField([
                'CodL' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'Titulo' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '40',
                ],
                'Editora' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '40',
                    'null'       => true,
                ],
                'Edicao' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                    'null'       => true,
                ],
                'AnoPublicacao' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '4',
                    'null'       => true,
                ],
                'Valor' => [
                    'type'       => 'DECIMAL',
                    'constraint' => '10,2',
                ],
            ]);
            $this->forge->addKey('CodL', true);
            $this->forge->createTable('Livro');
        }

        if (!$this->db->tableExists('Autor')) {
            $this->forge->addField([
                'CodAu' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'Nome' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '40',
                ],
            ]);
            $this->forge->addKey('CodAu', true);
            $this->forge->createTable('Autor');
        }

        if (!$this->db->tableExists('Assunto')) {
            $this->forge->addField([
                'codAs' => [
                    'type'           => 'INT',
                    'constraint'     => 11,
                    'auto_increment' => true,
                ],
                'Descricao' => [
                    'type'       => 'VARCHAR',
                    'constraint' => '20',
                ],
            ]);
            $this->forge->addKey('codAs', true);
            $this->forge->createTable('Assunto');
        }

        if (!$this->db->tableExists('Livro_Autor')) {
            $this->forge->addField([
                'Livro_CodL' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'Autor_CodAu' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
            ]);
            $this->forge->addKey(['Livro_CodL', 'Autor_CodAu'], true);
            $this->forge->addForeignKey('Livro_CodL', 'Livro', 'CodL', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('Autor_CodAu', 'Autor', 'CodAu', 'CASCADE', 'CASCADE');
            $this->forge->createTable('Livro_Autor');
        }

        if (!$this->db->tableExists('Livro_Assunto')) {
            $this->forge->addField([
                'Livro_CodL' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
                'Assunto_codAs' => [
                    'type'       => 'INT',
                    'constraint' => 11,
                ],
            ]);
            $this->forge->addKey(['Livro_CodL', 'Assunto_codAs'], true);
            $this->forge->addForeignKey('Livro_CodL', 'Livro', 'CodL', 'CASCADE', 'CASCADE');
            $this->forge->addForeignKey('Assunto_codAs', 'Assunto', 'codAs', 'CASCADE', 'CASCADE');
            $this->forge->createTable('Livro_Assunto');
        }

        if (!$this->db->query("SHOW TABLES LIKE 'vw_relatorio_consolidado'")->getRow()) {
            $this->db->query("
                CREATE VIEW vw_relatorio_consolidado AS
                SELECT
                    L.CodL,
                    L.Titulo,
                    L.Editora,
                    L.Edicao,
                    L.AnoPublicacao,
                    L.Valor,
                    GROUP_CONCAT(DISTINCT A.Nome ORDER BY A.Nome SEPARATOR ', ') as Autores,
                    GROUP_CONCAT(DISTINCT S.Descricao ORDER BY S.Descricao SEPARATOR ', ') as Assuntos
                FROM Livro L
                LEFT JOIN Livro_Autor LA ON L.CodL = LA.Livro_CodL
                LEFT JOIN Autor A ON LA.Autor_CodAu = A.CodAu
                LEFT JOIN Livro_Assunto LS ON L.CodL = LS.Livro_CodL
                LEFT JOIN Assunto S ON LS.Assunto_codAs = S.codAs
                GROUP BY L.CodL, L.Titulo, L.Editora, L.Edicao, L.AnoPublicacao, L.Valor
                ORDER BY L.Titulo
            ");
        }
    }

    public function down()
    {
        $this->db->query("DROP VIEW IF EXISTS vw_relatorio_consolidado");
        $this->forge->dropTable('Livro_Assunto', true);
        $this->forge->dropTable('Livro_Autor', true);
        $this->forge->dropTable('Livro', true);
        $this->forge->dropTable('Assunto', true);
        $this->forge->dropTable('Autor', true);
    }
}
