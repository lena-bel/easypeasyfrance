<?php

namespace App\Controller;

use App\Entity\Post;
use App\Form\PostForm;
use App\Repository\PostRepository;
use App\Repository\VisaTypeProfileRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

// #[IsGranted('ROLE_USER')]
class PostController extends AbstractController // extending AbstractController allow access to some frequently used utilities such as render() and redirectToRoute(), it facilitates the development of controllers
{
    #[Route('/post', name:"posts_index")]
    public function postsDisplay(
        PostRepository $postRepository,
        VisaTypeProfileRepository $visaRepository
    ): Response
    {
        $posts = $postRepository-> findAllPosts();
        $visas= $visaRepository->findAll();
        return $this->render('post.html.twig', [
            'posts' => $posts,
            'visas'=>$visas
        ]);
    }
    #[Route('/post/new-post', name:"new-post")]
    public function createNewPost(
        Request $request,
        EntityManagerInterface $em
    ): Response
    {
        $post = new Post();
        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $em->persist($post);
            $post->setUser($this->getUser());
            $post->setCreationDate(new DateTime());
            $post->setPublicationDate(new DateTime());
            $post->setCommentsNumber('0');
            $em->flush();
            $this->addFlash('success', "your post was posted successfully!");
            return $this->redirectToRoute('posts_index');
        }

        return $this->render('new-post.html.twig',[
            'form'=>$form
        ]);
    }

}
