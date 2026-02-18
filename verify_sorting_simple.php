<?php

$names = [
    "Ana Zapatero",
    "Bernardo Alvarez",
    "Carlos De la Fuente",
    "David Benitez",
    "Elena"
];

function getSortKey($name) {
    $parts = explode(' ', $name, 2);
    // Logic from controller: return substring after first space, or full name if no space
    return isset($parts[1]) ? $parts[1] : $parts[0];
}

usort($names, function($a, $b) {
    return strnatcasecmp(getSortKey($a), getSortKey($b));
});

echo "Sorted Names:\n";
foreach ($names as $name) {
    echo "- " . $name . " (Key: '" . getSortKey($name) . "')\n";
}
