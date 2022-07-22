// window.addEventListener("DOMContentLoaded", () => {

//   });


window.onload = function() {
    console.log('Window Onload');
    //var isAtRoot = location.pathname == "/"; //Equals true if we're at the root
    if (document.body.classList.contains('home') == false) {
        loadTOCstatus();
        loadLocalStorage();
        setCurrentPageLink();
        setSidebarActive();
    }
    else{ //is home
        document.body.style.visibility = 'visible';
    }
    loadColorScheme();
    if ((window.location.href.includes('voteUp')) || (window.location.href.includes('voteDown')) ){//remove URL parameters when voting
        window.history.replaceState({}, document.title, location.protocol + '//' + location.host + location.pathname);
    }
    if(window.location.href.includes('?s=')){
        console.log('This is a search results page');
        const articleBody = document.getElementsByClassName('article-body');
        if (articleBody[0]) {
            console.log('YUP it exists');
            articleBody[0].style.marginRight = '0px';
            articleBody[0].style.marginTop = '0px';
            //articleBody[0].style.display = 'none';
        }
    }


}
window.mobileAndTabletCheck = function() {
    let check = false;
    (function(a){if(/(android|bb\d+|meego).+mobile|avantgo|bada\/|blackberry|blazer|compal|elaine|fennec|hiptop|iemobile|ip(hone|od)|iris|kindle|lge |maemo|midp|mmp|mobile.+firefox|netfront|opera m(ob|in)i|palm( os)?|phone|p(ixi|re)\/|plucker|pocket|psp|series(4|6)0|symbian|treo|up\.(browser|link)|vodafone|wap|windows ce|xda|xiino|android|ipad|playbook|silk/i.test(a)||/1207|6310|6590|3gso|4thp|50[1-6]i|770s|802s|a wa|abac|ac(er|oo|s\-)|ai(ko|rn)|al(av|ca|co)|amoi|an(ex|ny|yw)|aptu|ar(ch|go)|as(te|us)|attw|au(di|\-m|r |s )|avan|be(ck|ll|nq)|bi(lb|rd)|bl(ac|az)|br(e|v)w|bumb|bw\-(n|u)|c55\/|capi|ccwa|cdm\-|cell|chtm|cldc|cmd\-|co(mp|nd)|craw|da(it|ll|ng)|dbte|dc\-s|devi|dica|dmob|do(c|p)o|ds(12|\-d)|el(49|ai)|em(l2|ul)|er(ic|k0)|esl8|ez([4-7]0|os|wa|ze)|fetc|fly(\-|_)|g1 u|g560|gene|gf\-5|g\-mo|go(\.w|od)|gr(ad|un)|haie|hcit|hd\-(m|p|t)|hei\-|hi(pt|ta)|hp( i|ip)|hs\-c|ht(c(\-| |_|a|g|p|s|t)|tp)|hu(aw|tc)|i\-(20|go|ma)|i230|iac( |\-|\/)|ibro|idea|ig01|ikom|im1k|inno|ipaq|iris|ja(t|v)a|jbro|jemu|jigs|kddi|keji|kgt( |\/)|klon|kpt |kwc\-|kyo(c|k)|le(no|xi)|lg( g|\/(k|l|u)|50|54|\-[a-w])|libw|lynx|m1\-w|m3ga|m50\/|ma(te|ui|xo)|mc(01|21|ca)|m\-cr|me(rc|ri)|mi(o8|oa|ts)|mmef|mo(01|02|bi|de|do|t(\-| |o|v)|zz)|mt(50|p1|v )|mwbp|mywa|n10[0-2]|n20[2-3]|n30(0|2)|n50(0|2|5)|n7(0(0|1)|10)|ne((c|m)\-|on|tf|wf|wg|wt)|nok(6|i)|nzph|o2im|op(ti|wv)|oran|owg1|p800|pan(a|d|t)|pdxg|pg(13|\-([1-8]|c))|phil|pire|pl(ay|uc)|pn\-2|po(ck|rt|se)|prox|psio|pt\-g|qa\-a|qc(07|12|21|32|60|\-[2-7]|i\-)|qtek|r380|r600|raks|rim9|ro(ve|zo)|s55\/|sa(ge|ma|mm|ms|ny|va)|sc(01|h\-|oo|p\-)|sdk\/|se(c(\-|0|1)|47|mc|nd|ri)|sgh\-|shar|sie(\-|m)|sk\-0|sl(45|id)|sm(al|ar|b3|it|t5)|so(ft|ny)|sp(01|h\-|v\-|v )|sy(01|mb)|t2(18|50)|t6(00|10|18)|ta(gt|lk)|tcl\-|tdg\-|tel(i|m)|tim\-|t\-mo|to(pl|sh)|ts(70|m\-|m3|m5)|tx\-9|up(\.b|g1|si)|utst|v400|v750|veri|vi(rg|te)|vk(40|5[0-3]|\-v)|vm40|voda|vulc|vx(52|53|60|61|70|80|81|83|85|98)|w3c(\-| )|webc|whit|wi(g |nc|nw)|wmlb|wonu|x700|yas\-|your|zeto|zte\-/i.test(a.substr(0,4))) check = true;})(navigator.userAgent||navigator.vendor||window.opera);
    return check;
  };

function loadLocalStorage(){
    //Load Switches for font and layout
    //console.log(allStorage());
    var list = document.querySelectorAll(`[type*="checkbox"]`);
    var completedChapters = JSON.parse(localStorage.getItem("checkedChapters"));
    list.forEach( el => {

        if ((el.id == 'tufte') || (el.id == 'opendyslexic')){
            var checked = JSON.parse(localStorage.getItem(el.id));
            var checkBox = document.getElementById(el.id);
            checkBox.checked = checked;
            if (el.id == 'tufte'){
                updateCSS(el.id,checked);
            }
            else if (el.id == 'opendyslexic'){
                updateCSS(el.id,checked);
            }

        }
        else if ((completedChapters) && (completedChapters.length > 0)){
            console.log('Completed Chapters: ' + completedChapters + ', and thisID: ' + el.id);
            if (completedChapters.includes(el.id)){
                console.log('It is in there!');
                var chapterCheckBox = document.getElementById(el.id);
                chapterCheckBox.checked = true;
            }
        }
    });
    document.body.style.visibility = 'visible';
}

function loadTOCstatus(){
    var TOCStatus = JSON.parse(localStorage.getItem('TOC-hidden'));
    //console.log('Loading TOC. Hidden: ' + TOCStatus);
   // hamburger = document.getElementsByClassName('fa-bars')[0];
    //leftArrow = document.getElementsByClassName('fa-arrow-left')[0];
    headerbar = document.getElementsByClassName('header-bar')[0];
    // if (headerbar){
    //     headerbar.classList.add('disable-css-transitions');
    // }
    TOC = document.getElementsByClassName('left-toc')[0];
   // TOC.classList.add('disable-css-transitions');
    article = document.getElementsByClassName('article')[0];
    if (article){
       // article.classList.add('disable-css-transitions');
    }
    if (TOCStatus == true){
        article.classList.add('article-margin');
        //headerbar.classList.add('banner-padding');
        TOC.classList.add('hidden-toc');
        //leftArrow.classList.add('hidden');
        //hamburger.classList.remove('hidden');
    }
}


function loadColorScheme(){
    var isAtRoot = location.pathname == "/"; //Equals true if we're at the root

    var colorScheme = localStorage.getItem('colorScheme');
    if (colorScheme){
        console.log('Loaded Color Scheme: ' + colorScheme);
       // if (isAtRoot != true){
            var radiobtn = document.getElementById(colorScheme + 'Check');
            radiobtn.checked = true;
        //}
        updateCSS(colorScheme,true);
    }
    else{
        updateCSS('white',true);
    }

}
function copyPodcastURL(thisText) {
    console.log('URL is ' + thisText);
     /* Copy the text inside the text field */
    //navigator.clipboard.writeText(thisText);
    /* Alert the copied text */


    if (!navigator.clipboard) {
        fallbackCopyTextToClipboard(thisText);
        return;
      }
      navigator.clipboard.writeText(thisText).then(function() {
        console.log('Async: Copying to clipboard was successful!');
        alert('Podcast URL has been copied to your clipboard.\nUse your favorite Podcasting app using a SUBSCRIBE WITH URL feature (CMD/CTRL-V or Edit>Paste).');
    }, function(err) {
        console.error('Async: Could not copy text: ', err);
      });
  }

  function fallbackCopyTextToClipboard(text) {
    var textArea = document.createElement("textarea");
    textArea.value = text;

    // Avoid scrolling to bottom
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.position = "fixed";

    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();

    try {
      var successful = document.execCommand('copy');
      var msg = successful ? 'successful' : 'unsuccessful';
      console.log('Fallback: Copying text command was ' + msg);
      alert('Podcast URL has been copied to your clipboard.\nUse your favorite Podcasting app using a SUBSCRIBE WITH URL feature (CMD/CTRL-V or Edit>Paste).');
    } catch (err) {
      console.error('Fallback: Oops, unable to copy', err);
    }

    document.body.removeChild(textArea);
  }


function changeColorScheme(thisRadioButton){
    //console.log('This is checked ' + thisRadioButton.value);
    const colorList = ['white','sepia','darkmode'];
    colorList.forEach(thisColor =>{
        if (thisColor == thisRadioButton.value){
            updateCSS(thisColor,true);
        }
        else{
            updateCSS(thisColor,false);
        }
    });
    localStorage.setItem('colorScheme',  thisRadioButton.value);
}
/* UPDATE DARK MODE WHEN DEVICE IS IN DARK MODE */
/*
window.matchMedia('(prefers-color-scheme: dark)')
      .addEventListener('change', event => {
        if (event.matches) {//Change to Dark Mode
            //dark mode
            console.log('Entering Dark Mode');
            var darkmode = document.getElementById('darkmodeCheck');
            darkmode.checked = true;
           changeColorScheme(darkmode);

        } else {//Change to Saved Color
            //light mode
            console.log('Entering Light Mode');
            var lightmode = document.getElementById('whiteCheck');
            lightmode.checked = true;
            changeColorScheme(lightmode);
        }

})*/


function setVoteStatus(postID){
    var votedPosts = JSON.parse(localStorage.getItem("postVotes"));
    if ((votedPosts) && (votedPosts.length > 0)){
        console.log(votedPosts.length + ' posts voted on.')
        console.log('Stored Posted IDs voted on: ' + votedPosts);
        if (votedPosts.includes(postID) == false){
            votedPosts.push(postID);
        }
    }
    else{
        var votedPosts = new Array();
        votedPosts.push(postID);
        console.log('Stored Posted IDs voted on: ' + votedPosts);
    }
    localStorage.setItem("postVotes", JSON.stringify(votedPosts));
}

function checkVoteStatus(postID){
    //console.log('Checking Vote Status for Post: ' + postID);
    var votedOnThis = false;
    var votedPosts = JSON.parse(localStorage.getItem("postVotes"));
    if ((votedPosts) && (votedPosts.length > 0)){
        if (votedPosts.includes(postID) == true){
            votedOnThis = true;
        }
    }
    const votingButtons = document.getElementsByClassName('submit-vote')[0];
    const thankYou = document.getElementsByClassName('did-vote')[0];
    if (votedOnThis ==true){
        votingButtons.classList.toggle('hidden');
        thankYou.classList.toggle('hidden');
    }
}

function saveCheckbox(thisCheckbox){
    //Save regular checkbox
    console.log('Saving Checkbox with ID: ' + thisCheckbox.id);
    if ((thisCheckbox.id == 'opendyslexic') || (thisCheckbox.id == 'tufte')){
        const dyslexicCheck = document.getElementById('opendyslexic');
        const tufteCheck = document.getElementById('tufte');
        if (thisCheckbox.checked){
            localStorage.setItem(thisCheckbox.id, true);
        }
        else{
            localStorage.setItem(thisCheckbox.id, false);
        }
        updateCSS(thisCheckbox.id, thisCheckbox.checked);
        if ((tufteCheck.checked) && (thisCheckbox == dyslexicCheck)){
            tufteCheck.checked = false;
            updateCSS(tufteCheck.id, false);
            localStorage.setItem(thisCheckbox.id, true);
            localStorage.setItem(tufteCheck.id, false);
        }
        else if ((dyslexicCheck.checked) && (thisCheckbox == tufteCheck)){
            dyslexicCheck.checked = false;
            updateCSS(dyslexicCheck.id, false)
            localStorage.setItem(thisCheckbox.id, true);
            localStorage.setItem(dyslexicCheck.id, false);
        }
        console.log('Book: ' + localStorage.getItem('tufte'));
        console.log('Dylexic: ' + localStorage.getItem('opendyslexic'));
    }
    else{
        var completedChapters = JSON.parse(localStorage.getItem("checkedChapters"));
        if ((completedChapters) && (completedChapters.length > 0)){
            if (completedChapters.includes(thisCheckbox.id) == false){
                completedChapters.push(thisCheckbox.id);
            }
            else{//Remove if it's already in the array
                const index = completedChapters.indexOf(thisCheckbox.id);
                if (index > -1) {
                    completedChapters.splice(index, 1);
                }
            }
        }
        else{
            var completedChapters = new Array();
            completedChapters.push(thisCheckbox.id);

        }
        localStorage.setItem("checkedChapters", JSON.stringify(completedChapters));
        console.log('Saved Chapters: ' + completedChapters);
    }
}

async function updateCSS(forID, isChecked)
{
    var bookURL = theopenbookURL.templateUrl;
    var body = document.body
    if  (isChecked == true)
    {
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.id = forID + "CSS";
        link.href = bookURL + '/css/' + forID + '.css';
        //console.log('Updating CSS to ' + link.href);
        document.head.append(link);
    }
    else if ((isChecked == false) || (isChecked == null)){
        //console.log('Removing ' + forID);
        var link = document.getElementById(forID + "CSS");
        if (link) {
            document.head.removeChild(link);
        }
    }
    else if ((forID == null) || (forID == 'white')){
        var link = document.createElement('link');
        link.rel = 'stylesheet';
        link.href = bookURL + '/css/default.css';
        document.head.append(link);
    }
}


function delay(delayInms) {
return new Promise(resolve => {
setTimeout(() => {
  resolve(2);
}, delayInms);
});
}

function resetStorage(){
    var completedChapters = JSON.parse(localStorage.getItem("checkedChapters"));
    if ((completedChapters) && (completedChapters.length > 0)){
        localStorage.removeItem('checkedChapters');
    }

    //localStorage.clear();
    location.reload();
}

function allStorage() {

    var values = [],
        keys = Object.keys(localStorage),
        i = keys.length;

    while ( i-- ) {
        values.push( localStorage.getItem(keys[i]) );
    }

    return keys;
}

function updateArticleMargin(){
    var articleBody = document.getElementsByClassName('article-body');
    if (articleBody[0]) {
        articleBody[0].style.marginRight = '0px';
    }
}

function setSidebarActive(){//this sets it up
    var sections = document.querySelectorAll('.page-sidebar-list li a');//get all links to headings
    const scrollPosArray = [0];
    var headerHeight = document.getElementsByClassName('article-header')[0].clientHeight;
      // console.log('Height:'+headerHeight);
    for( var i = 0; i < sections.length; i++ ){//iterate through headings
        var currentSection = sections[i];
        var val = currentSection.getAttribute('href');//get the target for the link
        var linkInDocument = document.querySelector(val);
         var newOffset = linkInDocument.offsetTop - headerHeight;
        scrollPosArray.push(newOffset);
    }
    if (sections.length > 0) {
        var currentLink = sections[0];
        currentLink.parentElement.classList.remove("active-header");
        window.addEventListener("scroll", () => {
            var currentScrollPos = window.pageYOffset;
            for (var x = scrollPosArray.length; x > 0; x--){
                currentLink.parentElement.classList.remove("active-header");
                if (scrollPosArray[x] < currentScrollPos && currentScrollPos > scrollPosArray[x-1]){
                        sections[x-1].parentElement.classList.add("active-header");
                        currentLink = sections[x-1];
                        break;
                }
            }
        });
    }
    else{
        updateArticleMargin();
    }
}

function setCurrentPageLink(){
    var url = window.location.protocol + '//' + window.location.host + window.location.pathname;
    var navLinks = document.getElementsByTagName("a");
    var i=0;
    var currentPage = url[url.length - 1];
    for(i;i<navLinks.length;i++){
        var lb = navLinks[i].href;//.split("/");
        var theParent = navLinks[i].parentElement.parentElement;
        //console.log(theParent);
        //console.log(lb);
        if (lb == url){

            if (theParent.classList.contains('toc-subsection') || theParent.classList.contains('toc-section')) {
                navLinks[i].classList.add('current-link');
            }
        }
    }
}

//PROGRESS SLIDER
window.addEventListener('scroll', function(){
    var winScroll = document.body.scrollTop || document.documentElement.scrollTop;
  var height = document.documentElement.scrollHeight - document.documentElement.clientHeight;
  var scrolled = (winScroll / height) * 100;
  const progressBar = document.getElementById("progress");
  if (progressBar){
    progressBar.style.width = scrolled + "%";
  }
});


function toggleHidden(el){
    if ((el.classList.contains('fa-comment-alt') == true) || (el.classList.contains('fa-times') == true)){
        $feedbackForm = document.getElementsByClassName('custom-feedbackform')[0];
        $feedbackForm.classList.toggle('hidden');
    }
    else if (el.firstChild.classList.contains('fa-bars') == true){
        TOC = document.getElementsByClassName('left-toc')[0];
        //TOC.classList.remove('disable-css-transitions')
       TOC.classList.toggle('hidden-toc');
       article = document.getElementsByClassName('article')[0];
       //article.classList.remove('disable-css-transitions')
       article.classList.toggle('article-margin');
       headerbar = document.getElementsByClassName('header-bar')[0];
      //headerbar.classList.remove('disable-css-transitions')
       //headerbar.classList.toggle('banner-padding');
       if (article.classList.contains('article-margin')){
            localStorage.setItem('TOC-hidden', true);
       }
       else{
            localStorage.setItem('TOC-hidden', false);
       }
       console.log('Saving Hidden Status: ' + localStorage.getItem('TOC-hidden'));

    }
}

var elem = document.documentElement;
function toggleFullscreen(el) {
    //console.log(el);
    if (el.classList.contains('fa-expand') == true){
        //Open
        if (elem.requestFullscreen) {
            elem.requestFullscreen();
        } else if (elem.webkitRequestFullscreen) { /* Safari */
            elem.webkitRequestFullscreen();
        } else if (elem.msRequestFullscreen) { /* IE11 */
            elem.msRequestFullscreen();
        }
        //console.log('Opening Full Screen');
        $collapse = document.getElementsByClassName('fa-compress')[0];
        $collapse.classList.remove('hidden');
        el.classList.add('hidden');
    }
    else{
    //Close
    if (document.exitFullscreen) {
        document.exitFullscreen();
      } else if (document.webkitExitFullscreen) { /* Safari */
        document.webkitExitFullscreen();
      } else if (document.msExitFullscreen) { /* IE11 */
        document.msExitFullscreen();
      }
     // console.log('Closing Full Screen');
    $expand = document.getElementsByClassName('fa-expand')[0];
    $expand.classList.remove('hidden');
    el.classList.add('hidden');
    }
  }

  function tappedprintbutton(){
      console.log('Print Tapped');
      if (navigator.userAgent.includes('Chrome')) {
        // the user is  running Chrome
        alert("Hi there!\nFor some reason, the Chrome browser doesn't like in-page print buttons right now.\nIf you're using Chrome, just use CMD-P (Mac) or CTRL-P (Windows).");
      }
      else{
          window.print();
      }
  }