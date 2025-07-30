<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Repository\TaskRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Serializer;

class GetTaskController extends AbstractController
{
    #[Route('tasks/{id}', name: 'app_get_task', methods: ['GET'])]
    public function getTask(?int $id, TaskRepository $taskRepository, Serializer $serializer): Response
    {
        $task = $taskRepository->find($id);

        if (is_null($task)) {
            throw new NotFoundHttpException("Task not found: " . $id);
        }

        return new JsonResponse(data: $serializer->serialize($task, 'json'), status: Response::HTTP_OK);
    }
}
