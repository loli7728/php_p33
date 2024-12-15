<?php

abstract class Employee {
    protected string $name;
    protected string $position;

    public function __construct(string $name, string $position) {
        $this->name = $name;
        $this->position = $position;
    }

    abstract public function calculateSalary(): float;

    public function getDetails(): string {
        return "Имя: " . $this->name . ", Должность: " . $this->position;
    }
}

class FullTimeEmployee extends Employee {
    private float $fixedSalary;

    public function __construct(string $name, string $position, float $fixedSalary) {
        parent::__construct($name, $position);
        $this->fixedSalary = $fixedSalary;
    }

    public function calculateSalary(): float {
        return $this->fixedSalary;
    }
}

class PartTimeEmployee extends Employee {
    private float $hourlyRate;
    private int $hoursWorked;

    public function __construct(string $name, string $position, float $hourlyRate, int $hoursWorked) {
        parent::__construct($name, $position);
        $this->hourlyRate = $hourlyRate;
        $this->hoursWorked = $hoursWorked;
    }

    public function calculateSalary(): float {
        return $this->hourlyRate * $this->hoursWorked;
    }
}

// Создание массива сотрудников
$employees = [
    new FullTimeEmployee("Иван Иванов", "Менеджер", 50000.0),
    new PartTimeEmployee("Петр Петров", "Курьер", 200.0, 15),
    new FullTimeEmployee("Светлана Светлова", "Разработчик", 80000.0),
    new PartTimeEmployee("Алексей Алексеев", "Дизайнер", 250.0, 10)
];

// Вывод информации о каждом сотруднике
foreach ($employees as $employee) {
    echo $employee->getDetails() . ", Зарплата: " . $employee->calculateSalary() . " руб." . PHP_EOL;
}

?>
