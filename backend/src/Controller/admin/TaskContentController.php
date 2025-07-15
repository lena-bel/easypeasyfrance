<?php

namespace App\Controller\admin;

use App\Repository\TaskRepository;

use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

use App\Entity\Task;
use App\Form\TaskType;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;


#[Route(path: '/admin')]
class TaskContentController extends AbstractController
{
    #[Route(path: '/task-content')]
    public function displayTasks(TaskRepository $taskRepository)
    {
        $tasks = $taskRepository->findAllTasks();
        // dd($tasks);

        return $this->render('admin/task-content.html.twig', [
            'tasks' => $tasks
        ]);
    }

    
    #[Route(path: '/task/{id}/edit', name: 'admin_task_edit')]
    public function editTask(
        Task $task,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(Task::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('admin_task_list'); // adjust to your route
        }

        return $this->render('admin/edit_task.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
}
