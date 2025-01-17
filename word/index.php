<?php

// Inclure le fichier de connexion
require_once 'connect.php';
require_once 'filtres.php';
session_start(); // Démarre la session


// Vérifier si l'utilisateur est connecté 


// Requête pour récupérer les projets et leurs catégories
require_once 'filtres.php';
?>
<html>
    <head>
        <title>Lewis Nathaniel</title>
        <meta charset="UTF-8">
        <link rel="stylesheet" href="assets/css/style.css">
        <style>
                #new-suggestion-notification {
                    background-color: #4CAF50; /* Couleur de fond (verte pour succès) */
                    color: white;
                    padding: 15px;
                    text-align: center;
                    font-size: 18px;
                    position: fixed;
                    top: 0;
                    left: 0;
                    width: 100%;
                    z-index: 9999;
                    display: none; /* Par défaut, cachée */
                    border-bottom: 2px solid #2e7d32; /* Ajout d'une bordure pour plus de style */
                }
        </style>
    </head>

    <body>

        <navbar>

            <div class="profil">
                <img class="entete" src="assets/images/lewis.png">
                <div class="nom">
                    <h3> Lewis <br>Nathaniel</h3>
                    <p>UI UX Designer</p>
                </div>   
            </div>

            <div class="menu">

                <!-- Menu déroulant pour Projet -->
                    <div class="nav">
                        <a href="#projet" onclick="toggleDropdown(event, 'dropdown-menu-projet')">PROJETS</a>
                            <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' )): ?>
                                <div id="dropdown-menu-projet" class="dropdown-menu">
                                    <a href="ajouter_projet.php">Ajouter un Projet</a>
                                    <a href="suggestion.php">Suggestions de Projet</a>
                                    <a href="modifier_projet.php">Modifier un Projet</a>
                                    <a href="supprimer_projet.php">Supprimer un Projet</a>
                                </div>
                            <?php endif; ?>
                    </div>
                <!-- //////////////////////////// -->

                <a class="nav" href="#apropos">A PROPOS</a>

                <!-- Menu déroulant pour BLOG -->
                    <div class="nav">
                        <a href="#blog" onclick="toggleDropdown(event, 'dropdown-menu-blog')">BLOG</a>
                            <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' )): ?>
                                <div id="dropdown-menu-blog" class="dropdown-menu">
                                    <a href="ajouter_article.php">Ajouter un Article</a>
                                    <a href="modifier_article.php">Modifier un Article</a>
                                </div>
                            <?php endif; ?>
                    </div>
                <!-- //////////////////////////// -->

                <!-- partie connexion et déconnexion-->
                    <?php if (isset($_SESSION['user_role']) && ($_SESSION['user_role'] == 'admin' || $_SESSION['user_role'] == 'user')): ?>
                        <a class="nav" href="deconnexion.php">DÉCONNEXION</a>
                    <?php else: ?>
                        <a class="nav" href="connexion.php">
                            <svg xmlns="http://www.w3.org/2000/svg" width="30" height="30" fill="currentColor" class="bi bi-person-fill-lock" viewBox="0 0 16 16">
                                <path d="M11 5a3 3 0 1 1-6 0 3 3 0 0 1 6 0m-9 8c0 1 1 1 1 1h5v-1a2 2 0 0 1 .01-.2 4.49 4.49 0 0 1 1.534-3.693Q8.844 9.002 8 9c-5 0-6 3-6 4m7 0a1 1 0 0 1 1-1v-1a2 2 0 1 1 4 0v1a1 1 0 0 1 1 1v2a1 1 0 0 1-1 1h-4a1 1 0 0 1-1-1zm3-3a1 1 0 0 0-1 1v1h2v-1a1 1 0 0 0-1-1"/>
                            </svg>
                        </a>
                    <?php endif; ?>
                <!-- //////////////////////////// -->

                <a class="contacte" onclick="togglePopup()" href="#">CONTACT</a>
        </div>

        <?php
            if (isset($_SESSION['success'])) {
                echo '<div id="alert-message" class="alert alert-success">' . htmlspecialchars($_SESSION['success']) . '</div>';
                // Supprimer le message après affichage pour éviter qu'il reste après rechargement
                unset($_SESSION['success']);
            }
            ?>
           
            <script>
            document.addEventListener('DOMContentLoaded', function () {
                const alertBox = document.getElementById('alert-message');
                if (alertBox) {
                    // Faire apparaître l'alerte avec une classe "show"
                    setTimeout(() => {
                        alertBox.classList.add('show');
                    }, 100); // Attendre 100 ms avant d'afficher

                    // Faire disparaître l'alerte après 10 secondes
                    setTimeout(() => {
                        alertBox.classList.remove('show'); // Réduit la visibilité
                        setTimeout(() => {
                            alertBox.remove(); // Supprime complètement
                        }, 500); // Temps pour la transition de disparition
                    }, 5000); // 5 secondes avant disparition
                }
            });
            </script>

                
        <?php


                // Vérifier si l'utilisateur est admin
                $is_admin = isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'admin';

                $new_suggestion = false; // Initialisation

                if ($is_admin) {
                    // Connexion à la base de données
                    // $pdo = new PDO("mysql:host=localhost;dbname=ma_base", "username", "password");

                    // Initialiser la dernière vérification si nécessaire
                    if (!isset($_SESSION['last_check_time'])) {
                        $_SESSION['last_check_time'] = '1970-01-01 00:00:00'; // Valeur par défaut
                    }

                    // Récupérer la dernière suggestion
                    $sql = "SELECT * FROM suggestion ORDER BY date_creation DESC LIMIT 1;";
                    $stmt = $pdo->prepare($sql);
                    $stmt->execute();
                    $last_suggestion = $stmt->fetch(PDO::FETCH_ASSOC);

                    if ($last_suggestion) {
                        $last_check_time = date('Y-m-d H:i:s', strtotime($_SESSION['last_check_time']));


                        // Comparaison des dates
                        if ($last_suggestion['date_creation'] > $last_check_time) {
                            $_SESSION['last_check_time'] = $last_suggestion['date_creation']; // Mettre à jour
                            $new_suggestion = true;
                        
                        }
                    }
                }
?>
        <div id="new-suggestion-notification" style="display: none;">
             <p><strong>Nouvelle suggestion ajoutée !</strong></p>
        </div>

    <script>
            document.addEventListener("DOMContentLoaded", function() {
                <?php if ($new_suggestion): ?>
                    document.getElementById("new-suggestion-notification").style.display = "block";
                    setTimeout(() => {
                        document.getElementById("new-suggestion-notification").style.display = "none";
                    }, 5000);
                <?php else: ?>
                    console.log("Pas de nouvelle suggestion.");
                <?php endif; ?>
            });
    </script>

        </navbar>


        <section1>

        <div class="presentation">

                <p id="t1">Hello, je suis...</p>
                <h1 id="t2">Lewis Nathaniel</h1>
                <h1 id="t3">UI & UX</h1>

                <div class="link">
                    <a href="https://www.behance.net/"> <img src="https://1000logos.net/wp-content/uploads/2020/11/Behance-Logo-2020.jpg" alt="Logo de Behance" width="50" height="50"></a>
                  <a href ="https://dribbble.com/"><img src="https://cdn.freebiesupply.com/logos/large/2x/dribbble-5-logo-png-transparent.png" width="50" height="50"></a>
                    <a href="https://fr.linkedin.com/"> <img src="assets/images/icons/linkedin.png" alt="Logo de Behance" width="50" height="50"></a>
                    <a href="https://www.facebook.com/?locale=fr_FR"><img src="assets/images/icons/facebook.png" width="50" height="50"></a>
                    <a href="https://www.instagram.com/"><img src="assets/images/icons/Instagram_icon.png" alt="Logo de Behance" width="50" height="50"></a>
                    <a href="https://x.com/home"><img src="assets/images/icons/X.png" width="50" height="50"></a>
                </div>


            <a class="contact" onclick="togglePopup()" href="#">CONTACT</a>
                <div id="popup-overlay">
                
                    <div class="popup-content">
                        <div>
                        <h1>CONTACT</h1>
                        <h2>APPELEZ-MOI OU ENVOYEZ-MOI UN MAIL</h2>
                        <div class="information">
                            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-telephone-fill" viewBox="0 0 16 16">
                                <path fill-rule="evenodd" d="M1.885.511a1.745 1.745 0 0 1 2.61.163L6.29 2.98c.329.423.445.974.315 1.494l-.547 2.19a.68.68 0 0 0 .178.643l2.457 2.457a.68.68 0 0 0 .644.178l2.189-.547a1.75 1.75 0 0 1 1.494.315l2.306 1.794c.829.645.905 1.87.163 2.611l-1.034 1.034c-.74.74-1.846 1.065-2.877.702a18.6 18.6 0 0 1-7.01-4.42 18.6 18.6 0 0 1-4.42-7.009c-.362-1.03-.037-2.137.703-2.877z"/>
                            </svg> 01 02 03 04 05</p>
                            <p><svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-envelope-fill" viewBox="0 0 16 16">
                                <path d="M.05 3.555A2 2 0 0 1 2 2h12a2 2 0 0 1 1.95 1.555L8 8.414zM0 4.697v7.104l5.803-3.558zM6.761 8.83l-6.57 4.027A2 2 0 0 0 2 14h12a2 2 0 0 0 1.808-1.144l-6.57-4.027L8 9.586zm3.436-.586L16 11.801V4.697z"/>
                            </svg> designer@ui43.com</p>
                            </div>

                            <form id="contact-form" action="contact.php" method="POST">
                                    <label for="email">Adresse e-mail</label>
                                    <input type="email" id="email" name="email" required />
                    
                                    <label for="content">Contenu</label>
                                    <textarea id="content" name="content" required></textarea>
                    
                                    <button type="submit" class="contact">ENVOYER</button>
                                </form>
                            
                        </div>

                        <div>
                            <img src="assets/images/popup-1.jpg" alt="Contact Image" />
                        </div>


                        <a href="javascript:void(0)" onclick="togglePopup()" class="popup-exit"><svg xmlns="http://www.w3.org/2000/ vg" width="16" height="16" fill="currentColor" class="bi bi-x-circle-fill" viewBox="0 0 16 16">
                            <path d="M16 8A8 8 0 1 1 0 8a8 8 0 0 1 16 0M5.354 4.646a.5.5 0 1 0-.708.708L7.293 8l-2.647 2.646a.5.5 0 0 0 .708.708L8 8.707l2.646 2.647a.5.5 0 0 0 .708-.708L8.707 8l2.647-2.646a.5.5 0 0 0-.708-.708L8 7.293z"/>
                          </svg></a>
                    </div>

                    
                </div>
                

            </div>

            <div class="illustration">
                <img src ="assets/images/undraw_innovative_b409.svg" width="659px" height="487px">
            </div>

        </section1>

        <section class="projet-section">

            <!-- Filtre des catégories -->
            <div class="filtre">
                <a href="#" data-category="all">Tous</a>
                <?php foreach ($categories as $category): ?>
                    <a href="#" data-category="<?= htmlspecialchars($category['nom']); ?>">
                        <?= htmlspecialchars($category['nom']); ?>
                    </a>
                <?php endforeach; ?>
            </div>

            <!-- Section des projets -->
            <div class="projets">
                <?php foreach ($projets as $projet): ?>
                    <div class="projet" data-categories="<?= htmlspecialchars($projet['categories']); ?>">

                        <!-- Image du projet -->
                        <img src="<?= htmlspecialchars($projet['image_url']); ?>" alt="<?= htmlspecialchars($projet['titre']); ?>">

                        <!-- Détails du projet -->
                        <div class="categorie">
                            <p><?= htmlspecialchars($projet['categories'] ?? 'Pas de catégorie'); ?></p>

                            <!-- Informations supplémentaires -->
                            <div class="infos">
                                <div class="titre">
                                    <h1><?= htmlspecialchars($projet['titre']); ?></h1>
                                </div>
                            </div>
                        </div>
                    </div>
                <?php endforeach; ?>

                <!-- Bouton suggestion de projet (visible pour les utilisateurs connectés) -->
                <?php if (isset($_SESSION['user_role']) && $_SESSION['user_role'] == 'user'): ?>
                    <a href="suggestion_utilisateur.php" class="suggestion-button">Demander une suggestion de projet</a>
                <?php endif; ?>
            </div>

        </section>

        <sectionapropos>

            <div class="image">
                <img src="assets/images/undraw_Designer_by46.svg" width="685px" height="500px">
            </div>

            <div class="apropos">
                <h1>A PROPOS...</h1>

                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. 
                    Maecenas pellentesque eu enim eget luctus. Sed augue felis, 
                    facilisis et elementum vitae, aliquam sit amet ante. Sed iaculis 
                    eros sem, elementum consequat est consequat eu. Quisque 
                    aliquet a ipsum nec tincidunt. Nulla vitae rhoncus leo. Praesent 
                    dui sapien, bibendum quis tempus dictum, auctor ac dui. 
                    Vestibulum ante ipsum primis in faucibus orci luctus et ultrices 
                    posuere cubilia Curae; Donec at mauris porta, ullamcorper sem 
                    quis, lobortis sem. Donec sit amet aliquet dui, at varius est. 
                    Phasellus porttitor finibus neque vel vehicula.</p>

                <a class="contact" href="#">CONTACT</a>

                </div>

        </sectionapropos>

        <sectiontemoignage>

            <div class="banniere">

                <p> Lorem ipsum dolor sit amet, consectetur adipiscing elit. Maecenas pellentesque eu enim eget luctus. Sed augue felis, 
                    facilisis et elementum vitae, aliquam sit amet ante. Sed iaculis eros sem, elementum consequat est consequat eu. Quisque 
                    aliquet a ipsum nec tincidunt. Nulla vitae rhoncus leo. Praesent dui sapien, bibendum quis tempus dictum.</p>
                
                <div class="profilauteur">
                    <img src="assets/images/lena.jpg" width="125px" height="125px">
                </div>

                <div class="auteur">
                    <h1> Lena M. Brooks </h1>
                    <h2>Marketing House</h2>
                    <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                      </svg> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                      </svg> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                      </svg> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-fill" viewBox="0 0 16 16">
                        <path d="M3.612 15.443c-.386.198-.824-.149-.746-.592l.83-4.73L.173 6.765c-.329-.314-.158-.888.283-.95l4.898-.696L7.538.792c.197-.39.73-.39.927 0l2.184 4.327 4.898.696c.441.062.612.636.282.95l-3.522 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256z"/>
                      </svg> <svg xmlns="http://www.w3.org/2000/svg" width="16" height="16" fill="currentColor" class="bi bi-star-half" viewBox="0 0 16 16">
                        <path d="M5.354 5.119 7.538.792A.52.52 0 0 1 8 .5c.183 0 .366.097.465.292l2.184 4.327 4.898.696A.54.54 0 0 1 16 6.32a.55.55 0 0 1-.17.445l-3.523 3.356.83 4.73c.078.443-.36.79-.746.592L8 13.187l-4.389 2.256a.5.5 0 0 1-.146.05c-.342.06-.668-.254-.6-.642l.83-4.73L.173 6.765a.55.55 0 0 1-.172-.403.6.6 0 0 1 .085-.302.51.51 0 0 1 .37-.245zM8 12.027a.5.5 0 0 1 .232.056l3.686 1.894-.694-3.957a.56.56 0 0 1 .162-.505l2.907-2.77-4.052-.576a.53.53 0 0 1-.393-.288L8.001 2.223 8 2.226z"/>
                      </svg>
                </div>

            </div>
        

            <div class="auteurtemoignage">


            </div>



        </sectiontemoignage>


        <sectionblog>

            <div class="title">

                <h1>MON BLOG</h1>
                <p>Mes avis sur les dernières tendances du Web</p>

            </div>

            <div class="articles">

                <div class="article">
                    <div class="dates">
                        <span>15</span>
                        <span>JAN</span>
                    </div>
                    <img src="assets/images/blog/airbnb-2384737_1920.jpg">
                    <h1>TOP 10 TRENDS FOR 2023</h1>
                    <p> Lorem ipsum dolor sit amet, consectetur 
                        adipiscing elit. Maecenas pellentesque eu enim 
                        eget luctus. Sed augue felis, facilisis et 
                        elementum vitae, aliquam sit amet ante. Sed 
                        iaculis eros sem, elementum consequat.</p>
                    <div class="boutonlire">
                    <a class="lire" href="#">LIRE</a>
                    </div>
                </div>

                <div class="article">
                    <div class="dates">
                        <span>15</span>
                        <span>JAN</span>
                    </div>
                    <img src="assets/images/blog/office-820390_1920.jpg">
                    <h1>WEBSITE INSPIRATION</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur 
                    adipiscing elit. Maecenas pellentesque eu enim 
                    eget luctus. Sed augue felis, facilisis et 
                    elementum vitae, aliquam sit amet ante. Sed 
                    iaculis eros sem, elementum consequat.</p>

                    <a class="lire" href="#">LIRE</a>
                </div>

                <div class="article">
                    <div class="dates">
                        <span>15</span>
                        <span>JAN</span>
                    </div>
                    <img src="assets/images/blog/technology-3164715_1920.jpg">
                    <h1>CHANGES IN SOCIAL MEDIA</h1>
                    <p>Lorem ipsum dolor sit amet, consectetur 
                    adipiscing elit. Maecenas pellentesque eu enim 
                    eget luctus. Sed augue felis, facilisis et 
                    elementum vitae, aliquam sit amet ante. Sed 
                    iaculis eros sem, elementum consequat.</p>

                    <a class="lire" href="#">LIRE</a>
                </div>
            </div>


        </sectionblog>

        




        <div class="footer-basic">
            <footer>
                
                <ul class="list-inline">
                    <li class="list-inline-item"><p>© 2018 UI43 - Free Templates </p></li>
                </ul>
                
            </footer>
        </div>

    <script src="assets/js/java.js"></script>
    </body>
</html>