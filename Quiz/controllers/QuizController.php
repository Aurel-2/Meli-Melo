<?php

namespace controllers;

use models\Quiz;

class QuizController
{
    private Quiz $quizModel;

    public function __construct($database)
    {
        $this->quizModel = new Quiz($database);
    }

    public function saveQuizQuestion(): void
    {
        $question = htmlspecialchars($_POST['question']);
        $options = $_POST['options'];
        $answer = $_POST['answer'];
        $answer = $answer[0];
        $this->quizModel->addQuestionToQuiz($question, $options, $answer);
        header('Location: ../views/QuizView.php');
    }
}