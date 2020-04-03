<?php

namespace App\Controller;
use App\Entity\User;
use App\Entity\Comment;
use App\Form\CommentType;
use App\Form\AdminCommentType;
use App\Repository\CommentRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminCommentController extends AbstractController
{
    /**
     * Liste les commentaires Repository est précédé du nom de lentite
     * @Route("/admin/comments", name="admin_comment_index")
     */
    public function index(CommentRepository $repo)
    {
        //autre méthode sans CommentRepository
        //$repo = $this->getDoctrine()->getRepository(Comment::class);

        return $this->render('admin/comment/index.html.twig', [
            'comments' => $repo->findAll()
        ]);

    }

    /**
     * edition d"un commentaires 
     * @Route("/admin/comments/{id}/edit", name="admin_comment_edit")
     * @param Comment $comment
     * @return Response
     */
    public function edit(Comment $comment,Request $request, EntityManagerInterface $manager)
    {
        //autre méthode 
        //$repo = $this->getDoctrine()->getRepository(Comment::class);

        /* $form = $this->createForm(CommentType::class, $comment); */
        $form = $this->createForm(AdminCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $manager->persist($comment);
            $manager->flush();

             $this->addFlash(
                'success',
                "Le commentaire <strong>{$comment->getId()}</strong> a bien été modifié !"
            ); 
        }

        return $this->render('admin/comment/edit.html.twig', [
            'comment' => $comment,
            'form' => $form->createView()
        ]);
    }

    /**
     * Permet de supprimer un commentaire
     * 
     * @Route("/admin/comments/{id}/delete", name="admin_comment_delete")
     *
     * @param Comment $comment
     * @param ObjectManager $manager
     * @return Response
     */
    public function delete(Comment $comment, EntityManagerInterface $manager) {
        $manager->remove($comment);
        $manager->flush();

        $this->addFlash(
            'success',
            "Le commentaire de {$comment->getAuthor()->getFullName()} a bien été supprimé !"
        );

        return $this->redirectToRoute('admin_comment_index');
    }

}
