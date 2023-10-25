<?php

namespace App\Service;

class RandomGame
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
        if(!$num > 0) return [];

        //$maxNum = count($this->games);
        $maxNum = 8;

        if(!$maxNum > 0) return [];

        if($num > $maxNum) {
            $num = $maxNum;
        }

        $keys = array_rand($this->games, $num);

        $newGames = [];
        if(is_array($keys)) {
            foreach($keys as $key) {
                $newGames[] = $this->games[$key];
            }
        }else{
            if(is_int($keys) && $keys >= 0) {
                $newGames[] = $this->games[$keys];
            }
        }

        shuffle($newGames);

        return $newGames;
    }
}