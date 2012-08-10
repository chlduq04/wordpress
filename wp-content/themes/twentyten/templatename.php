<?php
/*
Template Name: templatename
*/
?>
<?
session_start(); 
?>
<script>
function logout(){
	location.href = "/ajaxEx/logout.php";
	}
function join(){
	location.href = "/ajaxEx/join.php";
	}
</script>
<form method='POST' action='./login.php'>
	<?
	$_SESSION['userID']=="";
	$_SESSION['password']=="";	
	if($_SESSION['userID']=="") {
		echo "<input name='id'> <br>";
		echo "<input type='password' name='pwd'> <br>";
		echo "<input type='submit' value='로그인'>";
		echo "</form>";
	}
	else {
		$userID = $_SESSION['userID'];
		$userName = $_SESSION['userName'];
		?>
	<script>alert("<?echo($userID)?>  <?echo($userName)?>");</script>
	</form>
	<button name=logout onclick="logout()">로그아웃</button>
	<?
	}
	?>
<button name=logout onclick="join()">회원가입</button>	


