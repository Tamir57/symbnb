<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBag;
use Symfony\Component\HttpFoundation\Session\Flash\FlashBagInterface;
use Symfony\Component\HttpFoundation\Session\SessionInterface;

class AdminAdController extends AbstractController
{
    /**
     * Route("/admin/ads/{page<\d+>1}", name="admin_ads_index")  ET dans ce cas dans la function index nous pouvons supprimer $page=1
     * @Route("/admin/ads/{page}", name="admin_ads_index", requirements={"page": "\d+"})
     */
    public function index(AdRepository $repo, $page = 1)
    {
        // Méthode find : permet de retrouver un enregistrement par son identifiant
       /*  $ad = $repo->find(380); */

       //on peut ajouter plusieur filtre
       //un resultat
/*         $ad = $repo->findOneBy([
             "author" => 92 
        ]); */

  /*       dump($ad); */

        //plusieur resultat
/*         $ads = $repo->findBy([
             'id' => 380 
            "author" => 92
             "title" => "Voluptatem vel est est." 
        ]); */

/*         $ads = $repo->findBy([], [], 5, 0);

        dump($ads); */

        //METHODE SANS SERVICE, LA METHODE AVEC SERVICE EST DANS ADMINADCONTROLLER OU ADMINCOMMENTCONTROLLER
        $limit = 10;
        $start = $page * $limit - $limit;
        $total = Count($repo->findAll());
        $pages = ceil($total / $limit);

        return $this->render('admin/ad/index.html.twig', [
            'ads' => $repo->findBy([],[],$limit,$start),
            'pages' => $pages,
            'page' => $page
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     * 
     * @Route("/admin/ads/{id}/edit", name="admin_ads_edit")
     *
     * @param Ad $ad
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager) {
        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($ad);
            $manager->flush();

             $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            ); 

        }

        return $this->render('admin/ad/edit.html.twig', [
            'ad' => $ad,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer une annonce
     *
     * @Route("/admin/ads/{id}/delete", name="admin_ads_delete")
     * 
     * @param Ad $ad
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Ad $ad, EntityManagerInterface $manager) {
        
        if(count($ad->getBookings()) > 0) {
            $this->addFlash(
                'warning',
                "Vous ne pouvez pas supprimer l'annonce <strong>{$ad->getTitle()}</strong> car elle possède déjà des réservations !"
            );
        } else {
            $manager->remove($ad);
            $manager->flush();
    
            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            );
        }

          /*$manager->remove($ad);
        $manager->flush();
    
          $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été supprimée !"
            ); */

        
        return $this->redirectToRoute('admin_ads_index');
    }
}
