<?php

namespace App\Entity;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompaniesRepository")
 */
class Companies
{
    /**
     * @ORM\Id()
     * @ORM\GeneratedValue()
     * @ORM\Column(type="integer")
     */
    private $id;

    /**
    * @ORM\Column(type="string", length = 255)
    */
    private $name;

    /**
    * @ORM\Column(type="string", length = 255, nullable = true)
    */
    private $email;

    /**
    * @ORM\Column(type="string", length = 255, nullable = true)
    */
    private $website;

    /**
     * @ORM\OneToMany(targetEntity="App\Entity\Employees", mappedBy="company", cascade={"persist"})
     */
    private $employees;

    public function __construct()
    {
        parent::__construct();
        $this->employees = new ArrayCollection();
    }


    public function getId(){
      return $this->id;
    }


    public function getName(){
      return $this->name;
    }

    public function getEmail(){
      return $this->email;
    }

    public function getWebsite(){
      return $this->website;
    }

    public function setName($name){
      $this->name = $name;
    }

    public function setEmail($email){
      $this->email = $email;
    }

    public function setWebsite($website){
      $this->website = $website;
    }

    /**
     * @return Collection|Employees[]
     */
    public function getEmployees(): Collection
    {
        return $this->employees;
    }

    public function addEmployee(Employees $employee): self{

        $this->employees[] = $employee;
        $employee->setCompany($this);
        return $this;
    }

    public function removeEmployee(Employees $employee): self
    {
        if ($this->employees->contains($employee)) {
            $this->employees->removeElement($employee);
            // set the owning side to null (unless already changed)
            if ($employee->getCompany() === $this) {
                $employee->setCompany(null);
            }
        }

        return $this;
    }

}
