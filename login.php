<?php
session_start();

$is_logged_in = isset($_SESSION['user']);

if ($is_logged_in) {
    $user = $_SESSION['user'];
    echo "<script>console.log('PHP: Utilisateur connecté : " . htmlspecialchars($user) . "');</script>";
} else {
    echo "<script>console.log('PHP: Utilisateur non connecté');</script>";
}

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
    $email = $conn->real_escape_string($_POST['email']);
    $password = $conn->real_escape_string($_POST['password']);

    // Rechercher l'utilisateur dans la base de données
    $sql = "SELECT * FROM users WHERE email='$email'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        $row = $result->fetch_assoc();

        // Vérifier le mot de passe
        if ($password === $row['password']) {
            // Mot de passe correct, démarrer une session
            $_SESSION['user'] = $row['nom'];
            $_SESSION['user_id'] = $row['id'];
            echo "<div class='alert alert-success'>Connexion réussie !</div>";
            // Redirection vers la page d'accueil ou une autre page
            header("Location: index.php");
            exit;
        } else {
            echo "<div class='alert alert-danger'>Mot de passe incorrect.</div>";
        }
    } else {
        echo "<div class='alert alert-danger'>Aucun compte trouvé avec cet email.</div>";
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
    <title>Connexion - Ressources Éducatives</title>
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
        <section id="login" class="vh-100 d-flex justify-content-center align-items-center">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="center f-w600">Connexion</h1>
                    </div>
                    <div class="col-md-8 center pad-t2">
                        <form method="post" action="">
                            <div class="container">
                                <div class="row">
                                    <div class="mb-3">
                                        <div class="input-container">
                                            <input placeholder="Votre email..." class="input-field" type="email"
                                                name="email" required />
                                            <label for="email" class="input-label">Email</label>
                                            <span class="input-highlight"></span>
                                        </div>
                                    </div>
                                    <div class="mb-3">
                                        <div class="input-container">
                                            <input placeholder="Votre mot de passe..." class="input-field"
                                                type="password" name="password" required />
                                            <label for="password" class="input-label">Mot de passe</label>
                                            <span class="input-highlight"></span>
                                        </div>
                                    </div>
                                    <button type="submit" class="btn btn-primary center-input">Se connecter</button>
                                </div>
                            </div>
                        </form>
                        <div class="text-center mt-3">
                            <a href="register.php" class="AF8260">Pas encore de compte ? Inscrivez-vous</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    </main>
    <script src="js/responsive-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>