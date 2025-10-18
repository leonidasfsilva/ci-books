-- Create Table: Livro
CREATE TABLE Livro (
    CodL INTEGER PRIMARY KEY AUTO_INCREMENT,
    Titulo VARCHAR(40) NOT NULL,
    Editora VARCHAR(40),
    Edicao INTEGER,
    AnoPublicacao VARCHAR(4),
    Valor DECIMAL(10,2) NOT NULL
);

-- Create Table: Autor
CREATE TABLE Autor (
    CodAu INTEGER PRIMARY KEY AUTO_INCREMENT,
    Nome VARCHAR(40) NOT NULL
);

-- Create Table: Assunto
CREATE TABLE Assunto (
    codAs INTEGER PRIMARY KEY AUTO_INCREMENT,
    Descricao VARCHAR(20) NOT NULL
);

-- Create Table: Livro_Autor
CREATE TABLE Livro_Autor (
    Livro_CodL INTEGER NOT NULL,
    Autor_CodAu INTEGER NOT NULL,
    FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Autor_CodAu) REFERENCES Autor(CodAu) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (Livro_CodL, Autor_CodAu)
);

-- Create Table: Livro_Assunto
CREATE TABLE Livro_Assunto (
    Livro_CodL INTEGER NOT NULL,
    Assunto_codAs INTEGER NOT NULL,
    FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL) ON DELETE CASCADE ON UPDATE CASCADE,
    FOREIGN KEY (Assunto_codAs) REFERENCES Assunto(codAs) ON DELETE CASCADE ON UPDATE CASCADE,
    PRIMARY KEY (Livro_CodL, Assunto_codAs)
);

-- Insert sample data into Livro
INSERT INTO Livro (CodL, Titulo, Editora, Edicao, AnoPublicacao, Valor)
VALUES (1, 'Book One', 'Publisher A', 1, '2020', 29.90);

-- Insert sample data into Autor
INSERT INTO Autor (CodAu, Nome)
VALUES (1, 'Author One');

-- Insert sample data into Assunto
INSERT INTO Assunto (codAs, Descricao)
VALUES (1, 'Fiction');

-- Insert sample data into Livro_Autor
INSERT INTO Livro_Autor (Livro_CodL, Autor_CodAu)
VALUES (1, 1);

-- Insert sample data into Livro_Assunto
INSERT INTO Livro_Assunto (Livro_CodL, Assunto_codAs)
VALUES (1, 1);

-- Query to get all books with their authors
SELECT L.Titulo, A.Nome
FROM Livro L
JOIN Livro_Autor LA ON L.CodL = LA.Livro_CodL
JOIN Autor A ON LA.Autor_CodAu = A.CodAu;

-- Query to get all books with their subjects
SELECT L.Titulo, AS.Descricao
FROM Livro L
JOIN Livro_Assunto LA ON L.CodL = LA.Livro_CodL
JOIN Assunto AS ON LA.Assunto_codAs = AS.codAs;

-- Create consolidated report view
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
ORDER BY L.Titulo;