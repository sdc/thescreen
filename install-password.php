<?php

/**
 * Installer - password setter.
 */

require_once( 'functions.inc.php' );

// Check for an existing password already set in the database. It's not likely, but a warning's in order.
$epwd = false;
if ( get_config( 'admin_password' ) ) {
  $epwd = true;
}

if ( isset( $_POST['password'] ) && !empty( $_POST['password'] ) && isset( $_POST['confirm'] ) && !empty( $_POST['confirm'] ) && $_POST['password'] === $_POST['confirm'] ) {

  // Both passwords were set and identical, so hash it and save it.

  // We're using http://www.openwall.com/phpass/
  require_once( 'passwordhash.php' );

  $hashing = new PasswordHash( $CFG['phpass']['base2log'], false );
  $hash = $hashing->HashPassword( $_POST['password'] );

  if ( $epwd ) {
    // Update, not insert, if it already exists.
    $saved = set_config( 'admin_password', $hash );

  } else {
    // Initial insert.
    $saved = set_config( 'admin_password', $hash, true );
  }

  // If the password was saved correctly.
  if ( $saved ) {
    echo "<h2>Admin password saved</h2>";
    echo "<p>Well done! Your chosen password was confirmed as correct, hashed and stored in the database.</p>";
    echo "<p>The link below will take you through to the administration pages (which you can get to easily by adding <code>admin/</code> to the end of the main screen url), but please note that if the installation script (or this one, setting the password) are run again either maliciously or intentionally, <strong>they will reset your database utterly</strong>. This is bad, and most likely not what you want, so <strong>please delete the files called <code>install.php</code> and <code>install-password.php</code> at your earliest convenience</strong>!</p>";
    echo '<p><a href="' . $CFG['adminpage'] . '">Click here to log in and configure the system</a>, or <a href="install-sdc.php">click here if you want to install SDC-specific data</a>.</p>';

    exit(0);
  } 
}

echo "<h1>The Screen&trade; Installation</h1>";
echo "<p>We need a password from you to protect the admin interface. Enter the same password into each of the boxes below, and click 'save'. There are no password policies in place: you do not need to use different cases, numbers or symbols, so make your password a good one, ok? I'm trusting you with this.</p>";
echo "<p><strong>Note:</strong> If this page simply reloads, there was a problem with your passwords matching, so try again.</p>";

if ( $epwd ) {
  echo "<p><strong>Note:</strong> There appears to already be an admin password in the database. Using this form will overwrite the existing password with a new one.</p>";
}

?>
<form action="install-password.php" method="post">
  <label for="password">Password:</label>
  <input type="password" id="password" name="password">
  <br>
  <label for="confirm">Confirm:</label>
  <input type="password" id="confirm" name="confirm">
  <br>
  <button type="submit">Submit</button>
</form>
