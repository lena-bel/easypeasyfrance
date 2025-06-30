<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;
use App\Repository\PostRepository;


class PostController extends AbstractController // extending AbstractController allow access to some frequently used utilities such as render() and redirectToRoute(), it facilitates the development of controllers
{
    #[Route('/posts', name:"posts_index")]
    public function postsDisplay(PostRepository $postRepository){
        $posts = $postRepository-> findAll();

        return $this->render('post.html.twig',[
            'posts'=>$posts,
        ]);


    }

}