<?php
namespace App\Controller;
// work on the filter thingy it is not working

use App\Repository\TaskRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Attribute\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'task_index')]
    // #[IsGranted('ROLE_USER')]
    public function tasksDisplay(Request $request, TaskRepository $taskRepository, PaginatorInterface $pagination): Response
    {
        $searchTerm = $request->query->get('searchbar');
        // $status = $request->query->get('status');

        if ($searchTerm) {
            $tasks = $taskRepository->createQueryBuilder('t')
                ->where('t.title LIKE :term OR t.description LIKE :term')
                ->setParameter('term', '%' . $searchTerm . '%')
                ->getQuery()
                ->getResult();
        }

        // else if ($status && $status !== 'all') {
        //     $tasks = $taskRepository->createQueryBuilder('t')
        //         ->andWhere('t.status = :status')
        //         ->setParameter('status', $status);
        // } 
        else { //remove all that is pagination please
            $tasks = $taskRepository->findAll();
            // $query = $taskRepository->findAll();
            // $pagination = $pagination-> paginate(
            //     $query,
            //     $request->query->getInt('page',1), 3
            // );
        }

        return $this->render('task.html.twig', [
            'tasks' => $tasks,
            // 'tasks'=>$pagination
        ]);
    }
}
