<?php

// Mocking a simple object
class Student {
    public $nombre_completo;
    public function __construct($name) {
        $this->nombre_completo = $name;
    }
}

// Simulating the collection and sorting logic
$students = collect([
    new Student("Ana Zapatero"),
    new Student("Bernardo Alvarez"),
    new Student("Carlos De la Fuente"),
    new Student("David Benitez"),
    new Student("Elena"), // Edge case: no surname
]);

echo "Original Order:\n";
foreach ($students as $s) {
    echo "- " . $s->nombre_completo . "\n";
}

$sorted = $students->sortBy(function ($alumno) {
    $parts = explode(' ', $alumno->nombre_completo, 2);
    return isset($parts[1]) ? $parts[1] : $parts[0];
}, SORT_NATURAL|SORT_FLAG_CASE);

echo "\nSorted Order (by substring after first space):\n";
foreach ($sorted as $s) {
    // Debug: show what is being used for sorting
    $parts = explode(' ', $s->nombre_completo, 2);
    $key = isset($parts[1]) ? $parts[1] : $parts[0];
    echo "- " . $s->nombre_completo . " (Sort Key: '$key')\n";
}

// Helper to mimic Laravel's collect
function collect($items) {
    return new Illuminate\Support\Collection($items);
}

// Minimal Mock of Illuminate\Support\Collection for standalone test
namespace Illuminate\Support;

class Collection implements \IteratorAggregate {
    protected $items = [];

    public function __construct($items = []) {
        $this->items = $items;
    }

    public function sortBy($callback, $options = SORT_REGULAR, $descending = false) {
        $results = [];
        foreach ($this->items as $key => $value) {
            $results[$key] = $callback($value, $key);
        }

        $descending ? arsort($results, $options) : asort($results, $options);

        $keys = array_keys($results);
        $newItems = [];
        foreach ($keys as $key) {
            $newItems[] = $this->items[$key];
        }
        return new self($newItems);
    }

    public function getIterator(): \Traversable {
        return new \ArrayIterator($this->items);
    }
}
