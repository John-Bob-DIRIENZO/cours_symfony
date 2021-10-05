<?php

namespace App\Controller;

use App\Repository\QuestionRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class SearchController extends AbstractController
{
    /**
     * @Route("/search-question", name="search-question")
     */
    public function search_question(QuestionRepository $questionRepository, Request $request): Response
    {
        $search = $request->query->get('s');
        $questions = $questionRepository->searchByName($search);

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions,
        ]);
    }
}
