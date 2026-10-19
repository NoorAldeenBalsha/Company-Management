<?php
declare(strict_types=1);

class SectionService {
    
    private SectionRepository $sectionRepo;

    // Constructor to initialize dependencies.
    public function __construct(SectionRepository $sectionRepo) {
        $this->sectionRepo = $sectionRepo;
    }

    // ==========================================
    // Core Business Operations
    // ==========================================

    // Process adding a new section with fail-fast validation.
    public function addSection(Section $section): bool {
        $name = trim($section->getName());
        
        // Fail-Fast: Empty name check
        if (empty($name)) {
            throw new InvalidArgumentException("Error: Section name cannot be empty.");
        }
        
        // Fail-Fast: Unique name check
        if ($this->sectionRepo->isNameExists($name)) {
            throw new Exception("Error: A section with the name '$name' already exists.");
        }
        
        // Execute database operation
        $isCreated = $this->sectionRepo->create($section);
        
        // Log ONLY if the operation was successful
        if ($isCreated) {
            AppLogger::log('SECTION_ADDED', "A new section was created: {$section->getName()}");
        }
        
        return $isCreated;
    }

    // Process updating an existing section.
    public function updateSection(Section $section): bool {
        $name = trim($section->getName());
        
        // Fail-Fast: Empty name check
        if (empty($name)) {
            throw new InvalidArgumentException("Error: Section name cannot be empty.");
        }
        
        // Fail-Fast: Unique name check (excluding current ID)
        if ($this->sectionRepo->isNameExists($name, $section->getId())) {
            throw new Exception("Error: A section with the name '$name' already exists.");
        }
        
        $isUpdated = $this->sectionRepo->update($section) > 0;
        
        if ($isUpdated) {
            AppLogger::log('SECTION_UPDATED', "Section ID ({$section->getId()}) was updated to: {$section->getName()}");
        }
        
        return $isUpdated;
    }

    // Process section deletion with strict data integrity checks.
    public function deleteSection(int $id): bool {
        
        // Business Rule: Prevent deletion if employees are still assigned to this section
        if ($this->sectionRepo->hasEmployees($id)) {
            throw new Exception("Error: Cannot delete this section because there are employees assigned to it.");
        }
        
        $isDeleted = $this->sectionRepo->delete($id) > 0;
        
        if ($isDeleted) {
            AppLogger::log('SECTION_DELETED', "Section ID ($id) was deleted", 'WARNING');
        }
        
        return $isDeleted;
    }
}