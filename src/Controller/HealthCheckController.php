<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Routing\Annotation\Route;

class HealthCheckController extends AbstractController
{
    /**
     * @Route("/server-health", name="league-server-health", methods={"GET"})
     * 
     * @return JsonResponse 
     */
    public function serverHealth()
    {
        return new JsonResponse(
            [
                'status' => 'OK'
            ]
        );
    }
}
