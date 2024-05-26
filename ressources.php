<?php
session_start();

// Connexion à la base de données
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "ressources-educations";

try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
    die();
}

// Vérifie si une recherche est effectuée
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['search'])) {
    $search_query = '%' . $_POST['search'] . '%';
    // Requête pour récupérer les publications correspondant à la recherche
    $stmt = $conn->prepare("SELECT p.*, u.pseudo AS author_name FROM publications p JOIN users u ON p.auteur = u.id WHERE p.titre LIKE ? OR p.description LIKE ? OR p.categorie LIKE ? OR p.motsCles LIKE ? OR p.nivEducation LIKE ?");
    $stmt->execute([$search_query, $search_query, $search_query, $search_query, $search_query]);
    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
} else {
    // Requête pour récupérer toutes les publications
    $stmt = $conn->prepare("SELECT p.*, u.pseudo AS author_name FROM publications p JOIN users u ON p.auteur = u.id");
    $stmt->execute();
    $publications = $stmt->fetchAll(PDO::FETCH_ASSOC);
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
    <title>Partage de Ressources Éducatives</title>
</head>

<body>
    <!-- En-tête de la page -->
    <header>
        <!-- Barre de navigation -->
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
                    action="<?php echo isset($_SESSION['user_id']) ? 'logout.php' : 'login.php'; ?>">
                    <?php if (isset($_SESSION['user_id'])): ?>
                        <a href="profile.php" class="btn btn-secondary mx-2">Profil</a>
                        <button type="submit" class="btn btn-danger ms-2">Déconnexion</button>
                    <?php else: ?>
                        <a href="login.php" class="btn btn-primary ms-2">Connexion</a>
                    <?php endif; ?>
                </form>
            </div>
        </nav>
    </header>

    <!-- Corps principal de la page -->
    <main>

        <section>
            <div class="container">
                <div class="row pad-t2">
                    <div class="col-md-12 pad-t8 center">
                        <h1 class="f-w700">Ressources Éducatives</h1>
                    </div>
                    <div class="col-md-2"></div>
                    <div class="col-md-8 pad-t2">
                        <!-- Formulaire de recherche -->
                        <form method="post">
                            <div class="input-container">
                                <input name="search"
                                    placeholder="Recherchez par catégories, mots-clés, niveau d'éducation..."
                                    class="input-field" type="text">
                                <label for="input-field" class="input-label">Rechercher</label>
                                <span class="input-highlight"></span>
                            </div>
                            <div class="center">
                                <button type="submit" class="btn">Rechercher</button>
                            </div>
                        </form>
                    </div>
                </div>
                <br>
                <br>
                <!-- Affichage des ressources -->
                <div class="row">
                    <?php foreach ($publications as $index => $publication): ?>
                        <div class="col-md-4">
                            <div class="card custom-card-bg mb-3 text-white" style="background-color: rgb(26, 31, 57);">
                                <!-- Ajout de la classe bg-dark et text-white -->
                                <img src="<?php echo htmlspecialchars($publication['affiche']); ?>" class="card-img-top"
                                    alt="Affiche de la publication">
                                <div class="card-body">
                                    <h5 class="card-title"><?php echo htmlspecialchars($publication['titre']); ?></h5>
                                    <p class="card-text"><?php echo htmlspecialchars($publication['description']); ?></p>
                                    <p class="card-text"><small
                                            class="white"><?php echo htmlspecialchars($publication['author_name']); ?></small>
                                    </p>
                                    <!-- Modifier le lien du bouton "Ouvrir" -->
                                    <a href="la_ressource.php?id=<?php echo $publication['id']; ?>"
                                        class="btn btn-primary">Ouvrir</a>
                                </div>
                            </div>
                        </div>
                    <?php endforeach; ?>
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
                        laissez-vous
                        inspirer par le savoir à portée de main. Rejoignez notre communauté d'apprenants passionnés et
                        découvrez de
                        nouvelles façons d'enrichir votre parcours éducatif. Avec notre engagement envers l'excellence
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
                        <li><a href="">Profil</a></li>
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
                        <a href="#">Cy tech</a>.
                    </p>
                </div>
            </div>
        </div>
    </footer>

    <!-- Chargement des scripts -->
    <script src="js/responsive-navbar.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous">
        </script>
</body>

</html>