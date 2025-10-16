<?php

namespace Tests\Unit;

use App\Models\BookModel;
use App\Models\AuthorModel;
use App\Models\SubjectModel;
use CodeIgniter\Test\CIUnitTestCase;
use CodeIgniter\Test\DatabaseTestTrait;

/**
 * @internal
 */
final class BookModelTest extends CIUnitTestCase
{
    use DatabaseTestTrait;

    protected $migrate = true;
    protected $seed = 'CreateSampleData';

    protected function setUp(): void
    {
        parent::setUp();
        $this->model = new BookModel();
        $this->authorModel = new AuthorModel();
        $this->subjectModel = new SubjectModel();
    }

    public function testModelCanInsertBook(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
            'publication_date' => '2023-01-01',
        ];

        $result = $this->model->insert($data);

        $this->assertIsInt($result);
        $this->assertGreaterThan(0, $result);
    }

    public function testModelCanFindBook(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
            'publication_date' => '2023-01-01',
        ];

        $id = $this->model->insert($data);
        $book = $this->model->find($id);

        $this->assertIsArray($book);
        $this->assertEquals('Test Book', $book['title']);
    }

    public function testModelCanUpdateBook(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
            'publication_date' => '2023-01-01',
        ];

        $id = $this->model->insert($data);
        $updateData = [
            'title' => 'Updated Book',
        ];

        $result = $this->model->update($id, $updateData);
        $this->assertTrue($result);

        $updatedBook = $this->model->find($id);
        $this->assertEquals('Updated Book', $updatedBook['title']);
    }

    public function testModelCanDeleteBook(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
            'publication_date' => '2023-01-01',
        ];

        $id = $this->model->insert($data);
        $result = $this->model->delete($id);

        $this->assertTrue($result);
        $this->assertNull($this->model->find($id));
    }

    public function testModelValidationFailsWithEmptyTitle(): void
    {
        $data = [
            'title' => '',
            'description' => 'Test Description',
            'value' => 29.99,
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('title', $errors);
    }

    public function testModelValidationFailsWithInvalidValue(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => -10,
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('value', $errors);
    }

    public function testModelValidationFailsWithInvalidPublicationDate(): void
    {
        $data = [
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
            'publication_date' => 'invalid-date',
        ];

        $result = $this->model->insert($data);
        $this->assertFalse($result);

        $errors = $this->model->errors();
        $this->assertArrayHasKey('publication_date', $errors);
    }

    public function testModelValidationPassesWithValidData(): void
    {
        $data = [
            'title' => 'Livro de Programação',
            'description' => 'Um livro sobre programação',
            'value' => 49.90,
            'publication_date' => '2023-05-15',
        ];

        $result = $this->model->insert($data);
        $this->assertIsInt($result);
    }

    public function testGetBooksWithRelations(): void
    {
        // Create test data
        $authorId = $this->authorModel->insert(['name' => 'Test Author']);
        $subjectId = $this->subjectModel->insert(['name' => 'Test Subject']);
        $bookId = $this->model->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        // Associate author and subject
        $this->db->table('book_authors')->insert(['book_id' => $bookId, 'author_id' => $authorId]);
        $this->db->table('book_subjects')->insert(['book_id' => $bookId, 'subject_id' => $subjectId]);

        $books = $this->model->getBooksWithRelations();

        $this->assertIsArray($books);
        $this->assertGreaterThan(0, count($books));
        $this->assertArrayHasKey('authors', $books[0]);
        $this->assertArrayHasKey('subjects', $books[0]);
    }

    public function testGetBookWithRelations(): void
    {
        // Create test data
        $authorId = $this->authorModel->insert(['name' => 'Test Author']);
        $subjectId = $this->subjectModel->insert(['name' => 'Test Subject']);
        $bookId = $this->model->insert([
            'title' => 'Test Book',
            'description' => 'Test Description',
            'value' => 29.99,
        ]);

        // Associate author and subject
        $this->db->table('book_authors')->insert(['book_id' => $bookId, 'author_id' => $authorId]);
        $this->db->table('book_subjects')->insert(['book_id' => $bookId, 'subject_id' => $subjectId]);

        $book = $this->model->getBookWithRelations($bookId);

        $this->assertIsArray($book);
        $this->assertEquals('Test Book', $book['title']);
        $this->assertArrayHasKey('authors', $book);
        $this->assertArrayHasKey('subjects', $book);
        $this->assertContains($authorId, $book['authors']);
        $this->assertContains($subjectId, $book['subjects']);
    }

    public function testGetBookWithRelationsReturnsNullForNonExistentBook(): void
    {
        $book = $this->model->getBookWithRelations(9999);

        $this->assertNull($book);
    }
}