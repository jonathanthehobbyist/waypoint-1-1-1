 
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
      

    /*  ----------- CREATE WAYPOINT CONTAINTER ----------  */

    // Create the main container to hold the waypoint table of contents
    let mainContainer = document.createElement('div');
    mainContainer.className = 'waypoint826-main';
    mainContainer.id = 'waypoint826-primary-container';


    if (myScriptData.waypointFieldAddTo){
         
        var entirePage = document.querySelector('.' + myScriptData.waypointFieldAddTo);
        // Append the main waypoint container to a DIV element on the page  
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

    // Set the base margin, this could be user CONFIGURABLE eventually (ask if they want nesting)
    // Cascades to other settings

    // define with user selection

    var baseMargin = 8;

    // 1 = Right 0 = Left
    if (myScriptData.waypointLeftOrRight == 'Right') {

        var waypointPosLeftOrRight = '1';
    } else {
        // Defaults to Left
        var waypointPosLeftOrRight = '0';
    }
    



        /*  ----------- BUILDING THE LIST CONTENT ----------  */

        associatedElements.forEach(function(item) {

            var selector = item.selector; // The selector (h2, h3, etc)
            var element = item.element; // The DOM element

            // Duplicates how the h2, h3, h4 is written - 'dirty version'
            // Currently keeps the exact formatting IE uppercase, all caps etc. 
           var innerContent = element.innerText;
           // console.log('innerContent ' + innerContent);

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

        /*   append Waypoint826 table of contents into the structure of the page    */
        if (mainContainer) {
             // If parent div has first child, insert mainContainer before first child
            if (mainContainer.firstChild) {
                 mainContainer.insertBefore(list, mainContainer.firstChild);
            } else {
                // If mainContainer has 0 children, append
                mainContainer.appendChild(list);
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

   

        if (positionedParent) {
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

        const elementHasID = /#/;
        const elementHasClassEs = /\./;

        if (elementHasID.test(myScriptData.waypointBorderColor)) {

            // Cleans up
            var waypointBorderColorClean = String(myScriptData.waypointBorderColor.replace('#', ''));
        } else {

            // Passes it through
            var waypointBorderColorClean = myScriptData.waypointBorderColor;
        }

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
                    // Width of Waypoint, as a number
                    var offset = parseFloat(mainContainer.offsetWidth); // Number of pixels to offset
                    // Calc init left offset for waypoint
                    var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5) + adjustMargin) + 'px';
                    //Experimental
                    var rightAdjustCalc = (offset - (baseMargin * 9)) + 'px';
                    //console.log('leftAdjustCalc', leftAdjustCalc);
                    //console.log('rightAdjustCalc', rightAdjustCalc);

                    // Experimental
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

            /*  ----------- POSITION TO TOP ----------  */

            /*     Duplicated code, put into a function...later      */

            // Could use a function to remove the #
            // myScriptData.waypointBorderColor


            // # Cleanup

            // searches for # or .
            const elementHasID = /#/;
            const elementHasClassEs = /\./;

            if (elementHasID.test(myScriptData.waypointBorderColor)) {

                // Cleans up
                var waypointBorderColorClean = String(myScriptData.waypointBorderColor.replace('#', ''));

            } else {

                // Passes it through
                var waypointBorderColorClean = myScriptData.waypointBorderColor;

            }

            // Test, find, replace and create var menuHeight
            if (elementHasID.test(myScriptData.waypointMasthead)) {
                // Log if it matches the ID pattern
                // console.log("Masthead has an ID: ", myScriptData.waypointMasthead);

                var elementIDName = String(myScriptData.waypointMasthead.replace('#', ''));
                var refToMasthead = document.getElementById(elementIDName);

                // console.log('Found masthead by ID: ', refToMasthead);

            } else if (document.getElementById('masthead')) {
                // Fallback to 'masthead' ID
                // console.log('Fallback to #masthead');
                var refToMasthead = document.getElementById('masthead');
            } else {
                // console.error('Masthead element not found');
            }

            // Default to height of the header element
            if (refToMasthead) {
                var distanceFromTop = refToMasthead.getBoundingClientRect().height;
                // console.log('Height of masthead: ', distanceFromTop);
            } else {
                // console.error('refToMasthead is null or undefined');
            }

            mainContainer.style.top = '0px';

            var waypointBox = document.getElementById('waypoint826-primary-container');
            let waypointTop = waypointBox.offsetTop;

            if (!waypointBox) {
                // console.error('Waypoint box element not found');
            }

            /*  ----------- SCROLL FUNCTION ----------  */

            // Initial position update
            updatePosition();

            // Check if myScriptData.waypointFieldAddTo exists
            if (myScriptData.waypointFieldAddTo) {

                var waypointAddToElement = document.querySelector('.' + myScriptData.waypointFieldAddTo);
            } else {
               
            }

            window.addEventListener('scroll', function(event) {

                var waypointY = document.documentElement.scrollTop || document.body.scrollTop;

                if (waypointAddToElement) {
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

            // searches for # or .
            const elementHasID = /#/;
            const elementHasClassEs = /\./;

            // TEST: Left in console.log testing cases

            // Test, find, replace and create var menuHeight
            if (elementHasID.test(myScriptData.waypointMasthead)) {
                // Log if it matches the ID pattern
                // console.log("Masthead has an ID: ", myScriptData.waypointMasthead);

                var elementIDName = String(myScriptData.waypointMasthead.replace('#', ''));
                var refToMasthead = document.getElementById(elementIDName);

                // console.log('Found masthead by ID: ', refToMasthead);

            } else if (document.getElementById('masthead')) {
                // Fallback to 'masthead' ID
                // console.log('Fallback to #masthead');
                var refToMasthead = document.getElementById('masthead');
            } else {
                // console.error('Masthead element not found');
            }

            // Default to height of the header element
            if (refToMasthead) {
                var distanceFromTop = refToMasthead.getBoundingClientRect().height;
                // console.log('Height of masthead: ', distanceFromTop);
            } else {
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
                    var rightAdjustCalc = (offset - (baseMargin * 9)) + 'px';
                    
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
                    var rightAdjustCalc = (offset - (baseMargin * 9)) + 'px';

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

        function setupIntersectionObserver() {
        // Keep
        // console.log('IntersectionObserver called');
        // Disconnect existing observer if it exists
        if (observer) {
            observer.disconnect();
        }

        // Array of links
        const tocLinks = document.querySelectorAll('.list-wrapper li a');

        // Create SECTIONS, Map links from ENTRIES to SECTIONS
        const sections = Array.from(tocLinks)
            .map(link => document.querySelector(link.getAttribute('href')))
            .filter(Boolean); // Ensure sections exist

            // Callback function to handle the intersections
            const handleIntersect = (entries) => {
                entries.forEach(entry => {
                    // Keeping for troubleshooting the handleIntersect
                    // console.log('Observing entry:', entry.target.id, 'Is intersecting:', entry.isIntersecting, 'Intersection ratio:', entry.intersectionRatio);
                    if (entry.isIntersecting) {
                        // Clear previous active list items
                        const tocListItems = document.querySelectorAll('.list-wrapper li');
                        tocListItems.forEach(li => li.classList.remove('active'));

                        const activeLink = document.querySelector(`.list-wrapper li a[href="#${entry.target.id}"]`);
                        if (activeLink) {
                            // Keep
                            // console.log('Setting active:', activeLink.parentElement);
                            activeLink.parentElement.classList.add('active');
                        }
                    }
                });
                setGlobalSettings();
            };

        const options = {
            rootMargin: '-10px 0px 0px 0px', // Adjust the top margin to handle elements near the top of the page
            threshold: 0.1 // Consider multiple thresholds
        };

            observer = new IntersectionObserver(handleIntersect, options);

            // Observe each section
            sections.forEach(section => { 
                // Keep
                // console.log('Observing section:', section.id);
                observer.observe(section);
            });
        } // END setupIntersectionObserver

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
        positionMainContainer();
        setupIntersectionObserver();
        updatePosition();
    }

    /*  ----------- GLOBAL SETTINGS  ----------  */

    function setGlobalSettings() {

        // Remove backgrounds before setting active
        var allListItemsLi = document.querySelectorAll('.waypoint826-main li');
         allListItemsLi.forEach(function(item) {
            item.style.backgroundColor = 'transparent';
         });

        // Get the active class, put it into an array
        var activeSelection = document.querySelectorAll('.waypoint826-main li.active');

        if (activeSelection.length > 0) {
            // Apply the passed var
            var activeColor = '#' + myScriptData.bgColorValue;
            activeSelection[0].style.backgroundColor = activeColor;
        }

        /*  Other settings  */

        //mainContainer.style.borderStyle = '#' + waypointBorderColorClean;
       // console.log('waypointBorderColorClean', waypointBorderColorClean);
        waypointBorderColorClean = '#' + waypointBorderColorClean;

        // Adjust border on R or L
        if ( waypointPosLeftOrRight == 1) { // Right

            // Border goes on left
            mainContainer.style.borderRight = 'none';
            mainContainer.style.borderLeft = '1px solid {$waypointBorderColorClean}';

            // or mainContainer.style.borderLeft = '1px solid' + waypointBorderColorClean';

        } else { // Left

            mainContainer.style.borderRight = '1px solid {$waypointBorderColorClean}';
            mainContainer.style.borderLeft = 'none';
        }
        
        // Border color + width
        // Border exists, Y/N -  on L or R
        // Menu title, what it says

    }

    window.addEventListener('resize', debounce(handleResize, 200));

});
    