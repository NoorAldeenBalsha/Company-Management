<?php 

declare(strict_types=1);

class Project{
    private int $id;
    private string $name;
    private string $customer_name;
    private string $date_start;
    private string $status;
    
    // Construct to initialize the project entity
    public function __construct(string $name,string $customer_name,string $date_start,string $status,int $id=0) {
        $this->id=$id;
        $this->name=$name;
        $this->customer_name=$customer_name;
        $this->date_start=$date_start;
        $this->status=$status;
    }

    // THis method returns the Project id
    public function getId():int{ return $this->id;}

    // THis method returns the Project name
    public function getName():string{ return $this->name;}

    // THis method returns the Project customer_name
    public function getCustomerName() : string { return $this->customer_name;}

    // THis method returns the Project date_start
    public function getDateStart(): string { return $this->date_start;}
    
    // THis method returns the Project status
    public function getstatus(): string { return $this->status;}
}