<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>MusicJourney</title>
    <link rel="stylesheet" href="../public/style/_normalize.css" type="text/css">
    <link rel="stylesheet" href="../public/style/global.css" type="text/css">
    <link rel="stylesheet" href="../public/style/header.css" type="text/css">
    <link href="https://fonts.googleapis.com/css2?family=Nunito:ital,wght@0,200;0,300;0,400;0,500;0,600;0,700;0,800;0,900;0,1000;1,200;1,300;1,400;1,500;1,600;1,700;1,800;1,900;1,1000&display=swap" rel="stylesheet">
    <?php foreach($cssFiles as $cssFile): ?>
    <link rel="stylesheet" href="<?= $cssFile; ?>" type="text/css">
    <?php endforeach ?>
    </head>
<body>

    <header>
        <?php require 'header.php'; ?>
    </header>

    <div id="content">
        <?php echo $content; ?>
    </div>

</body>
</html>