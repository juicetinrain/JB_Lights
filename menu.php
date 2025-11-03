<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
            <link rel="stylesheet" href="menu.css">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Menu</title>
</head>
<body>
<div id="mySidenav" class="sidenav">
  <a href="javascript:void(0)" class="closebtn" onclick="closeNav()"><</a><br><br>
  <a href="#" class="menu-item">PROFILE</a>
  <a href="#" class="menu-item">CONTACT US</a>
  <a href="#" class="menu-item">PAYMENT METHOD</a><br><br><br>
  <button class="logout-button">LOG OUT</button>
</div>
<div class="topnav">
<span style="font-size:30px;cursor:pointer" onclick="openNav()">&#9776;</span>
</div>
<script>
function openNav() {
  document.getElementById("mySidenav").style.width = "500px";
}

function closeNav() {
  document.getElementById("mySidenav").style.width = "0";
}
</script>
</body>
</html>