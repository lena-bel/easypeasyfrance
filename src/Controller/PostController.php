<?php

namespace App\Controller;

use DateTime;
use App\Enum\Tags;
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
        $tags = array_map(fn($tag)=> $tag->value, Tags::cases());
        // dd($posts);
        return $this->render('post.html.twig', [
            'posts' => $posts,
            'visas'=>$visas,
            'tags'=>$tags,
            'selectedTag' => null,
        ]);
    }

    #[Route('/posts/tag/{tag}', name: 'posts_by_tag')]
    public function findPostsByTag(
        PostRepository $postRepository, 
        string $tag
    ): Response{
        $tagEnum = Tags::from($tag);

        $posts = $postRepository->findByTag($tagEnum);
        dd($tagEnum);
        return$this ->render('post.html.twig',
        [
            'posts'=>$posts,
            'selectedTag'=>$tagEnum
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

//     #[Route('/post/{id}/favorite', name: 'post_favorite', methods: ['POST'])]
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

#[Route('/post/{id}', name:"post_detail")]
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
        'commentForm' => $form ->createView(),
    ]);
}


}
