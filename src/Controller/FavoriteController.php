<?php
namespace App\Controller;

use App\Entity\Post;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class FavoriteController extends AbstractController 
{
    #[Route(path:'/post/{id}/favorite', name:'post_favorite')]
    public function toogleFavorite(
        Post $post,
        EntityManagerInterface $em
    ) :Response{


        /** @var \App\Entity\User $user */
        $user = $this->getUser();

        if(!$user){
            throw $this->createAccessDeniedException();
        }

        if($user->getFavoritePosts()->contains($post)){
            $user->removeFavoritePost($post);

        } else{
            $user->addFavoritePost($post);
        }

        $em->persist($user);
        $em->flush();
        
        return $this->redirectToRoute('posts_index',[
            'id' =>$post->getId()
        ]);
    }
}