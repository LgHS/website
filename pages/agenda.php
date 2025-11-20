<?php
include('includes/ical-parser.php');
$icsContent = @file_get_contents($calendar_ics_url);

$events = [];

if ($icsContent) {
    $parser = new ICalParser();
    $parser->parse($icsContent);
    $events = $parser->getUpcomingEvents(100, ceil($agenda_days_limit / 7));
    $limitDate = new DateTime();
    $limitDate->modify("+{$agenda_days_limit} days");
    $events = array_filter($events, function($event) use ($limitDate) {
        return $event['start'] <= $limitDate;
    });
}

function processDescription($text) {
    $pattern = '/(https?:\/\/[^\s<]+)/i';
    return preg_replace($pattern, '<a href="$1" target="_blank" rel="noopener">$1</a>', $text);
}
?>
<article class="mb-6">
    <h3 class="bg-black text-white uppercase font-bold px-4 py-3 text-base mb-4">
        Agenda
    </h3>

    <p class="text-lg mb-3">
        Trouvez ci-dessous l'agenda du Liège Hackerspace pour les <strong><?php echo $agenda_days_limit; ?> prochains jours</strong>.
    </p>
    <p class="text-sm text-gray-600">
        <em>Certains événements sont partiellement ou totalement indépendants du hackerspace mais organisés dans ses murs, souvent par ses membres.</em>
    </p>
    <?php if (empty($events)): ?>
        <div class="leading-relaxed">
            <p>Aucun événement programmé pour le moment.</p>
            <p>Restez connectés sur nos réseaux sociaux pour ne rien manquer !</p>
        </div>
    <?php else: ?>
        <div class="leading-relaxed">
            <?php foreach ($events as $event): ?>
                <h4><?php echo htmlspecialchars($event['title']); ?></h4>
                <p class="text-sm text-gray-600">
                    <span class="font-semibold">📅 <?php 
                        $formatter = new IntlDateFormatter(
                            'fr_FR',
                            IntlDateFormatter::FULL,
                            IntlDateFormatter::NONE,
                            'Europe/Brussels',
                            IntlDateFormatter::GREGORIAN,
                            'EEEE d MMMM'
                            //'EEEE d MMMM yyyy'
                        );
                        echo ucfirst($formatter->format($event['start']));
                    ?></span>
                    <?php if ($event['end']): ?>
                        <span class="ml-4">🕐 <?php echo $event['start']->format('H:i'); ?> 
                            → 
                            <?php echo $event['end']->format('H:i'); ?>
                        </span>
                    <?php endif; ?>
                </p>
                <?php if ($event['location']): ?>
                    <p class="text-sm text-gray-600">
                        📍 <?php echo htmlspecialchars($event['location']); ?>
                    </p>
                <?php endif; ?>
                
                <?php if ($event['description']): ?>
                    <p class="text-sm">
                        <?php echo processDescription($event['description']); ?>
                    </p>
                <?php endif; ?>
            <?php endforeach; ?>
        </div>
        <div class="mt-8 p-4 bg-gray-50 border-2 border-black text-center">
            <p class="font-bold mb-3 text-lg">📆 S'abonner au calendrier</p>
            <p class="text-sm mb-4">Ajoutez notre calendrier à votre application préférée (Google Calendar, Apple Calendar, Outlook...)</p>
            <div class="bg-white border-2 border-black px-4 py-3 mb-3 inline-block">
                <code class="text-sm font-mono">https://lghs.be/calendar.php</code>
            </div>
            <button onclick="navigator.clipboard.writeText('https://lghs.be/calendar.php'); this.textContent='Copié !'; setTimeout(() => this.textContent='Copier l\'adresse', 2000)" 
                    class="inline-block bg-black text-white px-4 py-2 font-bold hover:bg-gray-800 transition-colors cursor-pointer">
                Copier l'adresse
            </button>
        </div>
    <?php endif; ?>
</article>
