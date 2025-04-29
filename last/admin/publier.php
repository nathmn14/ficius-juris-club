<?php
session_start();

// Vérifier si l'utilisateur est un admin
if (!isset($_SESSION['admin']) || $_SESSION['admin'] !== TRUE) {
    header("Location: index.php"); // Rediriger vers index.php si l'utilisateur n'est pas admin
    exit();
}

// Déconnexion de l'admin
if (isset($_GET['logout'])) {
    session_unset();  // Supprime toutes les variables de session
    session_destroy(); // Détruit la session
    header("Location: index.php"); // Redirige vers la page d'accueil après la déconnexion
    exit();
}
include('../fonctions/connect_bdd.php');

$pdo = pdo();
// Vérification du formulaire soumis
$message = ''; // Initialisation du message
$errorMessage = ''; // Initialisation du message d'erreur
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Vérification des champs du formulaire
    if (!empty($_POST['titre']) && !empty($_POST['description']) && isset($_FILES['image'])) {
        // Récupération des valeurs du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];

        // Vérification si le titre existe déjà dans la base de données
        $sqlCheckTitle = "SELECT * FROM activites WHERE titre = :titre";
        $stmtCheckTitle = $pdo->prepare($sqlCheckTitle);
        $stmtCheckTitle->execute([':titre' => $titre]);
        $existingActivity = $stmtCheckTitle->fetch();

        if ($existingActivity) {
            $errorMessage = 'Une activité avec ce titre existe déjà. Veuillez choisir un autre titre.'; // Message d'erreur
        } else {
            // Gestion de l'upload de l'image
            $imageName = $_FILES['image']['name'];
            $imageTmpName = $_FILES['image']['tmp_name'];
            $imageSize = $_FILES['image']['size'];
            $imageError = $_FILES['image']['error'];

            // Vérifier s'il n'y a pas d'erreur dans l'upload
            if ($imageError === 0) {
                // Générer un nom unique pour l'image
                $imageNewName = uniqid('', true) . "." . pathinfo($imageName, PATHINFO_EXTENSION);
                $imageDestination = 'uploads/' . $imageNewName;

                // Déplacer l'image vers le dossier 'uploads'
                if (move_uploaded_file($imageTmpName, $imageDestination)) {
                    // Insertion des données dans la base de données
                    $sql = "INSERT INTO activites (titre, description, image) VALUES (:titre, :description, :image)";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute([
                        ':titre' => $titre,
                        ':description' => $description,
                        ':image' => $imageDestination
                    ]);
                    $message = 'Activité publiée avec succès !'; // Message de succès
                } else {
                    $errorMessage = 'Une erreur est survenue lors de l\'upload de l\'image.'; // Message d'erreur
                }
            } else {
                $errorMessage = 'Erreur lors de l\'upload de l\'image.'; // Message d'erreur
            }
        }
    } else {
        $errorMessage = 'Veuillez remplir tous les champs.'; // Message d'erreur si des champs sont vides
    }
}
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une nouvelle activité - Ficius Juris Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        // Cacher le message après 5 secondes
        window.onload = function() {
            const messageElement = document.getElementById('confirmation-message');
            if (messageElement) {
                setTimeout(function() {
                    messageElement.style.display = 'none';
                }, 5000); // 5 secondes
            }

            const errorElement = document.getElementById('error-message');
            if (errorElement) {
                setTimeout(function() {
                    errorElement.style.display = 'none';
                }, 5000); // 5 secondes
            }
        };
    </script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white shadow-md py-4 px-6 flex justify-between items-center">
        <img src="images/Logo.png" alt="Ficius Juris Club" class="h-12">
        <ul id="navbar" class="hidden md:flex space-x-6">
            <li><a href="home.php" class="text-lg font-semibold hover:text-blue-600">Accueil</a></li>
            <li><a href="publier.php" class="text-lg font-semibold hover:text-blue-600">Ajouter un événement</a></li>
            <li><a href="membres.php" class="text-lg font-semibold hover:text-blue-600">Membres du club</a></li>
            <li><a href="events.php" class="text-lg font-semibold hover:text-blue-600">Événements du club</a></li>
            <li><a href="?logout=true" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700">Se déconnecter</a></li> <!-- Bouton de déconnexion -->
        </ul>
        <button id="menu-toggle" class="md:hidden text-white text-2xl">☰</button>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-[300px] flex items-center justify-center bg-cover bg-center text-white" style="background-image: url('images/img01.jfif');">
        <div class="bg-black bg-opacity-50 p-8 rounded-xl">
            <h1 class="text-4xl font-bold">Ajouter une nouvelle activité</h1>
            <p class="text-lg mt-4">Publiez de nouvelles activités pour le Ficius Juris Club</p>
        </div>
    </header>

    <!-- Message de confirmation -->
    <?php if ($message): ?>
        <div id="confirmation-message" class="bg-green-500 text-white p-4 mt-4 text-center rounded-lg">
            <?php echo $message; ?>
        </div>
    <?php endif; ?>

    <!-- Message d'erreur -->
    <?php if ($errorMessage): ?>
        <div id="error-message" class="bg-red-500 text-white p-4 mt-4 text-center rounded-lg">
            <?php echo $errorMessage; ?>
        </div>
    <?php endif; ?>

    <!-- Form Section -->
    <section class="container mx-auto p-6 mt-6">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-6">Formulaire d'ajout d'une activité</h2>
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="titre" class="block text-lg font-semibold">Titre de l'activité</label>
                    <input type="text" name="titre" id="titre" class="w-full p-3 border rounded-lg mt-2" placeholder="Titre de l'activité">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-lg font-semibold">Description</label>
                    <textarea name="description" id="description" class="w-full p-3 border rounded-lg mt-2" rows="4" placeholder="Description de l'activité"></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-lg font-semibold">Image de couverture</label>
                    <input type="file" name="image" id="image" class="w-full p-3 border rounded-lg mt-2">
                </div>

                <div class="flex justify-between mt-6">
                    <a href="home.php" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Annuler</a>
                    <button type="submit" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Publier</button>
                </div>
            </form>

        </div>
    </section>

    <!-- Footer -->
    <footer class="bg-gray-900 text-white text-center py-6">
        <p>&copy; 2025 - Ficius Juris Club, Tous droits réservés</p>
        <p>Nous contacter au +243 999 999 999</p>
        <p>By Digital Web Services</p>
    </footer>

    <!-- JavaScript for Mobile Menu -->
    <script>
        const menuToggle = document.getElementById('menu-toggle');
        const navbar = document.getElementById('navbar');

        menuToggle.addEventListener('click', () => {
            navbar.classList.toggle('hidden');
        });
    </script>
</body>
</html>
