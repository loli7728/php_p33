<?php

class Book {
    private $title;
    private $author;

    public function __construct($title, $author) {
        $this->title = $title;
        $this->author = $author;
    }

    public function getTitle() {
        return $this->title;
    }

    public function getAuthor() {
        return $this->author;
    }
}

class BookAlreadyExistsException extends Exception {}
class LibraryFullException extends Exception {}

class Library {
    private $books = [];
    private $maxBooks;

    public function __construct($maxBooks) {
        $this->maxBooks = $maxBooks;
    }

    public function addBook(Book $book) {
        if (count($this->books) >= $this->maxBooks) {
            throw new LibraryFullException("Библиотека уже полна. Максимальное количество книг: " . $this->maxBooks);
        }

        foreach ($this->books as $existingBook) {
            if ($existingBook->getTitle() === $book->getTitle()) {
                throw new BookAlreadyExistsException("Книга с таким названием уже существует: " . $book->getTitle());
            }
        }

        $this->books[] = $book;
    }

    public function getBooks() {
        return $this->books;
    }
}

// Логирование ошибок
function logError($message) {
    file_put_contents('error_log.txt', $message . PHP_EOL, FILE_APPEND);
}

// Пример использования класса
try {
    $library = new Library(3);

    $book1 = new Book("1984", "Джордж Оруэлл");
    $book2 = new Book("Война и всемир", "Лев Толстой");
    $book3 = new Book("Преступление и наказание", "Федор Достоевский");
    
    $library->addBook($book1);
    $library->addBook($book2);
    
    // Попробуем добавить ту же книгу
    $library->addBook($book1);
    
    // Попробуем превысить лимит
    $library->addBook($book3);

} catch (BookAlreadyExistsException $e) {
    logError("Ошибка: " . $e->getMessage());
    echo $e->getMessage();
} catch (LibraryFullException $e) {
    logError("Ошибка: " . $e->getMessage());
    echo $e->getMessage();
} catch (Exception $e) {
    logError("Неизвестная ошибка: " . $e->getMessage());
    echo "Произошла ошибка: " . $e->getMessage();
}

// Вывод списка книг
$books = $library->getBooks();
foreach ($books as $book) {
    echo "Название: " . $book->getTitle() . ", Автор: " . $book->getAuthor() . PHP_EOL;
}
?>
