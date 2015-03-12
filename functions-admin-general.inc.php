<?php

/**
 * General functions.
 */

// If there are any 'alerts' set, do cool stuff.
function display_alerts() {

  if ( isset( $_SESSION['alerts'] ) ) {

    $out = '';
    foreach ( $_SESSION['alerts'] as $alert ) {

      foreach ( $alert as $alert_type => $alert_text ) {

        // Catch 'danger' alerts and ensure they can't be dismissed.
        if ( $alert_type == 'danger' ) {
          $out .= '<div class="alert alert-' . $alert_type . '" role="alert">' . "\n";

        } else {
          $out .= '<div class="alert alert-' . $alert_type . ' alert-dismissible alert-' . $alert_type . '-fade" role="alert">' . "\n";
          $out .= '  <button type="button" class="close" data-dismiss="alert" aria-label="Close"><span aria-hidden="true">&times;</span></button>' . "\n";
        }

        $out .= $alert_text . "\n";
        $out .= '</div>' . "\n";

      }

    }

    unset( $_SESSION['alerts'] );
    return $out;
  }

}

// Footer content.
function footer_content() {

  global $CFG;

?>
      <p>Designed and built with all the love in the world by <a href="https://twitter.com/sdcmoodle" target="_blank">@sdcmoodle</a>.</p>
      <ul class="bs-docs-footer-links text-muted">
        <li>Currently v<?php echo $CFG['version']['build']; ?>, <?php echo $CFG['version']['date']; ?></li>
        <li>&middot;</li>
        <li>Built with <a href="http://getbootstrap.com">Bootstrap 3</a></li>
        <span id="browserdata"></span>
      </ul>
<?php
}
