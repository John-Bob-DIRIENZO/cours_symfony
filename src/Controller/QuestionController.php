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
        return $this->render('question/homepage.html.twig', []);
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

        dump($this);

        return $this->render('question/show.html.twig', [
            'question' => sprintf('La question posée est : %s', $ma_wildcard),
            'answers' => $answers
        ]);
    }
}

