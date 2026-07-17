<?php
$backup = file_get_contents('resources/views/obe/index_backup.blade.php');
$new = file_get_contents('resources/views/obe/index.blade.php');

$parts = explode('<!-- MODAL: UPLOAD BAAK -->', $backup);
$modals = '';
if(isset($parts[1])) {
    $modals = explode('@endsection', $parts[1])[0];
}

$sparts = explode('@section(\'scripts\')', $backup);
$scripts = '';
if(isset($sparts[1])) {
    $scripts = explode('@endsection', $sparts[1])[0];
}

$new = preg_replace('/<\?php\s*\$modals.*?@endsection/s', '', $new);

$new .= "\n<!-- MODAL: UPLOAD BAAK -->\n" . $modals . "\n@section('scripts')\n" . $scripts . "\n@endsection\n";

file_put_contents('resources/views/obe/index.blade.php', $new);
echo "Fixed.";
