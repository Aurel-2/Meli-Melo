<?php

namespace models;

use PDO;

class Quiz
{
    private PDO $database;

    public function __construct($database)
    {
        $this->database = $database;
    }

    public function addQuestionToQuiz($question, $options, $answer): void
    {
        $sql = "INSERT INTO questions(question) VALUES (:question)";
        $stmt = $this->database->prepare($sql);
        $stmt->execute([':question' => $question]);
        $questionID = $this->database->lastInsertId();

        foreach ($options as $option) {
            $optionSql = "INSERT INTO options(id_question, option_text) VALUES (:question_id, :option)";
            $optionStmt = $this->database->prepare($optionSql);
            $optionStmt->execute([':question_id' => $questionID, ':option' => $option]);
        }

        $answerSql = "INSERT INTO answers(id_question, answer) VALUES (:question_id, :answer)";
        $answerStmt = $this->database->prepare($answerSql);
        $answerStmt->execute([':question_id' => $questionID, ':answer' => $answer]);
    }

    public function getQuizQuestions(): array
    {
        $result = [];

        $sql = "SELECT id_question, question FROM questions";
        $stmt = $this->database->prepare($sql);
        $stmt->execute();
        $questions = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($questions as $question) {
            $optionSql = "SELECT option_text FROM options WHERE id_question = :question_id";
            $optionStmt = $this->database->prepare($optionSql);
            $optionStmt->execute([':question_id' => $question['id_question']]);
            $options = $optionStmt->fetchAll(PDO::FETCH_COLUMN);

            $answerSql = "SELECT answer FROM answers WHERE id_question = :question_id";
            $answerStmt = $this->database->prepare($answerSql);
            $answerStmt->execute([':question_id' => $question['id_question']]);
            $answer = $answerStmt->fetchColumn();

            $result[] = [
                'question' => $question['question'],
                'options' => $options,
                'answer' => $answer
            ];
        }
        return $result;
    }
}