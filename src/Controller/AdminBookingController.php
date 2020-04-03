<?php

namespace App\Controller;

use App\Entity\Booking;
use App\Form\AdminBookingType;
use App\Service\PaginationService;
use App\Repository\BookingRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminBookingController extends AbstractController
{
    /**
     * 
     * @Route("/admin/bookings/{page}", name="admin_booking_index", requirements={"page": "\d+"})
     */
    public function index(BookingRepository $repo, PaginationService $pagination, $page = 1)
    {        
        //METHODE AVEC SERVICE, LA METHODE SANS SERVICE EST DANS ADMINADCONTROLLER
        $pagination->setEntityClass(Booking::class)
                   ->setPage($page);

        return $this->render('admin/booking/index.html.twig', [
            //L'on peut ici envoyer uniquement 'pagination' au lieu des trois ligne du dessous
            /* 'bookings' => $pagination->getData(),
            'pages' => $pagination->getPages(),
            'page' => $page */
            
            'pagination' => $pagination
        ]); 
    }

    /**
     * Permet d'editer les booking 
     * @Route("/admin/bookings/{id}/edit", name="admin_booking_edit")
     */
    public function edit(Booking $booking, Request $request, EntityManagerInterface $manager)
    {        
        $form = $this->createForm(AdminBookingType::class, $booking);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            /* $booking->setAmount($booking->getAd()->getPrice() * $booking->getDuration()); */

            /* dans lentity on recalcul quand la valeur est a zero */
            $booking->setAmount(0);

            $manager->persist($booking);
            $manager->flush();

            $this->addFlash(
                'success', 
                "La réservation n°{$booking->getId()} a bien été modifiée" 
            );

            return $this->redirectToRoute("admin_booking_index");
        }

        return $this->render('admin/booking/edit.html.twig', [
            'form' => $form->createView(),
            'booking' => $booking
        ]); 
    }

    /**
     * Permet de supprimer une réservation
     * 
     * @Route("/admin/bookings/{id}/delete", name="admin_booking_delete")
     *
     * @return Response
     */
    public function delete(Booking $booking, EntityManagerInterface $manager) {
        $manager->remove($booking);
        $manager->flush();

        $this->addFlash(
            'success',
            "La réservation a bien été supprimée"
        );

        return $this->redirectToRoute("admin_booking_index");
    }
}
