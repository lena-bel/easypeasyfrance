<?php

namespace App\Controller;

use App\Entity\Task;
use App\Repository\TaskRepository;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskDetailsController extends AbstractController
{
    #[Route('/tasks/{id}', name: 'task-details')]
   
public function taskDetails(int $id, TaskRepository $taskRepository): Response
{
    $task = $taskRepository->findTaskWithDetails($id);

    if (!$task) {
        throw $this->createNotFoundException('Task not found');
    }
    // dd($task->getSteps());

    return $this->render('task-detail.html.twig', [
        'task' => $task,
        'steps' => $task->getSteps(),
        'documents' => $task->getDocuments(),
        'links' => $task->getLinks(),
    ]);
}
}
