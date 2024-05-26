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

// Mise à jour des informations de l'utilisateur
if ($_SERVER['REQUEST_METHOD'] === 'POST' && $is_logged_in) {
    // Récupérer les données du formulaire
    $pseudo = $_POST['pseudo'] ?? $user['pseudo'];
    $email = $_POST['email'] ?? $user['email'];
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';
    $description = $_POST['description'] ?? $user['description'];
    $prenom = $_POST['prenom'] ?? $user['prenom'];
    $nom = $_POST['nom'] ?? $user['nom'];
    $date_naissance = $_POST['date_naissance'] ?? $user['date_naissance'];

    // Validation des données
    if ($password && $password === $confirm_password) {
        // Mise à jour du mot de passe dans la base de données
        $stmt = $conn->prepare("UPDATE users SET pseudo = ?, email = ?, password = ?, description = ?, prenom = ?, nom = ?, date_naissance = ? WHERE id = ?");
        $stmt->execute([$pseudo, $email, $password, $description, $prenom, $nom, $date_naissance, $user['id']]);
    } else {
        // Mise à jour sans le mot de passe
        $stmt = $conn->prepare("UPDATE users SET pseudo = ?, email = ? WHERE id = ?");
        $stmt->execute([$pseudo, $email, $user['id']]);
    }

    // Récupérer les informations mises à jour de l'utilisateur
    $stmt = $conn->prepare("SELECT * FROM users WHERE id = ?");
    $stmt->execute([$user['id']]);
    $updated_user = $stmt->fetch(PDO::FETCH_ASSOC);

    // Vérifier si les données de l'utilisateur ont été récupérées avec succès
    if ($updated_user) {
        $user = $updated_user;
        // Mettre à jour la session utilisateur
        $_SESSION['user'] = $user;
    } else {
        echo "Erreur: Impossible de récupérer les informations de l'utilisateur.";
        die();
    }
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

<div class="container">
    <div class="row pad-t7 v-align">
        <div class="col-md-1 profile-picture v-align">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none" stroke="#7b2d2d" stroke-width="2"
                stroke-linecap="round" stroke-linejoin="round" class="lucide lucide-circle-user max420">
                <circle cx="12" cy="12" r="10" />
                <circle cx="12" cy="10" r="3" />
                <path d="M7 20.662V19a2 2 0 0 1 2-2h6a2 2 0 0 1 2 2v1.662" />
            </svg>
        </div>
        <div class="col-md-11 v-align">
            <h1 class="AF8260 bold">
                <?php
                if ($is_logged_in && is_array($user) && isset($user['pseudo'])) {
                    // Si oui, afficher le pseudo de l'utilisateur
                    echo htmlspecialchars($user['pseudo']);
                } else {
                    // Si non, afficher un message d'erreur
                    echo "Utilisateur non trouvé";
                }

                ?>
            </h1>
            <br>

        </div>
    </div>
    <div class="row">
        <div class="col-md-7 pad-t2">
            <div class="row">
                <p class="l-s1 white">
                    <!-- Afficher le prénom et le nom de l'utilisateur -->
                    <?php if (isset($user['prenom']) && isset($user['nom'])): ?>
                    <p class="l-s1 white">
                        <?php echo htmlspecialchars($user['prenom']) . ' ' . htmlspecialchars($user['nom']); ?><br>
                    </p>
                <?php endif; ?>

                <!-- Afficher la date de naissance de l'utilisateur -->
                <?php if (isset($user['date_naissance'])): ?>
                    <p class="l-s1 white">Née le : <?php echo htmlspecialchars($user['date_naissance']); ?></p>
                <?php endif; ?>

                <!-- Afficher la description de l'utilisateur -->
                <?php if (isset($user['description'])): ?>
                    <p class="l-s1 white"><?php echo htmlspecialchars($user['description']); ?></p>
                <?php else: ?>
                    <p class="l-s1 white">Aucune description disponible.</p>
                <?php endif; ?>
                </p>
            </div>
            <div class="row">
                <h2 class="f-z100 bold white">Ressources Publiées</h2>
                <?php if (count($publications) > 0): ?>
                    <?php foreach ($publications as $index => $publication): ?>
                        <div class="col-md-4">
                            <div class="card mb-3" style="background-color: rgb(26, 31, 57);">
                                <img src="<?php echo htmlspecialchars($publication['affiche']); ?>" class="card-img-top"
                                    alt="Affiche de la publication">
                                <div class="card-body">
                                    <h5 class="card-title white"><?php echo htmlspecialchars($publication['titre']); ?></h5>
                                    <p class="card-text white"><?php echo htmlspecialchars($publication['description']); ?></p>
                                    <p class="card-text white"><small
                                            class="white"><?php echo htmlspecialchars($user['pseudo']); ?></small>
                                    </p>
                                    <a href="<?php echo htmlspecialchars($publication['document']); ?>" class="btn btn-primary"
                                        target="_blank">Ouvrir</a>
                                    <button type="button" class="btn btn-danger"
                                        onclick="confirmDelete(<?php echo $publication['id']; ?>)">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor"
                                            class="bi bi-trash" viewBox="0 0 16 16">
                                            <path
                                                d="M5.5 1a.5.5 0 0 1 .5.5V2h4V1.5a.5.5 0 0 1 1 0V2h1a2 2 0 0 1 2 2v1H1V4a2 2 0 0 1 2-2h1V1.5A.5.5 0 0 1 5.5 1zM2 6v9a2 2 0 0 0 2 2h8a2 2 0 0 0 2-2V6H2z" />
                                            <path fill-rule="evenodd"
                                                d="M0 3a1 1 0 0 1 1-1h14a1 1 0 0 1 1 1v1H0V3zm5.5 4a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm2 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1zm2 0a.5.5 0 0 0 0 1h1a.5.5 0 0 0 0-1h-1z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <?php if (($index + 1) % 3 === 0): ?>

                        <?php endif; ?>
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="l-s1 white">Aucune publication disponible pour cet utilisateur.</p>
                <?php endif; ?>
            </div>
        </div>
        <div class="col-md-3 offset-1 pad-t2">
            <div class="row">
                <div class="col-md-12">
                    <h2 class="f-z100 bold white">Modification de profil</h2>
                    <p class="l-s1 white">(Merci de remplir tous les champs)</p>
                    <form method="post" enctype="multipart/form-data">
                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="pseudo" placeholder="Entrez un pseudo..." class="input-field" type="text"
                                    value="<?php echo htmlspecialchars($user['pseudo']); ?>" />
                                <label for="input-field" class="input-label">Pseudo</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="email" placeholder="Entrez une adresse e-mail..." class="input-field"
                                    type="email" value="<?php echo htmlspecialchars($user['email']); ?>" />
                                <label for="input-field" class="input-label">Adresse e-mail</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="prenom" placeholder="Entrez votre prénom..." class="input-field"
                                    type="text" value="<?php echo htmlspecialchars($user['prenom']); ?>" />
                                <label for="input-field" class="input-label">Prénom</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="nom" placeholder="Entrez votre nom..." class="input-field" type="text"
                                    value="<?php echo htmlspecialchars($user['nom']); ?>" />
                                <label for="input-field" class="input-label">Nom</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="date_naissance" placeholder="Entrez votre date de naissance..."
                                    class="input-field" type="date"
                                    value="<?php echo isset($user['date_naissance']) ? htmlspecialchars($user['date_naissance']) : ''; ?>" />
                                <label for="input-field" class="input-label">Date de Naissance</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="description" placeholder="Entrez une description..." class="input-field"
                                    type="text"
                                    value="<?php echo isset($user['description']) ? htmlspecialchars($user['description']) : ''; ?>" />
                                <label for="input-field" class="input-label">Description</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="password" placeholder="Entrez un nouveau mot de passe..."
                                    class="input-field" type="password" />
                                <label for="input-field" class="input-label">Nouveau Mot de Passe</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <div class="input-container">
                                <input name="confirm_password" placeholder="Confirmez le mot de passe..."
                                    class="input-field" type="password" />
                                <label for="input-field" class="input-label">Confirmer le Mot de Passe</label>
                                <span class="input-highlight"></span>
                            </div>
                        </div>

                        <div class="col-md-12 center">
                            <button type="submit" class="btn btn-primary btn-lg">Enregistrer les modifications</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<footer class="site-footer marg-t12">
    <div class="container">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <h6>À propos</h6>
                <p class="text-justify">Explorez notre collection diversifiée de ressources éducatives et laissez-vous
                    inspirer par le savoir à portée de main. Rejoignez notre communauté d'apprenants passionnés et
                    découvrez
                    de
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
                    <a href="#">CY tech</a>.
                </p>
            </div>
        </div>
    </div>
</footer>
<!-- Scripts JavaScript -->
<script src="https://stackpath.bootstrapcdn.com/bootstrap/5.3.3/js/bootstrap.bundle.min.js"
    integrity="sha384-xHG2l6Dv5a7lxEeueJlkHDf8H5CqU5Fkg4HFPoSllhh9boDjw4osvB+alN1p70y/" crossorigin="anonymous">
    </script>
<script>
    function confirmDelete(publicationId) {
        // Afficher une boîte de dialogue modale de confirmation
        if (confirm("Êtes-vous sûr de vouloir supprimer cette publication ?")) {
            // Si l'utilisateur clique sur OK, effectuer la suppression
            window.location.href = 'delete_publication.php?id=' +
                publicationId; // Remplace 'delete_publication.php' par le nom de ton script de suppression
        }
    }
</script>


</body>

</html>