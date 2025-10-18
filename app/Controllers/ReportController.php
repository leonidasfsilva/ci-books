<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;

class ReportController extends BaseController
{
    protected $db;

    public function __construct()
    {
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Relatório Consolidado de Livros',
            'reportData' => $this->getConsolidatedReport(),
        ];

        return view('reports/index', $data);
    }

    public function exportExcel()
    {
        $reportData = $this->getConsolidatedReport();

        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment; filename="relatorio_livros_' . date('Y-m-d_H-i-s') . '.xls"');
        header('Cache-Control: max-age=0');

        echo "<html>";
        echo "<head>";
        echo "<meta charset='UTF-8'>";
        echo "<style>";
        echo "table { border-collapse: collapse; width: 100%; }";
        echo "th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }";
        echo "th { background-color: #f2f2f2; font-weight: bold; }";
        echo "tr:nth-child(even) { background-color: #f9f9f9; }";
        echo "</style>";
        echo "</head>";
        echo "<body>";
        echo "<h2>Relatório Consolidado de Livros</h2>";
        echo "<p>Gerado em: " . date('d/m/Y H:i:s') . "</p>";
        echo "<table>";
        echo "<thead>";
        echo "<tr>";
        echo "<th>Livro</th>";
        echo "<th>Autor</th>";
        echo "<th>Editora</th>";
        echo "<th>Edição</th>";
        echo "<th>Ano de Publicação</th>";
        echo "<th>Assuntos</th>";
        echo "<th>Valor</th>";
        echo "</tr>";
        echo "</thead>";
        echo "<tbody>";

        $currentAuthor = '';
        foreach ($reportData as $row) {
            $showAuthor = $currentAuthor !== $row['Autores'];
            if ($showAuthor) {
                $currentAuthor = $row['Autores'];
            }

            echo "<tr>";
            echo "<td>" . htmlspecialchars($row['Titulo']) . "</td>";
            echo "<td>" . ($showAuthor ? htmlspecialchars($row['Autores']) : '') . "</td>";
            echo "<td>" . htmlspecialchars($row['Editora'] ?: 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['Edicao'] ?: 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['AnoPublicacao'] ?: 'N/A') . "</td>";
            echo "<td>" . htmlspecialchars($row['Assuntos'] ?: 'N/A') . "</td>";
            echo "<td>R$ " . number_format($row['Valor'], 2, ',', '.') . "</td>";
            echo "</tr>";
        }

        echo "</tbody>";
        echo "</table>";
        echo "</body>";
        echo "</html>";
        exit;
    }

    private function getConsolidatedReport()
    {
        $query = $this->db->query("SELECT * FROM vw_relatorio_consolidado ORDER BY Autores, Titulo");
        return $query->getResultArray();
    }
}
