<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
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

function waypoint826_place_files() {}

function waypoint826_define_paths() {}

function waypoint826_activate () {} // Activation calls

register_activation_hook(__FILE__, 'waypoint826_activate' );

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');

function waypoint826_deactivate() {}  // Deactivate plugin

register_deactivation_hook(__FILE__,  'waypoint826_deactivate' );

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

        // Append the main waypoint container to a DIV element on the page
        var parentDiv = document.querySelector('.main-wrapper');
        parentDiv.appendChild(mainContainer);

        // Create the list of h2 and/or h3 and/or h4
        var headings = document.querySelectorAll("h4");

        // var headings = document.querySelectorAll("h2, h3, h4");
        const list = document.createElement('ol');
        list.classList.add('list-wrapper');

        for (i=0; i<headings.length; i++) {

            // Tests to see if there's a span element inside the h2, h3, h4
            if(headings[i].getElementsByTagName('span')[0]) {

                var listOfH2InnerText = headings[i].getElementsByTagName('span')[0];

            } else {
                continue;
            }

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
           var innerSpan = listOfH2InnerText.innerText;
           console.log(innerSpan);

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

            // append
            listItem.appendChild(link);
            list.appendChild(listItem);

        } //end for loop

        // Fetch the newly created parent div where you want to insert the new element
        
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

        // Set the right-hand position of the waypoint826 plugin
        function positionMainContainer() {
          
            // .main-container .row-container
            const contentElement = document.querySelector('.limit-width');
            // Get the computed styles for the contentElement
            const computedStyle = window.getComputedStyle(contentElement);
            // Access the left margin
            const marginLeft = computedStyle.marginLeft;
            const marginTop = computedStyle.marginTop;

            // Computer style returns a string not a number

            const marginLeftValue = parseFloat(computedStyle.marginLeft);
            var marginTopValue = parseFloat(computedStyle.marginTop);
         
            //Resize content
         
            // NEED NOTE HERE
            var relativeRect = headings[0].getBoundingClientRect();

            // Set the absolute div's position
            //mainContainer.style.top = relativeRect.top + 'px'; // Align vertically
            mainContainer.style.left = (marginLeftValue - mainContainer.offsetWidth) + 'px'; // align R to L edge
            console.log(marginLeftValue);

        } // end positionMainContainer


        // Run the function when the page loads
        window.addEventListener('load', positionMainContainer);

        // Run the function whenever the window is resized

        window.addEventListener('resize', () => {
            setTimeout(() => {
                positionMainContainer();
                //const rect = headings[0].getBoundingClientRect();
                //console.log(rect);
            }, 0); // Adjust delay as needed
        });

        // Find all elements with margin 0 auto and shrink + push to the left by some px

        // First, find all elems and add them to an array
        // Experiment 8/27/24 may delete later
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

        // Offset from Browswer window top

        // const distanceFromTop = headings[0].getBoundingClientRect();  don't get rid of yet
        var menuHeight = document.querySelector('.row-menu');
        const distanceFromTop = menuHeight.getBoundingClientRect();
        //console.log(menuHeight);

        //console.log(distanceFromTop.y);
        mainContainer.style.top = (distanceFromTop.y + 130 + 'px');
        //console.log(distanceFromTop);



        var box = document.getElementById('waypoint826-primary-container'),
        top = box.offsetTop;

        window.addEventListener('scroll', function(event) {
            //console.log('Page scrolled!');

            var y = document.documentElement.scrollTop || document.body.scrollTop;
            //console.log(y);

            if ( (y - 80) >= top) { 
                box.classList.add('stick');
               
            } else { 
                box.classList.remove('stick');
            }

        });

        // Oberserver - creates effect where nav bolds when it crosses the boundary of its related h4
        let observer;

        function setupIntersectionObserver() {

            // array of links
            const tocLinks = document.querySelectorAll('.list-wrapper li a');
            //not sure
            const sections = Array.from(tocLinks).map(link => document.querySelector(link.getAttribute('href')));

          // Callback function to handle the intersections
          const handleIntersect = (entries, observer) => {
            entries.forEach(entry => {
              if (entry.isIntersecting) {
                // Clear previous active list items
                const tocListItems = document.querySelectorAll('.list-wrapper li');
                tocListItems.forEach(li => li.classList.remove('active'));

                const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);
                if (activeLink && activeLink.parentElement.tagName.toLowerCase() === 'li') {
                    //adds active class to .li
                  activeLink.parentElement.classList.add('active');
                }
              }
            });
          };

          // Set up the observer
          const options = {
            rootMargin: '0px 0px -50% 0px', // Adjust this value if you want to highlight a TOC list item earlier or later
            threshold: 0
          };

          observer = new IntersectionObserver(handleIntersect, options);

          sections.forEach(section => observer.observe(section));
        }

        // Call the function to set it up
        setupIntersectionObserver();

    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');






