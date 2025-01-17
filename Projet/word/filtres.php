<?php
// Requête pour récupérer les projets et leurs catégories
$sql = "
    SELECT 
        p.id AS projet_id, 
        p.titre, 
        p.description, 
        p.annee, 
        p.image_url, 
        GROUP_CONCAT(c.nom SEPARATOR ' / ') AS categories
    FROM projets p
    LEFT JOIN projets_categories pc ON p.id = pc.projet_id
    LEFT JOIN categories c ON pc.categorie_id = c.id
    GROUP BY p.id
";
$stmt = $pdo->query($sql);
$projets = $stmt->fetchAll(PDO::FETCH_ASSOC);

// Récupérer les catégories depuis la table
$query = $pdo->query("SELECT * FROM categories");
$categories = $query->fetchAll();