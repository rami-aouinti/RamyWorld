<?php

namespace App\Controller;

use App\Entity\QuizScore;
use App\Repository\QuizCategoryRepository;
use App\Repository\QuizQuestionRepository;
use App\Repository\QuizScoreRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Core\Security;

class QuizController extends AbstractController
{

    /**
     * @var Security
     */
    private Security $security;


    public function __construct(Security $security)
    {
        $this->security = $security;
    }


    #[Route('/quiz', name: 'quiz')]
    public function index(QuizCategoryRepository $categoryRepository, QuizScoreRepository $quizScoreRepository): Response
    {
        $category = $categoryRepository->find(1);

        $scoreUser = $quizScoreRepository->findBy([
            'user' => $this->security->getUser(),
            'category' => $category
        ]);

        $maxLevel = 0;

        foreach ($scoreUser as $score) {
            if($maxLevel < $score->getLevel()) {
                $maxLevel = $score->getLevel();
            }
        }

        return $this->render('quiz/index.html.twig', [
            'categories' => $categoryRepository->findAll(),
            'scoreUser' => $maxLevel
        ]);
    }

    /**
     * @Route("/questions", name="questions", methods={"POST"})
     */
    public function getQuestions(Request $request, QuizCategoryRepository $categoryRepository, QuizQuestionRepository $questionRepository): JsonResponse
    {
        $data = json_decode($request->getContent());
        $category = $categoryRepository->find($data->id);
        $level = $data->level;
        $code = 201;

        $quiz = [];
        $array = $category->getQuizquestion()->toArray();

        $ids = array_rand($array, 5);
        $questions = [];
        foreach ($ids as $id) {

            $questions[] = $questionRepository->find($id);
        }
        foreach (array_filter($questions) as $question)
        {
            if($question->getLevel() == $level)
            {
                $quiz[$question->getId()]['question'][] = $question;
                $quiz[$question->getId()]['question']['response'][] = $question->getAnswers()->toArray();
            }
        }
        $quiz = array_combine(range(1, count($quiz)), array_values($quiz));
        return new JsonResponse([
            'status' => $code,
            'data' => $quiz
        ]);
    }

    /**
     * @Route("/saveScore", name="savescore", methods={"POST"})
     */
    public function saveScore(
        Request $request,
        QuizCategoryRepository $categoryRepository,
        QuizScoreRepository $quizScoreRepository
    ): JsonResponse
    {
        $data = json_decode($request->getContent());
        $score = $data->score;
        $categoryId = $data->category;
        $level = $data->level;
        $code = 201;
        $category = $categoryRepository->find($categoryId);

        $quizScore = $quizScoreRepository->findOneBy([
            'user' => $this->security->getUser(),
            'level' => $level,
            'category' => $category
        ]);

        if ($quizScore) {
            if ($score > $quizScore->getScore()) {
                $quizScore->setScore($score);
            }
        } else {
            $quizScore = new QuizScore();
            $quizScore->setScore($score);
            $quizScore->setLevel($level);
            $quizScore->setCategory($category);
            $quizScore->setUser($this->security->getUser());
        }

        $em = $this->getDoctrine()->getManager();
        $em->persist($quizScore);
        $em->flush();
        // On retourne le code
        return new JsonResponse([
            'status' => $code
        ]);
    }
}
