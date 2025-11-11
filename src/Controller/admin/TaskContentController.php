<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\Task;
use App\Form\TaskForm;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[IsGranted('ROLE_ADMIN')]
#[Route(path: '/admin')]
class TaskContentController extends AbstractController
{
    #[Route(path: '/task-content', name: 'task-content')]
    public function displayTasks(
        TaskRepository $taskRepository,
        Request $request
        ): Response
    {
        $search = $request ->query ->get('search');
        if($search){
            $tasks = $taskRepository ->findTasksBySearch($search);
        } else{
            $tasks = $taskRepository->findAllTasks();
        }

        return $this->render('admin/task-content.html.twig', [
            'tasks' => $tasks
        ]);
    }


    #[Route('/task/new', name: 'admin_task_new')]
    public function newTask(
        Request $request, 
        EntityManagerInterface $em
        ): Response
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
            'form' => $form->createView()
        ]);
    }


    #[Route('/task/{id}/delete', name: 'delete-task', methods: ['POST'])]
    public function deleteTask(
        Task $task,
        EntityManagerInterface $em,
        Request $request
    ): Response {
        $tokenId = 'delete' . $task->getId(); //concatenate the delete with the user id eg delete12
        $submittenToken = $request->request->get('_token'); //Gets the value of the _token field that was submitted through a POST form which is why in the twig the delete has to be a form
        if ($this->isCsrfTokenValid($tokenId, $submittenToken)) {
            $em->remove($task, true);
            $em->flush();
            $this->addFlash('success', 'task deleted successfully.');
        } else {
            $this->addFlash('error', "the task was not deleted");
        }
        return $this->redirectToRoute('task-content');
    }




    #[Route('/task/{id}/details', name: 'task_details')]
    public function showTaskDetails(
        TaskRepository $taskRepository,
        Task $task
    ): Response {
        $tasks = $taskRepository->findTaskWithDetails($task->getId());
        $steps = $task->getSteps();
        $documents = $task->getDocuments();
        $externalLinks = $task->getLinks();
        return $this->render('admin/task-details.html.twig', [
            'steps' => $steps,
            'task'   => $tasks,
            'documents' => $documents,
            'externalLinks' => $externalLinks
        ]);
    }

    #[Route(path: '/task/{id}/edit', name: 'task-edit')]
    public function editTask(
        Task $task,
        Request $request,
        EntityManagerInterface $em
    ): Response {
        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            $em->flush();

            $this->addFlash('success', 'Task updated successfully!');
            return $this->redirectToRoute('task-content');
        }
        return $this->render('admin/task-edit.html.twig', [
            'task' => $task,
            'form' => $form->createView(),
        ]);
    }
}
