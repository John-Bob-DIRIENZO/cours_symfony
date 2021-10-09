<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class ApiController extends AbstractController
{
    /**
     * @Route("/api", name="api")
     */
    public function index(QuestionRepository $repository): Response
    {
        $questions = $repository->findAllAskedOrderByNewest();
        return $this->json($questions, 200, [], [
            'groups' => ['main']
        ]);
    }
}
