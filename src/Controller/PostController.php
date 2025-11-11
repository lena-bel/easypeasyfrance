<?php

namespace App\Controller;

use DateTime;
use App\Entity\Post;
use App\Form\PostForm;
use App\Entity\Comment;
use App\Form\CommentForm;
use App\Repository\PostRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\VisaTypeProfileRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class PostController extends AbstractController // extending AbstractController allow access to some frequently used utilities such as render() and redirectToRoute(), it facilitates the development of controllers
{
    #[Route('/post', name: "posts_index")]
    public function postsDisplay(
        PostRepository $postRepository,
        VisaTypeProfileRepository $visaRepository
    ): Response {
        $posts = $postRepository->findAllPosts();
        $visas = $visaRepository->findAll();
        // dd($posts);
        return $this->render('post.html.twig', [
            'posts' => $posts,
            'visas' => $visas,
        ]);
    }

    #[Route('/post/new-post', name: "new-post")]
    public function createNewPost(
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $post = new Post();
        $form = $this->createForm(PostForm::class, $post);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($post);
            $post->setUser($this->getUser());
            $post->setCreationDate(new DateTime());
            $post->setPublicationDate(new DateTime());
            $post->setCommentsNumber('0');
            $em->flush();
            $this->addFlash('success', "your post was posted successfully!");
            return $this->redirectToRoute('posts_index');
        }

        return $this->render('new-post.html.twig', [
            'form' => $form
        ]);
    }

    #[Route('/post/search', name: "posts_search")]
    public function searchPosts(
        Request $request,
        PostRepository $postRepository,
        VisaTypeProfileRepository $visaRepository
    ): Response {
        $keyword = $request->query->get('q', '');

        $posts = $keyword
            ? $postRepository->searchPosts($keyword)
            : $postRepository->findAllPosts();

        $visas = $visaRepository->findAll();

        return $this->render('post.html.twig', [
            'posts' => $posts,
            'visas' => $visas,
            'keyword' => $keyword,
        ]);
    }

   


    

    #[Route('/post/{id}', name: "post_detail")]
    public function postDetail(Post $post, Request $request, EntityManagerInterface $em): Response
    {
        $comment = new Comment();
        $form = $this->createForm(CommentForm::class, $comment);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $comment->setUser($this->getUser());
            $comment->setPost($post);
            $comment->setCreationDate(new \DateTime());

            $em->persist($comment);
            $em->flush();

            $this->addFlash('success', 'Your comment has been added!');
            return $this->redirectToRoute('post_detail', ['id' => $post->getId()]);
        }

        return $this->render('postComment.html.twig', [
            'post' => $post,
            'commentForm' => $form->createView(),
        ]);
    }

    #[Route(path:'/my-posts', name:'my_posts')]
    public function myPosts() :Response{

        $user = $this->getUser();
    if (!$user instanceof \App\Entity\User) {
        throw $this->createAccessDeniedException();
    }
    $posts = $user->getPosts();

    return $this->render('myPosts.html.twig', [
        'posts' => $posts,
        'favoritePosts'=>$user->getFavoritePosts()
    ]);
    }

    #[Route('/post/{id}/delete', name: 'post_delete', methods: ['POST'])]
public function delete(Post $post, EntityManagerInterface $em, Request $request): Response
{
    $user = $this->getUser();
    if (!$user instanceof \App\Entity\User || $post->getUser() !== $user) {
        throw $this->createAccessDeniedException();
    }

    if ($this->isCsrfTokenValid('delete' . $post->getId(), $request->request->get('_token'))) {
        $em->remove($post);
        $em->flush();
    }

    return $this->redirectToRoute('my_posts');
}











}











//     #[Route('/post/{id}/favorite', name: 'post_favorite')]
    // public function toggleFavorite(Post $post, EntityManagerInterface $em): Response
    // {
    //     /** @var \App\Entity\User $user */
    //     $user = $this->getUser();
    //     if ($user->getFavoritePosts()->contains($post)) {
    //         $user->removeFavoritePost($post);
    //     } else {
    //         $user->addFavoritePost($post);
    //     }

    //     $em->persist($user);
    //     $em->flush();

    //     return $this->redirectToRoute('posts_index'); 
    // }


