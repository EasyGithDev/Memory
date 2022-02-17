<?php

require_once __DIR__ . '/../classes/Game.php';

header('Content-Type: application/json; charset=utf-8');

// Réception de l'action
$action = filter_input(INPUT_GET, 'action', FILTER_SANITIZE_SPECIAL_CHARS);

$game = new Game();

switch ($action) {

        // Initialisation du jeu
        // ?action=start
    case 'list':
        echo json_encode($game->list());
        break;

        // Initialisation du jeu
        // ?action=start
    case 'start':
        echo json_encode($game->start());
        break;

        // Obtention d'une image et vérification d'une paire    
        // ?action=play&session_id=1&pline=1&pcolumn=1
    case 'play':

        $session_id = filter_input(INPUT_GET, 'session_id', FILTER_VALIDATE_INT);
        $pline = filter_input(INPUT_GET, 'pline', FILTER_VALIDATE_INT);
        $pcolumn = filter_input(INPUT_GET, 'pcolumn', FILTER_VALIDATE_INT);

        if (!$session_id || !$pline || !$pcolumn) {
            echo json_encode(['error' => 'invalid input']);
        } else if (!$game->checkSession($session_id)) {
            echo json_encode(['error' => 'invalid session']);
        } else if (!$game->checkPosition($pline, $pcolumn)) {
            echo json_encode(['error' => 'invalid position']);
        } else {
            $result = $game->play($session_id, $pline, $pcolumn);
            echo json_encode(['session_id' => $session_id, 'result' => $result]);
        }

        break;

        // Fin du jeu
        // ?action=end&session_id=1
    case 'end':
        $session_id = filter_input(INPUT_GET, 'session_id', FILTER_VALIDATE_INT);
        if (!$session_id) {
            echo json_encode(['error' => 'invalid input']);
        } else if (!$game->checkSession($session_id)) {
            echo json_encode(['error' => 'invalid session']);
        } else {
            $game->end($session_id);
            echo json_encode(['session_id' => 0]);
        }
        break;

        // Erreur
    default:
        header('HTTP/1.0 403 Forbidden', true, 403);
        break;
}
