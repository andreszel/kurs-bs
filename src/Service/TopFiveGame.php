<?php

namespace App\Service;

class TopFiveGame
{
    private array $games;
    public function __construct() {
        $this->games = [
            'The Legend of Zelda',
            'Metroid Prime Remastered',
            'The Witcher 3',
            'Tetris Effect',
            'Resident Evil 4',
            'The Case of the Golden Idol',
            'Turbo Overkill',
            'Fifa 23',
            'GTA V',
            'City Racing'
        ];
    }

    public function random(int $num = 5): array
    {
        $keys = array_rand($this->games, $num);
        $newGames = [];
        foreach($keys as $key) {
            $newGames[] = $this->games[$key];
        }

        return $newGames;
    }
}