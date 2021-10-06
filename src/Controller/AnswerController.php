<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/popular", name="app_answers_popular")
     */
    public function mostPopular(AnswerRepository $answerRepository, Request $request)
    {
        $search = $request->query->get('q');
        $popularAnswers = $answerRepository->findMostPopular($search);

        return $this->render('answer/popular.html.twig', [
            'answers' => $popularAnswers
        ]);
    }

    /**
     * @Route("/answers/{id}/vote", methods="POST")
     */
    public function commentVote(Answer $answer, Request $request, EntityManagerInterface $entityManager)
    {
        $direction = $request->request->get('direction');

        if ($direction === 'up') {
            $answer->upVote();
        }
        else {
            $answer->downVote();
        }

        $entityManager->flush();

        return $this->json([
            'votes' => $answer->getVotes()
        ]);
    }
}