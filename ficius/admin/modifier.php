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

// Vérifier si l'ID de l'activité est passé en paramètre
if (isset($_GET['id'])) {
    $id = $_GET['id'];

    // Récupérer les informations de l'activité à modifier
    $sql = "SELECT * FROM activites WHERE id = :id";
    $stmt = $pdo->prepare($sql);
    $stmt->execute([':id' => $id]);
    $activite = $stmt->fetch();

    // Vérifier si l'activité existe
    if (!$activite) {
        echo "<p>Cette activité n'existe pas.</p>";
        exit;
    }

    // Traitement du formulaire de modification
    if ($_SERVER['REQUEST_METHOD'] == 'POST') {
        // Récupérer les nouvelles valeurs du formulaire
        $titre = $_POST['titre'];
        $description = $_POST['description'];

        // Vérification si une autre activité avec le même titre existe déjà
        $sqlCheck = "SELECT * FROM activites WHERE titre = :titre AND id != :id";
        $stmtCheck = $pdo->prepare($sqlCheck);
        $stmtCheck->execute([
            ':titre' => $titre,
            ':id' => $id
        ]);
        $existingActivity = $stmtCheck->fetch();

        // Si le titre existe déjà
        if ($existingActivity) {
            $error_message = "Une activité avec ce titre existe déjà. Veuillez choisir un autre titre.";
        } else {
            // Gestion de l'upload de l'image (optionnel)
            $imageDestination = $activite['image']; // Garder l'ancienne image par défaut

            if (isset($_FILES['image']) && $_FILES['image']['error'] == 0) {
                $imageName = $_FILES['image']['name'];
                $imageTmpName = $_FILES['image']['tmp_name'];
                $imageNewName = uniqid('', true) . "." . pathinfo($imageName, PATHINFO_EXTENSION);
                $imageDestination = 'uploads/' . $imageNewName;

                // Déplacer l'image vers le dossier 'uploads'
                if (!move_uploaded_file($imageTmpName, $imageDestination)) {
                    echo "<p>Une erreur est survenue lors de l'upload de l'image.</p>";
                    exit;
                }
            }

            // Mettre à jour les informations dans la base de données
            $sqlUpdate = "UPDATE activites SET titre = :titre, description = :description, image = :image WHERE id = :id";
            $stmtUpdate = $pdo->prepare($sqlUpdate);
            $stmtUpdate->execute([
                ':titre' => $titre,
                ':description' => $description,
                ':image' => $imageDestination,
                ':id' => $id
            ]);

            // Rediriger directement vers la page des activités après la mise à jour
            header("Location: events.php");
            exit; // On s'assure que le script ne continue pas après la redirection
        }
    }
} else {
    echo "<p>Aucune activité sélectionnée.</p>";
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier Activité - Ficius Juris Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
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
            <h1 class="text-4xl font-bold">Modifier une Activité</h1>
            <p class="text-lg mt-4">Modifiez les informations de l'activité sélectionnée</p>
        </div>
    </header>

    <!-- Form Section -->
    <section class="container mx-auto p-6 mt-6">
        <div class="max-w-3xl mx-auto bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-6">Formulaire de modification d'une activité</h2>

            <!-- Affichage du message d'erreur si le titre existe déjà -->
            <?php if (isset($error_message)): ?>
                <div class="bg-red-500 text-white p-4 rounded-lg mb-6">
                    <strong>Erreur:</strong> <?php echo $error_message; ?>
                </div>
            <?php endif; ?>

            <!-- Formulaire de modification -->
            <form method="POST" enctype="multipart/form-data">
                <div class="mb-4">
                    <label for="titre" class="block text-lg font-semibold">Titre de l'activité</label>
                    <input type="text" name="titre" id="titre" class="w-full p-3 border rounded-lg mt-2" value="<?php echo htmlspecialchars($activite['titre']); ?>" placeholder="Titre de l'activité">
                </div>

                <div class="mb-4">
                    <label for="description" class="block text-lg font-semibold">Description</label>
                    <textarea name="description" id="description" class="w-full p-3 border rounded-lg mt-2" rows="4" placeholder="Description de l'activité"><?php echo htmlspecialchars($activite['description']); ?></textarea>
                </div>

                <div class="mb-4">
                    <label for="image" class="block text-lg font-semibold">Image de couverture</label>
                    <input type="file" name="image" id="image" class="w-full p-3 border rounded-lg mt-2">
                    <p class="text-gray-600 mt-2">Actuellement : <img src="<?php echo htmlspecialchars($activite['image']); ?>" alt="Image actuelle" class="w-20 h-20 object-cover rounded-lg"></p>
                </div>

                <div class="flex justify-between mt-6">
                    <a href="events.php" class="inline-block bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700">Annuler</a>
                    <button type="submit" class="inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Mettre à jour</button>
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
