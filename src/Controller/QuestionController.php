<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/")
     * @return Response
     */
    public function homepage()
    {
        return new Response('Hello World (again) !');
    }

    /**
     * @Route("/questions/{ma_wildcard}")
     */
    public function show($ma_wildcard)
    {
        $answers = [
            'Je ne suis pas spécialement magicien moi !',
            'As tu essayé de fermer les fenêtres et de recommencer ?',
            'Crame tout !'
        ];

        return $this->render('question/show.html.twig', [
            'question' => sprintf('La question posée est : %s', $ma_wildcard),
            'answers' => $answers
        ]);
    }
}

