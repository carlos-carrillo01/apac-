<?php
// Redirección hacia la vista frontend correspondiente
$query = !empty($_SERVER['QUERY_STRING']) ? ('?' . $_SERVER['QUERY_STRING']) : '';
header('Location: index.html' . $query);
exit;
