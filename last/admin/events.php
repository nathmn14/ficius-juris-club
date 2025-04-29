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

// Suppression d'une activité
if (isset($_GET['delete'])) {
    $id = $_GET['delete'];
    $sqlDelete = "DELETE FROM activites WHERE id = :id";
    $stmtDelete = $pdo->prepare($sqlDelete);
    $stmtDelete->execute([':id' => $id]);
    header("Location: events.php"); // Rediriger après suppression
    exit;
}

// Récupération des activités
$sql = "SELECT * FROM activites";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$activites = $stmt->fetchAll();
?>

<!DOCTYPE html> 
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>admin - events</title>
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

    <!-- Section pour gérer les activités -->
    <section class="container mx-auto p-6 mt-6">
        <h1 class="text-3xl font-bold text-center mb-6">Gérer les Activités du Club</h1>

        <!-- Bouton Ajouter un événement -->
        <div class="text-center mb-6">
            <a href="publier.php" class="bg-green-500 text-white px-6 py-3 rounded-lg shadow-md hover:bg-green-700">Ajouter un événement</a>
        </div>

        <!-- Tableau des activités -->
        <table class="min-w-full bg-white border border-gray-300 shadow-lg">
            <thead>
                <tr class="bg-gray-800 text-white">
                    <th class="py-2 px-4">#</th> <!-- Colonne pour numéroter -->
                    <th class="py-2 px-4">Titre</th>
                    <th class="py-2 px-4">Description</th>
                    <th class="py-2 px-4">Image</th>
                    <th class="py-2 px-4">Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1; // Initialisation du compteur
                foreach ($activites as $activite): ?>
                <tr class="border-b border-gray-200">
                    <td class="py-2 px-4"><?php echo $counter++; ?></td> <!-- Affichage du numéro -->
                    <td class="py-2 px-4"><?php echo htmlspecialchars($activite['titre']); ?></td>
                    <td class="py-2 px-4"><?php echo htmlspecialchars($activite['description']); ?></td>
                    <td class="py-2 px-4">
                        <img src="<?php echo htmlspecialchars($activite['image']); ?>" alt="Image de l'activité" class="w-20 h-20 object-cover rounded-lg">
                    </td>
                    <td class="py-2 px-4">
                        <a href="modifier.php?id=<?php echo $activite['id']; ?>" class="bg-blue-500 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Modifier</a>
                        <a href="?delete=<?php echo $activite['id']; ?>" class="bg-red-500 text-white px-4 py-2 rounded-lg hover:bg-red-700" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette activité ?');">Supprimer</a>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
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
