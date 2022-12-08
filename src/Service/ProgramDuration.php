<?php

namespace App\Service;

use App\Entity\Program;

class ProgramDuration
{
    public function calculate(Program $program): string
    {
        $totalDuration = 0;
        $seasons = $program->getSeasons();
        foreach($seasons as $season){
            foreach($season->getEpisodes() as $episode){
                $totalDuration += $episode->getDuration();
            }      
        }
        $hour = floor($totalDuration / 60);
        $day = floor($hour / 24);
        $min = $totalDuration - ($hour * 60); 
        return $day . 'jour(s) ' . $hour . 'heure(s) ' . $min . 'minute(s)';
    }
}