<?php

namespace App\Controller;

use App\Entity\Category;
use App\Form\CategoryFormType;
use App\Repository\CategoryRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class CategoryController extends AbstractController
{
/**
 * @Route("/ajouter",name="category_ajouter")
 */
public function ajouterCategory(EntityManagerInterface $entityManager, Request $request){
    $category= new Category();
    $categoryForm =$this->createForm(CategoryFormType::class,$category);
    $categoryForm->handleRequest($request);
    if($categoryForm->isSubmitted() && $categoryForm->isValid()) {
        $entityManager->persist($category);
        $entityManager->flush();
        return $this->redirectToRoute('category_liste');
    }
    return $this->renderForm("category/ajouter.html.twig",
    compact('categoryForm')
    );
}
/**
 * @Route("/liste",name="category_liste")
 */
public function liste(CategoryRepository $repository){
    $liste=$repository->findAll();
    return $this->render("category/liste.html.twig",
    compact('liste')
    );
}
}