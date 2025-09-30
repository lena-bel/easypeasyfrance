<?php

namespace App\Controller;

use App\Entity\Comment;
use App\Entity\Post;
use App\Form\CommentForm;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class CommentController extends AbstractController
{
    #[Route(name: 'addComment', path: '/post/{id}/comment')]
    public function addComment(
        Request $request,
        EntityManagerInterface $em,
        Post $post
    ) {
        $comment = new Comment();
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);


        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setCreationDate(new \DateTime());

            $parentId = $request->request->get('parent_id');
            if ($parentId) {
                $parentComment = $em->getRepository(Comment::class)->find($parentId);
                if ($parentComment) {
                    $comment->setParent($parentComment);
                }
            }

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Your comment has been added!');
        }
        return $this->redirectToRoute('post_detail', ['id' => $post->getId()]);
    }

    #[Route('/comment/{id}/delete', name: 'delete_comment', methods: ['POST'])]
    public function deleteComment(Comment $comment, EntityManagerInterface $em)
    {
        $user = $this->getUser();

        if ($user === $comment->getUser() || $this->isGranted('ROLE_ADMIN')) {
            $em->remove($comment);
            $em->flush();
            $this->addFlash('success', 'Comment deleted successfully!');
        } else {
            $this->addFlash('error', 'You are not allowed to delete this comment.');
        }

        return $this->redirectToRoute('posts_index', ['id' => $comment->getPost()->getId()]);
    }
}
