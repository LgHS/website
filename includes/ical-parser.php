<?php
class ICalParser {
    private $events = [];
    
    public function parse($icsContent) {
        $lines = explode("\n", $icsContent);
        $currentEvent = null;
        $currentProperty = '';
        
        foreach ($lines as $line) {
            // Enlever seulement les retours chariots, PAS les espaces de début
            $line = rtrim($line, "\r");
            
            // Début d'un événement
            if (trim($line) === 'BEGIN:VEVENT') {
                $currentEvent = [];
                continue;
            }
            
            // Fin d'un événement
            if (trim($line) === 'END:VEVENT') {
                if ($currentEvent) {
                    $this->events[] = $currentEvent;
                }
                $currentEvent = null;
                continue;
            }
            
            if ($currentEvent !== null) {
                // Gérer les lignes continuées (commençant par un espace ou tab)
                if (!empty($line) && ($line[0] === ' ' || $line[0] === "\t")) {
                    // Ligne continuée : ajouter au contenu de la propriété précédente
                    // Enlever SEULEMENT le premier caractère (l'espace de continuation)
                    if (isset($currentEvent[$currentProperty])) {
                        $currentEvent[$currentProperty] .= substr($line, 1);
                    }
                    continue;
                }
                
                // Ligne normale avec propriété
                if (strpos($line, ':') !== false) {
                    list($property, $value) = explode(':', $line, 2);
                    
                    // Nettoyer le nom de propriété (enlever les paramètres mais les garder pour DTSTART)
                    $propertyClean = explode(';', $property)[0];
                    $currentProperty = $propertyClean;
                    
                    // Garder la ligne complète pour DTSTART (avec timezone)
                    if ($propertyClean === 'DTSTART') {
                        $currentEvent['DTSTART_RAW'] = $property;
                    }
                    
                    // Gérer les propriétés multiples (EXDATE, RDATE, etc.) comme tableaux
                    if ($propertyClean === 'EXDATE' || $propertyClean === 'RDATE') {
                        if (!isset($currentEvent[$propertyClean])) {
                            $currentEvent[$propertyClean] = [];
                        }
                        $currentEvent[$propertyClean][] = $property . ':' . $value; // Garder ligne complète
                    } else {
                        $currentEvent[$propertyClean] = $value;
                    }
                }
            }
        }
        
        return $this->events;
    }
    
    public function getUpcomingEvents($limit = 10, $weeksAhead = 12) {
        $now = new DateTime();
        $now->setTime(0, 0, 0); // Début de journée pour comparaison
        $maxDate = clone $now;
        $maxDate->modify("+{$weeksAhead} weeks");
        
        $upcoming = [];
        
        // Séparer les événements récurrents et les exceptions
        $recurringEvents = [];
        $exceptions = [];
        $simpleEvents = [];
        
        foreach ($this->events as $event) {
            if (!isset($event['DTSTART'])) continue;
            
            if (isset($event['RECURRENCE-ID'])) {
                // Événement exception - on le stocke pour remplacer l'occurrence
                $exceptions[$event['UID']][] = $event;
            } elseif (isset($event['RRULE'])) {
                // Événement récurrent
                $recurringEvents[] = $event;
            } else {
                // Événement simple
                $simpleEvents[] = $event;
            }
        }
        
        // Traiter les événements simples
        foreach ($simpleEvents as $event) {
            $timezone = $this->getTimezone($event);
            $startDate = $this->parseDate($event['DTSTART'], $timezone);
            if (!$startDate) continue;
            
            if ($startDate >= $now) {
                $upcoming[] = [
                    'title' => $this->decodeText($event['SUMMARY'] ?? 'Sans titre'),
                    'description' => $this->decodeText($event['DESCRIPTION'] ?? ''),
                    'location' => $this->decodeText($event['LOCATION'] ?? ''),
                    'start' => $startDate,
                    'end' => isset($event['DTEND']) ? $this->parseDate($event['DTEND'], $timezone) : null,
                    'url' => $event['URL'] ?? '',
                    'is_recurring' => false
                ];
            }
        }
        
        // Traiter les événements récurrents
        foreach ($recurringEvents as $event) {
            $occurrences = $this->generateOccurrences($event, $now, $maxDate);
            
            // Pour chaque occurrence, vérifier s'il y a une exception
            foreach ($occurrences as $occurrence) {
                $uid = $event['UID'];
                $occurrenceDateTime = $occurrence['start']->format('Ymd\THis');
                
                // Chercher une exception qui correspond
                $replaced = false;
                if (isset($exceptions[$uid])) {
                    foreach ($exceptions[$uid] as $exception) {
                        // Parser RECURRENCE-ID
                        $recurrenceId = $exception['RECURRENCE-ID'];
                        $timezone = $this->getTimezone($exception);
                        
                        // Nettoyer le RECURRENCE-ID (peut avoir TZID)
                        if (strpos($recurrenceId, 'TZID=') !== false && strpos($recurrenceId, ':') !== false) {
                            $parts = explode(':', $recurrenceId, 2);
                            $recurrenceId = $parts[1];
                        }
                        
                        $exceptionDate = $this->parseDate($recurrenceId, $timezone);
                        
                        if ($exceptionDate && 
                            $exceptionDate->format('Y-m-d H:i') === $occurrence['start']->format('Y-m-d H:i')) {
                            // Cette occurrence est remplacée par l'exception
                            $occurrence = [
                                'title' => $this->decodeText($exception['SUMMARY'] ?? $occurrence['title']),
                                'description' => $this->decodeText($exception['DESCRIPTION'] ?? $occurrence['description']),
                                'location' => $this->decodeText($exception['LOCATION'] ?? $occurrence['location']),
                                'start' => $this->parseDate($exception['DTSTART'], $timezone),
                                'end' => isset($exception['DTEND']) ? $this->parseDate($exception['DTEND'], $timezone) : $occurrence['end'],
                                'url' => $exception['URL'] ?? $occurrence['url'],
                                'is_recurring' => true
                            ];
                            $replaced = true;
                            break;
                        }
                    }
                }
                
                $upcoming[] = $occurrence;
            }
        }
        
        // Trier par date
        usort($upcoming, function($a, $b) {
            return $a['start'] <=> $b['start'];
        });
        
        return array_slice($upcoming, 0, $limit);
    }
    
    /**
     * Extraire la timezone d'un événement
     */
    private function getTimezone($event) {
        $timezone = 'UTC';
        if (isset($event['DTSTART_RAW']) && strpos($event['DTSTART_RAW'], 'TZID=') !== false) {
            preg_match('/TZID=([^:]+)/', $event['DTSTART_RAW'], $matches);
            if ($matches) {
                $timezone = $matches[1];
            }
        }
        return $timezone;
    }
    
    /**
     * Génère les occurrences d'un événement récurrent
     */
    private function generateOccurrences($event, $startLimit, $endLimit) {
        $occurrences = [];
        
        if (!isset($event['RRULE'])) return $occurrences;
        
        // Extraire la timezone
        $timezone = $this->getTimezone($event);
        
        // Parser la RRULE
        $rrule = $this->parseRRule($event['RRULE']);
        if (!$rrule) return $occurrences;
        
        $dtstart = $this->parseDate($event['DTSTART'], $timezone);
        if (!$dtstart) return $occurrences;
        
        $dtend = isset($event['DTEND']) ? $this->parseDate($event['DTEND'], $timezone) : null;
        $duration = null;
        if ($dtend) {
            $duration = $dtstart->diff($dtend);
        }
        
        // Parser les dates d'exception (EXDATE)
        $exdates = [];
        if (isset($event['EXDATE'])) {
            $exdateValues = is_array($event['EXDATE']) ? $event['EXDATE'] : [$event['EXDATE']];
            
            foreach ($exdateValues as $exdateLine) {
                // Extraire la valeur de la ligne complète
                // Format: EXDATE;TZID=Europe/Brussels:20251224T160000
                if (strpos($exdateLine, ':') !== false) {
                    $parts = explode(':', $exdateLine, 2);
                    $exdateLine = $parts[1];
                }
                
                // Séparer les dates multiples (séparées par des virgules)
                $dates = explode(',', $exdateLine);
                
                foreach ($dates as $dateStr) {
                    $dateStr = trim($dateStr);
                    $exDate = $this->parseDate($dateStr, $timezone);
                    if ($exDate) {
                        // Stocker la date ET l'heure pour comparaison précise
                        $exdates[] = $exDate->format('Y-m-d H:i:s');
                        // Aussi stocker juste la date pour compatibilité
                        $exdates[] = $exDate->format('Y-m-d');
                    }
                }
            }
        }
        
        // Générer les occurrences selon la fréquence
        $current = clone $dtstart;
        $maxOccurrences = 100; // Limite de sécurité
        $count = 0;
        
        while ($current <= $endLimit && $count < $maxOccurrences) {
            // Vérifier si cette occurrence n'est pas dans les exceptions
            $currentDateOnly = $current->format('Y-m-d');
            $currentDateTime = $current->format('Y-m-d H:i:s');
            
            if (!in_array($currentDateOnly, $exdates) && 
                !in_array($currentDateTime, $exdates) && 
                $current >= $startLimit) {
                $occEnd = null;
                if ($duration) {
                    $occEnd = clone $current;
                    $occEnd->add($duration);
                }
                
                $occurrences[] = [
                    'title' => $this->decodeText($event['SUMMARY'] ?? 'Sans titre'),
                    'description' => $this->decodeText($event['DESCRIPTION'] ?? ''),
                    'location' => $this->decodeText($event['LOCATION'] ?? ''),
                    'start' => clone $current,
                    'end' => $occEnd,
                    'url' => $event['URL'] ?? '',
                    'is_recurring' => true
                ];
            }
            
            // Calculer la prochaine occurrence
            if ($rrule['FREQ'] === 'WEEKLY') {
                $current = $this->getNextWeeklyOccurrence($current, $rrule, $dtstart);
            } elseif ($rrule['FREQ'] === 'MONTHLY') {
                $current = $this->getNextMonthlyOccurrence($current, $rrule, $dtstart);
            } elseif ($rrule['FREQ'] === 'DAILY') {
                $interval = $rrule['INTERVAL'] ?? 1;
                $current->modify("+{$interval} day");
            } else {
                // Fréquence non supportée
                break;
            }
            
            $count++;
        }
        
        return $occurrences;
    }
    
    /**
     * Parse une RRULE en tableau
     */
    private function parseRRule($rruleString) {
        $parts = explode(';', $rruleString);
        $rrule = [];
        
        foreach ($parts as $part) {
            if (strpos($part, '=') !== false) {
                list($key, $value) = explode('=', $part, 2);
                $rrule[$key] = $value;
            }
        }
        
        return $rrule;
    }
    
    /**
     * Calcule la prochaine occurrence pour un événement hebdomadaire
     */
    private function getNextWeeklyOccurrence($current, $rrule, $dtstart) {
        $interval = isset($rrule['INTERVAL']) ? (int)$rrule['INTERVAL'] : 1;
        
        if (isset($rrule['BYDAY'])) {
            // Jours de la semaine : MO, TU, WE, TH, FR, SA, SU
            $daysMap = ['SU' => 0, 'MO' => 1, 'TU' => 2, 'WE' => 3, 'TH' => 4, 'FR' => 5, 'SA' => 6];
            $targetDays = explode(',', $rrule['BYDAY']);
            $targetDayNumbers = array_map(function($day) use ($daysMap) {
                return $daysMap[$day] ?? null;
            }, $targetDays);
            $targetDayNumbers = array_filter($targetDayNumbers, function($v) { return $v !== null; });
            
            if (empty($targetDayNumbers)) {
                $current->modify("+{$interval} week");
                return $current;
            }
            
            // Trouver le prochain jour correspondant
            $next = clone $current;
            $next->modify('+1 day');
            
            for ($i = 0; $i < 14; $i++) { // Max 2 semaines de recherche
                $dayOfWeek = (int)$next->format('w');
                if (in_array($dayOfWeek, $targetDayNumbers)) {
                    return $next;
                }
                $next->modify('+1 day');
            }
        }
        
        $current->modify("+{$interval} week");
        return $current;
    }
    
    /**
     * Calcule la prochaine occurrence pour un événement mensuel
     */
    private function getNextMonthlyOccurrence($current, $rrule, $dtstart) {
        $interval = isset($rrule['INTERVAL']) ? (int)$rrule['INTERVAL'] : 1;
        
        if (isset($rrule['BYDAY'])) {
            // Ex: 1TH = 1er jeudi, -1FR = dernier vendredi
            preg_match('/(-?\d+)([A-Z]{2})/', $rrule['BYDAY'], $matches);
            if ($matches) {
                $occurrence = (int)$matches[1]; // 1, 2, 3, 4, ou -1
                $dayCode = $matches[2]; // MO, TU, WE, etc.
                
                $daysMap = ['SU' => 'Sunday', 'MO' => 'Monday', 'TU' => 'Tuesday', 
                           'WE' => 'Wednesday', 'TH' => 'Thursday', 'FR' => 'Friday', 'SA' => 'Saturday'];
                $dayName = $daysMap[$dayCode] ?? 'Monday';
                
                // Passer au mois suivant
                $next = clone $current;
                $next->modify("+{$interval} month");
                $next->setDate($next->format('Y'), $next->format('m'), 1);
                
                // Trouver le Nième jour de la semaine du mois
                if ($occurrence > 0) {
                    $next->modify("first {$dayName} of this month");
                    if ($occurrence > 1) {
                        $next->modify('+' . ($occurrence - 1) . ' week');
                    }
                } else {
                    // Dernier occurrence (-1)
                    $next->modify("last {$dayName} of this month");
                }
                
                // Conserver l'heure de l'événement original
                $next->setTime($dtstart->format('H'), $dtstart->format('i'), $dtstart->format('s'));
                
                return $next;
            }
        }
        
        // Par défaut, même jour du mois suivant
        $current->modify("+{$interval} month");
        return $current;
    }
    
    private function parseDate($dateString, $timezone = 'UTC') {
        // Format: 20251120T160000Z ou 20251120 ou 20251120T160000
        $dateString = str_replace(['T', 'Z'], ['', ''], $dateString);
        
        $date = null;
        if (strlen($dateString) === 8) {
            // Date seulement
            $date = DateTime::createFromFormat('Ymd', $dateString, new DateTimeZone($timezone));
        } else {
            // Date + heure
            $date = DateTime::createFromFormat('YmdHis', $dateString, new DateTimeZone($timezone));
        }
        
        return $date ?: null;
    }
    
    private function decodeText($text) {
        // Décoder les caractères échappés
        // IMPORTANT: \, dans l'ICS signifie une vraie virgule (pas un séparateur)
        // On garde l'espace qui suit si présent
        $text = str_replace(['\n', '\,', '\;'], ["\n", ',', ';'], $text);
        return $text;
    }
}
