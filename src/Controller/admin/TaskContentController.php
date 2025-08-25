<?php

namespace App\Controller\admin;

use DateTime;
use App\Entity\Task;
use App\Form\TaskForm;
use App\Entity\TaskDetails;
use App\Form\TaskDetailEditForm;
use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

#[Route(path: '/admin')]
class TaskContentController extends AbstractController
{
    #[Route(path: '/task-content', name: 'task-content')]
    public function displayTasks(TaskRepository $taskRepository): Response
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
        // $taskDetail = new TaskDetails();
        // $task->addTaskDetail($taskDetail);

        $form = $this->createForm(TaskForm::class, $task);
        $form->handleRequest($request);
        // dd($form);

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




    // #[Route('/task/{id}/details', name: 'task_details')]
    // public function showTaskDetails(TaskDetails $taskDetails): Response
    // {
    //     $title = $taskDetails->getTaskId()->getTitle();
    //     return $this->render('admin/task-details.html.twig', [
    //         'details' => $taskDetails,
    //         'title'   => $title,
    //     ]);
    // }
    // #[Route('/task-detail/{id}/edit', name: 'edit_task_detail')]
    // public function editTaskDetail(
    //     TaskDetails $detail,
    //     Request $request,
    //     EntityManagerInterface $em
    // ): Response {
    //     $form = $this->createForm(TaskDetailEditForm::class, $detail);
    //     $form->handleRequest($request);

    //     if ($form->isSubmitted() && $form->isValid()) {
    //         $detail->setUpdatedAt(new \DateTime());
    //         $em->flush();

    //         $this->addFlash('success', 'Task detail updated.');
    //         return $this->redirectToRoute('task_details', ['id' => $detail->getTaskId()->getId()]);
    //     }

    //     return $this->render('admin/task-detail-edit.html.twig', [
    //         'form' => $form->createView(),
    //     ]);
    // }
}
