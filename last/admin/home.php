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
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil Admin - Ficius Juris Club</title>
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
            <h1 class="text-4xl font-bold">Espace Administrateur</h1>
            <p class="text-lg mt-4">Gérez les événements et les membres du Ficius Juris Club</p>
        </div>
    </header>

    <!-- Admin Dashboard Links -->
    <section class="container mx-auto p-6 mt-8">
        <h2 class="text-3xl font-bold text-center mb-6">Tableau de bord Admin</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
            <div class="bg-white p-6 shadow-md rounded-lg hover:shadow-lg transition duration-300 ease-in-out">
                <h3 class="text-xl font-semibold mb-4">Accueil-Admin</h3>
                <p class="text-gray-600">Retour à la page d'accueil de l'administration du club.</p>
                <a href="home.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Accéder</a>
            </div>

            <div class="bg-white p-6 shadow-md rounded-lg hover:shadow-lg transition duration-300 ease-in-out">
                <h3 class="text-xl font-semibold mb-4">Ajouter un événement</h3>
                <p class="text-gray-600">Publiez de nouveaux événements pour le Ficius Juris Club.</p>
                <a href="publier.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Accéder</a>
            </div>

            <div class="bg-white p-6 shadow-md rounded-lg hover:shadow-lg transition duration-300 ease-in-out">
                <h3 class="text-xl font-semibold mb-4">Voir la liste des membres</h3>
                <p class="text-gray-600">Consultez la liste des membres du club et leurs informations.</p>
                <a href="membres.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Accéder</a>
            </div>

            <div class="bg-white p-6 shadow-md rounded-lg hover:shadow-lg transition duration-300 ease-in-out">
                <h3 class="text-xl font-semibold mb-4">Voir les événements du club</h3>
                <p class="text-gray-600">Consultez les événements passés et à venir organisés par le club.</p>
                <a href="events.php" class="mt-4 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700">Accéder</a>
            </div>
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
