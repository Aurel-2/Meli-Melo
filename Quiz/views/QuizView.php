<?php
session_start();
global $database;

use controllers\LoginController;
use controllers\QuizController;
use models\Quiz;

require_once '../config/database.php';
require_once '../models/Quiz.php';
require_once '../controllers/QuizController.php';
require_once '../controllers/LoginController.php';

$quizModel = new Quiz($database);
$quizController = new QuizController($database);
$loginController = new LoginController($database);
$message = "";

if (isset($_GET['action']) && $_GET['action'] === 'logout') {
    $loginController->logout();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['form_type'])) {
        switch ($_POST['form_type']) {
            case 'login':
                $username = $_POST['username'];
                $password = $_POST['password'];
                if ($loginController->login($username, $password)) {
                    $message = "<h4 style='color: green'>Connexion réussie</h4>";
                } else {
                    $message = "<h4 style='color: red'>Identifiants incorrects</h4>";
                }
                break;

            case 'add_question':
                $quizController->saveQuizQuestion();
                $message = "<h4 style='color: green'>Ajout de la question réussi.</h4>";
                header("Location: QuizView.php");
                exit;

            case 'submit_quiz':
                $questions = $quizModel->getQuizQuestions();
                $score = 0;
                $total_questions = count($questions);
                $feedback = [];

                foreach ($questions as $key => $question) {
                    if (isset($_POST['question' . $key])) {
                        $user_answer = (int)$_POST['question' . $key];
                        $correct_answer = (int)$question['answer'];
                        if ($user_answer === $correct_answer) {
                            $score++;
                            $feedback[$key] = "<h4 style='color: green'>" . $question['options'][$correct_answer] . " est correct!</h4>";
                        } else {
                            $feedback[$key] = "<h4 style='color: red'>Incorrect. La bonne réponse était : " . $question['options'][$correct_answer] . "</h4>";
                        }
                    }
                }
                break;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Quiz</title>
    <link href="../public/quiz.css" rel="stylesheet">
</head>
<body>
<?php if (!$loginController->isConnected()): ?>
    <!-- Formulaire de connexion -->
    <form method="POST">
        <input type="hidden" name="form_type" value="login">
        <h1>Connexion</h1>
        <input type="text" name="username" placeholder="Nom d'utilisateur" required>
        <input type="password" name="password" placeholder="Mot de passe" required><br>
        <button type="submit">Se connecter</button>
        <?php echo $message; ?>
    </form>
<?php else: ?>

    <!-- Formulaire d'ajout de question -->
    <form method="POST">
        <!-- Bouton de déconnexion en haut à droite -->
        <div class="logout-container">
            <h1>Ajouter une question</h1>
            <a href="?action=logout">Déconnexion</a>
        </div>
        <input type="hidden" name="form_type" value="add_question">

        <h3>Veuillez saisir une question</h3>
        <input type="text" name="question" placeholder="Question">
        <div id="options-container">
            <h3>Options</h3>
            <div class="option-item">
                <input type="text" name="options[]" placeholder="Option">
                <input type="checkbox" name="answer[]" value="0">
                <label>Bonne réponse</label>
            </div>
            <div class="option-item">
                <input type="text" name="options[]" placeholder="Option">
                <input type="checkbox" name="answer[]" value="1">
                <label>Bonne réponse</label>
            </div>
        </div>
        <button type="button" onclick="add_options()">Ajouter une option</button>
        <button type="submit">Ajouter la question</button>
        <div id="error-container" class="error-container"></div>
        <?php echo $message; ?>
    </form>
<?php endif; ?>

<!-- Formulaire du quiz -->
<form method="POST">
    <input type="hidden" name="form_type" value="submit_quiz">
    <h1>Quiz</h1>
    <?php
    $questions = $quizModel->getQuizQuestions();
    foreach ($questions as $key => $value) {
        echo "<h2>" . ($key + 1) . ". " . htmlspecialchars($value["question"]) . "</h2>";
        foreach ($value["options"] as $index => $option) {
            echo "<input type='radio' name='question" . $key . "' value='$index' required> " . htmlspecialchars($option) . "<br>";
        }
        if (isset($feedback[$key])) {
            echo $feedback[$key];
        }
    }
    if (!empty($questions)) {
        echo "<button type='submit'>Soumettre le quiz</button>";
    }
    ?>
</form>

<?php
if (isset($score)) {
    echo "<p style='color: blue; text-align: center; font-weight: bold'>Score : $score / $total_questions</p>";
}
?>

<script src="../public/quiz.js"></script>
</body>
</html>