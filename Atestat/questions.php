<?php
// Read the questions and answers from the text file
if (isset($_GET['quizDomain'])) {
    $quizDomain = $_GET['quizDomain'];

    $lines = file('quiz-' . $quizDomain . '.txt', FILE_IGNORE_NEW_LINES);

    // Parse the lines and create an array of questions
    $questions = array();
    foreach ($lines as $line) {
        $fields = explode('|', $line);
        $question = array(
            'question' => trim($fields[0]),
            'answers' => array(
                trim($fields[1]),
                trim($fields[2]),
                trim($fields[3]),
                trim($fields[4])
            ),
            'correct_answer' => trim($fields[5])
        );
        array_push($questions, $question);
    }
    shuffle($questions);
    // Encode the questions as a JSON string and return it
    echo json_encode($questions);
} else {
    echo "no quizDomain";
}
?>