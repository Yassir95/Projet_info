<?php
// Vérifier si la méthode de requête est POST
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à la base de données
    $servername = "localhost";
    $username = "root";
    $password = ""; // Mettez le mot de passe de votre base de données ici
    $dbname = "ressources-educations";

    $conn = new mysqli($servername, $username, $password, $dbname);

    // Vérifier la connexion
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Récupérer les données du formulaire
    $nom = $conn->real_escape_string($_POST['nom']);
    $prenom = $conn->real_escape_string($_POST['prenom']);
    $date_naissance = $conn->real_escape_string($_POST['date_naissance']);
    $pseudo = $conn->real_escape_string($_POST['pseudo']);
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);
    $confirm_password = $conn->real_escape_string($_POST['confirm-password']);

    // Vérifier si les mots de passe correspondent
    if ($password !== $confirm_password) {
        echo "<div class='alert alert-danger'>Les mots de passe ne correspondent pas.</div>";
    } else {
        // Vérifier si l'email est déjà utilisé
        $sql_check_email = "SELECT * FROM users WHERE email='$email'";
        $result_check_email = $conn->query($sql_check_email);
        if ($result_check_email->num_rows > 0) {
            echo "<div class='alert alert-danger'>L'adresse e-mail est déjà utilisée.</div>";
        } else {
            // Insérer les données dans la base de données
            $sql = "INSERT INTO users (nom, prenom, date_naissance, pseudo, email, password) 
                    VALUES ('$nom', '$prenom', '$date_naissance', '$pseudo', '$email', '$password')";
            if ($conn->query($sql) === TRUE) {
                echo "<div class='alert alert-success'>Inscription réussie !</div>";
                // Redirection vers la page de connexion ou autre page après inscription
                header("Location: login.php");
                exit;
            } else {
                echo "<div class='alert alert-danger'>Erreur lors de l'inscription : " . $conn->error . "</div>";
            }
        }
    }

    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/default.css" />
    <link rel="icon" href="img/favicon.svg" type="image/x-icon" />
    <title>Inscription - Ressources Éducatives</title>
</head>

<body>
    <header>
        <nav class="navbar animate__animated animate__fadeInDown fixed w100 z-10">
            <div class="container-fluid">
                <a href="index.php" class="navbar-brand f-w600 AF8260 max840">Ressources Éducatives</a>
                <div class="d-flex justify-content-start align-items-center flex-grow-1">
                    <a class="AF8260 link mx-2" href="index.php">Accueil</a>
                    <a class="AF8260 link mx-2" href="ressources.php">Ressources</a>
                    <a class="AF8260 link mx-2" href="poster.php">Poster</a>
                    <a class="AF8260 link mx-2" href="contact.php">Contact</a>
                </div>
            </div>
        </nav>
    </header>
    <main>
        <section class="pad-t7">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="center f-w600">Inscription</h1>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="nom" placeholder="Entrez votre nom..." class="input-field" type="text"
                                    required />
                                <label for="input-field" class="input-label">Nom</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="prenom" placeholder="Entrez votre prénom..." class="input-field"
                                    type="text" required />
                                <label for="input-field" class="input-label">Prénom</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="date_naissance" placeholder="Entrez votre date de naissance..."
                                    class="input-field" type="date" required />
                                <label for="input-field" class="input-label">Date de naissance</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="pseudo" placeholder="Choisissez votre pseudo..." class="input-field"
                                    type="text" required />
                                <label for="input-field" class="input-label">Pseudo</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="email" placeholder="Entrez votre adresse e-mail..." class="input-field"
                                    type="email" required />
                                <label for="input-field" class="input-label">Adresse e-mail</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="password" placeholder="Choisissez un mot de passe..." class="input-field"
                                    type="password" required />
                                <label for="input-field" class="input-label">Mot de passe</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="confirm-password" placeholder="Confirmez votre mot de passe..."
                                    class="input-field" type="password" required />
                                <label for="input-field" class="input-label">Confirmer le mot de passe</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <button type="submit" class="btn marg-t3">
                                <svg height="24" width="24" fill="#FFFFFF" viewBox="0 0 24 24" data-name="Layer 1"
                                    id="Layer_1" class="sparkle">
                                    <path
                                        d="M10,21.236,6.755,14.745.264,11.5,6.755,8.255,10,1.764l3.245,6.491L19.736,11.5l-6.491,3.245ZM18,21l1.5,3L21,21l3-1.5L21,18l-1.5-3L18,18l-3,1.5ZM19.333,4.667,20.5,7l1.167-2.333L24,3.5,21.667,2.333,20.5,0,19.333,2.333,17,3.5Z">
                                    </path>
                                </svg>
                                S'inscrire
                            </button>
                        </div>
                    </form>
                    <div class="text-center mt-3">
                        <a href="login.php" class="AF8260">Déjà un compte ? Connectez-vous</a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <!-- Chargement des scripts -->
    <script src="js/responsive-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>