<?php

namespace App\Controller;

use App\Entity\Question;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class QuestionController extends AbstractController
{
    /**
     * @Route("/", name="app_homepage")
     * @return Response
     */
    public function homepage(QuestionRepository $repository)
    {
        $questions = $repository->findAllAskedOrderByNewest();

        return $this->render('question/homepage.html.twig', [
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {
        return new Response('Un jour on fera ça...');
    }

    /**
     * @Route("/questions/{slug}", name="app_show")
     */
    public function show(Question $question)
    {
        $answers = $question->getAnswers();

        return $this->render('question/show.html.twig', [
            'question' => $question
        ]);
    }

    /**
     * @Route("/questions/{slug}/vote", name="app_question_vote", methods="POST")
     * @return Response
     */
    public function questionVote(Question $question, Request $request, EntityManagerInterface $entityManager)
    {
        $vote = $request->request->get('vote');
        if ($vote === 'up') {
            $question->upVote();
        }
        elseif ($vote === 'down') {
            $question->downVote();
        }

        $entityManager->flush();

        return $this->redirectToRoute('app_show', [
            'slug' => $question->getSlug()
        ]);
    }

    /**
     * @Route("/questions/{id}/delete")
     */
    public function questionDelete($id, EntityManagerInterface $entityManager)
    {
        // getReference ne marche qu'avec un id
        $question = $entityManager->getReference(Question::class, $id);
        $entityManager->remove($question);
        $entityManager->flush();

        return $this->redirectToRoute('app_homepage');
    }
}

