<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * @ORM\Entity(repositoryClass="App\Repository\CompaniesRepository")
 */
class Companies
{
    /**
     * @ORM\Id()
     * @ORM\Column(type="string", length = 255, nullable =true)
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

}
