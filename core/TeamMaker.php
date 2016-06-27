<?php
  require_once('Team.php');

  Class TeamMaker
  {
    /**
    * @access    public
    * @since     0.1.0
    * 
    * @param     Object $skills Player skill sets
    * 
    * @return    void
    */
    public function __construct($players)
    {
      $this->players = $players;
      $this->debt = 0;

      $this->pairPlayers();

      $this->bestTeam1 = new Team();
      $this->bestTeam2 = new Team();
      $this->bestDiff = 1000;

      $this->numTry = 0;
    }

    /**
    * @access    private
    * @since     0.1.0
    * 
    * @return    Pair the number of players
    */
    private function pairPlayers()
    {
      if(count($this->players) % 2 !== 0)
      {
        $emptyPlayer = new Player(
          'Empty',
          [
            'attack' => 0,
            'defence' => 0,
            'stamina' => 0,
            'teamSpirit' => 0
          ]
        );

        $this->players[] = $emptyPlayer;
      }
    }

    private function cmpPlayer($a, $b) 
    {
      if ($a->skills['attack'] == $b->skills['attack'])
      {
        return 0;
      }
      return ($a->skills['attack'] > $b->skills['attack']) ? -1 : 1;
    }

    /**
    * @access    public
    * @since     0.1.0
    * 
    * @return    Array The sum of all the Player's skills
    */
    public function makeTeams()
    {
      $team1 = new Team();
      $team2 = new Team();

      $this->numTry++;
      $playerLoop = $this->players;
      usort($playerLoop, array('TeamMaker', 'cmpPlayer'));

      // echo "</br></br>makeTeams";
      for( $i=0; $i<count($this->players)/2; $i++ )
      {
        $player = $playerLoop[0];
        // echo "</br>".$player->name;
        $playerLoop = $this->filterPlayers($playerLoop, $team1, $player);

        $opponent = $this->closestTo($playerLoop, $player);

        // echo "</br>".$player->name." VS ".$opponent->name;
        $playerLoop = $this->filterPlayers($playerLoop, $team2, $opponent);

        sort($playerLoop); // Temp: need to find out how to take the first element of an unsorted array
      }
      $currentDiff = $this->compareTeams($team1, $team2);
      if ( $currentDiff < $this->bestDiff )
      {
        $this->bestDiff = $currentDiff;
        $this->bestTeam1 = $team1;
        $this->bestTeam2 = $team2;
      }
      if ( $this->numTry > 200 )
      {
        // echo "STOP!!!";
        return [$this->bestTeam1, $this->bestTeam2];
      }
      else
        return ( $currentDiff < 5 ) ? [$team1, $team2] : $this->makeTeams();
    }

    private function compareTeams($team1, $team2)
    {
      $t1strength = array_reduce( $team1->level(), function($attack, $defence) { return $attack + $defence; } );

      $t2strength = array_reduce( $team2->level(), function($attack, $defence) { return $attack + $defence; } );
      // echo "t1strength: ".$t1strength;
      // echo " t2strength: ".$t2strength."  DIFF ".($t1strength-$t2strength)."</br>";
      return abs($t1strength - $t2strength);
    }

    /**
    * @access    private
    * @since     0.1.0
    * 
    * @param     Array $players The collection of all the players
    * @param     Team The team wo add the player to
    * @param     Player The player you want to add the to team
    * @return    Array The collection of the players, without the given one
    */
    private function filterPlayers($players, $team, $player)
    {
      $team->add($player);
      return $this->arrayRemoveObject($players, $player->name, 'name');
    }

    /**
    * @access    private
    * @since     0.1.0
    * 
    * @param     Player $player The player to find
    * @return    Player The closest player to the given one
    */
    private function closestTo($players, $player)
    {
      $playerLevel = $player->level() + $this->debt;
      $diff = 0;

      while (true)
      {
        foreach ($players as $opponent)
        {
          $opponentLevel = $opponent->level();

          if($playerLevel >= $opponentLevel - $diff && $playerLevel <= $opponentLevel + $diff)
          {
            $this->debt = $playerLevel - $opponentLevel;
            return $opponent;
          }
        }

        $diff++;
      }
    }

    /**
    * @access    private
    * @since     0.1.0
    * 
    * @param Array $array
    * @param mixed $value
    * @param string $prop
    * 
    * @return Array
    */
    private function arrayRemoveObject(&$array, $value, $prop)
    {
      return array_filter( $array, function($a) use($value, $prop) { return $a->$prop !== $value; } );
    }
  }
?>