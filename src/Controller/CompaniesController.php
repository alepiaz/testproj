<?php
namespace App\Controller;


use App\Entity\Companies;
use App\Entity\Employees;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\TextAreaType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Validator\Constraints\UniqueEntity;
class CompaniesController extends AbstractController{
    /**
    * @Route("/companies", name="companies_list")
    */
    public function index(){
        $companies = $this->getDoctrine()->getRepository(Companies::class)->findAll();
        return $this->render('companies/index.html.twig',array ('companies'=>$companies));
    }

    /**
    * @Route("/employees", name="employees_list")
    */
    public function index_employees(){
        $employees = $this->getDoctrine()->getRepository(Employees::class)->findAll();
        return $this->render('employees/index.html.twig',array ('employees'=>$employees));
    }

    /**
    * @Route("/companies/new", name="new_company"), methods={"GET", "POST"}
    */

    public function new(Request $request){
        $company = new Companies();

        $form = $this->createFormBuilder($company)->add('name',TextType::class,array('attr'=>array('class'=>'form-control')))
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
    * @Route("/employees/new/{id}", name="new_employee"), methods={"GET", "POST"}
    */

    public function new_company_employee(Request $request, $id){
        $employee = new Employees();
        if(!$id == 'null'){
          $company = $this->getDoctrine()->getRepository(Companies::class)->findOneBy(['id'=>$id]);
          $employee->setCompany($company);
          $company->addEmployee($employee);
          if($company){
            $form = $this->createFormBuilder($employee)->add('firstname',TextType::class, array('label'=>'First name','attr'=>array('class'=>'form-control')))
              ->add('lastname',TextType::class, array('label'=>'Last name','attr'=>array('class'=>'form-control')))
              ->add('company_id',HiddenType::class, array('data'=>$id))
              // ->add('company_id', ChoiceType::class, array('label'=>'Company ID ','attr'=>array('class'=>'custom-select mr-sm-2'),'choices'=>[$company->getId()=>$company->getId()]))
              ->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
              ->add('number',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
              ->add('save', SubmitType::class, array('label'=>'Create', 'attr'=>array('class'=>'btn btn-primary mt-3')))
              ->getForm();

            $form->handleRequest($request);

            if($form->isSubmitted() && $form->isValid()){
              $employee = $form->getData();
              $entityManager = $this->getDoctrine()->getManager();
              $entityManager->persist($employee);
              $entityManager->flush();

              return $this->redirectToRoute('companies_show',['id'=>$id]);

            }
          }
          return $this->render('employees/new.html.twig', array('form'=>$form->createView()));
        }
        elseif($id == 'null'){
          $companies = $this->getDoctrine()->getRepository(Companies::class)->findAll();
          $names = [];
          foreach($companies as $c){
            $names[]=[$c->getName()=>$c->getId()];
          }
          $form = $this->createFormBuilder($employee)->add('firstname',TextType::class, array('label'=>'First name','attr'=>array('class'=>'form-control')))
          ->add('lastname',TextType::class, array('label'=>'Last name','attr'=>array('class'=>'form-control')))
          // ->add('company_id',HiddenType::class, array('data'=>$id))
          ->add('company_id', ChoiceType::class, array('label'=>'Company Name ','attr'=>array('class'=>'custom-select mr-sm-2'),'choices'=>$names))
          ->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('number',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('save', SubmitType::class, array('label'=>'Create', 'attr'=>array('class'=>'btn btn-primary mt-3')))
          ->getForm();


          $form->handleRequest($request);

          if($form->isSubmitted() && $form->isValid()){
            $company_id = $form->get('company_id')->getData();
            $company = $this->getDoctrine()->getRepository(Companies::class)->findOneBy(['id'=>$company_id]);
            $employee->setCompany($company);
            $company->addEmployee($employee);
            $employee = $form->getData();
            $entityManager = $this->getDoctrine()->getManager();
            $entityManager->persist($employee);
            $entityManager->flush();

            return $this->redirectToRoute('employees_list');

          }
          return $this->render('employees/new.html.twig', array('form'=>$form->createView()));
        }


    }


    /**
    * @Route("/companies/edit/{id}", name="edit_company"), methods={"GET", "POST"}
    */

    public function edit(Request $request, $id){
        // $company = new Companies();
        $company = $this->getDoctrine()->getRepository(Companies::class)->findOneBy(['id'=>$id]);

        $form = $this->createFormBuilder($company)->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('website',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('save', SubmitType::class, array('label'=>'Save', 'attr'=>array('class'=>'btn btn-primary mt-3')))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return $this->redirectToRoute('companies_show',['id'=>$id]);

        }

        return $this->render('companies/edit.html.twig', array('form'=>$form->createView()));
    }

    /**
    * @Route("/employees/edit/{id}", name="edit_employee"), methods={"GET", "POST"}
    */

    public function edit_employee(Request $request, $id){

        $employee = $this->getDoctrine()->getRepository(Employees::class)->findOneBy(['id'=>$id]);

        $form = $this->createFormBuilder($employee)->add('email', TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('number',TextType::class, array('required'=>false,'attr'=>array('class'=>'form-control')))
          ->add('save', SubmitType::class, array('label'=>'Save', 'attr'=>array('class'=>'btn btn-primary mt-3')))
          ->getForm();

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
          $entityManager = $this->getDoctrine()->getManager();
          $entityManager->flush();

          return $this->redirectToRoute('employee_show',['id'=>$id]);

        }

        return $this->render('employees/edit.html.twig', array('form'=>$form->createView()));
    }


    /**
    * @Route("/companies/{id}", name="companies_show")
    */
    public function show($id){
        $company = $this->getDoctrine()->getRepository(Companies::class)->find($id);
        $employees = $this->getDoctrine()->getRepository(Employees::class)->findBy(['company_id' => $id]);
        return $this->render('companies/show.html.twig', array('company'=>$company,'employees'=>$employees));


    }

    /**
    * @Route("/employees/{id}", name="employee_show")
    */
    public function show_employee($id){

        $employee = $this->getDoctrine()->getRepository(Employees::class)->find($id);

        $name = $this->getDoctrine()->getRepository(Companies::class)->find($employee->getCompanyId())->getName();
        return $this->render('employees/show.html.twig', array('employee'=>$employee,'name'=>$name));


    }

    /**
    * @Route("/companies/delete/{id}"), methods={"DELETE"}
    */

    public function delete(Request $request, $id){
      $company = $this->getDoctrine()->getRepository(Companies::class)->find($id);

      $entityManager = $this->getDoctrine()->getManager();
      $employees = $company->getEmployees();

      foreach ($employees as $e){
        $entityManager->remove($e);

      }
      $entityManager->remove($company);
      $entityManager->flush();

      $response = new Response();
      $response->send();

      // return $this->render('companies/show.html.twig', array('company'=>$company,'employees'=>$employees));

    }

    /**
    * @Route("/employees/delete/{id}"), name="employee_delete",methods={"DELETE"}
    */

    public function delete_employee(Request $request, $id){
      $employee = $this->getDoctrine()->getRepository(Employees::class)->find($id);
      $company = $this->getDoctrine()->getRepository(Companies::class)->find($employee->getCompanyId());
      $entityManager = $this->getDoctrine()->getManager();
      $employees = $company->getEmployees();

      $company->removeEmployee($employee);
      $entityManager->remove($employee);
      $entityManager->flush();

      $response = new Response();
      $response->send();

      return $this->render('companies/show.html.twig', array('company'=>$company,'employees'=>$employees));

    }

}

?>
