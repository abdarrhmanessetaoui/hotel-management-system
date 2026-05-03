<?php
function getDirContents($dir, &$results = array()) {
    $files = scandir($dir);
    foreach ($files as $key => $value) {
        $path = realpath($dir . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path)) {
            if (substr($path, -10) === '.blade.php') {
                $results[] = $path;
            }
        } else if ($value != "." && $value != "..") {
            getDirContents($path, $results);
            $results[] = $path;
        }
    }
    return $results;
}

$files = getDirContents(__DIR__ . '/resources/views');

$translations = [
    // Admin specifics
    'Total Rooms' => 'Total Chambres',
    'Total Reservations' => 'Total Réservations',
    'Statistics' => 'Statistiques',
    'Customers' => 'Clients',
    'Menu' => 'Menu',
    'admin' => 'Admin',
    'Sign out' => 'Se déconnecter',
    'No Hotel Assigned' => 'Aucun Hôtel Assigné',
    'Menu' => 'Menu',
    
    // Services
    'Rooms & Appartment' => 'Chambres & Appartements',
    "Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem\n                        sed diam stet diam sed stet lorem." => "Profitez de nos prestations haut de gamme pour un séjour d'exception avec un confort optimal.",
    "Erat ipsum justo amet duo et elitr dolor, est duo duo eos lorem sed diam stet diam sed stet lorem." => "Profitez de nos prestations haut de gamme pour un séjour d'exception avec un confort optimal.",
    
    // Team & Testimonials
    'Designation' => 'Poste',
    'Client Name' => 'Nom du Client',
    'Profession' => 'Profession',
    "Tempor stet labore dolor clita stet diam amet ipsum dolor ipsum vero magna" => "Un service exceptionnel et un séjour inoubliable avec un personnel accueillant."
];

$count = 0;
foreach ($files as $file) {
    if (is_dir($file)) continue;

    $content = file_get_contents($file);
    $original = $content;

    foreach ($translations as $en => $fr) {
        $content = str_replace(">".$en."<", ">".$fr."<", $content);
        $content = str_replace("> ".$en." <", "> ".$fr." <", $content);
        $content = str_replace($en, $fr, $content); // Direct aggressive replacement for long Lorem Ipsum sentences
    }
    
    if ($content !== $original) {
        file_put_contents($file, $content);
        $count++;
    }
}
echo "Translated $count additional Blade files to French successfully.\n";

