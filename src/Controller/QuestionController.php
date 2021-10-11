<?php

namespace App\Controller;

use App\Entity\Question;
use App\Form\QuestionFormType;
use App\Repository\QuestionRepository;
use Doctrine\ORM\EntityManagerInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
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
     * @param QuestionRepository $repository
     * @return Response
     * @Route("/questions/list", name="app_question_list")
     */
    public function listAll(QuestionRepository $repository)
    {
        $questions = $repository->findBy([], ['createdAt' => 'DESC']);
        return $this->render('question/list.html.twig', [
            'questions' => $questions
        ]);
    }

    /**
     * @Route("/questions/new", name="app_question_new")
     * @IsGranted("ROLE_USER")
     */
    public function new(Request $request, EntityManagerInterface $em): Response
    {
        $form = $this->createForm(QuestionFormType::class);

        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $question Question
             */
            $question = $form->getData();

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Bien joué, une nouvelle question');

            return $this->redirectToRoute('app_question_list');
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $form->createView(),
            'titre' => 'Create Question'
        ]);
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
        } elseif ($vote === 'down') {
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

    /**
     * @Route("questions/{slug}/edit", name="app_question_edit")
     * @IsGranted("ROLE_ADMIN_QUESTION")
     */
    public function edit(Question $question, Request $request, EntityManagerInterface $em)
    {
        // Je passe ma question en second argument
        $form = $this->createForm(QuestionFormType::class, $question);

        // Le reste ne change pas
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            /**
             * @var $question Question
             */
            $question = $form->getData();

            $em->persist($question);
            $em->flush();

            $this->addFlash('success', 'Bien joué, votre question est updatée');

            return $this->redirectToRoute('app_show', ['slug' => $question->getSlug()]);
        }

        return $this->render('question/new.html.twig', [
            'questionForm' => $form->createView(),
            'titre' => 'Edit Question'
        ]);
    }
}

