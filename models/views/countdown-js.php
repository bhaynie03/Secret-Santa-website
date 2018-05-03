<?php
?>
<script>
var countDownDate = new Date("<?php echo $date?>").getTime();
var x = setInterval(function() {
  var now = new Date().getTime();
  var distance = countDownDate - now;

  var months = Math.floor(distance / (1000 *60 * 60 * 24 * 30));
  var days = Math.floor((distance % (1000 * 60 * 60 * 24 * 30)) / (1000 * 60 * 60 *24));
  var hours = Math.floor((distance % (1000 * 60 * 60 * 24)) / (1000 * 60 * 60));
  var minutes = Math.floor((distance % (1000 * 60 * 60)) / (1000 * 60));
  var seconds = Math.floor((distance % (1000 * 60)) / 1000);


  var elements = document.getElementsByClassName("countdown");

	for (var i = 0; i < elements.length; i++){
	  elements[i].innerHTML = months + " months " + days + " days " + hours + ":"
	  + minutes + ":" + seconds + "";
	}

  if (distance < 0) {
    clearInterval(x);
    for (var i = 0; i <elements.length; i++){
    elements[i].innerHTML = "EXPIRED";
	}
  }
}, 1000);
</script>
<?php
?>