<?php
include('fonctions/connect_bdd.php');

$bdd = pdo();

// Traitement du formulaire pour rejoindre le club
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Récupérer les données du formulaire
    $nom = htmlspecialchars($_POST['nom']);
    $telephone = htmlspecialchars($_POST['telephone']);
    $email = htmlspecialchars($_POST['email']);

    // Vérifier que les champs ne sont pas vides
    if (!empty($nom) && !empty($telephone) && !empty($email)) {
        // Vérification si l'email existe déjà dans la base de données
        $sql = "SELECT COUNT(*) FROM membres WHERE email = :email";
        $stmt = $bdd->prepare($sql);
        $stmt->bindParam(':email', $email);
        $stmt->execute();
        $emailExist = $stmt->fetchColumn();

        if ($emailExist) {
            $message = "Cette adresse e-mail est déjà utilisée. Veuillez en choisir une autre.";
        } else {
            // Insertion des données dans la base de données
            $sql = "INSERT INTO membres (nom, telephone, email) VALUES (:nom, :telephone, :email)";
            $stmt = $bdd->prepare($sql);
            $stmt->bindParam(':nom', $nom);
            $stmt->bindParam(':telephone', $telephone);
            $stmt->bindParam(':email', $email);

            if ($stmt->execute()) {
                $message = "Votre inscription a été prise en compte !";
            } else {
                $message = "Une erreur est survenue. Veuillez réessayer.";
            }
        }
    } else {
        $message = "Tous les champs doivent être remplis.";
    }
}

// Récupérer les activités depuis la base de données
$sql = "SELECT * FROM activites";
$stmt = $bdd->prepare($sql);
$stmt->execute();
$activites = $stmt->fetchAll();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accueil | Ficius Juris Club</title>
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
    <header class="relative h-[500px] flex items-center justify-center bg-cover bg-center text-white" style="background-image: url('images/img01.jfif');">
        <div class="bg-black bg-opacity-50 p-8 rounded-xl">
            <h1 class="text-4xl font-bold">Bienvenue au Ficius Juris Club</h1>
            <p class="text-lg mt-4">Un espace d'échange et de réflexion sur le droit</p>
            <a href="#rejoindre" class="mt-6 inline-block bg-blue-600 text-white px-6 py-3 rounded-lg shadow-md hover:bg-blue-700">Rejoignez-nous</a>
        </div>
    </header>

    <!-- Nos Objectifs -->
    <section class="bg-blue-50 py-12 text-center">
        <h2 class="text-3xl font-bold mb-4">Nos objectifs</h2>
        <div class="grid md:grid-cols-3 gap-6 container mx-auto px-6">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Éducation juridique</h3>
                <p class="text-gray-600">Encourager l’apprentissage du droit à travers des séminaires, des formations et des études de cas.</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Développement professionnel</h3>
                <p class="text-gray-600">Aider les étudiants et jeunes juristes à bâtir leur carrière grâce à des stages et du mentorat.</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Sensibilisation et engagement</h3>
                <p class="text-gray-600">Promouvoir les droits humains et encourager l’engagement citoyen à travers des actions concrètes.</p>
            </div>
        </div>
    </section>

    <!-- Nos Activités -->
    <section id="activites" class="container mx-auto p-6">
        <h2 class="text-3xl font-bold text-center mb-6">Nos activités</h2>
        <div class="grid md:grid-cols-3 gap-6">
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Conférences</h3>
                <p class="text-gray-600">Des experts du droit viennent partager leur savoir sur des thématiques variées allant du droit constitutionnel au droit international.</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Débats et tables rondes</h3>
                <p class="text-gray-600">Nous organisons des discussions interactives pour analyser les enjeux juridiques contemporains et encourager l'esprit critique.</p>
            </div>
            <div class="bg-white p-4 shadow-md rounded-lg">
                <h3 class="text-xl font-semibold">Publications et recherches</h3>
                <p class="text-gray-600">Nous rédigeons et publions des articles juridiques accessibles à tous pour enrichir la connaissance du droit.</p>
            </div>
        </div>

        <!-- Affichage des activités depuis la base de données -->
        <div class="grid md:grid-cols-3 gap-6 mt-8">
            <?php foreach ($activites as $activite): ?>
                <div class="bg-white p-4 shadow-md rounded-lg">
                    <h3 class="text-xl font-semibold"><?php echo htmlspecialchars($activite['titre']); ?></h3>
                    <p class="text-gray-600"><?php echo htmlspecialchars($activite['description']); ?></p>
                    <?php if (!empty($activite['image'])): ?>
                        <img src="<?php echo 'admin/'.htmlspecialchars($activite['image']); ?>" alt="Image de l'activité" class="w-full h-48 object-cover rounded-lg mt-4">
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </section>

    <!-- Formulaire pour rejoindre le club -->
    <section id="rejoindre" class="bg-gray-100 py-12 text-center">
        <h2 class="text-3xl font-bold mb-6">Rejoignez-nous !</h2>
        <?php if (isset($message)): ?>
            <p class="text-lg text-green-500"><?php echo $message; ?></p>
        <?php endif; ?>
        <form action="" method="POST" class="max-w-lg mx-auto bg-white p-6 shadow-md rounded-lg">
            <label class="block text-left mb-2 font-semibold">Nom</label>
            <input type="text" name="nom" class="w-full p-2 border rounded-lg mb-4" placeholder="Votre nom" required>
            
            <label class="block text-left mb-2 font-semibold">Numéro de téléphone</label>
            <input type="text" name="telephone" class="w-full p-2 border rounded-lg mb-4" placeholder="Votre numéro" required>
            
            <label class="block text-left mb-2 font-semibold">Adresse e-mail</label>
            <input type="email" name="email" class="w-full p-2 border rounded-lg mb-4" placeholder="Votre email" required>
            
            <button class="w-full bg-blue-600 text-white py-2 rounded-lg hover:bg-blue-700">Soumettre</button>
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
