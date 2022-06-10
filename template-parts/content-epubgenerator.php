<?php
include (dirname(__FILE__) . '/../vendor/autoload.php');
require_once (dirname(__FILE__) . '/../vendor/autoload.php');

use PHPePub\Core\EPub;
use PHPePub\Core\Logger;
use PHPePub\Core\Structure\OPF\DublinCore;
use PHPePub\Helpers\CalibreHelper;
use PHPePub\Helpers\IBooksHelper;
use PHPePub\Helpers\ImageHelper;
use PHPePub\Helpers\MimeHelper;
use PHPePub\Helpers\StringHelper;
use PHPePub\Helpers\Rendition\RenditionHelper;
use PHPePub\Helpers\URLHelper;
use PHPZip\Zip\File\Zip;

error_reporting(E_ALL | E_STRICT);
ini_set('error_reporting', E_ALL | E_STRICT);
ini_set('display_errors', 1);

/* ------ WP PREPARATION -----*/
$wpimport = $_SERVER["DOCUMENT_ROOT"] . '/wp-blog-header.php';
require($wpimport);
global $post;

$pageID = '';
if (isset($_GET['pageid'])) {
    $pageID = $_GET['pageid'];
    //echo 'Making Book for Book ID: '.$thisID;
    //echo get_the_title($thisID);
} else {
    // Fallback behaviour goes here
}
// //FOR TESTING DELETE THE BELOW WHEN EVERYTHING WORKS
// //$pageID = get_the_ID();

//Setup Wordpress Stuff
$site_title = get_bloginfo( 'name' );
$site_url = network_site_url( '/' );

$thisChapter = get_post($pageID);
$thisBookID = $pageID;

if ($thisChapter->post_parent) {
    // This is a subpage
    $thisBookID = getRootForPage($thisChapter);
}

$thisBook = get_post($thisBookID);
$bookTitle = get_the_title($thisBookID);
$bookIndex = $thisBook->menu_order;
$bookURL = get_permalink($thisBookID);

//Author Name
$fname = get_the_author_meta('first_name');
$lname = get_the_author_meta('last_name');
$full_name = '';
$full_reversed = '';

// //CClicense
$licenseText = '';
$CCLicense = get_post_meta( $thisBook->ID, 'bookLicense', true );
//consolePrint('License for '.$root->post_title.' is '.$CCLicense);
if (($CCLicense == 'allrightsreserved') || ($CCLicense == null)){
    $licenseText = 'All original content in this book is All Rights Reserved &copy; '.the_modified_time('Y');
}
else{
    $licenseText = 'All original content in this book is licenced under the '.$CCDescription.'unless otherwise noted.';
}

$fileDir = './PHPePub';
$log = new Logger("Example", TRUE);
//$book = new EPub(); // no arguments gives us the default ePub 2, lang=en and dir="ltr"
$ePubBook = new EPub(EPub::BOOK_VERSION_EPUB3, "en", EPub::DIRECTION_LEFT_TO_RIGHT); // Default is ePub 2


/* ------ SET PARAMTERS -----*/
// FIXED-LAYOUT METADATA (ONLY AVAILABLE IN EPUB3)
// RenditionHelper::addPrefix($book);
// RenditionHelper::setLayout($book, RenditionHelper::LAYOUT_PRE_PAGINATED);
// RenditionHelper::setOrientation($book, RenditionHelper::ORIENTATION_AUTO);
// RenditionHelper::setSpread($book, RenditionHelper::SPREAD_AUTO);

IBooksHelper::addPrefix($ePubBook);
IBooksHelper::setIPadOrientationLock($ePubBook, IBooksHelper::ORIENTATION_PORTRAIT_ONLY);
IBooksHelper::setIPhoneOrientationLock($ePubBook, IBooksHelper::ORIENTATION_PORTRAIT_ONLY);
IBooksHelper::setSpecifiedFonts($ePubBook, true);
IBooksHelper::setFixedLayout($ePubBook, true);
$log->logLine("Set up parameters");

/* ------ START BOOK CONSTRUCTION -----*/


// This is for the example, this is the XHTML 1.1 header
$content_start =
"<?xml version=\"1.0\" encoding=\"utf-8\"?>\n"
. "<!DOCTYPE html PUBLIC \"-//W3C//DTD XHTML 1.1//EN\"\n"
. "    \"http://www.w3.org/TR/xhtml11/DTD/xhtml11.dtd\">\n"
. "<html xmlns=\"http://www.w3.org/1999/xhtml\">\n"
. "<head>"
. "<meta http-equiv=\"Content-Type\" content=\"text/html; charset=utf-8\" />\n"
//. "<link rel=\"stylesheet\" type=\"text/css\" href=\"styles.css\" />\n"
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"ePub.css\" />\n"//ePub
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"all.css\" />\n"//all
. "<link rel=\"stylesheet\" type=\"text/css\" href=\"bookSS.css\" />\n"//BookSS
. "<title>".$bookTitle."</title>\n"
. "</head>\n"
. "<body>\n";

$bookEnd = "</body>\n</html>\n";

// setting timezone for time functions used for logging to work properly
date_default_timezone_set('Europe/Berlin');


$log->logLine("new EPub()");
// Title and Identifier are mandatory!
//$book->setTitle("Simple Test book");
$ePubBook->setTitle($bookTitle);

//$book->setIdentifier("http://JohnJaneDoePublications.com/books/TestBookSimple.html", EPub::IDENTIFIER_URI); // Could also be the ISBN number, preferrd for published books, or a UUID.
$ePubBook->setIdentifier($bookURL, EPub::IDENTIFIER_URI); // Could also be the ISBN number, preferrd for published books, or a UUID.

$ePubBook->setLanguage("en"); // Not needed, but included for the example, Language is mandatory, but EPub defaults to "en". Use RFC3066 Language codes, such as "en", "da", "fr" etc.

$ePubBook->setDescription("This is a brief description\nA test ePub book as an example of building a book in PHP");

//$book->setAuthor("John Doe Johnson", "Johnson, John Doe");

//TESTING 9/6/22
$post_author_id = get_post_field( 'post_author', $thisBookID);
$fname = get_the_author_meta('first_name', $post_author_id);
$lname = get_the_author_meta('last_name', $post_author_id);
$full_name = "";
if( empty($fname)){
    $full_name = $lname;
    $ePubBook->setAuthor($full_name, $full_name);
} elseif( empty( $lname )){
    $full_name = $fname;
    $ePubBook->setAuthor($full_name, $full_name);
} else {
    //both first name and last name are present
    $full_name = "{$fname} {$lname}";
    $ePubBook->setAuthor($full_name, $full_reversed);
}

//$book->setPublisher("John and Jane Doe Publications", "http://JohnJaneDoePublications.com/"); // I hope this is a non existent address :)
$ePubBook->setPublisher($site_title, $site_url); // I hope this is a non existent address :)

$ePubBook->setDate(time()); // Strictly not needed as the book date defaults to time().

//$book->setRights("Copyright and licence information specific for the book.");
$ePubBook->setRights($licenseText);

//$book->setSourceURL("http://JohnJaneDoePublications.com/books/TestBookSimple.html");
$ePubBook->setSourceURL($bookURL);

// Insert custom meta data to the book, in this case, Calibre series index information.
//CalibreHelper::setCalibreMetadata($book, "PHPePub Test books", "5");
CalibreHelper::setCalibreMetadata($ePubBook, $site_title, $bookIndex);

$ePubBook->isGifImagesEnabled = TRUE;

// Add CSS
//$cssData = "body {\n  margin-left: .5em;\n  margin-right: .5em;\n  text-align: justify;\n}\n\np {\n  font-family: serif;\n  font-size: 10pt;\n  text-align: justify;\n  text-indent: 1em;\n  margin-top: 0px;\n  margin-bottom: 1ex;\n}\n\nh1, h2 {\n  font-family: sans-serif;\n  font-style: italic;\n  text-align: center;\n  background-color: #6b879c;\n  color: white;\n  width: 100%;\n}\n\nh1 {\n    margin-bottom: 2px;\n}\n\nh2 {\n    margin-top: -2px;\n    margin-bottom: 2px;\n}\n";
//$book->addCSSFile("styles.css", "css1", $cssData);
$log->logLine("Add css");

$ePubCSS = file_get_contents(dirname(__FILE__) . "/../css/ePub.css");
$ePubBook->addCSSFile("ePub.css", "ePub", $ePubCSS);
$all = file_get_contents(dirname(__FILE__) . "/../css/all.css");
$ePubBook->addCSSFile("all.css", "all", $all);
$bookSS = file_get_contents(dirname(__FILE__) . "/../css/bookSS.css");
$ePubBook->addCSSFile("bookSS.css", "bookSS", $bookSS);
//$ePubBook->addCSSFile("styles.css", "css1", $ePubCSS);

//Add Default CSS for Books
// $bookSSCSS = file_get_contents(dirname(__FILE__) . "/../css/bookSS.css");
// $ePubBook->addCSSFile("bookSS.css", "bookSS", $bookSSCSS);


//Add Cover Image
$log->logLine("Add Cover Image");
 $bookImageURL = get_the_post_thumbnail_url($thisBook);
if ($bookImageURL){
//CROPPING TO BOOK SIZE
/*
   $im =  imagecreatefromstring(file_get_contents($bookImageURL));
    $size = min(imagesx($im), imagesy($im));
    print
    $im2 = imagecrop($im, ['x' => 0, 'y' => 0, 'width' => 600, 'height' => 800]);
    if ($im2 !== FALSE) {
        imagepng($im2, 'example-cropped.png');
        //imagedestroy($im2);
    }
    imagedestroy($im);
    $imageData = base64_encode($im2);
    $ePubBook->setCoverImage("Cover.jpg",$imageData);
*/
    //THIS WORKS
   $ePubBook->setCoverImage("Cover.jpg", file_get_contents($bookImageURL), "image/jpeg");
}

 //Cover Content - NOT APPLICABLE AS THE ABOVE WILL DEAL WITH THE COVER FOR ANY EREADER APP
 /*
 $cover = $content_start;
 $cover .= '<div class="book-cover"><div class="book-gradient"></div>';
 $cover .= '<div class="book-title"><h1>'.$bookTitle.'</h1>';

$post_author_id = get_post_field( 'post_author', $thisBook);
$cover .=  '<div class="book-authorexcerpt"><h2>'.get_the_author_meta('display_name', $post_author_id).'</h2>';
$cover .=  '<h3>'.$bookTitle->post_excerpt.'</h3></div>';
		if ($bookImageURL){
			$cover .= '<img id="cover-image" src="'.esc_url($bookImageURL).'" rel="lightbox">';
		}
		$cover .= '</div></li>';
		$cover .= '</a>'. $bookEnd;;
$ePubBook->addChapter("Cover", "Cover.html", $cover,FALSE, EPub::EXTERNAL_REF_ADD);
*/



//BUILDING BOOK CHAPTERS

//The order of the following is to addChapter($titleString,$ChapterFileName,$chapterContent,$autosplitChapter,$externalReferences)
//$book->addChapter("Chapter 2: Vivamus bibendum massa", "Chapter002.html", $chapter2, true, EPub::EXTERNAL_REF_ADD);

//FRONT MATTER
    $chapterTitle = "Front Matter";
    $cleanChapterTitle = preg_replace("/[^a-zA-Z0-9\s]/", "", $chapterTitle);

    //Start Chapter Content
    $thisChapterContent = '<h1>'.$chapterTitle.'</h1>';

    //Featured image Of Title
    $featured_img_url = get_the_post_thumbnail_url($thisBook);
    if ($featured_img_url){
        $chapterContent .= '<img src="'.esc_url($featured_img_url).'" />';
    }

    //Prepare and Clean Content
    $rawChapterContent = $thisBook->post_content;
    $thisChapterContent .= preparePageContentForePub($rawChapterContent);

    //Adding Chapter
    $ePubBook->addChapter($chapterTitle, $cleanChapterTitle.".html", $content_start . $thisChapterContent . $bookEnd, FALSE, EPub::EXTERNAL_REF_ADD);

    //Add TOC
//$ePubBook->buildTOC();
$ePubBook->buildTOC(NULL, "toc", "Table of Contents", TRUE, TRUE);

//CHAPTERS
$chapters = getKids($thisBookID);
$chapterNo = 1;
if ( $chapters){
    foreach ( $chapters as $chapter ) {

        //Chapter Title
        $chapterTitle = get_the_title($chapter);
        //$cleanChapterTitle = str_replace(' ', '', $chapterTitle);
        $cleanChapterTitle = preg_replace("/[^a-zA-Z0-9\s]/", "", $chapterTitle);

        //Start Chapter Content
        $thisChapterContent = '<h1>'.$chapterNo.'. '.$chapterTitle.'</h1>';

        //Featured image Of Title
        $featured_img_url = get_the_post_thumbnail_url($thisChapter);
        if ($featured_img_url){
           $chapterContent .= '<img src="'.esc_url($featured_img_url).'" />';
        }

        //Prepare and Clean Content
        $rawChapterContent = $chapter->post_content;
		$thisChapterContent .= preparePageContentForePub($rawChapterContent);

        //Adding Chapter
        $ePubBook->addChapter($chapterNo.'. '.$chapterTitle, $cleanChapterTitle.".html", $content_start . $thisChapterContent . $bookEnd, FALSE, EPub::EXTERNAL_REF_ADD);

        $subChapterNo = 1;

       $subChapters = getKids($chapter->ID);
       $log->logLine('There are '.count($subChapters).' subchapters in '.$chapterTitle);

        if ( $subChapters){
            $subChapterNo = 1;
            $ePubBook->subLevel();
            foreach ($subChapters as $subChapter){

                //Title
                $subChapterTitle = get_the_title($subChapter);
                //$cleanSubChapterTitle = str_replace(' ', '', $subChapterTitle);
                $cleanSubChapterTitle = preg_replace("/[^a-zA-Z0-9\s]/", "", $subChapterTitle);

                 //Start Chapter Content
                $thisSubChapterContent = '<h1>'.$chapterNo.'.'.$subChapterNo.'. '.$subChapterTitle.'</h1>';

                //Chapter Content
                $rawSubChapterContent = $subChapter->post_content;
                $thisSubChapterContent .= preparePageContentForePub($rawSubChapterContent);

                //Adding Chapter
                $ePubBook->addChapter($chapterNo.'.'.$subChapterNo.'. '.$subChapterTitle, $cleanSubChapterTitle.".html", $content_start . $thisSubChapterContent . $bookEnd, FALSE, EPub::EXTERNAL_REF_ADD);

                $subChapterNo++;
            }
            $ePubBook->backLevel();
        }

        $chapterNo++;
    }
}

if ($ePubBook->isLogging) { // Only used in case we need to debug EPub.php.
    $epuplog = $ePubBook->getLog();
    $ePubBook->addChapter("ePubLog", "ePubLog.xhtml", $content_start . $epuplog . "\n</pre>" . $bookEnd);
}

$ePubBook->finalize(); // Finalize the book, and build the archive.

// Send the book to the client. ".epub" will be appended if missing.
$cleanbookTitle = str_replace(' ', '', $bookTitle);
$zipData = $ePubBook->sendBook($cleanbookTitle);

// After this point your script should call exit. If anything is written to the output,
// it'll be appended to the end of the book, causing the epub file to become corrupt.
