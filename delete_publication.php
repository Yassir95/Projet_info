<?php
// Vérifier si l'ID de la publication est présent dans l'URL
if (isset($_GET['id'])) {
  // Récupérer l'ID de la publication depuis l'URL
  $publicationId = $_GET['id'];

  // Connexion à la base de données
  // Remplace ces valeurs par les informations de connexion à ta base de données
  $servername = "localhost";
  $username = "root";
  $password = "";
  $dbname = "ressources-educations";

  try {
    $conn = new PDO("mysql:host=$servername;dbname=$dbname", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Préparer et exécuter la requête de suppression
    $stmt = $conn->prepare("DELETE FROM publications WHERE id = ?");
    $stmt->execute([$publicationId]);

    // Rediriger l'utilisateur vers une page de confirmation ou une autre page appropriée
    header("Location: profile.php");
    exit();
  } catch (PDOException $e) {
    // Gérer les erreurs de connexion ou de requête
    echo "Erreur : " . $e->getMessage();
  }
} else {
  // Si l'ID de la publication n'est pas présent dans l'URL, rediriger l'utilisateur vers une autre page appropriée
  header("Location: profile.php");
  exit();
}
?>
