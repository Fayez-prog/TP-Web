<?php
session_start();

// Configuration des constantes
define('HORAIRES', ['08h-09h', '09h-10h', '10h-11h', '11h-12h', '12h-13h', '13h-14h', '14h-15h', '15h-16h', '16h-17h']);
define('JOURS', ['Lundi', 'Mardi', 'Mercredi', 'Jeudi', 'Vendredi', 'Samedi']);

// Initialisation du tableau des matières
if (!isset($_SESSION['tab_mat'])) {
    $_SESSION['tab_mat'] = [];
}

// Gestion des actions
if (isset($_GET['action'])) {
    if ($_GET['action'] === 'delmat' && isset($_GET['jour'], $_GET['heure'])) {
        unset($_SESSION['tab_mat'][$_GET['jour']][$_GET['heure']]);
    } elseif ($_GET['action'] === 'videremploi') {
        $_SESSION['tab_mat'] = [];
    }
    header("Location: ".$_SERVER['PHP_SELF']);
    exit();
}

// Traitement du formulaire
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['submit'])) {
    $jour = htmlspecialchars(trim($_POST['jour'] ?? ''), ENT_QUOTES, 'UTF-8');
    $heure = htmlspecialchars(trim($_POST['heure'] ?? ''), ENT_QUOTES, 'UTF-8');
    $matiere = htmlspecialchars(trim($_POST['matiere'] ?? ''), ENT_QUOTES, 'UTF-8');
    
    if (in_array($jour, JOURS) && in_array($heure, HORAIRES) && !empty($matiere)) {
        $_SESSION['tab_mat'][$jour][$heure] = $matiere;
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Emploi du Temps Universitaire</title>
    <style>
        :root {
            --primary: #4361ee;
            --secondary: #3f37c9;
            --light: #f8f9fa;
            --dark: #212529;
            --success: #4cc9f0;
            --danger: #f72585;
            --border-radius: 8px;
            --shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            line-height: 1.6;
            background-color: #f1f5f9;
            color: var(--dark);
            padding: 20px;
        }
        
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: var(--border-radius);
            box-shadow: var(--shadow);
            overflow: hidden;
        }
        
        header {
            background: linear-gradient(135deg, var(--primary), var(--secondary));
            color: white;
            padding: 1.5rem;
            text-align: center;
        }
        
        h1 {
            font-weight: 500;
            margin-bottom: 0.5rem;
        }
        
        .form-container {
            padding: 1.5rem;
            background-color: var(--light);
            border-bottom: 1px solid #e9ecef;
        }
        
        .form-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 1rem;
            margin-bottom: 1rem;
        }
        
        .form-group {
            margin-bottom: 1rem;
        }
        
        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: var(--dark);
        }
        
        select, input {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ced4da;
            border-radius: var(--border-radius);
            font-size: 1rem;
            transition: border-color 0.3s;
        }
        
        select:focus, input:focus {
            outline: none;
            border-color: var(--primary);
            box-shadow: 0 0 0 3px rgba(67, 97, 238, 0.2);
        }
        
        .btn-group {
            display: flex;
            gap: 1rem;
            flex-wrap: wrap;
        }
        
        .btn {
            padding: 0.75rem 1.5rem;
            border: none;
            border-radius: var(--border-radius);
            cursor: pointer;
            font-weight: 500;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            transition: all 0.3s;
        }
        
        .btn-primary {
            background-color: var(--primary);
            color: white;
        }
        
        .btn-primary:hover {
            background-color: #3a56d4;
            transform: translateY(-2px);
        }
        
        .btn-success {
            background-color: var(--success);
            color: white;
        }
        
        .btn-success:hover {
            background-color: #3bb1d8;
            transform: translateY(-2px);
        }
        
        .btn-danger {
            background-color: var(--danger);
            color: white;
        }
        
        .btn-danger:hover {
            background-color: #e5177b;
            transform: translateY(-2px);
        }
        
        .schedule-container {
            padding: 1.5rem;
            overflow-x: auto;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 0 auto;
            min-width: 800px;
        }
        
        th, td {
            border: 1px solid #dee2e6;
            padding: 1rem;
            text-align: center;
        }
        
        th {
            background-color: #e9ecef;
            font-weight: 500;
            position: sticky;
            top: 0;
        }
        
        tr:nth-child(even) {
            background-color: #f8f9fa;
        }
        
        tr:hover {
            background-color: #e9ecef;
        }
        
        .course {
            position: relative;
            padding: 8px;
            border-radius: 4px;
            background-color: #e3f2fd;
            color: #1976d2;
            font-weight: 500;
        }
        
        .delete-btn {
            position: absolute;
            top: -8px;
            right: -8px;
            width: 20px;
            height: 20px;
            background-color: var(--danger);
            color: white;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            font-size: 12px;
            opacity: 0;
            transition: opacity 0.3s;
        }
        
        td:hover .delete-btn {
            opacity: 1;
        }
        
        .empty-cell {
            color: #adb5bd;
        }
        
        /* Styles des icônes */
        .icon {
            display: inline-block;
            width: 1em;
            height: 1em;
            margin-right: 0.5em;
            background-size: contain;
            background-repeat: no-repeat;
            background-position: center;
            vertical-align: middle;
        }
        .icon-calendar { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='%234361ee' d='M148 288h-40c-6.6 0-12-5.4-12-12v-40c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v40c0 6.6-5.4 12-12 12zm108-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 96v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm-96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96 0v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-12v-40c0-6.6-5.4-12-12-12h-40c-6.6 0-12 5.4-12 12v40c0 6.6 5.4 12 12 12h40c6.6 0 12-5.4 12-12zm96-260v352c0 26.5-21.5 48-48 48H48c-26.5 0-48-21.5-48-48V112c0-26.5 21.5-48 48-48h48V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h128V12c0-6.6 5.4-12 12-12h40c6.6 0 12 5.4 12 12v52h48c26.5 0 48 21.5 48 48zm-48 346V160H48v298c0 3.3 2.7 6 6 6h340c3.3 0 6-2.7 6-6z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-clock { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%234361ee' d='M256 8C119 8 8 119 8 256s111 248 248 248 248-111 248-248S393 8 256 8zm0 448c-110.5 0-200-89.5-200-200S145.5 56 256 56s200 89.5 200 200-89.5 200-200 200zm61.8-104.4l-84.9-61.7c-3.1-2.3-4.9-5.9-4.9-9.7V116c0-6.6 5.4-12 12-12h32c6.6 0 12 5.4 12 12v141.7l66.8 48.6c5.4 3.9 6.5 11.4 2.6 16.8L334.6 349c-3.9 5.3-11.4 6.5-16.8 2.6z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-book { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='%234361ee' d='M448 360V24c0-13.3-10.7-24-24-24H96C43 0 0 43 0 96v320c0 53 43 96 96 96h328c13.3 0 24-10.7 24-24v-16c0-7.5-3.5-14.3-8.9-18.7-4.2-15.4-4.2-59.3 0-74.7 5.4-4.3 8.9-11.1 8.9-18.6zM128 134c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm0 64c0-3.3 2.7-6 6-6h212c3.3 0 6 2.7 6 6v20c0 3.3-2.7 6-6 6H134c-3.3 0-6-2.7-6-6v-20zm253.4 250H96c-17.7 0-32-14.3-32-32 0-17.6 14.4-32 32-32h285.4c-1.9 17.1-1.9 46.9 0 64z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-plus { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='%23ffffff' d='M416 208H272V64c0-17.67-14.33-32-32-32h-32c-17.67 0-32 14.33-32 32v144H32c-17.67 0-32 14.33-32 32v32c0 17.67 14.33 32 32 32h144v144c0 17.67 14.33 32 32 32h32c17.67 0 32-14.33 32-32V304h144c17.67 0 32-14.33 32-32v-32c0-17.67-14.33-32-32-32z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-print { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 512 512'%3E%3Cpath fill='%23ffffff' d='M448 192H64C28.65 192 0 220.7 0 256v96c0 17.67 14.33 32 32 32h32v96c0 17.67 14.33 32 32 32h320c17.67 0 32-14.33 32-32v-96h32c17.67 0 32-14.33 32-32v-96C512 220.7 483.3 192 448 192zM384 448H128v-96h256V448zM96 256c-17.67 0-32-14.33-32-32s14.33-32 32-32s32 14.33 32 32S113.7 256 96 256zM432 96H80C71.16 96 64 88.84 64 80v-32C64 39.16 71.16 32 80 32h352c8.836 0 16 7.164 16 16v32C448 88.84 440.8 96 432 96z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-trash { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 448 512'%3E%3Cpath fill='%23ffffff' d='M432 32H312l-9.4-18.7A24 24 0 0 0 281.1 0H166.8a23.72 23.72 0 0 0-21.4 13.3L136 32H16A16 16 0 0 0 0 48v32a16 16 0 0 0 16 16h416a16 16 0 0 0 16-16V48a16 16 0 0 0-16-16zM53.2 467a48 48 0 0 0 47.9 45h245.8a48 48 0 0 0 47.9-45L416 128H32z'%3E%3C/path%3E%3C/svg%3E"); }
        .icon-times { background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 352 512'%3E%3Cpath fill='%23ffffff' d='M242.72 256l100.07-100.07c12.28-12.28 12.28-32.19 0-44.48l-22.24-22.24c-12.28-12.28-32.19-12.28-44.48 0L176 189.28 75.93 89.21c-12.28-12.28-32.19-12.28-44.48 0L9.21 111.45c-12.28 12.28-12.28 32.19 0 44.48L109.28 256 9.21 356.07c-12.28 12.28-12.28 32.19 0 44.48l22.24 22.24c12.28 12.28 32.2 12.28 44.48 0L176 322.72l100.07 100.07c12.28 12.28 32.2 12.28 44.48 0l22.24-22.24c12.28-12.28 12.28-32.19 0-44.48L242.72 256z'%3E%3C/path%3E%3C/svg%3E"); }

        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            
            .btn-group {
                flex-direction: column;
            }
            
            .btn {
                width: 100%;
            }
        }
        
        @media print {
            body {
                padding: 0;
                font-size: 10pt;
                background: white;
            }
            
            .container {
                box-shadow: none;
            }
            
            .form-container, .delete-btn {
                display: none;
            }
            
            table {
                min-width: 100%;
            }
            
            th, td {
                padding: 0.5rem;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Emploi du Temps Universitaire</h1>
            <p>Année <?= date('Y') ?>-<?= date('Y')+1 ?></p>
        </header>
        
        <section class="form-container">
            <form method="post">
                <div class="form-grid">
                    <div class="form-group">
                        <label for="jour"><span class="icon icon-calendar"></span> Jour</label>
                        <select name="jour" id="jour" required>
                            <option value="">Sélectionnez un jour</option>
                            <?php foreach (JOURS as $jour): ?>
                                <option value="<?= htmlspecialchars($jour, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($jour, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="heure"><span class="icon icon-clock"></span> Créneau horaire</label>
                        <select name="heure" id="heure" required>
                            <option value="">Sélectionnez un créneau</option>
                            <?php foreach (HORAIRES as $heure): ?>
                                <option value="<?= htmlspecialchars($heure, ENT_QUOTES, 'UTF-8') ?>">
                                    <?= htmlspecialchars($heure, ENT_QUOTES, 'UTF-8') ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div class="form-group">
                        <label for="matiere"><span class="icon icon-book"></span> Matière</label>
                        <input type="text" name="matiere" id="matiere" required placeholder="Nom de la matière">
                    </div>
                </div>
                
                <div class="btn-group">
                    <button type="submit" name="submit" class="btn btn-primary">
                        <span class="icon icon-plus"></span> Ajouter
                    </button>
                    <button type="button" onclick="window.print()" class="btn btn-success">
                        <span class="icon icon-print"></span> Imprimer
                    </button>
                    <button type="button" onclick="if(confirm('Vider tout l\\\'emploi du temps ?')) location.href='?action=videremploi'" class="btn btn-danger">
                        <span class="icon icon-trash"></span> Réinitialiser
                    </button>
                </div>
            </form>
        </section>
        
        <section class="schedule-container">
            <table>
                <thead>
                    <tr>
                        <th>Jour / Heure</th>
                        <?php foreach (HORAIRES as $heure): ?>
                            <th><?= htmlspecialchars($heure, ENT_QUOTES, 'UTF-8') ?></th>
                        <?php endforeach; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach (JOURS as $jour): ?>
                        <tr>
                            <th><?= htmlspecialchars($jour, ENT_QUOTES, 'UTF-8') ?></th>
                            <?php foreach (HORAIRES as $heure): ?>
                                <td>
                                    <?php if (!empty($_SESSION['tab_mat'][$jour][$heure])): ?>
                                        <div class="course">
                                            <?= htmlspecialchars($_SESSION['tab_mat'][$jour][$heure], ENT_QUOTES, 'UTF-8') ?>
                                            <span class="delete-btn" 
                                                  onclick="if(confirm('Supprimer ce cours ?')) location.href='?action=delmat&jour=<?= urlencode($jour) ?>&heure=<?= urlencode($heure) ?>'">
                                                <span class="icon icon-times"></span>
                                            </span>
                                        </div>
                                    <?php else: ?>
                                        <span class="empty-cell">-</span>
                                    <?php endif; ?>
                                </td>
                            <?php endforeach; ?>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </section>
    </div>

    <script>
        // Focus sur le premier champ au chargement
        document.addEventListener('DOMContentLoaded', function() {
            document.getElementById('jour').focus();
        });
    </script>
</body>
</html>