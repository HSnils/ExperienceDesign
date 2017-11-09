<?php 
	require_once('partials/phphead.php');
	require_once('partials/header.php');
 ?>

    <div id="main">
        <a href="https://placeholder.com"><img src="http://via.placeholder.com/600x300"></a>
    </div>
    
   <div class="details">
       <div class="details-element"><p>Building</p></div>
       <div class="details-element"><p>Room</p></div>
       <div class="details-element"><p>Equipment</p></div>
       <div class="details-element"><p>Seats</p></div>
   </div>
   
  <div class="booking">
      <!-- php for loop here -->
      <div class="booking-element">
          <p>username</p>
          <p class="date">10:00 - 12:00</p>
      </div>
      <div class="booking-element">
          <p>username</p>
          <p class="date">12:00 - 14:00</p>
      </div>
      <!-- if room availible - button clickable -->
      <a href="" class="btn">Book now</a>
  </div>