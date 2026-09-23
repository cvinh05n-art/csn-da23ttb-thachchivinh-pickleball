<!DOCTYPE html>
<html lang="vi">
<head>
    <meta charset="UTF-8">
    <title>Pickleball Center</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
<?php
$isNestedPage = in_array(basename(dirname($_SERVER['SCRIPT_NAME'])), ['admin', 'chusan'], true);
$siteRoot = $isNestedPage ? '../' : '';
?>
<nav class="navbar navbar-expand-lg bg-light">
  <div class="container">
    <a class="navbar-brand" href="<?= $siteRoot ?>index.php">🏓 Pickleball</a>
  </div>
</nav>
<div class="container mt-4">
