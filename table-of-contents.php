<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 */

// Activate plugin
/*
* from https://developer.wordpress.org/plugins/plugin-basics/activation-deactivation-hooks/
* On activate, download files to proper directories
*
*/

// Activation functions

function waypoint826_enqueue_styles() {
    // Register the style
    wp_enqueue_style(
        'waypoint-style', // Handle for the stylesheet
        plugin_dir_url(__FILE__) . 'css/waypoint-style.css', // Path to the stylesheet
        array(), // Dependencies (if any)
        '1.0.0', // Version number (optional)
        'all' // Media type (optional, e.g., 'all', 'screen', 'print')
    );
}






function waypoint826_place_files() {

	//function blah() copies files from the plugin directory into Wordpress core

	//define files to be inserted from plugin folder
	$source_template = plugin_dir_path(__FILE__) . 'templates/template-tableofcontents.php';

	//define destination path
	$destination_template = get_stylesheet_directory() . '/templates/template-tableofcontents.php';

	// Check if the template already exists in the theme directory
    if (!file_exists($destination_template)) {

        // Copy the file from the plugin folder to the theme folder
        copy($source_template, $destination_template);
    }
}

function waypoint826_define_paths() {

    //define('table_of_contents_dir', plugin_dir_path(__FILE__));
}


// Activation calls

function waypoint826_activate () {
	//Trigger function to copy files
	//waypoint826_place_files();
	//waypoint826_define_paths();
}

register_activation_hook(__FILE__, 'waypoint826_activate' );

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');

//add_action( 'widgets_init', 'waypoint826_register' );



// Deactivate plugin
/*
* On deactivate, delete files from directories - clean way to handle this
*
*
*/

function waypoint826_deactivate() {
	// Remove files previously added - be damn careful

	// Define path of file(s) to be removed
	//$will_delete = get_stylesheet_directory() . '/templates/template-tableofcontents.php';


	// Check if the template already exists in the theme directory
	// Will probably need a for loop eventually
    //if (file_exists($will_delete)) {

        // Copy the file from the plugin folder to the theme folder
        //unlink($will_delete);
    //}

}

// Deactivation calls

register_deactivation_hook(__FILE__,  'waypoint826_deactivate' );





    //Global variable
    //$plugin_template_path = plugins_url ('templates/singlar-tableofcontents.php',__FILE__);
    //define('table_of_contents_dir', plugin_dir_path(__FILE__));

        //DELETE by 9.01.24

    //adding other files, will need the conditional to check if they exist
   // $source_singular = plugin_dir_path(__FILE__) . 'templates/singular-tableofcontents.php';
   // $destination_singular = get_stylesheet_directory() . '/singular-tableofcontents.php';
    //copy($source_singular, $destination_singular);

	//adding other files, will need the conditional to check if they exist
    //$source_sidebar = plugin_dir_path(__FILE__) . '/sidebar-tableofcontents.php';
    //$destination_sidebar = get_stylesheet_directory() . '/sidebar-tableofcontents.php';
   // copy($source_sidebar, $destination_sidebar);




// Run plugin



function waypoint826_run() {
    // Your custom code here
    if (is_page()) {
    ?>
    <script>
    document.addEventListener('DOMContentLoaded', function() {
        // console.log('This is the home page');

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        mainContainer.id = 'waypoint826-primary-container';
        //console.log(mainContainer); can delete eventually

        // Append the main waypoint container to a DIV element on the page
        var parentDiv = document.querySelector('.main-wrapper');
        parentDiv.appendChild(mainContainer);
        //console.log(parentDiv); can delete eventually

        

        // Create the list of h2, h3, h4

        // Init elements to hold list of h4s
        var headings = document.querySelectorAll("h4");
        // Could be more...
        // var headings = document.querySelectorAll("h2, h3, h4");
        const list = document.createElement('ol');
        list.classList.add('list-wrapper');

        for (i=0; i<headings.length; i++) {

            /*
            *  LEARNING
            *  var variables are not block scoped, are accessible outside of {}
            *  meaning they are accessible only within a function, but for example are accessible outside of a for loop {}
            *
            *  let variables are block scoped (only relevant within the 'block': {} )
            * 
            *  const variables are block scoped
            *
            */


            // Tests to see if there's a span element inside the h2, h3, h4
            if(headings[i].getElementsByTagName('span')[0]) {

                var listOfH2InnerText = headings[i].getElementsByTagName('span')[0];

            } else {

             continue;

            }
            // console.log(listOfH2InnerText);

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
           var innerSpan = listOfH2InnerText.innerText;

           // Cleans up the string to make it into a usable class name / on-page anchor link
           var str = listOfH2InnerText.innerText;
           str = str.replace(/^\s/g, ''); //matches any space at the beginning of an input
           str = str.replace(/\s+/g, '-'); //matches 1 or more spaces and converts to a dash
           str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&+]/g, '').toLowerCase(); //matches 
           // Takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase

           //console.log(str);

           // Assign a unique ID to the h2, h3, h4 tag based on its position
            listOfH2InnerText.id = str;
            
            // Create a list item and link for each h2, h3, h4
            
            const listItem = document.createElement('li');
            const link = document.createElement('a');
            link.href = "#" + str;
            link.innerHTML = innerSpan;

            console.log(innerSpan);

            listItem.appendChild(link);
            list.appendChild(listItem);
        }

        // Now we need to take the list generated, put it into an ol (ordered list) and insert it into the DIV

        /*
        *
        *  Structure of the DIV to hold ol, li
        *
        *  waypoint826-main
        *  |
        *  └--ol
        *    |
        *    |--li 
        *    |  |
        *    |  └-- href, InnerHTML
        *    |
        *    └--li etc
        *
        */

        // Append ol list to waypoint826-main, append li to ol

        // Fetch the newly created parent div where you want to insert the new element
        //let tocDiv = document.querySelector('.waypoint826-main');
        
        if (mainContainer) {
             // If parent div has first child, insert mainContainer before first child
            if (mainContainer.firstChild) {
                 mainContainer.insertBefore(list, mainContainer.firstChild);
            } else {
                // If mainContainer has 0 children, append
                mainContainer.appendChild(list);
                //tocDiv.appendChild(content);
                //tocDiv.appendChild(list);
            }
        }

        /*
        *
        *  I need to find the left bounding box of the element that mainContainer should align to on its right side
        *  -- That isn't its parent. That's actually the H2 from above
        */

        function positionMainContainer() {
          
            // Optional check if DOM is loaded

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', function() {
                    //console.log('DOM is fully loaded and parsed');
                    // Your code here
                });
            } else {
                //console.log('DOM is already ready');
                // Your code here
            }

            /*
            *   CHEAT
            *   I'm using non-programmatic knowledge to offet the mainContainer
            *   I'm using the margin value of limit-width, which won't be standard at all
            *   Cheat is marginLeftValue
            */

            // Primary colors


            // .main-container .row-container

            const contentElement = document.querySelector('.limit-width');
            // Get the computed styles for the contentElement
            const computedStyle = window.getComputedStyle(contentElement);
            // Access the left margin
            const marginLeft = computedStyle.marginLeft;
            const marginTop = computedStyle.marginTop;

            // Computer style returns a string not a number
            //console.log('width:', widthOfContent);

            const marginLeftValue = parseFloat(computedStyle.marginLeft);
            var marginTopValue = parseFloat(computedStyle.marginTop);
            console.log(marginTopValue);
         
           

            //Resize content
         

            // NEED NOTE HERE
            var relativeRect = headings[0].getBoundingClientRect();

            // Set the absolute div's position
            //mainContainer.style.top = relativeRect.top + 'px'; // Align vertically
            mainContainer.style.left = (marginLeftValue - mainContainer.offsetWidth) + 'px'; // Align the right edge to the left edge of the relative div
            // console.log(mainContainer.offsetWidth);
            //console.log(mainContainer.left);

        } // end positionMainContainer

        // Run the function when the page loads
        window.addEventListener('load', positionMainContainer);

        // Run the function whenever the window is resized
        //window.addEventListener('resize', positionMainContainer); need to set a delay timer

        window.addEventListener('resize', () => {
            setTimeout(() => {
                positionMainContainer();
                //const rect = headings[0].getBoundingClientRect();
                //console.log(rect);
            }, 0); // Adjust delay as needed
        });

        // Find all elements with margin 0 auto and shrink + push to the left by some px

        // First, find all elems and add them to an array

        function findAutoMarginElements() {
            const allElements = document.querySelectorAll('*');
            var autoMarginElements = [];

                allElements.forEach(element => {
                    const computedStyle = window.getComputedStyle(element);
                    const marginLeft = computedStyle.marginLeft;
                    const marginRight = computedStyle.marginRight;
                    const marginTop = computedStyle.marginTop;
                    const marginBottom = computedStyle.marginBottom;

                    if (marginLeft === 'auto' && marginRight === 'auto') {
                        autoMarginElements.push(element);
                    }
                });

                return autoMarginElements;
        }

        var elementsWithAutoMargin = findAutoMarginElements();
        //console.log(elementsWithAutoMargin.length);

        /*
        *   Second, parse the results and add 'position: relative', offset and new width
        *   
        *   To add the correct 'right' offset, we'll need to calculate the viewport size, add a new width
        *
        *
        */

        for (i=0; i<elementsWithAutoMargin.length; i++) {

            // Something
            // elementsWithAutoMargin[i].style.position = 'relative';
            // elementsWithAutoMargin[i].style.width = '80%';
            // console.log(elementsWithAutoMargin[i]);


        }

        // Removes .active class from li 
        window.addEventListener('scroll', function() {
        // If scrolled to the very top
        if (window.scrollY === 0) {
            //console.log('at top');
            // Find all active menu items and remove the 'active' class
            document.querySelectorAll('.list-wrapper li.active').forEach(item => {
                item.classList.remove('active');

                });
            }
        });

        // Waypoint needs to start below any menu and once it hits the top of the page, it needs to stick
        // Set the absolute div's position
       //mainContainer.style.top = marginTopValue + 'px';
        //console.log(marginTopValue);

        // Offset from Browswer window top
        const distanceFromTop = headings[0].getBoundingClientRect();
        //console.log(distanceFromTop.y);
        mainContainer.style.top = (distanceFromTop.y + 'px');

        var box = document.getElementById('waypoint826-primary-container'),
        top = box.offsetTop;
        //console.log(top);

        window.addEventListener('scroll', function(event) {
            //console.log('Page scrolled!');

            var y = document.documentElement.scrollTop || document.body.scrollTop;
            console.log(y);

            if ( (y - 200) >= top) { 
                box.classList.add('stick');
            } else { 
                box.classList.remove('stick');
            }

        });






        // chatGPT might have a better solution to the observer










        /*
        *  
        *   
        *   OBSERVER
        *
        *
        */

        let observer;

        function setupIntersectionObserver() {
            // block-scoped variable, lists all ol li a
            const tocLinks = document.querySelectorAll('.list-wrapper li a');
            console.log(tocLinks);

            // block-scoped variable creates an array of
           // const listOfLinks = Array.from(tocLinks).map(link => document.querySelector(link.getAttribute('href')));

            const listOfLinks = [...tocLinks].map(link => document.querySelector(link.getAttribute('href')));

            console.log(listOfLinks);

            // Callback function to handle the intersections
            const handleIntersect = (entries, observer) => {

                // This?
                entries.forEach(entry => {
                    if (entry.isIntersecting) {
                    // Clear previous active list items
                    const tocListItems = document.querySelectorAll('.list-wrapper li');
                    tocListItems.forEach(li => li.classList.remove('active'));

                    const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);
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

          listOfLinks.forEach(listOfLinks => observer.observe(listOfLinks));
        } // END setupIntersectionObserver

        // Call the function to set it up
        setupIntersectionObserver();




    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');






