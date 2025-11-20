<?php
/**
 * Proxy ICS - Liège Hackerspace
 * Récupère le calendrier Google et le sert proprement
 */

require_once('includes/config.php');

// Fonction pour récupérer et nettoyer l'ICS
function fetchAndCleanICS($url) {
    $icsContent = @file_get_contents($url);
    
    if (!$icsContent) {
        return false;
    }
    
    // Nettoyer les propriétés Google
    $lines = explode("\n", $icsContent);
    $cleanedLines = [];
    
    foreach ($lines as $line) {
        $line = rtrim($line, "\r");
        
        // Virer les propriétés Google spécifiques
        if (strpos($line, 'X-GOOGLE-') === 0) {
            continue;
        }
        
        // Virer d'autres métadonnées inutiles
        if (strpos($line, 'X-MOZ-') === 0) {
            continue;
        }
        
        $cleanedLines[] = $line;
    }
    
    return implode("\n", $cleanedLines);
}

// Récupérer l'ICS
$icsContent = fetchAndCleanICS($calendar_ics_url);

// Envoyer les en-têtes appropriés
header('Content-Type: text/calendar; charset=utf-8');
header('Content-Disposition: inline; filename="lghs-calendar.ics"');

// Envoyer le contenu
if ($icsContent) {
    echo $icsContent;
} else {
    // Erreur : renvoyer un calendrier vide valide
    http_response_code(503);
    echo "BEGIN:VCALENDAR\r\n";
    echo "VERSION:2.0\r\n";
    echo "PRODID:-//Liège Hackerspace//Calendar Proxy//FR\r\n";
    echo "X-WR-CALNAME:LgHs - Événements (indisponible)\r\n";
    echo "END:VCALENDAR\r\n";
}