<?php
    //trace info
  function trace ( $input ) {
    if ( !isset($input) || is_null($input) )
      echo "NULL".PHP_EOL;
    
    if ( is_array( $input) ) {
      foreach ( $input as $line ) {
        echo $line.PHP_EOL;
      }
    }
    else
      echo $input.PHP_EOL;
  }
?>