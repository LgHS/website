<?php
// Router simple
$page = isset($_GET['page']) ? $_GET['page'] : 'home';

// Whitelist des pages autorisées
$allowed_pages = ['home', 'contact', 'faq', 'agenda'];

// Check
if (!in_array($page, $allowed_pages)) {
    $page = 'home';
}

// Définir le titre selon la page
$page_titles = [
    'home' => 'Accueil',
    'contact' => 'Contact',
    'faq' => 'FAQ',
    'agenda' => 'Agenda'
];
$page_title = $page_titles[$page];
?>