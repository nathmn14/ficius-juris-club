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

// Connexion à la base de données
$pdo = pdo();

// Récupérer les membres
$sql = "SELECT * FROM membres";
$stmt = $pdo->prepare($sql);
$stmt->execute();
$membres = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des membres - Ficius Juris Club</title>
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
            <h1 class="text-4xl font-bold">Liste des membres du Ficius Juris Club</h1>
            <p class="text-lg mt-4">Consultez la liste complète des membres inscrits</p>
        </div>
    </header>

    <!-- Table Section -->
    <section class="container mx-auto p-6 mt-6">
        <div class="bg-white p-8 rounded-lg shadow-md">
            <h2 class="text-3xl font-bold text-center mb-6">Liste des Membres</h2>

            <table class="w-full table-auto border-collapse">
                <thead>
                    <tr class="bg-gray-800 text-white">
                        <th class="p-4 text-left">#</th>
                        <th class="p-4 text-left">Nom</th>
                        <th class="p-4 text-left">Numéro de Téléphone</th>
                        <th class="p-4 text-left">Adresse E-mail</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $counter = 1; // Initialisation du compteur
                    foreach ($membres as $membre): ?>
                        <tr class="border-b">
                            <td class="p-4"><?php echo $counter++; ?></td> <!-- Affichage du numéro -->
                            <td class="p-4"><?php echo htmlspecialchars($membre['nom']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($membre['telephone']); ?></td>
                            <td class="p-4"><?php echo htmlspecialchars($membre['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
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
