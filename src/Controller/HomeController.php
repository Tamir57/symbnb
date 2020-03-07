<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class HomeController extends Controller {

    /**
     * @Route("/hello/{prenom}/age/{age}", name ="hello")
     * @Route("/hello" , name ="hello_base")
     * @Route("/hello/{prenom}", name ="hello_prenom")
     */
    public function hello($prenom = "anonyme",$age = 0) {
        return $this->render(
            'hello.html.twig',
            [
                'prenom' => $prenom,
                'age' => $age
            ]
            );
    }

    /** 
     * @Route("/", name="homepage")
     */
    public function home() {

        $prenoms = ["lior" => 31,"joseph" => 12,"julien" => 57];

        return $this->render(
            'home.html.twig',
            [ 
                'title' => "bonjour à tous",
                'age' => 11,
                'tableau' => $prenoms
            ]
        );
    }

}

?>
