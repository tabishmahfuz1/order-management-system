<!DOCTYPE html>
<html>
<head>
	<title>TEST</title>
</head>
<body>
	<button onclick="myFunc()">GET POSITION</button>
	<p id="txt"></p>
<script type="text/javascript">
	function myFunc() {
		alert("IN F")
		console.log("Func Called");
		if(navigator.geolocation) {
			alert("IN IF")
			console.log("Hai Na!");
			navigator.geolocation.getCurrentPosition((position) => {
				alert("GOT P")

				console.log("Position", position);
				document.getElementById("txt").innerHTML = position.coords.latitude + ", " + position.coords.longitude;
			});
		} else {
			console.log("NOT HAI");
			alert('NANANA')
		}
	}
</script>
</body>
</html>