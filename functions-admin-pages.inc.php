<?php

/**
 * Page functions.
 */

// Gets the page background (if set) and displays as a thumbnail.
function get_page_background_thumb() {
  global $CFG;

  $out = '          <div class="row">' . "\n";
  $out .= '            <div class="col-sm-10 col-sm-offset-1">' . "\n";

  if ( $img = get_page_background_image() ) {
    $out = '          <div class="row">' . "\n";
    $out .= '            <div class="col-sm-10 col-sm-offset-1">' . "\n";
    $out .= '              <p><img src="' . $CFG['dir']['bg'] . '/' . $img . '" alt="Current page in use" class="img-thumbnail"></p>' . "\n";

  } else {
    $out = '          <div class="row">' . "\n";
    $out .= '            <div class="col-sm-12">' . "\n";
    $out .= '              <div class="alert alert-info" role="alert">' . "\n";
    $out .= '                <strong>Info:</strong> This page doesn\'t appear to have a background image associated with it. This may or may not be a problem.' . "\n";
    $out .= "              </div>\n";   
  }

  $out .= '            </div>' . "\n";
  $out .= '          </div>' . "\n";

  return $out;

}
