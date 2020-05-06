<?php

namespace App\Service;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Common\Persistence\ObjectManager;

class StatsService {
    private $manager;

    public function __construct(EntityManagerInterface $manager) {
        $this->manager = $manager;
    }

    public function getStats() {
        $users      = $this->getUsersCount();
        $ads        = $this->getAdsCount();
        $bookings   = $this->getBookingsCount();
        $comments   = $this->getCommentsCount();

        /* return compact('users', 'ads', 'bookings', 'comments'); */

        return array_merge_recursive ([ 
                'users'     => $users,
                'ads'       => $ads,
                'bookings'  => $bookings,
                'comments'  => $comments,
        ]);
    }

    //Exemple de requetes faites directement dans le controler
    /* $users = $manager->createQuery('SELECT u FROM App\Entity\User u')->getResult(); 
    $users      = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    $ads        = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\Ad u')->getSingleScalarResult();
    $bookings   = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\Booking u')->getSingleScalarResult();
    $comments   = $manager->createQuery('SELECT COUNT(u) FROM App\Entity\Comment u')->getSingleScalarResult(); */

    public function getUsersCount() {
        return $this->manager->createQuery('SELECT COUNT(u) FROM App\Entity\User u')->getSingleScalarResult();
    }

    public function getAdsCount() {
        return $this->manager->createQuery('SELECT COUNT(a) FROM App\Entity\Ad a')->getSingleScalarResult();
    }

    public function getBookingsCount() {
        return $this->manager->createQuery('SELECT COUNT(b) FROM App\Entity\Booking b')->getSingleScalarResult();
    }

    public function getCommentsCount() {
        return $this->manager->createQuery('SELECT COUNT(c) FROM App\Entity\Comment c')->getSingleScalarResult();
    }

    /* EQUIVALENT SQL         
    SELECT c.id id_commentaire,c.author_id id_auteur_commentaire, c.ad_id id_annonce_commentaire, avg(c.rating) note_commentaire, a.id id_annonce, a.title titre_annonce, u.first_name 
    from comment c
    INNER JOIN ad a on c.ad_id = a.id
    INNER JOIN user u on a.author_id = u.id
    group by id_annonce
    order by note_commentaire desc */

    /* dans l'entity commentaire ad correspond à ad_id 
    je mes dans l'alias les annonces ayant un commentaire
    JOIN c.ad a */
    public function getAdsStats($direction) {
        return $this->manager->createQuery(
            'SELECT AVG(c.rating) as note, a.title, a.id, u.firstName, u.lastName, u.picture
            FROM App\Entity\Comment c 
            JOIN c.ad a
            JOIN a.author u
            GROUP BY a
            ORDER BY note ' . $direction
        )
        ->setMaxResults(5)
        ->getResult();
    }

}