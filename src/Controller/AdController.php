<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Form\AdType;
use App\Repository\AdRepository;
use Symfony\Component\HttpFoundation\Request;
/* use Doctrine\Common\Persistence\ObjectManager; */
use Doctrine\ORM\EntityManagerInterface;
/* use Doctrine\Common\Persistence\EntityManagerInterface; */
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
/* use Symfony\Bundle\FrameworkBundle\Controller\Controller; */
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdController extends AbstractController
{
    /**
     * @Route("/ads", name="ads_index")
     */
    public function index(AdRepository $repo)
    {
        /* remplacer par un paramètre dans la function index au dessus  
        $repo = $this->getDoctrine()->getRepository(Ad::class); */

        $ads = $repo->findAll();

        return $this->render('ad/index.html.twig', [
            'ads' => $ads
        ]);
    }

    /** 
     * Permet de créer une annoce
     * 
     * @Route("/ads/new", name ="ads_create")
     * 
     * @return Response
     */
    public function create(Request $request, EntityManagerInterface $manager) {
        $ad = new Ad();
        
        $form = $this->createForm(AdType::class, $ad);

        /* récupérer information formulaire 
        $request->request->get('title');
        */

        //pour récuperer les infos du formulaire
        $form->handleRequest($request);     
        //dump($ad);

        if($form->isSubmitted() && $form->isValid()) {
            /* cette ligne est remplace par EntityManagerInterface $manager dans les param de la méthode create
            $manager = $this->getDoctrine()->getManager(); */

            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            //assigné une annonce a un utilisateur
            $ad->setAuthor($this->getUser());

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "L'annonce <strong>{$ad->getTitle()}</strong> a bien été enregistrée !"
            );

            return $this->redirectToRoute('ads_show',[
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/new.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet d'afficher le formulaire d'édition
     *
     * @Route("/ads/{slug}/edit", name="ads_edit")
     * 
     * 
     * @return Response
     */
    public function edit(Ad $ad, Request $request, EntityManagerInterface $manager){

        $form = $this->createForm(AdType::class, $ad);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            foreach($ad->getImages() as $image) {
                $image->setAd($ad);
                $manager->persist($image);
            }

            $manager->persist($ad);
            $manager->flush();

            $this->addFlash(
                'success',
                "Les modifications de l'annonce <strong>{$ad->getTitle()}</strong> ont bien été enregistrées !"
            );

            return $this->redirectToRoute('ads_show', [
                'slug' => $ad->getSlug()
            ]);
        }

        return $this->render('ad/edit.html.twig', [
            'form' => $form->createView(),
            'ad' => $ad
        ]);
    }

    /**
     * Permet d'afficher une seule annonce
     *
     * @Route ("/ads/{slug}", name="ads_show")
     *  
     * @return Response
     */
    /* public function show($slug, AdRepository $repo) {
        // Je récupère l'annonce qui correspond au slug
        $ad = $repo->findOneBySlug($slug);

        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    } */

    /**
     * Permet d'afficher une seule annonce ici en utilisant ParamConvert soit injection de dépendance
     * La méthode au dessus ne lutilise pas mais marche aussi ...
     *
     * @Route ("/ads/{slug}", name="ads_show")
     *  
     * @return Response
     */
    public function show(Ad $ad) {
        return $this->render('ad/show.html.twig', [
            'ad' => $ad
        ]);
    }

}
