<?php
declare(strict_types=1);

class Laptop{
    private string $serialNumber;
    private string $model;
    private string $dateBuy;
    private ?int $employeeId; 
    private int $id;

    // Construct to initialize the Laptop entity  
    public function __construct(string $serialNumber, string $model, string $dateBuy, ?int $employeeId = null, int $id = 0) {
        $this->serialNumber = $serialNumber;
        $this->model = $model;
        $this->dateBuy = $dateBuy;
        $this->employeeId = $employeeId;
        $this->id = $id;
    }

    // THis method returns the Laptop id
    public function getId(): int{return $this->id;}

    // THis method returns the Laptop serial_number
    public function getSerialNumber(): string{return $this->serialNumber;}

    // THis method returns the Laptop model
    public function getModel(): string{return $this->model;}

    // THis method returns the Laptop date_buy
    public function getDateBuy(): string{return $this->dateBuy;}
    
    // THis method returns the Laptop employee_id
    public function getEmployeeId(): ?int{return $this->employeeId;}
}