-- Create Table: Livro
CREATE TABLE Livro (
    CodL INTEGER PRIMARY KEY,
    Titulo VARCHAR(40),
    Editora VARCHAR(40),
    Edicao INTEGER,
    AnoPublicacao VARCHAR(4)
);

-- Create Table: Autor
CREATE TABLE Autor (
    CodAu INTEGER PRIMARY KEY,
    Nome VARCHAR(40)
);

-- Create Table: Assunto
CREATE TABLE Assunto (
    codAs INTEGER PRIMARY KEY,
    Descricao VARCHAR(20)
);

-- Create Table: Livro_Autor
CREATE TABLE Livro_Autor (
    Livro_CodL INTEGER,
    Autor_CodAu INTEGER,
    FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL),
    FOREIGN KEY (Autor_CodAu) REFERENCES Autor(CodAu),
    PRIMARY KEY (Livro_CodL, Autor_CodAu)
);

-- Create Table: Livro_Assunto
CREATE TABLE Livro_Assunto (
    Livro_CodL INTEGER,
    Assunto_codAs INTEGER,
    FOREIGN KEY (Livro_CodL) REFERENCES Livro(CodL),
    FOREIGN KEY (Assunto_codAs) REFERENCES Assunto(codAs),
    PRIMARY KEY (Livro_CodL, Assunto_codAs)
);

-- Insert sample data into Livro
INSERT INTO Livro (CodL, Titulo, Editora, Edicao, AnoPublicacao)
VALUES (1, 'Book One', 'Publisher A', 1, '2020');

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