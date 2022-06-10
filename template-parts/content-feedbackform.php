<?php
// Add it for logged in users and guests:
/* ------------ COMMENT FORM ------------ */

add_filter( 'comment_form_defaults', 'custom_comment_notes' );
function custom_comment_notes( $fields ) {
    $comment_field = $fields['title_reply'];
    $comment_field = $fields['comment_notes_before'];
    $comment_field = $fields['label_submit'];
    unset($fields['title_reply']);
    unset($fields['comment_notes_before']);
    unset($fields['label_submit']);
    $fields['title_reply'] = __( 'Provide Feedback on this Chapter' );
    $fields['comment_notes_before'] = null;
    $fields['label_submit'] = 'Submit Feedback';
    return $fields;
}

add_filter( 'comment_form_fields', 'custom_comment_field' );
function custom_comment_field( $fields ) {
    // What fields you want to control.
    $comment_field = $fields['author'];
    $comment_field = $fields['email'];
    $comment_field = $fields['comment'];
    $comment_field = $fields['url'];
    $comment_field = $fields['cookies'];

    // The fields you want to unset (remove).
    unset($fields['author']);
    unset($fields['email']);
    unset($fields['url']);
    unset($fields['comment']);
    unset($fields['cookies']);

    $fields['name'] = null;
    $fields['email'] = null;
    $fields['comment'] = '<p class="comment-form-comment"><label for="comment">Any suggestions for changes, additions or other ideas are welcome. Thank you!<span class="required"></span></label><textarea id="comment" name="comment" required="required" placeholder="Your suggestions here."></textarea></p>';
    return $fields;
}?>
<div class="custom-feedbackform hidden">
    <a style="cursor:pointer;"  class = "" onclick="toggleHidden(this);"><i class="far fa-times"></i></a>
    <?php  echo comment_form(); ?>
</div>
