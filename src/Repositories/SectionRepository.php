<?php

declare(strict_types=1);

class SectionRepository {
    
    private PDO $pdo;

    // Constructor to initialize the database connection.
    public function __construct(PDO $pdo) {$this->pdo = $pdo;}

    // ==========================================
    // Core CRUD Operations
    // ==========================================

    // Create a new section in the database.
    public function create(Section $section): bool {
        $stmt = $this->pdo->prepare("INSERT INTO sections (name) VALUES (:name)");
        return $stmt->execute([':name' => $section->getName()]);
    }

    // Update an existing section's name.
    public function update(Section $section): int {
        $stmt = $this->pdo->prepare("UPDATE sections SET name = :name WHERE id = :id");
        $stmt->execute([
            ':name' => $section->getName(),
            ':id'   => $section->getId()
        ]);
        return $stmt->rowCount();
    }
    
    // Delete a section permanently from the database.
    public function delete(int $id): int {
        $stmt = $this->pdo->prepare("DELETE FROM sections WHERE id = :id");
        $stmt->execute([':id' => $id]);
        return $stmt->rowCount();
    }

    // ==========================================
    // Primary Fetch Methods
    // ==========================================

    // Retrieve a specific section by its ID and return it as an Object.
    public function findById(int $id): ?Section {
        $stmt = $this->pdo->prepare("SELECT * FROM sections WHERE id = :id LIMIT 1");
        $stmt->execute([':id' => $id]);
        $row = $stmt->fetch();
        
        if (!$row) return null;
        return new Section($row['name'], (int)$row['id']);
    }

    // Retrieve all sections from the database, ordered by name alphabetically.
    public function findAll(): array {
        $stmt = $this->pdo->query("SELECT * FROM sections ORDER BY name ASC");
        return $stmt->fetchAll();
    }

    // ==========================================
    // Validation & Business Rules
    // ==========================================

    // Check if a section name already exists in the system.
    public function isNameExists(string $name, int $excludeId = 0): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM sections WHERE name = :name AND id != :exclude_id");
        $stmt->execute([':name' => $name, ':exclude_id' => $excludeId]);
        return (int)$stmt->fetchColumn() > 0;
    }

    // Check if a department currently has employees linked to it.
    public function hasEmployees(int $sectionId): bool {
        $stmt = $this->pdo->prepare("SELECT COUNT(*) FROM employees WHERE section_id = :id");
        $stmt->execute([':id' => $sectionId]);
        return $stmt->fetchColumn() > 0;
    }

    // ==========================================
    // Dashboard & Statistics
    // ==========================================
    
    // Get the total count of all sections in the company.
    public function countAll(): int {
        return (int)$this->pdo->query("SELECT COUNT(*) FROM sections")->fetchColumn();
    }
}