<?php

namespace App\Controller;

use App\Entity\Wish;
use App\Form\WishFormType;
use App\Repository\WishRepository;
use App\Services\AppCensurator;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class WishController extends AbstractController
{
/**
 * @Route("/list",name="wish_list")
 */
public function list(WishRepository $repo){

    //TODO:recuper tous les souhaits en BDD
    $list=$repo->findBy([],["id"=>"DESC"]);
    return $this->render("wish/list.html.twig",
        compact("list")

    );
}
/**
 * @Route("/detail/{souhait}",name="wish_detail")
 * {souhait} est l'id du souhait
 */
public function detail(Wish $souhait){
   //TODO: recuper un seul souhait en BDD
    //la récuperation du souhait se fait tout seul a partir du lien cliqué
    //$detail=$repository->findOneBy(["id"=>$id]);
    return $this->render("wish/detail.html.twig",
    compact("souhait")

    );
}
    /**
     * @Route("/listdecetteannee",name="wish_list_de_cette_annee")
     */
    public function listDeCetteAnnee(WishRepository $repo){

        //TODO:recuper tous les souhaits en BDD
        $list=$repo->findCurrentYearWish();
        return $this->render("wish/list.html.twig",
            compact("list")

        );
    }
    /**
     * @IsGranted("ROLE_USER")
     * @Route("/form",name="wish_form")
     */
    public function formulaire(EntityManagerInterface $entityManager,
                               Request $request,
                               AppCensurator $censurator){
        $wish=new Wish();
        $wish->setAuthor($this->getUser()->getUserIdentifier());
        $monFormulaire=$this->createForm(WishFormType::class, $wish);
        $monFormulaire->handleRequest($request);

        if($monFormulaire->isSubmitted() && $monFormulaire->isValid()){
            $description=$wish->getDescription();
            $title=$wish->getTitle();
            $author=$wish->getAuthor();
            $descCens=$censurator->purify($description);
            $titleCens=$censurator->purify($title);
            $authorCens=$censurator->purify($author);
            $wish->setDateCreated(new \DateTime());
            $wish->setIsPublished(true);
            $wish->setDescription($descCens);
            $wish->setTitle($titleCens);
            $wish->setAuthor($authorCens);


            $entityManager->persist($wish);
            $entityManager->flush();
            $this->addFlash("success","idea successfully added");
            return $this->redirectToRoute('wish_detail',
               ['souhait'=>$wish->getId()]
            );
        }

    return $this->renderForm("wish/form.html.twig",
    compact("monFormulaire")
    );
    }
    /**
     * @Route("/ajouter",name="wish_ajouter")
     */
public function ajouter(EntityManagerInterface $entityManager){
    $monWish1= new Wish();
    $monWish1->setTitle('Be rich');
    $monWish1->setDescription('Je veux pouvoir depenser sans compter');
    $monWish1->setAuthor('Simon');
    $monWish1->setIsPublished(true);
    $monWish1->setDateCreated(new \DateTime());
    $entityManager->persist($monWish1);
    $monWish2= new Wish();
    $monWish2->setTitle('Be kind');
    $monWish2->setDescription('Arreter d\'être un connard avec tout le monde');
    $monWish2->setAuthor('Simon');
    $monWish2->setIsPublished(true);
    $monWish2->setDateCreated(new \DateTime());
    $entityManager->persist($monWish2);
    $entityManager->flush();
    return $this->render("wish/ajouter.html.twig");
}
/**
 * @Route("/supprimer/{souhait}",name="wish_supprimer")
 */
public function supprimer(Wish $souhait, EntityManagerInterface $em){
    if($souhait!=null) {
        $em->remove($souhait);
        $em->flush();
    }
   return $this->redirectToRoute("wish_list");
}
}
