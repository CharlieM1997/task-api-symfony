<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;

final class TaskController extends AbstractController
{
    #[Route('/tasks', name: 'app_task')]
    public function index(): Response
    {
        $json = '{
            "tasks": "Soon!"
        }';
        return new JsonResponse(data: $json, status: 200, json: true);
    }
}
