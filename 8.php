<?php

class FileManager {
    private string $directory;

    public function __construct(string $directory) {
        $this->directory = rtrim($directory, DIRECTORY_SEPARATOR) . DIRECTORY_SEPARATOR;
        
        // Проверка на существование директории
        if (!is_dir($this->directory)) {
            mkdir($this->directory, 0755, true);
        }
    }

    public function readFile(string $filename): string {
        $filePath = $this->directory . $filename;
        if (file_exists($filePath)) {
            return file_get_contents($filePath);
        }
        throw new Exception("Файл не найден: " . $filename);
    }

    public function writeFile(string $filename, string $data): void {
        $filePath = $this->directory . $filename;
        file_put_contents($filePath, $data);
    }

    public function deleteFile(string $filename): void {
        $filePath = $this->directory . $filename;
        if (file_exists($filePath)) {
            unlink($filePath);
        } else {
            throw new Exception("Файл не найден: " . $filename);
        }
    }

    public function listFiles(): array {
        return array_diff(scandir($this->directory), ['..', '.']);
    }
}

// Пример использования
try {
    $fileManager = new FileManager('my_files');

    // Запись файла
    $fileManager->writeFile('example.txt', 'Это пример текста.');
    
    // Чтение файла
    echo $fileManager->readFile('example.txt') . PHP_EOL;

    // Список файлов
    print_r($fileManager->listFiles());

    // Удаление файла
    $fileManager->deleteFile('example.txt');

    // Проверка списка файлов после удаления
    print_r($fileManager->listFiles());

} catch (Exception $e) {
    echo 'Ошибка: ' . $e->getMessage();
}

?>
