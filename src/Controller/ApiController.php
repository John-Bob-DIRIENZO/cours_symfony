<?php

namespace App\Controller;

use App\Entity\Question;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api_index")
     */
    public function index(): Response
    {
        return $this->json([
            'message' => "Hello World"
        ]);
    }

    /**
     * @param Question $question
     * @return \Symfony\Component\HttpFoundation\JsonResponse
     * @Route("/api/{id}", name="api_show")
     */
    public function show(Question $question)
    {
        return $this->json($question);
    }
}
