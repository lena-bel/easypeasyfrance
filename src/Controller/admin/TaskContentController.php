<?php

namespace App\Controller\admin;

use App\Entity\Task;

use App\Form\TaskForm;
use PhpParser\Node\Name;
use App\Repository\TaskRepository;
use DateTime;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;


#[Route(path: '/admin')]
class TaskContentController extends AbstractController
{
    #[Route(path: '/task-content', name: 'task-content')]
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
        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $task->setUpdatedAt(new DateTime());
            $em->flush();

            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('task-content');
        }

        return $this->render('admin/task-edit.html.twig', [
            'form' => $form->createView(),
            'task' => $task,
        ]);
    }
    #[Route('/task/new', name: 'admin_task_new')]
    public function newTask(Request $request, EntityManagerInterface $em): Response
    {
        $task = new Task();

        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $em->persist($task);
            $task->setCreatedAt(new DateTime());
            $task->setUpdatedAt(new DateTime());
            $em->flush();

            $this->addFlash('success', 'New task created successfully!');
            return $this->redirectToRoute('task-content');
        }

        return $this->render('admin/new-task.html.twig', [
            'form' => $form->createView(),
        ]);
    }
    #[Route('/task/{id}/delete', name: 'admin_task_delete', methods: ['POST'])]
    public function deleteTask(Task $task, Request $request, EntityManagerInterface $em): RedirectResponse
    {
        // Check CSRF token for security
        if ($this->isCsrfTokenValid('delete-task-' . $task->getId(), $request->request->get('_token'))) {
            $em->remove($task);
            $em->flush();
            $this->addFlash('success', 'Task deleted successfully!');
        } else {
            $this->addFlash('error', 'Invalid CSRF token.');
        }

        return $this->redirectToRoute('task-content'); // Your task list route
    }
}
