<?php
namespace App\Controller;


use App\Entity\Companies;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextAreaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;

class CompaniesController extends AbstractController{
    /**
    * @Route("/companies", name="companies_list")
    */
    public function index(){
        $companies = $this->getDoctrine()->getRepository(Companies::class)->findAll();
        return $this->render('companies/index.html.twig',array ('companies'=>$companies));
    }

    /**
    * @Route("/companies/new", name="new_company"), methods={"GET", "POST"}
    */

    public function new(Request $request){
        $company = new Companies();

        $form = $this->createFormBuilder($company)->add('name',TextType::class, array('attr'=>array('class'=>'form-control')))
          ->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('website',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('save', SubmitType::class, array('label'=>'Create', 'attr'=>array('class'=>'btn btn-primary mt-3')))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $companies = $form->getData();
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->persist($companies);
          $entityManager->flush();

          return $this->redirectToRoute('companies_list');

        }

        return $this->render('companies/new.html.twig', array('form'=>$form->createView()));
    }

    /**
    * @Route("/companies/edit/{name}", name="edit_company"), methods={"GET", "POST"}
    */

    public function edit(Request $request, $name){
        $company = new Companies();
        $company = $this->getDoctrine()->getRepository(Companies::class)->find($name);

        $form = $this->createFormBuilder($company)->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('website',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('save', SubmitType::class, array('label'=>'Create', 'attr'=>array('class'=>'btn btn-primary mt-3')))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return $this->redirectToRoute('companies_list');

        }

        return $this->render('companies/edit.html.twig', array('form'=>$form->createView()));
    }


    /**
    * @Route("/companies/{name}", name="companies_show")
    */
    public function show($name){
        $company = $this->getDoctrine()->getRepository(Companies::class)->find($name);
        return $this->render('companies/show.html.twig', array('company'=>$company));


    }



    /**
    * @Route("/companies/delete/{name}"), methods={"DELETE"}
    */

    public function delete(Request $request, $name){
      $company = $this->getDoctrine()->getRepository(Companies::class)->find($name);

      $entityManager = $this->getDoctrine()->getManager();
      $entityManager->remove($company);
      $entityManager->flush();

      $response = new Response();
      $response->send();


    }


}

?>
