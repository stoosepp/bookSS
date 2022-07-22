# theopenbook
An Open Textbook theme for Wordpress
![Screenshot of theopenbook Theme](https://github.com/stoosepp/theopenbook/blob/4822746d9f7ab4fb73c942699ff07a548a547500/screenshot.png)

## But Why?
For teachers who want to write their own learning materials / books, there's not many options out there, but this is a growing space.
* There's [Pressbooks](https://www.pressbooks.org), which is a great solution for writing more formal books with editorial features and great layouts and export options. Unfortunately, Pressbooks requires a wordpress multisite so isn't that easy to set up on a simple single site wordpress instance.
* There's [Jupyter Notebooks](https://jupyter.org/) that have an awesome layout and ability to embed interactive elements. Unfortunately, most of these elements are written in either Python or Markdown, so they may not be accessible to those without a programming background
* [Docsify.js](https://docsify.js.org/) is another option written in Markdown, but again MD may not be the easiest or most accessible way to write learnign materials for teachers.

Enter theopenbook (just pronounced "books" - don't overcomplicate it), a theme for Wordpress which packs in many of the same design language and features that these other platforms do, well *except for ePub and PDF export, but I'm working on that.*

## Features
* Simple, page-based book author
* Front-end Search
* Back-end filtering of pages by Book
* Pretty home page displaying all your books
* Easy to navigate pages, based on headings.
* Custom Creative Commons Licenses on every page footer
* Students can use checkboxes to mark chapters complete
* Voting system on front and back end
* 'Chromeless' embeds that strip out extraneous design elements
* Gutenberg friendly

Recommended Plugins to make life easier: [Simple Page Ordering](https://wordpress.org/plugins/simple-page-ordering/) | [Broken Link Checker](https://wordpress.org/plugins/broken-link-checker/)


*IN PROGRESS:*
* ePub & PDF Download options (probably ready in early 2023)

## In Practice
**Wanna see what it looks like for real?**

Visit [Readings on Learning Technology](http://learn.stoosepp.com), a site I'm current piloting for university courses on learning technology.

## How to use the thing
1. Create a Page. This is your book
2. Create a sub-page of a page - this is a chapter within your book.
3. Create a sub-sub-page of a sub-page - this is a subchapter within your book - THE END - that's as deep as you can go.
4. Edit your chapter (page) content by using H2 headings to structure your work. These headings will auto-generate a quick-jump menu on the right hand side of the page.

**Want to embed a chapter in your Learning Management System (LMS) like Moodle, Canvas or D2L?**
Just add '?chromeless=true' at the end of the url for the page you want to embed (e.g., http://yoursite.com/yourbook/yourchapter/?chromeless=true) - add this as a src to your iframe and it'll add a link, directing readers back to the original site. You can also use '?chromeless=truefull' to remove all margins and footer.

### Posts too!
If you want to create posts, these will show up on your home page as document-based pages based on category, so make a category (e.g., "General Academic Support") and put all your posts in that category in there
    _You can even make posts public, but hidden from the front page by simply including the word 'hidden' in the category title._

Each root-level Page (your book) has settings on it so you can enable a specific license, change footer text, allow for voting on each chapter and



## Credits and Acknowledgements

Primary Developer: [Stoo Sepp](www.stoosepp.com)

Thank you to [@hibbittsdesign](https://twitter.com/hibbittsdesign), [@FirasM](https://twitter.com/FirasM), and [@RichAsInRichard](https://twitter.com/RichAsInRichard) for their patience in answering my questions and helping me to learn more about Wordpress and Open Textbook platforms.

This Theme incorporates the following work:
[Tufte CSS](https://github.com/edwardtufte/tufte-css) led by Dave Liepmann
License: MIT License

## Licence
Contributors: the WordPress team
Requires at least: WordPress 4.4
Tested up to: WordPress 6.0
Version: 1.3
License: GPLv2 or later
License URI: https://www.gnu.org/licenses/gpl-2.0.html

## Contribute and Support

Feel free to fork and submit issues / pull requests.
I am an entirely self-taught coder, so I admit my code is NOT GOOD.

If you want to support my work feel free to [BUY ME A COFFEE](https://buymeacoffee.com/stooatwork).
Also, feel free to yell at me at [@stoosepp](https://twitter.com/stoosepp) on the bird place.