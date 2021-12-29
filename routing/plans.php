<?php
echo $GLOBALS['twig']->render('booking-register.twig', ['user'=>$GLOBALS[user],'basket'=>$GLOBALS[basket]]);
?>
