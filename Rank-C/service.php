<?php
  function run($query,$top) {
    exec("Rank-C/querydb ".$query." ".$top,$a);
      return $a;
  }
 ?>
