<?php
namespace App\Controller;


use App\Entity\Companies;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CompaniesController extends AbstractController{
    /**
    * @Route("/companies")
    */
    public function index(){
        $companies = $this->getDoctrine()->getRepository(Companies::class)->findAll();
        return $this->render('companies/index.html.twig',array ('companies'=>$companies));
    }

    /**
    * @Route("/companies/{name}", name="companies_show")
    */
    public function show($name){
        $company = $this->getDoctrine()->getRepository(Companies::class)->find($name);
        return $this->render('companies/show.html.twig', array('company'=>$company));

    }

    // /**
    // * @Route("/companies/save")
    // */
    // public function save(){
    //     $entityManager = $this->getDoctrine()->getManager();
    //     $company = new Companies();
    //     $company->setName('company1');
    //     $company->setEmail('company@mail.com');
    //     $company->setWebsite('company.com');
    //
    //     $entityManager->persist($company);
    //     $entityManager->flush();
    //
    //     return new Response('Saves a company named '.$company->getName());
    // }
}

?>
