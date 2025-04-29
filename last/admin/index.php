<?php 
session_start();

// Mot de passe admin
$password = "admin";

// Vérification de la méthode d'envoi du formulaire
if($_SERVER['REQUEST_METHOD'] === "POST"){

    // Ensuite, on vérifie si le bouton connexion est cliqué
    if(isset($_POST['connexion'])){
        // Alors on vérifie si tous les champs sont remplis
        if(!empty($_POST['mdp'])){
            // Puis on récupère les entrées de l'utilisateur dans des variables
            $entree = trim($_POST['mdp']);

            // Vérification du mot de passe
            if ($entree === $password){
                $_SESSION['admin'] = TRUE;
                header("Location: home.php");
                exit;
            } else {
                echo "<p class='text-red-600'>Mot de passe incorrect !</p>";
            }
        }
    }
}  
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Espace Administrateur - Ficius Juris Club</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

    <!-- Navbar -->
    <nav class="bg-gray-900 text-white shadow-md py-4 px-6 flex justify-between items-center">
        <img src="images/Logo.png" alt="Ficius Juris Club" class="h-12">
        <ul id="navbar" class="hidden md:flex space-x-6">
            <li><a href="#" class="text-lg font-semibold hover:text-blue-600">Accueil</a></li>
            <li><a href="#activites" class="text-lg font-semibold hover:text-blue-600">Nos activités</a></li>
            <li><a href="#rejoindre" class="text-lg font-semibold hover:text-blue-600">Nous rejoindre</a></li>
        </ul>
        <button id="menu-toggle" class="md:hidden text-white text-2xl">☰</button>
    </nav>

    <!-- Hero Section -->
    <header class="relative h-[300px] flex items-center justify-center bg-cover bg-center text-white" style="background-image: url('images/img01.jfif');">
        <div class="bg-black bg-opacity-50 p-8 rounded-xl">
            <h1 class="text-4xl font-bold">Bienvenue dans l'espace Administrateur</h1>
            <p class="text-lg mt-4">Authentifiez-vous pour accéder à l'administration du Ficius Juris Club</p>
        </div>
    </header>

    <!-- Login Form -->
    <section class="container mx-auto p-6 mt-8">
        <h2 class="text-3xl font-bold text-center mb-6">Connexion Admin</h2>
        <form method="POST" class="max-w-md mx-auto bg-white p-6 shadow-md rounded-lg">
            <label for="mdp" class="block text-left mb-2 font-semibold">Mot de passe</label>
            <input type="password" name="mdp" id="mdp" class="w-full p-2 border rounded-lg mb-4" placeholder="Entrez le mot de passe" required>

            <button type="submit" name="connexion" class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Se connecter</button>
        </form>
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
