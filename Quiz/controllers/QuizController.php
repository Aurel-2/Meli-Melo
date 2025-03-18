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
        $index = "";
        foreach ($answer as $key => $value) {
            if ($value == "on") {
                $index = $key;
                break;
            }

        }
        $this->quizModel->addQuestionToQuiz($question, $options, $index);
    }
}