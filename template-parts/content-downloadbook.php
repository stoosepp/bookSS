<?php
// Add it for logged in users and guests:
/* ------------ COMMENT FORM ------------ */

?>

<?php $URL = get_template_directory_uri().'/template-parts/content-epubgenerator.php'; ?>


<div class="custom-downloadbook hidden">
    <p style="font-size:0.7em;">NOTE: Downloads are in BETA (EPUB and PDFs may not have perfect formatting, and embedded videos and interactives may not contain necessary links)</p>
<a onclick="toggleHidden(this);" href="<?php echo $URL.'?pageid='.$post->ID?>"><i class="far fa-book"></i>EPUB</a>
<a onclick="toggleHidden(this);" href="<?php echo $URL.'?pageid='.$post->ID?>"><i class="far fa-file-pdf"></i>PDF</a>
</div>
