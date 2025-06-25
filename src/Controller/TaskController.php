<?php
namespace App\Controller;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_index')]
    public function index(TaskRepository $taskRepository): Response
    {
        $tasks = $taskRepository->findAll();

        return $this->render('task.html.twig', [
            'tasks' => $tasks,
        ]);
    }
}