<?php
session_start();

// Vérification de l'authentification de l'utilisateur
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

    // Récupérer les publications de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM publications WHERE auteur = ?");
    $stmt->execute([$user_id]);
    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
}

// Requête SQL pour sélectionner 3 ressources au hasard
$sql = "SELECT * FROM publications ORDER BY RAND() LIMIT 3";
$stmt = $conn->prepare($sql);
$stmt->execute();
$publications = $stmt->fetchAll(PDO::FETCH_ASSOC);

?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/animate.css/4.1.1/animate.min.css">
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/default.css">
    <link rel="icon" href="img/favicon.svg" type="image/x-icon">
    <title>Partage de Ressources Éducatives</title>
    <script>
    document.addEventListener("DOMContentLoaded", function() {
        <?php if ($is_logged_in): ?>
        console.log('PHP: Utilisateur connecté : <?php echo htmlspecialchars($user_id); ?>');
        <?php else: ?>
        console.log('PHP: Utilisateur non connecté');
        <?php endif; ?>
    });
    </script>
</head>

<body>
    <header>
        <nav class="navbar animate__animated animate__fadeInDown fixed w100 z-10 nav-back">
            <div class="container-fluid">
                <!-- Logo ou nom du site -->
                <a href="index.php" class="navbar-brand f-w600 AF8260 max840">Ressources Éducatives</a>

                <!-- Liens de navigation alignés à gauche -->
                <div class="d-flex justify-content-start align-items-center flex-grow-1">
                    <a class="AF8260 link mx-2" href="index.php">Accueil</a>
                    <a class="AF8260 link mx-2" href="ressources.php">Ressources</a>
                    <a class="AF8260 link mx-2" href="poster.php">Poster</a>
                    <a class="AF8260 link mx-2" href="contact.php">Contact</a>
                </div>

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
    <main>
        <section id="home">
            <img src="img/background-home.jpg" class="w100 absolute z--1 darken blur-0 background-home">
            <div class="container z10">
                <div class="row">
                    <div class="col-md-8 center">
                        <h1 class="f-z250 f-w700 marg-t48 animate__animated animate__fadeInUp l-s1">Le Partage de
                            Ressources Éducatives</h1>
                        <hr class="AF8260">
                        <p class="AF8260 animate__animated animate__fadeInUp">Découvrez la passion d'apprendre</p>
                        <a href="#presentation">
                            <button class="btn marg-t3">
                                <svg height="24" width="24" fill="#FFFFFF" viewBox="0 0 24 24" data-name="Layer 1"
                                    id="Layer_1" class="sparkle">
                                    <path
                                        d="M10,21.236,6.755,14.745.264,11.5,6.755,8.255,10,1.764l3.245,6.491L19.736,11.5l-6.491,3.245ZM18,21l1.5,3L21,21l3-1.5L21,18l-1.5-3L18,18l-3,1.5ZM19.333,4.667,20.5,7l1.167-2.333L24,3.5,21.667,2.333,20.5,0,19.333,2.333,17,3.5Z">
                                    </path>
                                </svg>
                                <span class="text">en savoir plus</span>
                            </button>
                        </a>
                    </div>
                </div>
            </div>
        </section>
        <section id="presentation">
            <div class="container pad-t30">
                <div class="row">
                    <div class="col-md-8 center bleuclair">
                        <h2 class="f-w700 center l-s1">Présentation</h2>
                        <hr>
                        <p class="grey center l-s1 white">Découvrez une nouvelle façon d'apprendre avec notre plateforme
                            de partage de ressources éducatives. Que vous soyez étudiant, enseignant ou simplement
                            passionné par l'apprentissage, notre site vous offre un accès facile et rapide à une
                            multitude de ressources pédagogiques de qualité.</p>
                        <img src="img/book1.png" class="w100 pad-t2">
                    </div>
                    <section id="acces">
                        <div class="container pad-t10">
                            <div class="row">
                                <div class="col-md-8 center bleuclair">
                                    <h2 class="f-w700 center">Accès aux ressources</h2>
                                    <hr>
                                    <p class="center white l-s1">Découvrez une bibliothèque numérique regorgeant de
                                        ressources éducatives variées pour enrichir vos connaissances et stimuler votre
                                        apprentissage. Plongez dans un océan de livres, de vidéos, d'articles et bien
                                        plus encore, accessibles en quelques clics.</p>
                                    <a href="ressources.php"><button class="btn marg-t5">Découvrir</button></a>
                                </div>
                            </div>
                        </div>
                    </section>
                    <div class="col-md-8 offset-2 pad-t10 bleuclair">
                        <h2 class="f-w700 center l-s1">Nos qualités</h2>
                        <hr>
                    </div>
                    <div class="col-md-4 center pad-t2 bleuclair">
                        <img src="img/icon-div.png" height="80" width="80" class="invert">
                        <h3>Diversité</h3>
                        <p class="w60 center white l-s1">Notre plateforme offre une large gamme de ressources
                            éducatives, allant des documents PDF et des vidéos aux quiz interactifs et aux tutoriels.
                            Quel que soit le sujet ou le niveau d'étude, nos utilisateurs peuvent trouver des ressources
                            adaptées à leurs besoins d'apprentissage.</p>
                    </div>
                    <div class="col-md-4 center pad-t2 bleuclair">
                        <img src="img/icon-eas.png" height="80" width="80" class="invert">
                        <h3>Accessibilité</h3>
                        <p class="w60 center white l-s1">Nous croyons fermement que l'éducation doit être accessible à
                            tous, partout. Notre plateforme est conçue pour être facile à utiliser et accessible depuis
                            n'importe quel appareil, afin que chacun puisse apprendre à son propre rythme, où qu'il se
                            trouve.</p>
                    </div>
                    <div class="col-md-4 center pad-t2 bleuclair">
                        <img src="img/icon-com.png" height="80" width="80" class="invert">
                        <h3>Qualité</h3>
                        <p class="w60 center white l-s1">Nous veillons à ce que toutes les ressources partagées sur
                            notre plateforme soient de la plus haute qualité. Chaque contenu est soigneusement
                            sélectionné et vérifié pour garantir sa pertinence, son exactitude et son utilité
                            pédagogique. Les utilisateurs peuvent ainsi se fier à nos ressources pour enrichir leur
                            apprentissage de manière fiable et efficace.</p>
                    </div>
                </div>
            </div>
        </section>
        <section id="qui">
            <div class="container pad-t6">
                <div class="row">
                    <div class="col-md-8 center bleuclair">
                        <h2 class="f-w700 center">Qui sommes-nous ?</h2>
                        <hr>
                        <p class="center white l-s1">Nous sommes une équipe passionnée par l'éducation et la
                            transmission du savoir. Notre mission est de rendre l'apprentissage accessible à tous, en
                            offrant une plateforme conviviale et riche en ressources de qualité. Rejoignez-nous dans
                            cette aventure éducative et découvrez une nouvelle façon d'apprendre et de partager vos
                            connaissances.</p>
                    </div>
                </div>
            </div>
        </section>
        <section>
            <div class="container pad-t6">
                <div class="row center">

                    <div class="col-md-12">
                        <div class="col-md-8 offset-2 pad-t10 bleuclair">
                            <h2 class="f-w700 center l-s1">Nos meilleures ressources du mois</h2>
                            <hr>
                        </div>
                    </div>
                    <?php
                    // Affichage des ressources
                    foreach ($publications as $publication):
                        ?>
                    <div class="col-md-3 pad-t1">
                        <div class="card custom-card-bg mb-3 text-white" style="background-color: rgb(26, 31, 57);">
                            <!-- Ajout de la classe bg-dark et text-white -->
                            <img src="<?php echo htmlspecialchars($publication['affiche']); ?>" class="card-img-top"
                                alt="Affiche de la publication">
                            <div class="card-body">
                                <h5 class="card-title"><?php echo htmlspecialchars($publication['titre']); ?></h5>
                                <p class="card-text"><?php echo htmlspecialchars($publication['description']); ?></p>
                                <p class="card-text"><small
                                        class="white"><?php echo htmlspecialchars($user['pseudo'] ?? ''); ?></small>
                                </p>
                                <!-- Modifier le lien du bouton "Ouvrir" -->
                                <a href="la_ressource.php?id=<?php echo $publication['id']; ?>"
                                    class="btn btn-primary">Ouvrir</a>
                            </div>
                        </div>
                    </div>
                    <?php
                    endforeach;
                    ?>
                    <div class="col-md-8">
                        <form method="post">
                            <div class="input-container">
                                <input name="search"
                                    placeholder="Recherchez par catégories, mots-clés, niveau d'éducation..."
                                    class="input-field center" type="text">
                                <label for="input-field" class="input-label">Rechercher</label>
                                <span class="input-highlight"></span>
                            </div>
                            <div class="center">
                                <button type="submit" class="btn">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </section>
        <section id="contact">
            <div class="container pad-t8">
                <div class="row">
                    <div class="col-md-8 center bleuclair">
                        <h2 class="f-w700 center">Contact</h2>
                        <hr>
                        <p class="center white l-s1">Pour toute question ou suggestion, n'hésitez pas à nous contacter.
                            Nous sommes à votre écoute et prêts à vous aider dans votre parcours d'apprentissage.</p>
                        <a href="contact.php"><button class="btn marg-t5">Nous contacter</button></a>
                    </div>
                </div>
            </div>
        </section>
    </main>
    <footer class="site-footer marg-t12">
        <div class="container">
            <div class="row">
                <div class="col-sm-12 col-md-6">
                    <h6>À propos</h6>
                    <p class="text-justify">Explorez notre collection diversifiée de ressources éducatives et
                        laissez-vous inspirer par le savoir à portée de main. Rejoignez notre communauté d'apprenants
                        passionnés et découvrez de nouvelles façons d'enrichir votre parcours éducatif. Avec notre
                        engagement envers l'excellence éducative, votre aventure d'apprentissage n'a pas de limite.</p>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Catégories</h6>
                    <ul class="footer-links">
                        <li><a href="index.php">Accueil</a></li>
                        <li><a href="ressource.php">Ressources</a></li>
                        <li><a href="poster.php">Poster</a></li>
                        <li><a href="contact.php">Contact</a></li>
                        <li><a href="profile.php">Profile</a></li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>informations</h6>
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
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
    </script>
</body>

</html>