<?php

namespace App\Controller;

use App\Entity\Auto;
use App\Form\AutoType;
use Doctrine\ORM\EntityManagerInterface;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class AutoController extends AbstractController
{
    #[Route('/auto', name: 'app_auto')]
    public function index(): Response
    {
        return $this->render('auto/index.html.twig', [
            'controller_name' => 'AutoController',
        ]);
    }

    #[Route('/display', name:'display')]
    public function display(EntityManagerInterface $entityManager):Response
    {
        $autos=$entityManager->getRepository(Auto::class)->findAll();
        //dd($autos);
        return $this->render('auto/display.html.twig',[
            'autos'=>$autos,
            'opdracht'=>'Toets CRUD'
        ]);
    }

    #[Route('/update/{id}',name:'update')]
    public function update(Request $request,EntityManagerInterface $entityManager, int $id)
    {
        $auto=$entityManager->getRepository(Auto::class)->find($id);

        $form=$this->createForm(AutoType::class,$auto);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $auto=$form->getData();
            $entityManager->persist($auto);
            $entityManager->flush();
            return $this->redirectToRoute('display');
        }
        return $this->renderForm('auto/insert.html.twig', [
            'auto_form'=>$form
        ]);
    }

    #[Route('/insert', name:'insert')]
    public function insert(Request $request,EntityManagerInterface $entityManager):Response
    {
        $auto=new Auto();
        //dd($request);
        $form=$this->createForm(AutoType::class,$auto);
        $form->handleRequest($request);

        if($form->isSubmitted()) {
            $auto=$form->getData();
            //dd($auto);
            $entityManager->persist($auto);
            $entityManager->flush();
            return $this->redirectToRoute('display');
        }
        return $this->renderForm('auto/insert.html.twig', [
            'auto_form'=>$form
        ]);
    }

}
