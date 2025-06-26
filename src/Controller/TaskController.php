<?php

namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_index')]
    public function tasksDisplay(Request $request, TaskRepository $taskRepository): Response
    {
        $searchTerm = $request->query->get('searchbar');
        if ($searchTerm) {
        $tasks = $taskRepository->createQueryBuilder('t')
            ->where('t.title LIKE :term OR t.description LIKE :term')
            ->setParameter('term', '%' . $searchTerm . '%')
            ->getQuery()
            ->getResult();
    } else {
        $tasks = $taskRepository->findAll();
    }

        return $this->render('task.html.twig', [
            'tasks' => $tasks,
        ]);
    }

   
}
