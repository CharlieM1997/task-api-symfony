<?php

declare(strict_types=1);

namespace App\Controller\Task;

use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\Tools\Pagination\Paginator;
use JsonException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;

class GetTaskListController extends AbstractController
{
    /**
     * @throws JsonException
     */
    #[Route('tasks', name: 'app_task_get_tasks', methods: ['GET'])]
    public function getTasks(Request $request, EntityManagerInterface $entityManager, Serializer $serializer): JsonResponse
    {
        $pageNumber = ($request->query->get('page') ?? 1);
        $pageSize = ($request->query->get('size') ?? 100);

        $firstResult = ($pageNumber - 1) * $pageSize;

        $dql = "SELECT t FROM Task t";
        $query = $entityManager->createQuery($dql)
            ->setFirstResult($firstResult)
            ->setMaxResults($pageSize);

        $paginator = new Paginator($query, true);

        $count = $paginator->count();

        $taskArray = [];
        foreach ($paginator as $task) {
            $taskArray[] = $task;
        }

        $finalArray = ["count" => $count, "tasks" => $taskArray];

        return new JsonResponse(data: json_encode($finalArray, JSON_THROW_ON_ERROR), status: Response::HTTP_OK);
    }
}
