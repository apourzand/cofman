<?php

namespace AppBundle\Utils;

class Utilities
{
    public function dbg($var, $die = true)
    {
      echo '<pre>' . print_r($var, true) . '<pre>';
      if ( $die )
      {
        die();
      }
    }
}