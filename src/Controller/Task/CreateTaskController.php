<?php

declare(strict_types=1);

namespace App\Controller\Task;

use App\Entity\Task;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Serializer\Exception\ExceptionInterface;
use Symfony\Component\Serializer\Serializer;
use Symfony\Component\Validator\Validator\ValidatorInterface;

class CreateTaskController extends AbstractController
{
    /**
     * @throws ExceptionInterface
     */
    #[Route('/tasks/create', name: 'app_task_create', methods: ['POST'])]
    public function createTask(Request $request, Serializer $serializer, ValidatorInterface $validator, EntityManagerInterface $entityManager): Response
    {
        $content = $request->getContent();

        $task = $serializer->deserialize($content, Task::class, 'json');

        $errors = $validator->validate($task);

        if (count($errors) > 0) {
            $errorsString = (string) $errors;

            return new JsonResponse("Bad request, " . $errorsString, Response::HTTP_BAD_REQUEST, [], true);
        }

        $entityManager->persist($task);
        $entityManager->flush();

        $id = $entityManager->getRepository(Task::class)->findBy(['id' => $task->getId()]) ?? null;

        if (is_null($id)) {
            // something went wrong
            throw new NotFoundHttpException("Shouldn't be here");
        }

        return new JsonResponse($serializer->serialize($task, 'json'), Response::HTTP_CREATED, [], true);
    }
}
