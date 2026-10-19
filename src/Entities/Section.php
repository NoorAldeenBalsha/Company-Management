<?php
declare(strict_types=1);

class Section{
    private int $id;
    private string $name;
    
    //Construct to initialize the Section entity
    public function __construct(string $name ,int $id=0) {
        $this->name=$name;
        $this->id=$id;
    }

    // THis method returns the section id
    public function getId(): int { return $this->id; }
    
    // This method returns the section name
    public function getName(): string { return $this->name; }
}