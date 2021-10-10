<?php

namespace App\Controller;

use App\Entity\Answer;
use App\Repository\AnswerRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

/**
 * @IsGranted("ROLE_ADMIN_ANSWER")
 */
class AnswerController extends AbstractController
{
    /**
     * @Route("/answers/popular", name="app_answers_popular")
     */
    public function mostPopular(AnswerRepository $answerRepository, Request $request, PaginatorInterface $paginator)
    {
        $search = $request->query->get('q');
        $popularAnswers = $answerRepository->findMostPopularQueryBuilder($search);

        $pagination = $paginator->paginate(
            $popularAnswers, /* query NOT result */
            $request->query->getInt('page', 1), /*page number*/
            10 /*limit per page*/
        );


        return $this->render('answer/popular.html.twig', [
            'answers' => $pagination
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
        } else {
            $answer->downVote();
        }

        $entityManager->flush();

        return $this->json([
            'votes' => $answer->getVotes()
        ]);
    }
}