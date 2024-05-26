<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user = null;
$publications = [];

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ressources-educations";
$conn = null;

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Récupérer les informations de l'utilisateur à partir de son ID
if ($is_logged_in) {
    $user_id = $_SESSION['user_id'];

    // Récupérer les informations de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user_id]);
    $user = $stmt->fetch(PDO::FETCH_ASSOC);
}

// Récupérer l'ID de la ressource à afficher depuis l'URL
if (isset($_GET['id'])) {
    $resource_id = $_GET['id'];

    // Récupérer les informations de la ressource depuis la base de données
    $stmt = $conn->prepare("SELECT publications.*, users.pseudo AS author_name FROM publications INNER JOIN users ON publications.auteur = users.id WHERE publications.id = ?");
    $stmt->execute([$resource_id]);
    $resource = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si la ressource existe
    if (!$resource) {
        echo "Ressource introuvable.";
        die();
    }

} else {
    echo "ID de ressource non spécifié.";
    die();
}
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <!-- Méta-tags pour la configuration du document -->
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />

    <!-- Liens vers les feuilles de style -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/default.css" />

    <!-- Icon de la page -->
    <link rel="icon" href="img/favicon.svg" type="image/x-icon" />

    <!-- Titre de la page -->
    <title>Ressource - <?php echo htmlspecialchars($resource['titre']); ?></title>

</head>

<body>
    <!-- En-tête de la page -->
    <header>
        <!-- Barre de navigation -->
        <nav class="navbar animate__animated animate__fadeInDown fixed w100 z-10 nav-back">
            <div class="container-fluid">
                <a class="navbar-brand f-w600 AF8260 max840">Ressources Éducatives</a>
                <div class="d-flex justify-content-start align-items-center flex-grow-1">
                    <a class="AF8260 link mx-2" href="index.php">Accueil</a>
                    <a class="AF8260 link mx-2" href="ressources.php">Ressources</a>
                    <a class="AF8260 link mx-2" href="poster.php">Poster</a>
                    <a class="AF8260 link mx-2" href="contact.php">Contact</a>
                </div>
                <form class="d-flex align-items-center" action="<?php echo htmlspecialchars($resource['document']); ?>">
                    <button id="downloadButton" class="btn btn-primary btn-lg">Télécharger la ressource</button>
                </form>
                <form class="d-flex align-items-center" role="search" method="post"
                    action="<?php echo $is_logged_in ? 'logout.php' : 'login.php'; ?>">
                    <?php if ($is_logged_in): ?>
                        <a href="profile.php" class="btn btn-secondary mx-2">Profil</a>
                        <button type="submit" class="btn btn-danger ms-2">Déconnexion</button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary ms-2">Connexion</a>
                    <?php endif; ?>
                </form>
            </div>
        </nav>
    </header>

    <div class="container">
        <div class="row pad-t7 v-align">
            <div class="col-md-1 profile-picture v-align">
                <img src="<?php echo htmlspecialchars($resource['affiche']); ?>" alt="Affiche de la ressource"
                    class="img-fluid" />
            </div>
            <div class="col-md-11 v-align">
                <h1><?php echo htmlspecialchars($resource['titre']); ?></h1>
            </div>
            <div class="row">
                <div class="col-md-12 pad-t2">
                    <h2 class="f-z100 bold white">Auteur</h2>
                    <p class="l-s1 white"><?php echo htmlspecialchars($resource['author_name']); ?></p>
                    <h2 class="f-z100 bold white">Mots-Clés</h2>
                    <p class="l-s1 white"><?php echo htmlspecialchars($resource['motsCles']); ?></p>
                    <h2 class="f-z100 bold white">Description</h2>
                    <p class="l-s1 white"><?php echo htmlspecialchars($resource['description']); ?></p>
                    <iframe src="<?php echo htmlspecialchars($resource['document']); ?>" width="100%"
                        height="1000px"></iframe>
                </div>
            </div>
        </div>
    </div>
    <footer class="site-footer marg-t12">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h6>À propos</h6>
                    <p class="text-justify">Explorez notre collection diversifiée de ressources éducatives et
                        laissez-vous
                        inspirer par le savoir à portée de main. Rejoignez notre communauté d'apprenants passionnés
                        et découvrez
                        de
                        nouvelles façons d'enrichir votre parcours éducatif. Avec notre engagement envers
                        l'excellence
                        éducative,
                        votre aventure d'apprentissage n'a pas de limite.</p>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Categories</h6>
                    <ul class="footer-links">
                        <li><a href="">Accueil</a></li>
                        <li><a href="">Ressources</a></li>
                        <li><a href="">Poster</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Profile</a></li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Quick Links</h6>
                    <ul class="footer-links">
                        <li><a href="">À propos</a></li>
                        <li><a href="">Nous contacter</a></li>
                        <li><a href="">Contribuer</a></li>
                        <li><a href="">Mentions Légales</a></li>
                    </ul>
                </div>
            </div>
            <hr>
        </div>
        <div class="container">
            <div class="row">
                <div class="col-md-8 col-sm-6 col-xs-12">
                    <p class="copyright-text">Copyright &copy; 2024 Touts droits réservés par
                        <a href="#">CY tech</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>
    <script>
        // Récupérer le bouton de téléchargement
        var downloadButton = document.getElementById('downloadButton');

        // Écouter l'événement click sur le bouton
        downloadButton.addEventListener('click', function () {
            // Soumettre le formulaire lorsque le bouton est cliqué
            document.getElementById('downloadForm').submit();
        });
    </script>
    <!-- Scripts JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
        integrity="sha384-xHG2l6Dv5a7lxEeueJlkHDf8H5CqU5Fkg4HFPoSllhh9boDjw4osvB+alN1p70y/" crossorigin="anonymous">
        </script>

</body>

</html>