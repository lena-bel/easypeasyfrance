<?php

namespace App\Controller;

use App\Entity\TaskDetails;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskDetailsController extends AbstractController
{
    #[Route('/tasks/{id}', name: 'task_show')]
    // #[IsGranted('ROLE_USER')]
    public function show(TaskDetails $taskDetails): Response
    {
    // Because you mapped the relation, Doctrine will fetch the Task lazily
        $title = $taskDetails->getTaskId()->getTitle();

        return $this->render('task-detail.html.twig', [
        'details' => $taskDetails,
        'title'   => $title,
        ]);
    }
}
