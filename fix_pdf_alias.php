<?php
$content = file_get_contents('app/Http/Controllers/ObePortalController.php');
$content = str_replace(
    '\\Barryvdh\\DomPDF\\Facade\\Pdf::loadView',
    '\\PDF::loadView',
    $content
);
file_put_contents('app/Http/Controllers/ObePortalController.php', $content);
echo "Fixed PDF alias.";
