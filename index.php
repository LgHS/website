<?php 
require_once('includes/config.php');
require_once('includes/router.php'); 
?>
<!DOCTYPE html>
<html lang="fr-BE">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
    
    <title>Liège Hackerspace | Learn \ Make \ Share | <?php echo $page_title; ?></title>
    
    <meta property="og:type" content="website">
    <meta property="og:title" content="Liège Hackerspace | Learn \ Make \ Share">
    <meta property="og:url" content="https://lghs.be">
    <meta property="og:image" content="https://lghs.be/images/opengraphHS.jpg">
    <meta property="og:image:width" content="1200">
    <meta property="og:image:height" content="630">
    <meta property="og:description" content="Le Liège Hackerspace est un tiers-lieu d'inclusion sociale et technologique où des personnes partageant un intérêt commun peuvent se rencontrer, collaborer, coopérer et mutualiser leurs ressources ainsi que leurs connaissances.">
    <meta property="og:locale" content="fr_BE">
    <meta property="og:site_name" content="Liège Hackerspace">
    <!-- Twitter -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="Liège Hackerspace | Learn \ Make \ Share">
    <meta name="twitter:description" content="Le Liège Hackerspace est un tiers-lieu d'inclusion sociale et technologique où des personnes partageant un intérêt commun peuvent se rencontrer, collaborer, coopérer et mutualiser.">
    <meta name="twitter:image" content="https://lghs.be/images/opengraphHS.jpg">
    <!-- SEO classique -->
    <meta name="description" content="Le Liège Hackerspace est un tiers-lieu d'inclusion sociale et technologique à Liège. Learn \ Make \ Share.">
    
    <link rel="icon" type="image/png" href="images/favicon/favicon-96x96.png" sizes="96x96" />
    <link rel="icon" type="image/svg+xml" href="images/favicon/favicon.svg" />
    <link rel="shortcut icon" href="images/favicon/favicon.ico" />
    <link rel="apple-touch-icon" sizes="180x180" href="images/favicon/apple-touch-icon.png" />
    <meta name="apple-mobile-web-app-title" content="LgHs" />
    <link rel="manifest" href="images/favicon/site.webmanifest" />

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="css/style.css">
    
    <style type="text/tailwindcss">
        @font-face {
            font-family: 'Open Sans';
            src: url('fonts/OpenSans-Regular.woff2') format('woff2');
            font-weight: 400;
            font-style: normal;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url('fonts/OpenSans-Bold.woff2') format('woff2');
            font-weight: 700;
            font-style: normal;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url('fonts/OpenSans-Italic.woff2') format('woff2');
            font-weight: 400;
            font-style: italic;
        }
        @font-face {
            font-family: 'Open Sans';
            src: url('fonts/OpenSans-BoldItalic.woff2') format('woff2');
            font-weight: 700;
            font-style: italic;
        }
        
        @layer components {
            .texte-marge {
                @apply px-2;
            }
            .liste-marge {
                @apply ml-10 mr-4;
            }
        }
    </style>
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Open Sans', 'sans-serif']
                    }
                }
            }
        }
    </script>
</head>
<body class="relative pb-24">
    <div class="container mx-auto px-4 relative" style="max-width: 1170px;">
    <?php include('./includes/top.php'); ?>
        <?php include('./includes/header.php'); ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <main class="md:col-span-2">
                <?php include "pages/{$page}.php"; ?>
            </main>
            <?php include('includes/side.php'); ?>
        </div>
        <?php //include('includes/partners.php'); ?>
        <?php include('includes/footer.php'); ?>
    </div>
    <script>
        async function updateSpaceStatus() {
            const light = document.getElementById('status-light');
            const text = document.getElementById('status-text');
            try {
                const response = await fetch('<?php echo $spaceapi_url; ?>');
                const data = await response.json();
                const isOpen = data.state && data.state.open === true;
                const openToVisitors = data.state && data.state.open_to_visitors === true;
                if (isOpen && openToVisitors) {
                    // Vert : Ouvert aux visiteurs
                    light.className = 'status-light status-open';
                    light.title = 'Le hackerspace est ouvert aux visiteurs !';
                    text.textContent = 'Ouvert';
                    text.className = 'text-xs text-green-600 font-bold';
                } else if (isOpen && !openToVisitors) {
                    // Orange : Ouvert mais réservé aux membres
                    light.className = 'status-light status-members-only';
                    light.title = 'Ouvert aux membres uniquement';
                    text.textContent = 'Membres';
                    text.className = 'text-xs text-orange-600 font-bold';
                } else {
                    // Rouge : Fermé
                    light.className = 'status-light status-closed';
                    light.title = 'Le hackerspace est fermé';
                    text.textContent = 'Fermé';
                    text.className = 'text-xs text-red-600';
                }
            } catch (error) {
                console.error('Erreur lors de la récupération du statut:', error);
                light.className = 'status-light status-unknown';
                light.title = 'API injoinable, état du hackerspace inconnu';
                text.textContent = 'État inconnu';
                text.className = 'text-xs text-gray-500';
            }
        }
        updateSpaceStatus();
        setInterval(updateSpaceStatus, 60000);
    </script>
</body>
</html>
