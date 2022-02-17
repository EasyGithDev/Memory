<?php

require_once __DIR__ . '/Connection.php';
require_once __DIR__ . '/Config.php';

class Game
{
    const NUMBER_OF_LINES = 4;
    const NUMBER_OF_COLUMNS = 7;
    const TIME_LIMIT = 5;

    protected $images = [
        'pommer', 'banane', 'orange', 'citronv',
        'grenade', 'pecheb', 'citronj', 'fraise',
        'pommev', 'pechej', 'raisin', 'pasteque',
        'figue', 'poire'
    ];

    protected $pdo = null;
    protected $config = null;

    /**
     * Constructeur de la classe
     *
     * @param void
     * @return void
     */
    public function __construct()
    {
        $this->pdo = Connection::getInstance()->getPdo();
        $this->config = Config::getInstance()->get('app');
    }

    /**
     * Création de la session et initialisation du plateau de jeu
     * @param void 
     * @return array $session_id le numéro de la session et la configuration
     */
    public function start(): array
    {
        // Fabrication du tableau d'images
        $images = $this->config->images;
        $images = array_merge($images, $images);
        shuffle($images);

        // Création de la session de jeu
        $query = 'INSERT INTO session (start_at) VALUES(?)';
        $this->pdo->prepare($query)->execute([date('Y-m-d H:i:s')]);
        $session_id = $this->pdo->lastInsertId();

        // Création du plateau de jeu
        $query = 'INSERT INTO position (session_id, pline, pcolumn, image) VALUES';
        for ($i = 0; $i < $this->config->number_of_lines; $i++) {
            for ($j = 0; $j < $this->config->number_of_columns; $j++) {
                $image = $images[$i * $this->config->number_of_columns + $j];
                $query .= '(' . $session_id . ',' . ($i + 1) . ',' . ($j + 1) . ',"' . $image . '"),';
            }
        }

        $query = rtrim($query, ',');
        $this->pdo->exec($query);

        return ['session_id' => $session_id, 'config' => $this->config];
    }

    /**
     * Le joueur demande l'image située à la ligne i et la colonne j pour une session donnée 
     * @param int $session_id la session de jeu
     * @param int $pline le numéro de la ligne
     * @param int $pcolumn le nnuméro de la colonne
     * @return array action, action précédente, paire trouvée, coup gagnant
     */
    public function play(int $session_id, int $pline, int $pcolumn): array
    {
        $find = false;
        $win = false;

        $query = 'SELECT id, image, pline, pcolumn FROM position WHERE session_id = ? AND previous = 1';
        $stm = $this->pdo->prepare($query);
        $stm->execute([$session_id]);
        $previous = $stm->fetch();

        $query = 'SELECT * FROM position WHERE session_id = ? AND pline = ? AND pcolumn = ?';
        $stm = $this->pdo->prepare($query);
        $stm->execute([$session_id, $pline, $pcolumn]);
        $position = $stm->fetch();

        // Si il n'y a pas de précédente image découverte
        if (!$previous) {
            $query = 'UPDATE position SET previous = 1 WHERE id = ?';
            $this->pdo->prepare($query)->execute([$position->id]);
        } else {

            // Si l'image précédente est identique à l'image courrante
            if (($previous->image == $position->image) && ($position->id != $previous->id)) {
                $query = 'UPDATE position SET find = 1 WHERE id IN (' . implode(',', [$previous->id, $position->id]) . ')';
                $this->pdo->prepare($query)->execute();
                $find = true;

                // Comptage des résultats
                $query = 'SELECT count(*) / 2 as nb FROM position WHERE session_id = ? AND find = 1';
                $stm = $this->pdo->prepare($query);
                $stm->execute([$session_id]);
                $finded = $stm->fetch();

                // Si toutes les pairs d'images sont trouvées
                if ($finded->nb == count($this->images)) {
                    $this->end($session_id, 1);
                    $win = true;
                }
            }

            // Suppression de la précédente position
            $query = 'UPDATE position SET previous = 0 WHERE id = ?';
            $this->pdo->prepare($query)->execute([$previous->id]);
        }

        return ['position' => $position, 'previous' => $previous, 'find' => $find,  'win' => $win];
    }

    /**
     * La session de jeu se termine par la suppresion du plateau de jeu.
     * @param int $session_id la session de jeu
     * @return void
     */
    public function end(int $session_id, int $sucess = 0): void
    {
        // Mise à jour de la session de jeu
        $query = 'UPDATE session SET end_at = ?, success = ? WHERE id = ?';
        $this->pdo->prepare($query)->execute([date('Y-m-d H:i:s'), $sucess, $session_id]);

        // Suppression du plateau de jeu
        $query = 'DELETE FROM position WHERE session_id = ?';
        $this->pdo->prepare($query)->execute([$session_id]);
    }

    /**
     * Retourne la liste des sessions
     * @return array la liste des sessions
     */
    public function list(): array
    {
        $query = 'SELECT * FROM session ORDER BY start_at desc LIMIT 0,5';
        $stm = $this->pdo->prepare($query);
        $stm->execute();
        $sessions = $stm->fetchAll();

        return $sessions;
    }

    /**
     * @return boolean vrai si la session est valide, faux sinon
     */
    public function checkSession(int $session_id): bool
    {
        $query = 'SELECT id, (DATE_ADD(start_at, INTERVAL "' . $this->config->time_limit . ':0" MINUTE_SECOND)  > now()) as finished
                    FROM session 
                    WHERE id = ? AND success IS NULL';
        $stm = $this->pdo->prepare($query);
        $stm->execute([$session_id]);
        $session = $stm->fetch();

        if (!$session) {
            return false;
        }

        if ($session->finished) {
            return false;
        }

        return true;
    }

    /**
     * @return boolean vrai si les numéros de ligne et colonne sont valides, faux sinon
     */
    public function checkPosition(int $pline, int $pcolumn): bool
    {
        return ($pline >= 1 &&
            $pline <= $this->config->number_of_lines &&
            $pcolumn >= 1 &&
            $pcolumn <= $this->config->number_of_columns);
    }
}
