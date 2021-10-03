<?php

namespace App\Controller;

use App\Entity\Question;
use App\Service\MarkdownHelper;
use Doctrine\ORM\EntityManagerInterface;
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
     * @Route("/questions/new")
     */
    public function new(EntityManagerInterface $entityManager)
    {
        $question = new Question();
        $question->setName('Comment rendre une pizza ?')
            ->setSlug('comment-rendre-une-pizza' . rand(0, 1000))
            ->setQuestion(<<<EOF
'Ma pizza finalement ne convient pas à mon intérieur, 
est-il possible de la retourner au magasin ?'
EOF
            );

        if (rand(1, 10) > 2) {
            $question->setAskedAt(new \DateTime(sprintf('-%d days', rand(1, 100))));
        }

        // Il faut faire les deux pour que ça marche
        $entityManager->persist($question); // Informe doctrine de l'objet, je peux en avoir plusieurs
        $entityManager->flush(); // Fait la query

        return new Response(sprintf('Votre question %s et avec l\'id %d est instrit en BDD',
                $question->getSlug(),
                $question->getId() // Je récupère l'id après la sauvegarde
            )
        );
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

