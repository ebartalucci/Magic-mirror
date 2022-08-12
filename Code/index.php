<!doctype html>
<html lang="it">
<head>
	
 <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<title>Magic Mirror</title>
	<meta name="description" content="Magic Mirror">
	<meta http-equiv="refresh" content="900" /> 
	<link rel="stylesheet" href="style.css">
	<link href='http://fonts.googleapis.com/css?family=Roboto' rel='stylesheet' type='text/css'>
		<script language="JavaScript"> 
			setInterval(function() { 
				var currentTime = new Date ( );
				var currentHours = currentTime.getHours ( );   
				var currentMinutes = currentTime.getMinutes ( );
				var currentMinutesleadingzero = currentMinutes > 9 ? currentMinutes : '0' + currentMinutes; 
				var currentDate = currentTime.getDate ( );
	
					var weekday = new Array(7);
					weekday[0] = "Domenica";
					weekday[1] = "Luned" + "&igrave;";
					weekday[2] = "Marted" + "&igrave;";
					weekday[3] = "Mercoled" + "&igrave;";
					weekday[4] = "Gioved" + "&igrave;";
					weekday[5] = "Venerd" + "&igrave;";
					weekday[6] = "Sabato";
				var currentDay = weekday[currentTime.getDay()]; 
	
					var actualmonth = new Array(12);
					actualmonth[0] = "Gennaio";
					actualmonth[1] = "Febbraio";
					actualmonth[2] = "Marzo";
					actualmonth[3] = "Aprile";
					actualmonth[4] = "Maggio";
					actualmonth[5] = "Giugno";
					actualmonth[6] = "Luglio";
					actualmonth[7] = "Agosto";
					actualmonth[8] = "Settembre";
					actualmonth[9] = "Ottobre";
					actualmonth[10] = "Novembre";
					actualmonth[11] = "Dicembre";
				var currentMonth = actualmonth[currentTime.getMonth ()];

    var currentTimeString = "<h1>" + currentHours + ":" + currentMinutesleadingzero + "</h1><h2>" + currentDay + " " + currentDate + " " + currentMonth + "</h2>";
    document.getElementById("clock").innerHTML = currentTimeString;
}, 1000);
	</script>
</head>
<body>

<div id="main">
	<div id="left">
		<div id="clock"></div>
 	
		<h3>
		<?php 
			$now = date('H');      	
				if (($now >= 06) and ($now < 10)) echo 'Il mattino ha l\'oro in bocca!';
				else if (($now >= 10) and ($now < 12)) echo 'Buona giornata!';
				else if (($now >= 12) and ($now < 14)) echo 'Ora di pranzo!';
				else if (($now >= 14) and ($now < 17)) echo 'Ora di uno spuntino!';
				else if (($now >= 17) and ($now < 20)) echo 'Hai pensato alla cena?';
				else if (($now >= 20) and ($now < 22)) echo 'Buona serata!';
				else if (($now >= 22) and ($now < 23)) echo 'Buonanotte!';
				else if (($now >= 23) and ($now < 24)) echo 'Sogni d\'oro...';
			?>
		</h3>
	    
	</div>

<canvas id="canvas" width="200" height="200"></canvas>
    
	<div id="right">
		<h2>Le notizie del giorno</h2>
		<br><br>
		<?php 
			$rss = new DOMDocument();
			$rss->load('http://www.repubblica.it/rss/homepage/rss2.0.xml'); 
			$feed = array();
				foreach ($rss->getElementsByTagName('item') as $node) {
					$item = array (
					'title' => $node->getElementsByTagName('title')->item(0)->nodeValue,
					'desc' => $node->getElementsByTagName('description')->item(0)->nodeValue,
					'date' => $node->getElementsByTagName('pubDate')->item(0)->nodeValue,
					);
				array_push($feed, $item);
				}
   
		$limit = 3; 
			for($x=0;$x<$limit;$x++) {
				$title = str_replace(' & ', ' &amp; ', $feed[$x]['title']);
				$description = $feed[$x]['desc'];
				$date = date('j F', strtotime($feed[$x]['date']));
				echo '<h2 class="smaller">'.$title.'</h2>';
				echo '<p class="date">'.$date.'</p>';
				//echo '<p>'.strip_tags($description, '<p><b>').'</p><h2>...</h2>';
			}
		?>		
  </div>
</div>

<script type="text/javascript">
    var canvas = document.getElementById("canvas");
    var ctx = canvas.getContext("2d");
    var radius = canvas.height / 2;
    ctx.translate(radius, radius);
    radius *= 0.90;
    setInterval(drawClock, 1000);

    function drawClock() 
	{
        drawFace(ctx, radius);
        drawNumbers(ctx, radius);
        drawTime(ctx, radius);
    }

    function drawFace(ctx, radius) {
        ctx.beginPath();
        ctx.arc(0, 0, radius, 0, 2 * Math.PI);
        ctx.fillStyle = 'white';
        ctx.fill();

        var gradient;
        gradient = ctx.createRadialGradient(0, 0, radius * 0.95, 0, 0, radius * 1.05);
        gradient.addColorStop(0, '#000');
        gradient.addColorStop(1, 'black');
        ctx.strokeStyle = gradient;
        ctx.lineWidth = radius * 0.1;
        ctx.stroke();

        ctx.beginPath();
        ctx.arc(0, 0, radius * 0.05, 0, 2 * Math.PI);
        ctx.fillStyle = '#000';
        ctx.fill();

        ctx.textBaseline = "middle";
        ctx.textAlign = "center";
        ctx.font = "bold " + radius * 0.12 + "px courier";
    }
    function drawNumbers(ctx, radius) {

        var ang, num, w, h;

        for (num = 1; num < 61; num++) {
            ang = num * Math.PI / 30;
            ctx.rotate(ang);
            ctx.translate(0, -radius * 0.85);
            if (num % 15 == 0) {
                w = 7;
                h = 4;
            } else if (num % 5 == 0) {
                w = 5;
                h = 2;
            } else {
                w = 2;
                h = 0.8;
            }
            ctx.beginPath();
            ctx.rect(0, 0, h, w);
            ctx.fillStyle = '#000';
            ctx.fill();
            ctx.translate(0, radius * 0.85);
            ctx.rotate(-ang);
        }
    }
    function drawTime(ctx, radius) {
        var now = new Date();
        var hour = now.getHours();
        var minute = now.getMinutes();
        var second = now.getSeconds();
        
        hour = hour % 12;
        hour = (hour * Math.PI / 6) + (minute * Math.PI / (6 * 60)) + (second * Math.PI / (360 * 60));
        drawHand(ctx, hour, radius * 0.5, radius * 0.04);
        
        minute = (minute * Math.PI / 30) + (second * Math.PI / (30 * 60));
        drawHand(ctx, minute, radius * 0.75, radius * 0.04);
        
        second = (second * Math.PI / 30);
        drawHand(ctx, second, radius * 0.85, radius * 0.02);
    }

    function drawHand(ctx, pos, length, width) {
        ctx.beginPath();
        ctx.strokeStyle = '#000';
        ctx.lineWidth = width;
        ctx.lineCap = "round";
        ctx.moveTo(0, 0);
        ctx.rotate(pos);
        ctx.lineTo(0, -length);
        ctx.stroke();
        ctx.rotate(-pos);
    }
</script>

</body>
</html>