
<?php require_once('./inc/functions.php'); ?>

<!doctype html>
<html lang="fr">
<head>
    <meta charset="utf-8">
    <title>My clapps</title>
    <link rel="stylesheet" href="style.css">
    <script src="script.js"></script>
</head>
<body>
    
    <header>
        <h1>My clapps</h1>
        <nav>
            <ul>
                <li>
                    <a href="#">Ajouter une annonce</a>
                </li>
                <li>
                    <a href="#">Mes annonces</a>
                </li>
            </ul>
        </nav>
    </header>
    
    <section>
        <article>
            <div class="preview">
                <h2>Nom du projet</h2>
                <div>Ajouté par <span>Admin</span> le <span>29 octobre 2012</span></div>
                <div class="available">
                    <a href="#">Acteurs dispo</a>
                    <a href="#">techniciens dispo</a>
                </div>
                <div class="share">
                    <a href="#">Ajouter aux favoris</a>
                    <a href="#">Partager l'annonce'</a>
                </div>
                <div class="desc">
                    <img src="#" alt="photo profil" />
                    <p>Description</p>
                </div>
                <a href="#">Voir plus</a>
            </div><!-- fin preview -->
            <div class="more">
                <div class="moreDesc">
                    <p>more description</p>
                </div>
                <div class="profiles">
                    <ul>
                        <li>
                            <span>Img</span>
                            <p>Un acteur blond de 30 ans</p>
                            <a href="#">Candidater</a>
                            <a href="#">Candidat trouvé</a>
                        </li>
                        <li>
                            <span>Img</span>
                            <p>Un acteur noir de 40 ans</p>
                            <a href="#">Candidater</a>
                            <a href="#">Candidat trouvé</a>
                        </li>
                    </ul>
                </div><!-- fin profile -->
                <div class="manage">
                    <a href="#">Cloturer l'annonce</a>
                    <a href="#">Supprimer l'annonce</a>
                </div>
            </div><!-- fin more -->
        </article>
        
        <?php
            
            getProjects(); 
            //$getProjects=getProjects();
            $getProjects=array("un", "deux", "trois");
            foreach ($getProjects as $project) {
                echo '<pre>'; 
                print_r($project); 
                echo '</pre>'; 
            }
            
         ?>
        <article>
            <div class="preview">
                <h2>Nom du projet</h2>
                <div>Ajouté par <span>Admin</span> le <span>29 octobre 2012</span></div>
                <div class="available">
                    <a href="#">Acteurs dispo</a>
                    <a href="#">techniciens dispo</a>
                </div>
                <div class="share">
                    <a href="#">Ajouter aux favoris</a>
                    <a href="#">Partager l'annonce'</a>
                </div>
                <div class="desc">
                    <img src="#" alt="photo profil" />
                    <p>Description</p>
                </div>
                <a href="#">Voir plus</a>
            </div><!-- fin preview -->
            <div class="more">
                <div class="moreDesc">
                    <p>more description</p>
                </div>
                <div class="profiles">
                    <ul>
                        <li>
                            <span>Img</span>
                            <p>Un acteur blond de 30 ans</p>
                            <a href="#">Candidater</a>
                            <a href="#">Candidat trouvé</a>
                        </li>
                        <li>
                            <span>Img</span>
                            <p>Un acteur noir de 40 ans</p>
                            <a href="#">Candidater</a>
                            <a href="#">Candidat trouvé</a>
                        </li>
                    </ul>
                </div><!-- fin profile -->
                <div class="manage">
                    <a href="#">Cloturer l'annonce</a>
                    <a href="#">Supprimer l'annonce</a>
                </div>
            </div><!-- fin more -->
        </article>
    </section>
        
</body>
</html>