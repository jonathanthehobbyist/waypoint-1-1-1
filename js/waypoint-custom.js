 
document.addEventListener('DOMContentLoaded', function() {



    /*  ------  USER CONFIGURABLE, FROM INDIVIDUAL POSTS  ----------  */

    // Change into a number    
    let wph2 = parseFloat(myScriptData.waypointH2);
    //let waypointH3 = <?php echo json_encode($checkbox_value_H3); ?>;
    let wph3 = parseFloat(myScriptData.waypointH3);
    //var waypointH4 = <?php echo json_encode($checkbox_value_H4); ?>;
    let wph4 = parseFloat(myScriptData.waypointH4);
    //var waypointH5 = <?php echo json_encode($checkbox_value_H5); ?>;
    let wph5 = parseFloat(myScriptData.waypointH5);




    /*  ----------- INPUT CLEANUP  ----------  */

    // Cleans up HEX colors
    function waypointHandleHashDot(element) {

        // Cleans the passsed 'element'

        const elementHasHash = /#/;
        const elementHasDot = /\./;

        // Is real
        if ( typeof element !== 'undefined' && element != null && element.length >= 2) { // Passes first test

            // Handles diff scenarios
            if (elementHasHash.test(element)) { // has #
                element = String(element).replace('#', '');
            } else if (elementHasDot.test(element)) { // has .
                element = String(element).replace('.', '');
            } else { //neither, just return element
                return element;
            }

            // Scrubs input of any upwanted characters
            // Might be better to do a passthrough of only [a-z][A-Z][1234567890]
            element = element.replace(/[?\u201c\u201d!\',’>\:\;\=<_~`/"\(\)&$+%^@*]/g, '');

        } else {
            return;
        }
        return element;
    }


    
    /*  ----------- CREATE WAYPOINT CONTAINTER ----------  */

    // Create the main container to hold the waypoint table of contents
    let mainContainer = document.createElement('div');
    mainContainer.className = 'waypoint826-main';
    mainContainer.id = 'waypoint826-primary-container';

    // Add ?
    if (typeof myScriptData.waypointFieldAddTo !== 'undefined' && myScriptData.waypointFieldAddTo != null){

        // Clean up
        const waypointFieldAdd = waypointHandleHashDot(myScriptData.waypointFieldAddTo);

        var entirePage = document.querySelector('.' + waypointFieldAdd);
        entirePage.appendChild(mainContainer);
    }



    /*  ------  USER CONFIGURABLE  ----------  */

    // USER CONFIGURE - which of the h2, h3, h4, h5 gets passed
    // Create array
    let waypointArr = [];

    //push into array if the checkbox is checked ( == 1)
    if (wph2 == '1') waypointArr.push("h2");
    if (wph3 == '1') waypointArr.push("h3");
    if (wph4 == '1') waypointArr.push("h4");
    if (wph5 == '1') waypointArr.push("h5");

    // Create the list h2,h3,h4,h5 based on user preference
    var headings = document.querySelectorAll(waypointArr.join(", "));

    // Iterate over waypointArr instead of headings
    waypointArr.forEach(function(selector, index) {
        var heading = document.querySelector(selector); // Get the first matching element for this selector
        if (heading) {
            var newValue = 'newValue_' + index; // Example new value
        } else {
        }
    });

    // Array to hold the associations
    var associatedElements = [];

    // Iterate over each heading and find its corresponding selector
    headings.forEach(function(heading) {
        // Iterate over the selectors to find which one matches the current heading
        waypointArr.forEach(function(selector) {
            // Check if the heading matches the selector (e.g., by tag name)
            if (heading.matches(selector)) {
                associatedElements.push({
                    selector: selector,
                    element: heading
                });
            }
        });
    });

    // 
    const list = document.createElement('ol');
    list.classList.add('list-wrapper');

    // Create a header or title area
    var contentParagraph = document.createElement('p');
    contentParagraph.className = "content";
    contentParagraph.innerHTML = "Contents";

    // Scroll to top
    var scrollToTopArea = document.createElement('p');
    scrollToTopArea.className = "scroll-to-top";
    scrollToTopArea.innerHTML = "Scroll to top";

    scrollToTopArea.onclick = function () {
        window.scrollTo({
            top: 0,
            behavior: 'smooth' // Smooth scroll
        });
    }

    // NOT currently used but keep

    // the map method creates a new array populated with the results of calling a provided function on every element in the calling array
    // 
    let valuesOfHeadings = waypointArr.map(function(heading) {
        return parseInt(heading.replace('h', ''), 10);
    });



    /*  ----------- SORT H2 etc FROM SMALLEST TO LARGEST ----------  */

    // Find the arraylength
    var numberOfHeadings = valuesOfHeadings.length;

    // Find the highest level H number (smallest number)
    var topLevel = Math.min(...valuesOfHeadings);

    // Find the loest level H number (highest number)
    var bottomLevel = Math.max(...valuesOfHeadings);

    // SORT H2, H3 etc. if every H is selected, h2, h3, h4, h5
    if (numberOfHeadings == 4) {
        valuesOfHeadings.sort(function(a, b) {
            return a - b;
        });
    }



    /*  ----------- BASE MARGIN ----------  */



    // Cascades to other settings
    var baseMargin = 8;




    /*  ----------- GLOBAL USER CONFIG OPTIONS ----------  */

    // On left or right of the screen

    // Validity
    if ( typeof myScriptData.waypointLeftOrRight !== 'undefined' && myScriptData.waypointLeftOrRight != null) {

        // 1 = Right 0 = Left
        if (myScriptData.waypointLeftOrRight == 'Right') {

            var waypointPosLeftOrRight = '1';
        } else {
            // Defaults to Left
            var waypointPosLeftOrRight = '0';
        }
    }


    
    /*  ----------- BUILDING THE LIST CONTENT ----------  */

    associatedElements.forEach(function(item) {

        var selector = item.selector; // The selector (h2, h3, etc)
        var element = item.element; // The DOM element

        // Duplicates how the h2, h3, h4 is written - 'dirty version'
        // Currently keeps the exact formatting IE uppercase, all caps etc. 
       var innerContent = element.innerText;

       // Cleans up the string to make it into a usable class name / on-page anchor link
       var str = innerContent;
       str = str.replace(/^\s/g, ''); //removes any space at the beginning of an input
       str = str.replace(/\s+/g, '-'); //converts 1 or more spaces to a dash
       str = str.replace(/[1234567890?\u201c\u201d.!\#',’>\:\;\=<_~`/"\(\)&$+%^@*]/g, '').toLowerCase(); //matches 
       // Takes h2 innerHTML, replaces spaces (1) with dashes, (2) replaces all other banned digitals with nothing, and (3)makes it lowercase

       // First, define the list of words to exclude
       const excludeWords = /(privacy|security|gdpr)/i; // i makes it case-insensitive

       // Next, look at the parents to see if its a GDPR or privacy notice
       const parentLevelOne = element.parentElement
       const parentLevelTwo = parentLevelOne.parentElement;
       const parentLevelThree = parentLevelTwo.parentElement;

       const parentOneClass = parentLevelOne.className;
       const parentTwoClass = parentLevelTwo.className;
       const parentThreeClass = parentLevelThree.className;

       // now exclude 
       if ( excludeWords.test(parentOneClass) || excludeWords.test(parentTwoClass) || excludeWords.test(parentThreeClass) ) {
        return;
       }

       // Assign a unique ID to the h2, h3, h4 tag based on its position
        element.id = str;
        
        // Create a list item and link for each h2, h3, h4
        const listItem = document.createElement('li');
        const link = document.createElement('a');

        // defining the waypoint a links
        link.href = "#" + str;
        link.innerHTML = innerContent.toLowerCase();

        // Add a class that says whether this came from an h2, h3, h4, or h5 elem
        listItem.classList.add(item.selector + '_selector');

        // getting rid of the 'h' in 'h2' so we can do math comparisons on them
        var breakDownSelector = parseInt(selector.replace('h', ''),10);



        /*  ----------- LOGIC FOR LEFT MARGIN FOR h2, h3, h4, h5 ----------  */

        switch (numberOfHeadings) {

                case 1:
                    break;

                case 2:
                    // Highest number IE bottomLevel gets a margin of 8px assigned
                    // Logic to if: when breakdownselector equals bottomlevle, set the leftmargin of the li to baseMargin (8)
                    if(breakDownSelector == bottomLevel) listItem.style.marginLeft = (baseMargin * 1) + "px";
                    break;
                    
                case 3:
                    // The topLevel - 1 (middle level) gets a base*1 margin
                    if(breakDownSelector != bottomLevel && breakDownSelector != topLevel) { 
                        listItem.style.marginLeft = (baseMargin * 1) + "px";
                    } else if (selector == bottomLevel) {
                    // The topLevel - 2 gets a base*2 margin 
                     listItem.style.marginLeft = (baseMargin * 2) + "px";
                    }
                    break;

                case 4:

                    if(breakDownSelector == valuesOfHeadings[1]) { //topLevel -1
                        listItem.style.marginLeft = (baseMargin * 1) + "px";
                    } else if (breakDownSelector == valuesOfHeadings[2]) { //topLevel -2
                        listItem.style.marginLeft = (baseMargin * 2) + "px";
                    } else if (breakDownSelector == bottomLevel) { //topLeft -3
                     listItem.style.marginLeft = (baseMargin * 3) + "px";
                    }
                    break;
        }

        // append
        listItem.appendChild(link);
        list.appendChild(listItem);

    }); //end for loop

    // Append Waypoint826 
    // Append title area is user selects to
    if (typeof mainContainer !== 'undefined' && mainContainer != null) {
         // If parent div has first child, insert mainContainer before first child
        if (mainContainer.firstChild) {
             mainContainer.insertBefore(list, mainContainer.firstChild);

        } else {
            // If mainContainer has 0 children, append
            mainContainer.appendChild(list);
        }

        if (typeof myScriptData.waypointMenuTitleOnOff !== 'undefined' && myScriptData.waypointMenuTitleOnOff == 'visible') {

            // If user sets title area to visible, insert title area
            mainContainer.insertBefore(contentParagraph, mainContainer.firstChild);
        }

        // This var could be user configurable
        const hasScrollToTop = true;

        if ( hasScrollToTop == true) {
            mainContainer.appendChild(scrollToTopArea);
        }
    }

    function findPositionedParent(element) {
        // Start with the parent of the element
        let parent = mainContainer.parentElement;

        // Traverse up the DOM tree
        while (parent) {
            // Get the computed style of the parent
            const style = window.getComputedStyle(parent);

            // Check if the parent has a position other than 'static'
            if (style.position !== 'static') {
                return parent; // This is the nearest positioned parent
            }

            // Move to the next parent element
            parent = parent.parentElement;
        }

        // If no positioned parent is found, return null
        return null;
    } // END FINDPOSITIONEDPARENT()

    // Get actual value for waypoint
    var absoluteElement = document.querySelector('.waypoint826-main'); 
    var positionedParent = findPositionedParent(absoluteElement);

    if ( typeof positionedParent !== 'undefined' && positionedParent != null) {
        // True distance from the left viewport edge

        if ( waypointPosLeftOrRight == 1) {

            // Distance from the viewport's left edge to the element's right edge
            var WaypointParentPos = positionedParent.getBoundingClientRect().right;
        } else {

            // Original
            // Distance from viewport left edge to element left edge
            var WaypointParentPos = positionedParent.getBoundingClientRect().left;
        }
    }

    // 
    if (WaypointParentPos){
        //
        var adjustMargin = (parseFloat(WaypointParentPos));
    } else {
        // 
        var adjustMargin = 0;
    }



    /*  -------------------- USER CONFIGURABLE --------------------  */

    // Remove whitespace from both ends of
    alignToElement = myScriptData.waypointFieldAlignToElement.trim();

    var spaceForWaypoint;

    function calcWaypointSpaceNeeded() {

        // waypointFieldAlignToElement might have more than one class so, replace spaces with dots
        const checkAndCombineField = "." + alignToElement.replace(/ /g, '.'); // replaces spaces with dots
        //
        var contentArea = document.querySelector(checkAndCombineField);

        // get the left position of the user-defined content block
        if (contentArea) {
            // get the width
            var elemContentWidth = window.getComputedStyle(contentArea).width;
            // replace px
            var cleanElemContentWidth = elemContentWidth.replace(/px/g, '');
            // get the contentLeft left.pos
            var contentLeftEdge = contentArea.getBoundingClientRect().left;
            var contentRightEdge = contentArea.getBoundingClientRect().right;
            //console.log('contentLeftEdge', contentLeftEdge);
            //console.log('contentRightEdge', contentRightEdge);
        }

        var viewportWidth = window.innerWidth;
        // Get waypoint
        var elementWaypoint = document.querySelector('.waypoint826-main');
        // Get width
        var elemWaypointWidth = window.getComputedStyle(elementWaypoint).width;
        // Remove 'px' REGEX
        var cleanElemWaypointWidth = elemWaypointWidth.replace(/px/g, '');
        // Calculating space on the margins around the content
        // What if used hasn't defined contentArea? 
        var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);
        // Waypoint width as a number
        let waypointSpaceNeeded = (Number(cleanElemWaypointWidth));
        // Send the calc'd values back to the function
        return { value1: spaceForWaypoint, value2: contentLeftEdge, value3: contentRightEdge  }
        // contentArea is a user configurable area 

    }



    /*--- USER CONFIGS ---*/

    // Sets border colors
    if (typeof myScriptData.waypointBorderColor !== 'undefined' && myScriptData.waypointBorderColor != null) {

        // Calls cleaning function
        var waypointBorderColorClean = waypointHandleHashDot(myScriptData.waypointBorderColor);

        // Used to be set in setGlobalSettings
        waypointBorderColorClean = '#' + waypointBorderColorClean;

        // Adjust border on R or L
        if ( waypointPosLeftOrRight == 1) { // Right
            // Border goes on left
            mainContainer.style.borderRight = 'none';
            mainContainer.style.borderLeft = `1px solid ${waypointBorderColorClean}`;

        } else { // Left
            // Border goes on right
            mainContainer.style.borderRight = `1px solid ${waypointBorderColorClean}`;
            mainContainer.style.borderLeft = 'none';
        }

        // Sets border for 'page content' and 'scroll to top'
        contentParagraph.style.borderBottom = `1px solid ${waypointBorderColorClean}`;
        scrollToTopArea.style.borderTop = `1px solid ${waypointBorderColorClean}`; 

    }

    // Set Background color
    if (typeof myScriptData.bgValue !== 'undefined' && myScriptData.bgValue != null) {

        const setColor = waypointHandleHashDot(myScriptData.bgValue);
        setbgColor = '#' + setColor;
        mainContainer.style.backgroundColor = setbgColor;

    }

    // Set text color
    if (typeof myScriptData.waypointTextColor !== 'undefined' && myScriptData.waypointTextColor != null) {

        // Calls element cleaning function
        const waypointTxtClr = waypointHandleHashDot(myScriptData.waypointTextColor);
        const setColorText = '#' + waypointTxtClr;
        const waypointTxtElem = document.querySelectorAll('.list-wrapper li a');

        // Iterate through list and set colors
        waypointTxtElem.forEach(item => {
            item.style.color = setColorText;
        });

    }

    // Set text size

    // Where is selected color? 



    /*----------- SET RIGHT-HAND POS  --------------*/

    // Set the right-hand position of the waypoint826 plugin
    function positionMainContainer() {

        const {value1, value2, value3} = calcWaypointSpaceNeeded();

        spaceForWaypoint = value1;
        // Left edge of content to left edge of viewport
        contentLeftEdge = value2;
        // Right edge of content to left edge of viewport
        contentRightEdge = value3;

        mainContainer.style.opacity = '0.2';

        /*  ----------- WINDOW RESIZE & HORZ. ALIGNMENT ----------  */

        /*  -----------  USER CONFIGURABLE  ----------  */

        // User can choose an element to align Waypoint to horizontally
        if ( alignToElement ) {

            // Class or ID
            // Remove spaces and add a dot '.' from the class or ID data passed from the user
            var alignElement = alignToElement.replace(/ /g, '.');

            // DOM element of class or ID 
            // .main-container .row-container
            const contentElement = document.querySelector('.' + alignElement);

            // Get the computed styles for the contentElement
            const computedStyle = window.getComputedStyle(contentElement);

            // Set transition style
            mainContainer.style.transition = 'opacity 0.5s ease-out, visibility 0.5s ease-out';

            /*  ----------- INITIAL LEFT POSITIONING ----------  */

            if ( spaceForWaypoint < 580) {

                mainContainer.style.display = 'none';

            } else if ( spaceForWaypoint > 620) {

                mainContainer.style.display = 'block';
                mainContainer.style.width = '230px';
                var offset = parseFloat(mainContainer.offsetWidth); 

                // L + R - Calc init left offset for waypoint
                var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5) + adjustMargin) + 'px';
                var rightAdjustCalc = 0 + 'px';

                // 
                if ( waypointPosLeftOrRight == 1) {
                    // right of elem to right of container
                    mainContainer.style.right = rightAdjustCalc;
                } else {
                    // left of elem to left of container
                    mainContainer.style.left = leftAdjustCalc;
                }

            } // Cut an else if and put it into notes

        } // END if ( alignToElement ) {

        // Start the pulse for 5 seconds
        startPulse(1500);

        /*  ----------- INIT POSITION TO TOP ----------  */


        // Cleaned HEX value
        if (typeof myScriptData.waypointBorderColor !== 'undefined') {
            // Calls HEX color cleaning function
            const waypointBorderColorClean = waypointHandleHashDot(myScriptData.waypointBorderColor);
        }

        // does waypointMasthead have a #
        if (typeof myScriptData.waypointMasthead !== 'undefined' && myScriptData.waypointMasthead != null && myScriptData.waypointMasthead !== '') {
            // Log if it matches the ID pattern
            // console.log("Masthead has an ID: ", myScriptData.waypointMasthead);

            // console.log('waypointElementIDName is getting');

            var waypointElementIDName = waypointHandleHashDot(myScriptData.waypointMasthead);
            //= String(myScriptData.waypointMasthead.replace('#', ''));
            var refToMasthead = document.getElementById(waypointElementIDName);

            // console.log('Found masthead by ID: ', refToMasthead);

        } else if (typeof myScriptData.waypointMasthead == 'undefined' || myScriptData.waypointMasthead.length < 3 || myScriptData.waypointMasthead.length > 7 || myScriptData.waypointMasthead == null) {

            // it *IS* undefined OR it's less than 3 characters
            var refToMasthead = undefined;
            const waypointFindBody = document.querySelector('body');
            waypointFindBody.appendChild(mainContainer);

        }

        // Default to height of the header element
        if (typeof refToMasthead !== 'undefined' && refToMasthead != null && refToMasthead !== '' && refToMasthead.length >= 3) {

            console.log('getting through');

            //get height of masthead object
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            // console.log('Height of masthead: ', distanceFromTop);
        } else {
            var distanceFromTop = 0;
            // console.error('refToMasthead is null or undefined');
            var waypointFindBody = document.querySelector('body');
            waypointFindBody.appendChild(mainContainer);
        }

        mainContainer.style.top = '0px';

        var waypointBox = document.getElementById('waypoint826-primary-container');
        let waypointTop = waypointBox.offsetTop;


        /*  ----------- SCROLL FUNCTION ----------  */

        // Initial position update
        updatePosition();

        // Check if myScriptData.waypointFieldAddTo exists
        if (typeof myScriptData.waypointFieldAddTo !== 'undefined' && myScriptData.waypointFieldAddTo != null) {

            // Clean up? 
            const waypointFieldAppendTo = waypointHandleHashDot(myScriptData.waypointFieldAddTo);

            var waypointAddToElement = document.querySelector('.' + waypointFieldAppendTo);
        } else {
           
        }

        window.addEventListener('scroll', function(event) {

            var waypointY = document.documentElement.scrollTop || document.body.scrollTop;

            if (typeof waypointAddToElement !== 'undefined') {
                var distanceFromTop = waypointAddToElement.getBoundingClientRect().top + window.scrollY;
               // console.log('Distance from top: ', distanceFromTop);
            } else {
               // console.error('waypointAddToElement is null or undefined');
            }

            if ((waypointY - distanceFromTop) >= waypointTop) { // sets the point of the scroll where mainContainer becomes fixed

                // stick mainContainer to the top of the viewport
                mainContainer.style.top = '0px';
               
            } else { // mainContainer need to act like a POS: ABSOL element, scrolling...

                updatePosition();

            }
        });

    } // end positionMainContainer

    /*  ----------- FOR POS:ABSOLUTE BEHAVIOR ----------  */

    function updatePosition() {

        // Call earlier function that calculates 1) spaceforwaypoint and 2) contentleftedge
        const {value1, value2, value3} = calcWaypointSpaceNeeded();
        spaceForWaypoint = value1;
        contentLeftEdge = value2;
        contentRightEdge = value3;

        // Get the bounding rectangle of the parent
        const parentRect = positionedParent.getBoundingClientRect().top;

        // Test, find, replace and create var menuHeight
        if (typeof myScriptData.waypointMasthead !== 'undefined' && myScriptData.waypointMasthead != null && typeof waypointElementIDName !== 'undefined' && myScriptData.waypointMasthead !== '') {

            // Clean up - removes any hashes or dots
            const waypiontMH = waypointHandleHashDot(myScriptData.waypointMasthead );

            var refToMasthead = document.getElementById(waypointElementIDName);

        } else {
            // console.error('Masthead element not found');
            const waypointFindBody = document.querySelector('body');
            waypointFindBody.appendChild(mainContainer);
        }

        // Default to height of the header element
        if (typeof refToMasthead !== 'undefined' && myScriptData.waypointMasthead !== '') {
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            // console.log('Height of masthead: ', distanceFromTop);
        } else {

            var distanceFromTop = 0;
            var waypointFindBody = document.querySelector('body');
            waypointFindBody.appendChild(mainContainer);

            // console.error('refToMasthead is null or undefined');
        }
    
        // console.log('distanceFromTop', distanceFromTop);

        if ( window.scrollY < distanceFromTop ){
            mainContainer.style.top = parentRect + 'px';
        } else if ( window.scrollY > distanceFromTop ) {
            //mainContainer.style.top = parentRect + 'px';
        }

        // console.log(parentRect.top);

        var offset = parseFloat(mainContainer.offsetWidth); // Number of pixels to offset

        // console.log('spaceForWaypoint', spaceForWaypoint);

        // Check how much screen real estate is left for waypoint to inhabit
        if ( spaceForWaypoint < 640) {

                // Waypoint displays NONE
                mainContainer.style.display = 'none';

            } else if ( spaceForWaypoint >= 640 && spaceForWaypoint < 700) {

                // Waypoint displays BLOCK, 200 width
                mainContainer.style.display = 'block';
                mainContainer.style.width = '210px';

                // Calc the left offset to give to 
                var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 3) + adjustMargin) + 'px';

                //Experimental
                var rightAdjustCalc = 0 + 'px';
                
                // Experimental
                if ( waypointPosLeftOrRight == 1) {
                    mainContainer.style.right = rightAdjustCalc;
                } else {
                    mainContainer.style.left = leftAdjustCalc;
                }
       
            } else if ( spaceForWaypoint >= 700 ) {

                // Waypoint displays BLOCK, 200 width
                mainContainer.style.display = 'block';
                mainContainer.style.width = '250px';

                // Calc the left offset to give to 
                var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5) + adjustMargin) + 'px';

                //Experimental
                var rightAdjustCalc = 0 + 'px';

                // Set left to calc
                
                // Experimental
                if ( waypointPosLeftOrRight == 1) {
                    mainContainer.style.right = rightAdjustCalc;
                } else {
                    mainContainer.style.left = leftAdjustCalc;
                }

            }// Cut an else if and put it into notes

            checkForBlackout();

    } // end updatePosition

    function checkForBlackout() {
        const parentRect = positionedParent.getBoundingClientRect().top;
        // console.log('window scroll Y: ', window.scrollY); 
        // console.log('parentRect', parentRect);

        // console.log('called during blackout');
    }

    // Pulse effect with JavaScript
    function startPulse(duration) {
        let isFading = false;
        let intervalId;

        // Start the interval to alternate opacity every 500ms
        intervalId = setInterval(() => {
            if (isFading) {
                mainContainer.style.opacity = '.5'; // Fully visible
            } else {
                mainContainer.style.opacity = '0.3'; // Semi-transparent
            }
            isFading = !isFading;
        }, 250); // Change opacity every 500ms

        // Stop the pulse effect after the specified duration
        setTimeout(() => {
            clearInterval(intervalId);
            mainContainer.style.opacity = '1'; // Reset to fully visible
        }, duration);
    }

    // Run the function when the page loads
    window.addEventListener('load', positionMainContainer);

    // Run the function whenever the window is resized

    function debounce(func, wait = 20, immediate = true) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            const later = function() {
                timeout = null;
                if (!immediate) func.apply(context, args);
            };
            const callNow = immediate && !timeout;
            clearTimeout(timeout);
            timeout = setTimeout(later, wait);
            if (callNow) func.apply(context, args);
        };
    }

    // Oberserver - creates effect where nav bolds when it crosses the boundary of its related h4
    let observer;
    let currentSection = null; // To keep track of the currently observed section

     function setupIntersectionObserver(onSectionChange) {
        // Disconnect existing observer if it exists
        if (observer) {
            observer.disconnect();
        }

        // Nodelist? of DOM elements
        const tocLinks = document.querySelectorAll('.list-wrapper li a');

        // Create an array of IDs by mapping the href attributes
        const sections = Array.from(tocLinks)
            .map(link => link.getAttribute('href').replace('#', '')) // Remove the '#' from href
            .filter(Boolean); // Ensure valid IDs

        // Callback function to handle the intersections
        const handleIntersect = (entries) => {
            entries.forEach(entry => {
                if (entry.isIntersecting) {
                    // Clear previous active list items
                    const tocListItems = document.querySelectorAll('.list-wrapper li');
                    tocListItems.forEach(li => li.classList.remove('active'));

                    const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);

                    if (activeLink) {
                        activeLink.parentElement.classList.add('active');
                    }

                    // Call the provided callback with the observed section
                    if (onSectionChange) {
                        onSectionChange(entry.target); // Pass the observed section element
                    }
                }
            });
            setGlobalSettings();
        };



        const options = {
            rootMargin: '-10px 0px 0px 0px', // Adjust the top margin
            threshold: 0.1 // Consider multiple thresholds
        };

        observer = new IntersectionObserver(handleIntersect, options);

        // Observe each section by their IDs
        sections.forEach(sectionId => {
            const section = document.getElementById(sectionId);
            if (section) {
                observer.observe(section);
            }
        });

        // Return the sections array
        return sections;
    } // END setupIntersectionObserver




    // BUG - scroll first, be in a middle of a section, then hit enter - sometimes it's not working
    // BUG - I"m having to click before the enter press works. Focus state?
    // BUG - on interactive-trivia, the enter key isn't working

    // 'Enter' keydown function
    window.addEventListener('keydown', function(event) {

        // Check if the key pressed is "Enter"
        if (event.key === 'Enter' || event.keyCode === 13) {

            // on Interactive Trivia, we're getting here...

            clearInterval(intervalID);

            // How many times function is called 
            var j = 0;

            let currentlyObserved = null; // Declare in the outer scope
            let lastObserved = null; // Track the last observed section

            // Callback function for setupIntersectionObserver
            const sections2 = setupIntersectionObserver((observedSection) => {

                // Update the variable when a section is observed
                currentlyObserved = observedSection.id; 
        
                // Only call handleSectionChange if it's a new observed section
                if (currentlyObserved !== lastObserved) {

                    // Update the last observed section
                    lastObserved = currentlyObserved; 
                    //console.log('lastObserved', lastObserved);

                    j++;
                    if ( j === 1) {

                        // Pass the var outside these brackets
                        handleSectionChange(currentlyObserved, j);
                    } 
                }
            }); // End sections2 = 
            
            function handleSectionChange(sectionId) {
                //console.log('section change called', j);
                //safely access currentlyObserverd as sectionId

                for (let i = 0; i< sections2.length; i++) {

                    const sectionsConv = sections2[i].toString();
                    const observedConv = sectionId.toString();
                    //sections2[i]
                    if ( sectionsConv == observedConv) {

                        // If there's a match, get the next section
                        const nextElem = document.getElementById(sections2[i + 1]);
                        // Scroll down
                        if (i < sections2.length - 1 && j === 1) {
                            //nextElem.scrollIntoView({ behavior: 'auto' });

                            //console.log('nextElem', nextElem);
                            nextElem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            // nextElem.focus();
                            // Increment number of calls after scrolled
                            j++;
                        } else if ( i = sections2.length -1 ) {
                            // Do we want the page to turn around? 
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth' // Smooth scroll
                                });
                            

                        } else  {
                            break;
                        }
                        //console.log('match',nextElem);
                        
                    } // end if
                } // end for
            } // End handleSectionChange  
        } // end eventKey if
    }); // end eventListener

    window.addEventListener('load', handleResize);

    function debounce(func, wait = 100) {
        let timeout;
        return function() {
            const context = this, args = arguments;
            clearTimeout(timeout);
            timeout = setTimeout(() => func.apply(context, args), wait);
        };
    }

    // Combine the functions into one debounced handler
    function handleResize() {
        /* document.addEventListener('load', function() {
            window.scrollTo({
                top: 0,
                behavior: 'smooth' // This makes the scroll smooth
            });
        });*/
        positionMainContainer();
        updatePosition();

        const handleSectionChange = (observedSection) => {
            // console.log("Currently Observed Section:", observedSection.id); // Logs the ID of the observed section
        };

        // Set up the observer with the callback
        const sections = setupIntersectionObserver(handleSectionChange);
    }

    /*  ----------- GLOBAL SETTINGS  ----------  */

    function setGlobalSettings() {

        // This really just needs to be called to reset the li a background

        // Remove backgrounds before setting active
        var allListItemsLi = document.querySelectorAll('.waypoint826-main li');
         allListItemsLi.forEach(function(item) {
            item.style.backgroundColor = 'transparent';
         });

        // Get the active class, put it into an array
        var activeSelection = document.querySelectorAll('.waypoint826-main li.active');

        if (activeSelection.length > 0) {
            // Apply the passed var

            // Need to clean this outside setGlobalSettings and then pass in
            var activeColor = '#' + myScriptData.bgColorValue;
            activeSelection[0].style.backgroundColor = activeColor;
        }

    }

    // Set interval for the bounce on the 'Press return to scroll down'
    let intervalID = setInterval(() => {

        // Try and find .waypoint-sc-scroll-down
        const waypointBounce = document.querySelector('.waypoint-sc-scroll-down');

        if (typeof waypointBounce !== 'undefined' && waypointBounce != null) {
            
            waypointBounce.classList.add('bounce');

            // Remove the bounce class after animation ends so it can be reapplied
            waypointBounce.addEventListener('animationend', () => {
                waypointBounce.classList.remove('bounce');
            });
        }
            
    }, 3000);
    

    window.addEventListener('resize', debounce(handleResize, 200));

});
    