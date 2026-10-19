<?php
declare(strict_types=1);

class Employee{
    private int $id;
    private string $full_name;
    private string $email;
    private string $phone_number;
    private string $date_start;
    private int $section_id;

    // Construct to initialize the Employee entity
    public function __construct(string $full_name,string $email,string $phone_number,string $date_start,int $section_id,int $id=0) {
        $this->full_name=$full_name;
        $this->email=$email;
        $this->phone_number=$phone_number;
        $this->date_start=$date_start;
        $this->section_id=$section_id;
        $this->id=$id;
    }

    // THis method returns the Employee id
    public function getId(): int{ return $this->id;}

    // THis method returns the Employee full_name
    public function getFullName(): string{return $this->full_name;}

    // THis method returns the Employee email
    public function getEmail(): string{return $this->email;}

    // THis method returns the Employee phone_number
    public function getPhoneNumber(): string{return $this->phone_number;}

    // THis method returns the Employee date_start
    public function getDateStart(): string{return $this->date_start;}
    
    // THis method returns the Employee section_id
    public function getSectionId(): int{return $this->section_id;}
}