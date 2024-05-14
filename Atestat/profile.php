<?php
session_start();
?>

<head>
    <link href="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css" rel="stylesheet" id="bootstrap-css">
    <script src="//maxcdn.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.min.js"></script>
    <script src="//cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
    <link rel="stylesheet" href="ss2.css">
</head>

<body>
    <?php if (isset($_SESSION['usr'])) {
        $usr = $_SESSION['usr'];
        $users = file('users.txt');
        foreach ($users as $user) {
            $user = rtrim($user);
            list($stored_username, $stored_password, $stored_profile_name) = explode(':', $user);
            if ($usr == $stored_username) {
                $profile_name = $stored_profile_name;
            }
        }
        for ($i = 0; $i < 6; $i++) {
            $materii[$i] = array(0, 0, "", ""); // initialize with 0 and ""
        }
        $tests = file($usr . '.txt');
        foreach ($tests as $test) {
            $test = rtrim($test);
            list($date_of_test, $mat, $score) = explode('|', $test);

            switch ($mat) {
                case ' Astronomy ':
                    $materii[0][0]++;
                    $materii[0][2] = $date_of_test;
                    $materii[0][1] += eval("return $score;");
                    $materii[0][3] = $score;
                    break;
                case ' Biology ':
                    $materii[1][0]++;
                    $materii[1][2] = $date_of_test;
                    $materii[1][1] += eval("return $score;");
                    $materii[1][3] = $score;
                    break;
                case ' Chemistry ':
                    $materii[2][0]++;
                    $materii[2][2] = $date_of_test;
                    $materii[2][1] += eval("return $score;");
                    $materii[2][3] = $score;
                    break;
                case ' History ':
                    $materii[3][0]++;
                    $materii[3][2] = $date_of_test;
                    $materii[3][1] += eval("return $score;");
                    $materii[3][3] = $score;
                    break;
                case ' Informatics ':
                    $materii[4][0]++;
                    $materii[4][2] = $date_of_test;
                    $materii[4][1] += eval("return $score;");
                    $materii[4][3] = $score;
                    break;
                case ' Geography ':
                    $materii[5][0]++;
                    $materii[5][2] = $date_of_test;
                    $materii[5][1] += eval("return $score;");
                    $materii[5][3] = $score;
                    break;
            }
        }

        ?>
        <script>

            var questions;

            function loadQuestions(url, callback) {
                // Create a new XMLHttpRequest object
                var xhr = new XMLHttpRequest();

                // Define the callback function to be called when the request completes
                xhr.onload = function () {
                    if (xhr.status === 200) {
                        // Parse the response text as JSON
                        var data = JSON.parse(xhr.responseText);
                        // Call the callback function with the parsed data
                        callback(data);
                    }
                };

                // Send the request to the PHP file
                xhr.open('GET', url);
                xhr.send();
            }

            function generateQuiz(quest, quizDomain) {
                questions=quest;
                //alert(JSON.stringify(questions));
                // Create a form element to hold the quiz
                var quizForm = document.getElementById("qform");
                quizForm.innerHTML="";
                //quizForm.setAttribute('id', 'quiz-form');

                // Loop through each question and create a quiz item
                questions.forEach(function (question, index) {
                    // Create a quiz item element
                    var quizItem = document.createElement('div');
                    quizItem.setAttribute('class', 'quiz-item');

                    // Create the question element
                    var questionElem = document.createElement('p');
                    questionElem.setAttribute('class', 'question');
                    questionElem.innerHTML = (index + 1) + '. ' + question.question;
                    quizItem.appendChild(questionElem);

                    // Create the answer options element
                    var answerOptions = document.createElement('ul');
                    answerOptions.setAttribute('class', 'answer-options');

                    // Loop through each answer option and create a radio button
                    question.answers.forEach(function (answer) {
                        var optionElem = document.createElement('div');
                        var labelElem = document.createElement('label');
                        var radioElem = document.createElement('input');

                        radioElem.setAttribute('type', 'radio');
                        radioElem.setAttribute('name', 'answer-' + index);
                        radioElem.setAttribute('value', answer);
                        labelElem.appendChild(radioElem);
                        labelElem.innerHTML += ' ' + answer;
                        optionElem.appendChild(labelElem);
                        answerOptions.appendChild(optionElem);
                    });

                    quizItem.appendChild(answerOptions);
                    quizForm.appendChild(quizItem);
                });

                // Add a submit button to the quiz
                var submitButton = document.createElement('button');
                submitButton.setAttribute('type', 'submit');
                submitButton.setAttribute('class', 'btn btn-success');
                submitButton.setAttribute('id', 'quizSubmit');
                submitButton.setAttribute('onclick', 'evaluateQuiz("'+quizDomain+'")');
                submitButton.innerHTML = 'Submit';

                quizForm.appendChild(submitButton);

                // Add the quiz to the document
                //var quizContainer = document.getElementById('quiz-container');
                //quizContainer.innerHTML = '';
                //quizContainer.appendChild(quizForm);
            }

            function evaluateQuiz(quizDomain) {
                //alert(questions);
                var score = 0;
                var numQuestions = questions.length;

                // Loop through each question and check the answer
                questions.forEach(function (question, index) {
                    var answer = document.querySelector('input[name="answer-' + index + '"]:checked');
                    //alert(question.correct_answer);
                    if (answer && answer.value === question.correct_answer) {
                        score++;
                        //answer.parentNode.style.backgroundColor = 'green';
                        answer.parentNode.setAttribute('class','good');
                    }
                    if (answer && answer.value != question.correct_answer) {
                        answer.parentNode.setAttribute('class','bad');
                    }
                });

                // Display the results to the user
                var resultContainer = document.createElement('div');
                resultContainer.setAttribute('class', 'quiz-result');
                resultContainer.innerHTML = 'You scored ' + score + ' out of ' + numQuestions + '.';
                //var quizContainer = document.getElementById('quiz-container');
                //quizContainer.innerHTML = '';
                qform.appendChild(resultContainer);
                document.getElementById("quizSubmit").style.display="none";

                var closeButton = document.createElement('button');
                closeButton.setAttribute('type', 'button');
                closeButton.setAttribute('class', 'btn btn-danger float-right');
                closeButton.setAttribute('onclick', 'closeQuiz()');
                closeButton.innerHTML = 'Close';
                qform.appendChild(closeButton);

                // Send score to server
                let currentDate = new Date();
                let dateString = currentDate.getFullYear() + '-' + ('0' + (currentDate.getMonth() + 1)).slice(-2) + '-' + ('0' + currentDate.getDate()).slice(-2);
                let timeString = ('0' + currentDate.getHours()).slice(-2) + ':' + ('0' + currentDate.getMinutes()).slice(-2);
                let content = dateString + ' ' + timeString + ' | '+quizDomain+' | ' + score+'/'+ numQuestions;

                // Create a new FormData object
                var formData = new FormData();
                let user=document.getElementById("user").innerText;
                // Append the data and user as key-value pairs to the FormData object
                formData.append('score', content);
                formData.append('user', user);

                // Create a new XHR object
                var xhr = new XMLHttpRequest();

                // Set the URL and HTTP method for the XHR request
                xhr.open('POST', 'saveresult.php');
                //alert(user);
                // Send the FormData object as the body of the XHR request
                xhr.send(formData);
            }
            function closeQuiz(){
                var qform = document.getElementById("qform");
                    qform.innerHTML="";
                var modalcontent = document.getElementById("modal-content");
                    modalcontent.innerHTML="";
                var openModal = document.getElementById("openModal");
                    openModal.style.display="none";
                location.reload();
            }

            function openModalLearn(fileHTML) {
                // Get the modal element
                var modal = document.getElementById("openModal");

                // Get the modal content element
                var modalContent = document.getElementById("modal-content");

                var xhr = new XMLHttpRequest();
                xhr.open('GET', fileHTML, true);
                xhr.onreadystatechange = function () {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        // Do something with the HTML content
                        var htmlCode = xhr.responseText;
                        // Set the HTML code in the modal content element
                        modalContent.innerHTML = htmlCode;
                        // Show the modal
                        modal.style.display = "block";
                        // Set up the close button
                        var closeBtn = document.getElementsByClassName("close")[0];
                        closeBtn.onclick = function () {
                            modal.style.display = "none";
                        }
                    }
                }
                xhr.send();
            };

            

            function openModalQuiz(quizDomain) {
                // Get the modal element
                var modal = document.getElementById("openModal");

                // Get the modal content element
                var modalContent = document.getElementById("modal-content");
                modalContent.innerHTML="";

                        loadQuestions('questions.php?quizDomain=' + quizDomain, function (data) {
                           // alert(data);
                        generateQuiz(data, quizDomain);
                        // Show the modal
                        modal.style.display = "block";
                        // Set up the close button
                        var closeBtn = document.getElementsByClassName("close")[0];
                        closeBtn.onclick = function () {
                            modal.style.display = "none";
                        }
                    });
                };
            function logout(){
                window.location.replace("index.php");
            }
        </script>
        <div class="container emp-profile">
            
            <div class="row">
                <div class="col-md-3">
                    <div class="profile-img">
                        <img src="img/u1.png" alt="" />
                    </div>
                    <div class="profile-head">
                        <h5>
                            <?php echo $profile_name; ?>
                        </h5>
                        <p>UserName:</p>
                        <h3 id="user">
                            <?php echo $usr; ?>
                        </h3>
                        <button type="button" class="btn btn-danger" onclick="logout()">log Out</button>
                    </div>
                </div>
                <div class="col-md-4">
                    <h3>Results listing:</h3>
                    <textarea name="Text1" cols="40" rows="15"><?php
                    $myfile = fopen($usr . ".txt", "r") or die("Unable to open file!");
                    echo fread($myfile, filesize($usr . ".txt"));
                    fclose($myfile);
                    ?>
                </textarea>
                </div>
                <div class="col-md-5">
                    <h3>Quizes statistic:</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th scope="col">Field</th>
                                <th scope="col">Nr. of tests</th>
                                <th scope="col">Pass</th>
                                <th scope="col">Last test date</th>
                                <th scope="col">Score last test</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>Astronomy</td>
                                <td>
                                    <?php echo $materii[0][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[0][0])
                                        echo number_format($materii[0][1] / $materii[0][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[0][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[0][3]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Biology</td>
                                <td>
                                    <?php echo $materii[1][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[1][0])
                                        echo number_format($materii[1][1] / $materii[1][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[1][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[1][3]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Chemistry</td>
                                <td>
                                    <?php echo $materii[2][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[2][0])
                                        echo number_format($materii[2][1] / $materii[2][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[2][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[2][3]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>History</td>
                                <td>
                                    <?php echo $materii[3][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[3][0])
                                        echo number_format($materii[3][1] / $materii[3][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[3][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[3][3]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Informatics</td>
                                <td>
                                    <?php echo $materii[4][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[4][0])
                                        echo number_format($materii[4][1] / $materii[4][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[4][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[4][3]; ?>
                                </td>
                            </tr>
                            <tr>
                                <td>Geography</td>
                                <td>
                                    <?php echo $materii[5][0]; ?>
                                </td>
                                <td>
                                    <?php if ($materii[5][0])
                                        echo number_format($materii[5][1] / $materii[5][0] * 100, 2) . "%";
                                    else
                                        echo "0%" ?>
                                    </td>
                                    <td>
                                    <?php echo $materii[5][2]; ?>
                                </td>
                                <td>
                                    <?php echo $materii[5][3]; ?>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <hr style="height:2px;border-width:0;color:gray;background-color:gray">
            <div class="row">
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/Astronomy.jpg" alt="Card image cap" onclick="openModalLearn('Astronomy.html')">
                        <div class="card-body">
                            <h5 class="card-title">Astronomy</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary"
                                    onclick="openModalLearn('Astronomy.html')">Learn</button>
                                <button type="button" class="btn btn-success"
                                    onclick="openModalQuiz('Astronomy')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/Biology.jpg" alt="Card image cap" onclick="openModalLearn('Biology.html')">
                        <div class="card-body">
                            <h5 class="card-title">Biology</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" onclick="openModalLearn('Biology.html')">Learn</button>
                                <button type="button" class="btn btn-success" onclick="openModalQuiz('Biology')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/Chemistry.jpg" alt="Card image cap" onclick="openModalLearn('Chemistry.html')">
                        <div class="card-body">
                            <h5 class="card-title">Chemistry</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" onclick="openModalLearn('Chemistry.html')">Learn</button>
                                <button type="button" class="btn btn-success" onclick="openModalQuiz('Chemistry')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/History.jpg" alt="Card image cap" onclick="openModalLearn('History.html')">
                        <div class="card-body">
                            <h5 class="card-title">History</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" onclick="openModalLearn('History.html')">Learn</button>
                                <button type="button" class="btn btn-success" onclick="openModalQuiz('History')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/Informatics.jpg" alt="Card image cap" onclick="openModalLearn('Informatics.html')">
                        <div class="card-body">
                            <h5 class="card-title">Informatics</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" onclick="openModalLearn('Informatics.html')">Learn</button>
                                <button type="button" class="btn btn-success" onclick="openModalQuiz('Informatics')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="col-md-2">
                    <div class="card text-center" style="width: 11rem;">
                        <img class="card-img-top" src="img/Geography.jpg" alt="Card image cap" onclick="openModalLearn('Geography.html')">
                        <div class="card-body">
                            <h5 class="card-title">Geography</h5>
                            <p class="card-text"></p>
                            <div class="btn-group btn-block" role="group" aria-label="Basic example">
                                <button type="button" class="btn btn-primary" onclick="openModalLearn('Geography.html')">Learn</button>
                                <button type="button" class="btn btn-success" onclick="openModalQuiz('Geography')">Quiz</button>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
        <div id="openModal" class="modal">
        <div class="container emp-profile">
            <div class=" modal-content ">
                <span class="close">&times;</span>
                <div id="modal-content"></div>
                <div id="qform"></div>
                
            </div>
        </div>

    <?php } else {
        echo "list of SESSION VARIALE";
        echo '<pre>' . print_r($_SESSION, TRUE) . '</pre>';
    } ?>
</body>