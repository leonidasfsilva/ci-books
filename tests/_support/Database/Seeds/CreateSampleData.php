<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class CreateSampleData extends Seeder
{
    public function run()
    {
        // SQLite doesn't support SET FOREIGN_KEY_CHECKS, skip for SQLite
        if ($this->db->DBDriver !== 'SQLite3') {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 0');
        }

        // Only delete if tables exist (for SQLite compatibility)
        $tablesToClear = ['Livro_Assunto', 'Livro_Autor', 'Livro', 'Assunto', 'Autor'];
        foreach ($tablesToClear as $table) {
            if ($this->db->tableExists($table)) {
                $this->db->table($table)->where('1=1')->delete();
            }
        }

        $authors = [
            ['Nome' => 'Machado de Assis'],
            ['Nome' => 'Clarice Lispector'],
            ['Nome' => 'Jorge Amado'],
            ['Nome' => 'Paulo Coelho'],
            ['Nome' => 'Lygia Fagundes Telles'],
            ['Nome' => 'Monteiro Lobato'],
            ['Nome' => 'Carlos Drummond de Andrade'],
            ['Nome' => 'Cecília Meireles'],
            ['Nome' => 'Vinicius de Moraes'],
            ['Nome' => 'Rubem Braga'],
            ['Nome' => 'Fernando Sabino'],
            ['Nome' => 'Guimarães Rosa'],
            ['Nome' => 'Rachel de Queiroz'],
            ['Nome' => 'Mário de Andrade'],
            ['Nome' => 'Oswald de Andrade'],
        ];

        $this->db->table('`Autor`')->insertBatch($authors);

        $subjects = [
            ['Descricao' => 'Ficção Literária'],
            ['Descricao' => 'Literatura Infantil'],
            ['Descricao' => 'Poesia'],
            ['Descricao' => 'Crônica'],
            ['Descricao' => 'Regionalismo'],
            ['Descricao' => 'Modernismo'],
        ];

        $this->db->table('`Assunto`')->insertBatch($subjects);

        $books = [
            [
                'Titulo' => 'Dom Casmurro',
                'Editora' => 'Editora Moderna',
                'Edicao' => 1,
                'AnoPublicacao' => '1899',
                'Valor' => 45.90,
            ],
            [
                'Titulo' => 'A Hora da Estrela',
                'Editora' => 'Editora Rocco',
                'Edicao' => 2,
                'AnoPublicacao' => '1977',
                'Valor' => 32.50,
            ],
            [
                'Titulo' => 'Gabriela, Cravo e Canela',
                'Editora' => 'Editora Record',
                'Edicao' => 3,
                'AnoPublicacao' => '1958',
                'Valor' => 52.80,
            ],
            [
                'Titulo' => 'O Alquimista',
                'Editora' => 'Editora Planeta',
                'Edicao' => 1,
                'AnoPublicacao' => '1988',
                'Valor' => 28.90,
            ],
            [
                'Titulo' => 'As Meninas',
                'Editora' => 'Editora Abril',
                'Edicao' => 2,
                'AnoPublicacao' => '1973',
                'Valor' => 38.75,
            ],
            [
                'Titulo' => 'O Sítio do Picapau Amarelo',
                'Editora' => 'Editora Globo',
                'Edicao' => 1,
                'AnoPublicacao' => '1920',
                'Valor' => 39.90,
            ],
            [
                'Titulo' => 'Sentimento do Mundo',
                'Editora' => 'Editora José Olympio',
                'Edicao' => 1,
                'AnoPublicacao' => '1940',
                'Valor' => 27.90,
            ],
            [
                'Titulo' => 'Romanceiro da Inconfidência',
                'Editora' => 'Editora Nova Fronteira',
                'Edicao' => 3,
                'AnoPublicacao' => '1980',
                'Valor' => 31.50,
            ],
            [
                'Titulo' => 'Soneto de Fidelidade',
                'Editora' => 'Editora Companhia das Letras',
                'Edicao' => 1,
                'AnoPublicacao' => '1940',
                'Valor' => 19.90,
            ],
            [
                'Titulo' => 'A Crônica dos Bons',
                'Editora' => 'Editora Record',
                'Edicao' => 1,
                'AnoPublicacao' => '1985',
                'Valor' => 35.90,
            ],
            [
                'Titulo' => 'O Homem e seus Tempos',
                'Editora' => 'Editora Civilização Brasileira',
                'Edicao' => 2,
                'AnoPublicacao' => '1965',
                'Valor' => 42.90,
            ],
            [
                'Titulo' => 'Grande Sertão: Veredas',
                'Editora' => 'Editora Nova Fronteira',
                'Edicao' => 1,
                'AnoPublicacao' => '1956',
                'Valor' => 89.90,
            ],
            [
                'Titulo' => 'O Quinze',
                'Editora' => 'Editora José Olympio',
                'Edicao' => 1,
                'AnoPublicacao' => '1930',
                'Valor' => 38.90,
            ],
            [
                'Titulo' => 'Macunaíma',
                'Editora' => 'Editora Martins',
                'Edicao' => 1,
                'AnoPublicacao' => '1928',
                'Valor' => 45.90,
            ],
            [
                'Titulo' => 'Serafim Ponte Grande',
                'Editora' => 'Editora Globo',
                'Edicao' => 1,
                'AnoPublicacao' => '1933',
                'Valor' => 41.90,
            ],
        ];

        $this->db->table('`Livro`')->insertBatch($books);

        $authorsData = $this->db->table('`Autor`')->orderBy('CodAu')->get()->getResultArray();
        $subjectsData = $this->db->table('`Assunto`')->orderBy('codAs')->get()->getResultArray();
        $booksData = $this->db->table('`Livro`')->orderBy('CodL')->get()->getResultArray();

        $bookAuthors = [
            ['Livro_CodL' => $booksData[0]['CodL'], 'Autor_CodAu' => $authorsData[0]['CodAu']], // Dom Casmurro -> Machado de Assis
            ['Livro_CodL' => $booksData[1]['CodL'], 'Autor_CodAu' => $authorsData[1]['CodAu']], // A Hora da Estrela -> Clarice Lispector
            ['Livro_CodL' => $booksData[2]['CodL'], 'Autor_CodAu' => $authorsData[2]['CodAu']], // Gabriela, Cravo e Canela -> Jorge Amado
            ['Livro_CodL' => $booksData[3]['CodL'], 'Autor_CodAu' => $authorsData[3]['CodAu']], // O Alquimista -> Paulo Coelho
            ['Livro_CodL' => $booksData[4]['CodL'], 'Autor_CodAu' => $authorsData[4]['CodAu']], // As Meninas -> Lygia Fagundes Telles
            ['Livro_CodL' => $booksData[5]['CodL'], 'Autor_CodAu' => $authorsData[5]['CodAu']], // O Sítio do Picapau Amarelo -> Monteiro Lobato
            ['Livro_CodL' => $booksData[6]['CodL'], 'Autor_CodAu' => $authorsData[6]['CodAu']], // Sentimento do Mundo -> Carlos Drummond de Andrade
            ['Livro_CodL' => $booksData[7]['CodL'], 'Autor_CodAu' => $authorsData[7]['CodAu']], // Romanceiro da Inconfidência -> Cecília Meireles
            ['Livro_CodL' => $booksData[8]['CodL'], 'Autor_CodAu' => $authorsData[8]['CodAu']], // Soneto de Fidelidade -> Vinicius de Moraes
            ['Livro_CodL' => $booksData[9]['CodL'], 'Autor_CodAu' => $authorsData[9]['CodAu']], // A Crônica dos Bons -> Rubem Braga
            ['Livro_CodL' => $booksData[10]['CodL'], 'Autor_CodAu' => $authorsData[10]['CodAu']], // O Homem e seus Tempos -> Fernando Sabino
            ['Livro_CodL' => $booksData[11]['CodL'], 'Autor_CodAu' => $authorsData[11]['CodAu']], // Grande Sertão: Veredas -> Guimarães Rosa
            ['Livro_CodL' => $booksData[12]['CodL'], 'Autor_CodAu' => $authorsData[12]['CodAu']], // O Quinze -> Rachel de Queiroz
            ['Livro_CodL' => $booksData[13]['CodL'], 'Autor_CodAu' => $authorsData[13]['CodAu']], // Macunaíma -> Mário de Andrade
            ['Livro_CodL' => $booksData[14]['CodL'], 'Autor_CodAu' => $authorsData[14]['CodAu']], // Serafim Ponte Grande -> Oswald de Andrade
        ];

        $this->db->table('`Livro_Autor`')->insertBatch($bookAuthors);

        $bookSubjects = [
            ['Livro_CodL' => $booksData[0]['CodL'], 'Assunto_codAs' => $subjectsData[0]['codAs']],
            ['Livro_CodL' => $booksData[1]['CodL'], 'Assunto_codAs' => $subjectsData[0]['codAs']],
            ['Livro_CodL' => $booksData[2]['CodL'], 'Assunto_codAs' => $subjectsData[0]['codAs']],
            ['Livro_CodL' => $booksData[3]['CodL'], 'Assunto_codAs' => $subjectsData[0]['codAs']],
            ['Livro_CodL' => $booksData[4]['CodL'], 'Assunto_codAs' => $subjectsData[0]['codAs']],
            ['Livro_CodL' => $booksData[5]['CodL'], 'Assunto_codAs' => $subjectsData[1]['codAs']],
            ['Livro_CodL' => $booksData[6]['CodL'], 'Assunto_codAs' => $subjectsData[2]['codAs']],
            ['Livro_CodL' => $booksData[7]['CodL'], 'Assunto_codAs' => $subjectsData[2]['codAs']],
            ['Livro_CodL' => $booksData[8]['CodL'], 'Assunto_codAs' => $subjectsData[2]['codAs']],
            ['Livro_CodL' => $booksData[9]['CodL'], 'Assunto_codAs' => $subjectsData[3]['codAs']],
            ['Livro_CodL' => $booksData[10]['CodL'], 'Assunto_codAs' => $subjectsData[3]['codAs']],
            ['Livro_CodL' => $booksData[11]['CodL'], 'Assunto_codAs' => $subjectsData[4]['codAs']],
            ['Livro_CodL' => $booksData[12]['CodL'], 'Assunto_codAs' => $subjectsData[4]['codAs']],
            ['Livro_CodL' => $booksData[13]['CodL'], 'Assunto_codAs' => $subjectsData[5]['codAs']],
            ['Livro_CodL' => $booksData[14]['CodL'], 'Assunto_codAs' => $subjectsData[5]['codAs']],
        ];

        $this->db->table('`Livro_Assunto`')->insertBatch($bookSubjects);

        // SQLite doesn't support SET FOREIGN_KEY_CHECKS, skip for SQLite
        if ($this->db->DBDriver !== 'SQLite3') {
            $this->db->query('SET FOREIGN_KEY_CHECKS = 1');
        }
    }
}
