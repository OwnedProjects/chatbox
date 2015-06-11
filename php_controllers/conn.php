<?php
$con= mysql_connect('localhost','root','')
        or die('<h3>We are extremely sorry our server is facing some issue. We will try to resolve it shortly.</h3>');
$db=  mysql_select_db('knowled1_portfolio_chatbox',$con)
       or die('<h4>We are extremely sorry our server is facing some issue. We will try to resolve it shortly</h4>'. mysql_error());
?>
