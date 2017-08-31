<?php
  require_once('Player.php');

  Class Team
  {
    /**
    * @access    public
    * @since     0.1.0
    * 
    * @param     Object $skills Player skill sets
    * 
    * @return    void
    */
    public function __construct($players=[])
    {
      $this->players = $players;
    }

    /**
    * @access    public
    * @since     0.1.0
    * 
    * @return    Player The player object
    */
    public function add($player)
    {
      array_push($this->players, $player);
      return $player;
    }

    /**
    * @access    public
    * @since     0.1.0
    * 
    * @return    Array The sum of all the Player's skills
    */
    public function level()
    {
      return $this->skill();
    }
    public function levelAtt()
    {
      return array_reduce( array_map( function($player) { return $player->att; }, $this->players ), function($prevAttack, $currAttack) { return $prevAttack + $currAttack; } );
    }
    public function levelDef()
    {
      return array_reduce( array_map( function($player) { return $player->def; }, $this->players ), function($prevAttack, $currAttack) { return $prevAttack + $currAttack; } );
    }
    public function levelSta()
    {
      return array_reduce( array_map( function($player) { return $player->sta; }, $this->players ), function($prevAttack, $currAttack) { return $prevAttack + $currAttack; } );
    }
    public function levelTsp()
    {
      return array_reduce( array_map( function($player) { return $player->tsp; }, $this->players ), function($prevAttack, $currAttack) { return $prevAttack + $currAttack; } );
    }

    /**
    * @access public
    * @since 0.1.0
    * 
    * @return Array The attack of all the players
    */
    public function skill()
    {
      return array_reduce( $this->playersSkill(), function($prevAttack, $currAttack) { return $prevAttack + $currAttack; } );
    }

    /**
    * @access private
    * @since 0.1.0
    * 
    * @return Array The attack of all the players
    */
    private function playersSkill()
    {
      return array_map( function($player) { return $player->level(); }, $this->players );
    }
  }
?>