<?php
$lines = file('storage/logs/laravel.log');
$errors = [];
$currentError = "";
foreach ($lines as $line) {
    if (strpos($line, 'local.ERROR:') !== false) {
        if ($currentError !== "") {
            $errors[] = $currentError;
        }
        $currentError = $line;
    } elseif (strpos($line, '#') !== false || strpos($line, '[stacktrace]') !== false || strpos($line, 'Stack trace') !== false) {
        $currentError .= $line;
    }
}
if ($currentError !== "") {
    $errors[] = $currentError;
}

$validErrors = array_filter($errors, function($e) {
    return strpos($e, 'Psy\\') === false && strpos($e, 'Psy/') === false;
});

file_put_contents('last_error.txt', end($validErrors));
