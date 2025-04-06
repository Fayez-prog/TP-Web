<!DOCTYPE html>
<html lang="<?php echo isset($_GET['lang']) ? htmlspecialchars($_GET['lang']) : 'ar'; ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Application Multilingue</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 800px;
            margin: 0 auto;
            padding: 20px;
            line-height: 1.6;
        }
        .language-switcher {
            margin-bottom: 20px;
            padding: 10px;
            background: #f5f5f5;
            border-radius: 5px;
            text-align: center;
        }
        .language-switcher a {
            margin: 0 10px;
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 5px 10px;
            border-radius: 3px;
            transition: all 0.3s ease;
        }
        .language-switcher a:hover, .language-switcher a.active {
            background: #4CAF50;
            color: white;
        }
        .greeting {
            font-size: 24px;
            text-align: center;
            margin: 30px 0;
            padding: 20px;
            background: #f9f9f9;
            border-radius: 5px;
        }
        .direction-rtl {
            direction: rtl;
        }
    </style>
</head>
<body>

<?php
// Définition de la langue (avec sécurisation)
$langue = isset($_GET['lang']) && in_array($_GET['lang'], ['ar', 'fr', 'en', 'de']) 
    ? $_GET['lang'] 
    : 'ar';

// Ajout de la classe RTL si la langue est l'arabe
$bodyClass = ($langue === 'ar') ? 'class="direction-rtl"' : '';

// Tableau des traductions
$translations = [
    'ar' => [
        'greeting' => 'صباح الخير',
        'title' => 'تطبيق متعدد اللغات'
    ],
    'fr' => [
        'greeting' => 'Bonjour',
        'title' => 'Application Multilingue'
    ],
    'en' => [
        'greeting' => 'Good morning',
        'title' => 'Multilingual Application'
    ],
    'de' => [
        'greeting' => 'Guten Morgen',
        'title' => 'Mehrsprachige Anwendung'
    ]
];
?>

<body <?php echo $bodyClass; ?>>

<div class="language-switcher">
    <a href="multilangue.php?lang=ar" <?php echo ($langue === 'ar') ? 'class="active"' : ''; ?>>العربية (AR)</a>
    <a href="multilangue.php?lang=fr" <?php echo ($langue === 'fr') ? 'class="active"' : ''; ?>>Français (FR)</a>
    <a href="multilangue.php?lang=en" <?php echo ($langue === 'en') ? 'class="active"' : ''; ?>>English (EN)</a>
    <a href="multilangue.php?lang=de" <?php echo ($langue === 'de') ? 'class="active"' : ''; ?>>Deutsch (DE)</a>
</div>

<div class="greeting">
    <?php echo htmlspecialchars($translations[$langue]['greeting']); ?>
</div>

</body>
</html>