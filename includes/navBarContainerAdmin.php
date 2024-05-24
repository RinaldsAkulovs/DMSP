<div id="navBarContainer">
	<nav class="navBar">
		
		<span tabindex="0" class="logo">
			<img src="assets/images/icons/Logo.png">
		</span>

		<div class="group">
			
			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('songData.php')" class="navItemLink">Song data</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('userData.php')" class="navItemLink">User data</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('')" class="navItemLink">Statistics</span>
			</div>

			<div class="navItem">
				<span role="link" tabindex="0" onclick="openPage('settings.php')" class="navItemLink"><?php echo $userLoggedIn->getFirstAndLastName(); ?></span>
			</div>

		</div>

	</nav>
</div>