<?php
use PHPUnit\Framework\TestCase;

require 'C:\Users\Administrator\Desktop\php\book.php'; // Замените на фактический путь к вашему файлу с классами

class LibraryTest extends TestCase {
    private $library;

    protected function setUp(): void {
        $this->library = new Library(2); // Установка максимума книг в 2
    }

    public function testAddBookSuccessfully() {
        $book1 = new Book("1984", "Джордж Оруэлл");
        $book2 = new Book("Кобзар", "Тарас Шевченко");

        $this->library->addBook($book1);
        $this->library->addBook($book2);

        $this->assertCount(2, $this->library->getBooks());
    }

    public function testAddDuplicateBookThrowsException() {
        $book1 = new Book("1984", "Джордж Оруэлл");
        $this->library->addBook($book1);

        $this->expectException(BookAlreadyExistsException::class);
        $this->library->addBook(new Book("1984", "Джордж Оруэлл")); // Пробуем добавить дубликат
    }

    public function testAddBookExceedsLibraryLimitThrowsException() {
        $book1 = new Book("1984", "Джордж Оруэлл");
        $book2 = new Book("Кобзар", "Тарас Шевченко");
        $this->library->addBook($book1);
        $this->library->addBook($book2);

        $this->expectException(LibraryFullException::class);
        $this->library->addBook(new Book("Новая книга", "Автор")); // Пробуем добавить еще одну книгу
    }

    public function testGetBooksReturnsCorrectBooks() {
        $book1 = new Book("1984", "Джордж Оруэлл");
        $book2 = new Book("Кобзар", "Тарас Шевченко");
        $this->library->addBook($book1);
        $this->library->addBook($book2);

        $books = $this->library->getBooks();
        
        $this->assertCount(2, $books);
        $this->assertEquals("1984", $books[0]->getTitle());
        $this->assertEquals("Кобзар", $books[1]->getTitle());
    }
}
