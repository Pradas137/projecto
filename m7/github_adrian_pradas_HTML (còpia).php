 <!DOCTYPE html>
<html>
<head>
<link href="index12.css" rel="stylesheet" type="text/css" media="screen" />
</head> 
<body>
	<h1>Projecte Welcome fotos</h1>
	<ul>

	<?php 

		$imgs = scandir("./img",SCANDIR_SORT_ASCENDING);
		foreach( $imgs as $img ) {	
			if( substr($img,-3)=="jpg" or substr($img,-3)=="png" or substr($img,-4)=="jpeg") {
				$name = substr($img,0,-4);

				echo "<a href='profile/$name.html'>";

				echo "<img src='img/$img' width='90'>";

				echo "$name.</a>";
				

				echo "<div></div>";
				;
			}
		}

	?>
</ul>
</body>
</html>
