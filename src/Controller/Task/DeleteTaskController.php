<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Repository\TaskRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;

class DeleteTaskController extends AbstractController
{
    #[Route('/tasks/delete/{id}', name: 'app_task_delete', methods: ['DELETE'])]
    public function deleteTask(?int $id, TaskRepository $taskRepository, EntityManagerInterface $entityManager): Response
    {
        $task = $taskRepository->find($id);

        if (is_null($task)) {
            throw new NotFoundHttpException("Task not found: " . $id);
        }

        $entityManager->remove($task);
        $entityManager->flush();

        return new Response(data: "", status: Response::HTTP_OK);
    }
}
