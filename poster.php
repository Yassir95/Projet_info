<?php
session_start();
$is_logged_in = isset($_SESSION['user_id']);
$user = null;
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "ressources-educations";
    

    if ($is_logged_in) {
        $user_id = $_SESSION['user_id'];
        // Vous pouvez ajouter ici du code pour afficher des informations utilisateur ou des actions spécifiques pour les utilisateurs connectés
        echo "<script>console.log('PHP: Utilisateur connecté : " . htmlspecialchars($user_id) . "');</script>";
    } else {
        // Message de log pour indiquer que l'utilisateur n'est pas connecté
        echo "<script>console.log('PHP: Utilisateur non connecté');</script>";
        // Redirection vers la page de connexion si nécessaire
        // header('Location: login.php'); 
        // exit();
    }
    try {
        $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
        $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

        $titre = $_POST['titre'];
        $description = $_POST['description'];
        $categorie = $_POST['categorie'];
        $motsCles = $_POST['motsCles'];
        $nivEducation = $_POST['nivEducation'];

        // Vérifier si le répertoire uploads existe, sinon le créer
        $uploadDirectory = "uploads/";
        if (!is_dir($uploadDirectory)) {
            mkdir($uploadDirectory, 0777, true);
        }

        // Gestion de l'upload de l'affiche
        if (isset($_FILES['affiche']) && $_FILES['affiche']['error'] == 0) {
            $affichePath = $uploadDirectory . basename($_FILES["affiche"]["name"]);
            if (move_uploaded_file($_FILES["affiche"]["tmp_name"], $affichePath)) {
                echo "Affiche téléchargée avec succès.<br>";
            } else {
                echo "Erreur lors du téléchargement de l'affiche.<br>";
            }
        } else {
            $affichePath = null;
        }

        // Gestion de l'upload du document
        if (isset($_FILES['document']) && $_FILES['document']['error'] == 0) {
            $documentPath = $uploadDirectory . basename($_FILES["document"]["name"]);
            if (move_uploaded_file($_FILES["document"]["tmp_name"], $documentPath)) {
                echo "Document téléchargé avec succès.<br>";
            } else {
                echo "Erreur lors du téléchargement du document.<br>";
            }
        } else {
            $documentPath = null;
        }

        // Assurez-vous que l'auteur existe dans la table users
        // Exemple : ID de l'auteur récupéré de la session (à remplacer par la logique réelle)
        $auteur = isset($_SESSION['user_id']) ? $_SESSION['user_id'] : 0;

        // Vérifier l'existence de l'auteur dans la table users
        $checkAuthor = $conn->prepare("SELECT COUNT(*) FROM users WHERE id = :auteur");
        $checkAuthor->bindParam(':auteur', $auteur);
        $checkAuthor->execute();
        $authorExists = $checkAuthor->fetchColumn();

        if ($authorExists == 0) {
            throw new Exception("L'auteur avec l'ID $auteur n'existe pas dans la table users.");
        }

        // Préparation de la requête SQL
        $sql = "INSERT INTO publications (titre, description, categorie, motsCles, nivEducation, affiche, document, auteur) 
                VALUES (:titre, :description, :categorie, :motsCles, :nivEducation, :affiche, :document, :auteur)";

        $stmt = $conn->prepare($sql);
        $stmt->bindParam(':titre', $titre);
        $stmt->bindParam(':description', $description);
        $stmt->bindParam(':categorie', $categorie);
        $stmt->bindParam(':motsCles', $motsCles);
        $stmt->bindParam(':nivEducation', $nivEducation);
        $stmt->bindParam(':affiche', $affichePath);
        $stmt->bindParam(':document', $documentPath);
        $stmt->bindParam(':auteur', $auteur);

        if ($stmt->execute()) {
            header("Location: poster.php?success=1");
            exit();
        } else {
            echo "Erreur lors du téléchargement de la ressource.";
        }
    } catch (PDOException $e) {
        echo "Connection failed: " . $e->getMessage();
    } catch (Exception $e) {
        echo "Erreur: " . $e->getMessage();
    }

    $conn = null;
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

    <script src="js/dropdown.js"></script>
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
                    action="<?php echo $is_logged_in ? 'logout.php' : 'login.php'; ?>">
                    <?php if ($is_logged_in): ?>
                    <a href="profile.php" class="btn btn-secondary mx-2">Profile</a>
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
        <section class="pad-t7">
            <div class="container">
                <div class="row">
                    <div class="col-md-12">
                        <h1 class="center f-w600">Poster une ressource</h1>
                    </div>

                    <form method="post" enctype="multipart/form-data">
                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="titre" placeholder="Choisissez un titre..." class="input-field"
                                    type="text" />
                                <label for="input-field" class="input-label">Titre</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="row center">
                            <div class="col-md-2">
                                <label for="affiche" class="labelFile">
                                    <span>
                                        <svg xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            xmlns="http://www.w3.org/2000/svg" id="Capa_1" version="1.1" width="60px"
                                            height="60px" class="invert">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813 C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834 C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538 c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392 c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094 c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168 c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391 v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill: #010002"></path>
                                                    </g>
                                                    <g>
                                                        <path
                                                            d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078 c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045 L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227 c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036 C104.91,91.608,107.183,91.608,108.586,90.201z"
                                                            style="fill: #010002"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>Uploadez votre affiche !</p>
                                </label>
                                <input class="input" id="affiche" name="affiche" type="file"
                                    accept=".png, .jpg, .jpeg, .heic, .tiff, .svg">
                            </div>
                            <div class="col-md-2">
                                <label for="document" class="labelFile">
                                    <span>
                                        <svg xml:space="preserve" viewBox="0 0 184.69 184.69"
                                            xmlns:xlink="http://www.w3.org/1999/xlink"
                                            xmlns="http://www.w3.org/2000/svg" id="Capa_1" version="1.1" width="60px"
                                            height="60px" class="invert">
                                            <g>
                                                <g>
                                                    <g>
                                                        <path
                                                            d="M149.968,50.186c-8.017-14.308-23.796-22.515-40.717-19.813 C102.609,16.43,88.713,7.576,73.087,7.576c-22.117,0-40.112,17.994-40.112,40.115c0,0.913,0.036,1.854,0.118,2.834 C14.004,54.875,0,72.11,0,91.959c0,23.456,19.082,42.535,42.538,42.535h33.623v-7.025H42.538 c-19.583,0-35.509-15.929-35.509-35.509c0-17.526,13.084-32.621,30.442-35.105c0.931-0.132,1.768-0.633,2.326-1.392 c0.555-0.755,0.795-1.704,0.644-2.63c-0.297-1.904-0.447-3.582-0.447-5.139c0-18.249,14.852-33.094,33.094-33.094 c13.703,0,25.789,8.26,30.803,21.04c0.63,1.621,2.351,2.534,4.058,2.14c15.425-3.568,29.919,3.883,36.604,17.168 c0.508,1.027,1.503,1.736,2.641,1.897c17.368,2.473,30.481,17.569,30.481,35.112c0,19.58-15.937,35.509-35.52,35.509H97.391 v7.025h44.761c23.459,0,42.538-19.079,42.538-42.535C184.69,71.545,169.884,53.901,149.968,50.186z"
                                                            style="fill: #010002"></path>
                                                    </g>
                                                    <g>
                                                        <path
                                                            d="M108.586,90.201c1.406-1.403,1.406-3.672,0-5.075L88.541,65.078 c-0.701-0.698-1.614-1.045-2.534-1.045l-0.064,0.011c-0.018,0-0.036-0.011-0.054-0.011c-0.931,0-1.85,0.361-2.534,1.045 L63.31,85.127c-1.403,1.403-1.403,3.672,0,5.075c1.403,1.406,3.672,1.406,5.075,0L82.296,76.29v97.227 c0,1.99,1.603,3.597,3.593,3.597c1.979,0,3.59-1.607,3.59-3.597V76.165l14.033,14.036 C104.91,91.608,107.183,91.608,108.586,90.201z"
                                                            style="fill: #010002"></path>
                                                    </g>
                                                </g>
                                            </g>
                                        </svg>
                                    </span>
                                    <p>Uploadez votre document !</p>
                                </label>
                                <input class="input" id="document" name="document" type="file" accept=".pdf">
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="description" placeholder="Choisissez une description..."
                                    class="input-field" type="text" />
                                <label for="input-field" class="input-label">Description</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <p class="white f-z50">Merci de bien séparer les mots-clés et catégories par des /</p>
                            <div class="input-container">
                                <input name="categorie" placeholder="Choisissez une ou plusieurs catégories..."
                                    class="input-field" type="text" />
                                <label for="input-field" class="input-label">Catégories</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="motsCles" placeholder="Choisissez un ou plusieurs mots-clés..."
                                    class="input-field" type="text" />
                                <label for="input-field" class="input-label">Mots Clés</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-4 center">
                            <div class="input-container">
                                <input name="nivEducation" placeholder="Choisissez le niveau d'éducation..."
                                    class="input-field" type="text" />
                                <label for="input-field" class="input-label">Niveau d'éducation</label>
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
                                Poster
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </section>
    </main>

    <footer class="site-footer marg-t10">
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
                    <h6>Catégories</h6>
                    <ul class="footer-links">
                        <li><a href="">Accueil</a></li>
                        <li><a href="">Ressources</a></li>
                        <li><a href="">Poster</a></li>
                        <li><a href="">Contact</a></li>
                        <li><a href="">Profil</a></li>
                    </ul>
                </div>

                <div class="col-xs-6 col-md-3">
                    <h6>Informations</h6>
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

    <!-- Liens vers les scripts JS -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.10.2/dist/umd/popper.min.js"
        integrity="sha384-+1L6itRpyCmhcLHlGmKPQls7ZUMZ1N8VkMCs74Z2rSS+ZvKHd9BShKH0gK1sXL6A" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.min.js"
        integrity="sha384-giPnvfNDBW6aAfj9/0szzviubQ+9zgjTQebFiT4kZj+flnjoNf7wH/2ynrl6iNwn" crossorigin="anonymous">
    </script>
</body>

</html>