<?php
namespace App\Controller;

use App\Repository\PostRepository;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_USER')]
class PostController extends AbstractController // extending AbstractController allow access to some frequently used utilities such as render() and redirectToRoute(), it facilitates the development of controllers
{
    #[Route('/post', name:"posts_index")]
    public function postsDisplay(PostRepository $postRepository){
        $posts = $postRepository-> findAll();

        return $this->render('post.html.twig',[
            'posts'=>$posts,
        ]);

    }
    #[Route('/post/new-post', name:"new-post")]
    public function createNewPost(){
        return $this->render('new-post.html.twig');
    }

}