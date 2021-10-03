<?php

namespace App\Controller;

use App\Service\MarkdownHelper;
use Sentry\State\HubInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage()
    {
        return $this->render('question/homepage.html.twig', []);
    }

    /**
     * @Route("/questions/{ma_wildcard}", name="app_show")
     */
    public function show($ma_wildcard, MarkdownHelper $helper)
    {
        $question_text = "Ma pizza finalement **ne convient pas** à mon intérieur, est-il possible de la retourner au magasin ?";

        $parsedQuestion = $helper->parse($question_text);

        $answers = [
            'Je ne suis pas spécialement magicien moi !',
            'As tu essayé de fermer les fenêtres et de recommencer ?',
            'Crame tout !'
        ];

        return $this->render('question/show.html.twig', [
            'question' => sprintf('La question posée est : %s', $ma_wildcard),
            'answers' => $answers,
            'question_text' => $parsedQuestion
        ]);
    }
}

