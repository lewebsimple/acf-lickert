(function ($) {

  /**
   * Initialize ACF Lickert field
   * @param $field
   */
  function initialize_field ($field) {
    // Input fields and error div
    let $score = $field.find('select'),
      $comment = $field.find('textarea');

    // Reset lickert number input
    function reset () {
      $score.val('');
      $comment.val('');
    }
  }

  // Initialization hooks
  acf.add_action('ready_field/type=lickert', initialize_field);
  acf.add_action('append_field/type=lickert', initialize_field);

})(jQuery);
