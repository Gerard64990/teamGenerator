<?php
  Class Player
  {
    /**
    * @access    public
    * @since     0.1.0
    * 
    * @param     Object $skills Player skill sets //To be updated
    * 
    * @return    void
    */
    public function __construct($name, $skills, $att, $def, $sta, $tsp, $role='player', $foot='right')
    {
      $this->name = $name;
      $this->skills = $skills;
      $this->att = $att;
      $this->def = $def;
      $this->sta = $sta;
      $this->tsp = $tsp;
      $this->role = $role;
      $this->foot = $foot;
    }

    /**
    * @access    public
    * @since     0.1.0
    * 
    * @return    Array The sum of all the Player's skills
    */
    public function level()
    {
      return variant_int($this->skills);
    }
  }
?>