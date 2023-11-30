<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurs Scrap</title>
    <style>
        .myButton {
	background-color:#44c767;
	border-radius:28px;
	border:1px solid #18ab29;
	display:inline-block;
	cursor:pointer;
	color:#ffffff;
	font-family:Arial;
	font-size:17px;
	padding:16px 31px;
	text-decoration:none;
	text-shadow:0px 1px 0px #2f6627;
}
.myButton:hover {
	background-color:#5cbf2a;
}
.myButton:active {
	position:relative;
	top:1px;
}
    </style>
</head>
<body>
    <a href="/api/kurs" onclick="myFunction()" class="myButton">Scrap</a>
</body>
<script>
	function myFunction() {
  		alert("Proses berhasil ditambahkan pada Job");
	}
</script>
</html>