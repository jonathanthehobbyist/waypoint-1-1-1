<aside id="sidebar-tableofcontents" class="sidebar">
		<script type="text/javascript">
		
	//remove links from comments
	//iterate through list


    /*
    *
    *
    *
    */

	function FULinkBots() {
	    
	    //get list of all URLs on Comments
	    var getUrls = document.getElementsByClassName('url');
	    
	    for (h=0; h<getUrls.length; h++) {
	        var indivAhref = getUrls[h];
	        /* come back to this
	        var commentAuthor = indivAhref.childNodes[1].nodeValue.innerHTML;
	        
	        if (commentAuthor.innerText == "Anne-Marie") {
	            h++;
	        }
	        else {
	        */
	       //console.log(indivAhref);
	       indivAhref.removeAttribute("href");
	       indivAhref.style.textDecoration = "none";
	    } //end for
	}//end FULinkBots
	FULinkBots();

    /*
    *    SOMETHING
    */

	function generateTOC() {
		 var toc_list = document.getElementsByTagName('h2');
		 const list = document.createElement('ol');
		 
		 // Create the new div elements
        let divElement = document.createElement('div');
        divElement.className = 'toc-container';
        divElement.id = 'toc';
        
        var content = document.createElement('div');
        content.className = "content";
        content.innerHTML = "Contents";
        
        let wrapElement = document.createElement('div');
        wrapElement.className = 'list-wrapper';
        
         // Get the parent div [PD] where you want to insert the new element
        
        if (window.innerWidth < 960) {
            //if the viewport is small, load to article

            //how could this work with pages?




            var parentDiv = document.querySelector('.post-inner');
            //console.log(parentDiv);
        } else {
            // if the viewpoert is larger, load to site-content
            //console.log( "larger than 960" + window.innerWidth);
            var parentDiv = document.querySelector('#site-content');
        }
    

        if (parentDiv) {
             // If the parent div PD has a first child, insert the new div before that first child
            if (parentDiv.firstChild) {
                parentDiv.insertBefore(divElement, parentDiv.firstChild);
                 //parentDiv.insertAdjacentElement('beforeend', divElement);
            } else {
                // If the parent div (PD) doesn't have any children, you can simply append the new div
               parentDiv.appendChild(divElement);
                // refElem.insertAdjacentElement('beforeend', myElem);
                //parentDiv.insertAdjacentElement('beforeend', divElement);
            }
        }
        
        //what does this do?

        // Fetch the newly created parent div where you want to insert the new element
        let tocDiv = document.querySelector('.toc-container');
        
        if (tocDiv) {
             // If the parent div has a first child, insert the new div before that first child
            if (tocDiv.firstChild) {
                 tocDiv.insertBefore(list, tocDiv.firstChild);
            } else {
                // If the parent div doesn't have any children, you can simply append the new div
                tocDiv.appendChild(wrapElement);
                //tocDiv.appendChild(content);
                //tocDiv.appendChild(list);
            }
        }
        
        let wrapDiv = document.querySelector('.list-wrapper');
        
        if (wrapDiv) {
            wrapDiv.appendChild(content);
            wrapDiv.appendChild(list);
        }

        //what does this do?

        for (i=0; i<toc_list.length; i++) {
            var indiv_h2 = toc_list[i];
            
            var toc_public = indiv_h2.innerText;
            //used innerText instead of innerHTML 12-7-23
            //public-facing links
         
            var str = indiv_h2.innerText;
            //used innerText instead of innerHTML 12-7-23
            str = str.replace(/^\s/g, ''); //matches any space at the beginning of an input
            str = str.replace(/\s+/g, '-'); //matches 1 or more spaces and converts to a dash
            str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&+]/g, '').toLowerCase(); //matches 
            //takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase
            //REGEX https://www.cl.cam.ac.uk/~mgk25/ucs/quotes.html
            //REGEX rules https://www3.ntu.edu.sg/home/ehchua/programming/howto/Regexe.html
            
            //console.log("after cleaning" + " " + str)
            // Assign a unique ID to the h2 tag based on its position
            indiv_h2.id = str;
            
            // Create a list item and link for each h2
            
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = "#" + str;
            link.innerHTML = toc_public;

            if( str.split("-").includes("thriving") || str.split("-").includes("anne")) {
                //nothing
                } else {
                //something
            listItem.appendChild(link);
            list.appendChild(listItem);
            }
            
            //logging
            //console.log(str);
            //console.log(toc_public)
        }
	} 
  
    //right now this works only on preview pages
	<?php if ( is_preview() && ! is_page_template( 'templates/template-pillar-content.php' ) || is_page_template( 'templates/template-tableofcontents.php' )) { ?>
        generateTOC();
   <?php } ?>
	
	/* scrollspy
	https://www.bram.us/2020/01/10/smooth-scrolling-sticky-scrollspy-navigation/
	https://dakotaleemartinez.com/tutorials/how-to-add-active-highlight-to-table-of-contents/
	https://stackoverflow.com/questions/25553187/sticky-div-on-scroll-with-plain-javascript
	 https://gomakethings.com/an-introduction-to-the-vanilla-js-intersection-observer-api/
    https://gomakethings.com/options-settings-and-approaches-with-the-vanilla-js-intersection-observer-api/
     https://stackoverflow.com/questions/1983648/replace-spaces-with-dashes-and-make-all-letters-lower-case
	*/
	
	window.addEventListener('scroll', function() {
    // If scrolled to the very top
    if (window.scrollY === 0) {
        // Find all active menu items and remove the 'active' class
        document.querySelectorAll('.list-wrapper li.active').forEach(item => {
            item.classList.remove('active');
            });
        }
    });

    function ready() {
         var box = document.getElementById('toc'),
        top = box.offsetTop;

        function scroll(event) {
            var y = document['documentElement' || 'body'].scrollTop;

            if (y >= top) box.classList.add('stick');
            else box.classList.remove('stick');

        }

            window.addEventListener('scroll', scroll);
    }

    if (document.readyState == 'complete' || document.readyState == 'loading') {
        ready();
    } else {
        //window.addEventListener('DOMContentLoaded', ready);
        document.addEventListener("DOMContentLoaded", function() {
        // Code to run after the DOM is loaded
        ready();
        
        });
    }
    

let observer;

function setupIntersectionObserver() {
  const tocLinks = document.querySelectorAll('.toc-container li a');
  const sections = Array.from(tocLinks).map(link => document.querySelector(link.getAttribute('href')));

  // Callback function to handle the intersections
  const handleIntersect = (entries, observer) => {
    entries.forEach(entry => {
      if (entry.isIntersecting) {
        // Clear previous active list items
        const tocListItems = document.querySelectorAll('.toc-container li');
        tocListItems.forEach(li => li.classList.remove('active'));

        const activeLink = document.querySelector(`.toc-container li a[href="#${entry.target.id}"]`);
        if (activeLink && activeLink.parentElement.tagName.toLowerCase() === 'li') {
          activeLink.parentElement.classList.add('active');
        }
      }
    });
  };

  // Set up the observer
  const options = {
    rootMargin: '0px 0px -80% 0px', // Adjust this value if you want to highlight a TOC list item earlier or later
    threshold: 0
  };

  observer = new IntersectionObserver(handleIntersect, options);

  sections.forEach(section => observer.observe(section));
}

// Call the function to set it up
setupIntersectionObserver();

        </script>
</aside>