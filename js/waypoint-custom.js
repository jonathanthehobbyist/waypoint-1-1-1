 
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
    //
    let waypointTxtSz = parseFloat(myScriptData.waypointTextSize)

    //console.log('Reposition', myScriptData.waypointFieldReposition);

    //console.log(myScriptData.waypointTextSize);

    /*
    *   Next up
    *   - Contents / scroll to top styling
    *   - DONE left padding on li
    *   - DONE Have the interval fire every 4-7 seconds, every 3 is too much 
        - DONE Pass left text size
        - DONE Margin between LI
        - 'Enter' isn't working on live instance
        - Contents P needs equal bottom margin to top
        - Realign central content - on/off
        - ? When you're scrolled to top, and the 1st (selected) element is actually off the screen - feels like we often need a top of page 'intro'
        - Kind of a brittle experience - need to do some form validation
    */




    /*  ----------- UTILITY: INPUT CLEANUP - COLOR  ----------  */

    // Cleans up HEX colors
    function waypointHandleHashDot(element) {

        // Cleans the passsed 'element'
        const elementHasHash = /#/;
        const elementHasDot = /\./;

        // What if there's two elements? 

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

    /*  ----------- UTILITY IDEAS ----------  */

    function qs(selector, parent = document) {
        return parent.querySelector(selector);
    }

    function qsa(selector, parent = document) {
        return [...parent.querySelectorAll(selector)];
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
        // Find DOM elem
        const entirePage = qs('.' + waypointFieldAdd);
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
        var heading = qs(selector); // Get the first matching element for this selector
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
    var scrllTopArea = document.createElement('p');
    scrllTopArea.className = "scroll-to-top";
    scrllTopArea.innerHTML = "Scroll to top";

    scrllTopArea.onclick = function () {

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
            // Set for later & easier useage
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

            // visible
            // invisible

            
                mainContainer.insertBefore(contentParagraph, mainContainer.firstChild);
         

            console.log("Title on/off" + myScriptData.waypointMenuTitleOnOff);
            // If user sets title area to visible, insert title area
            //mainContainer.insertBefore(contentParagraph, mainContainer.firstChild);
        } else {
            console.log("Title on/off" + myScriptData.waypointMenuTitleOnOff);
        }

        // This var could be user configurable
        const hasScrllTop = true;

        if ( hasScrllTop == true) {
            
            mainContainer.appendChild(scrllTopArea);
        }
    }

    // What is the purpose here? I think it has to do with left

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
    var absoluteElement = qs('.waypoint826-main'); 
    var positionedParent = findPositionedParent(absoluteElement);

    if ( typeof positionedParent !== 'undefined' && positionedParent != null) {
        // True distance from the left viewport edge

        if ( waypointPosLeftOrRight == 1) {
            // Distance from the viewport's left edge to the element's right edge
            var WaypointParentPos = positionedParent.getBoundingClientRect().right;
        } else {
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

       var alignToElement = myScriptData.waypointFieldAlignToElement.trim();


    /*  -----------  UTILITY: INPUT CLEAN & GET DOM ELEM  ----------  */


    // Parameters: param: element |  elemType: 'class' or 'id'
    function waypointScrub(param, elemType) {
        if (typeof param !== 'undefined' && param != null && param.length > 2) { 

            if ( typeof elemType !== 'undefined' && elemType.toLowerCase() == 'class') {
                //console.log('log');
                let space = param.trim(); 
                let space2 = "." + space.replace(/ /g, '.');
                let getElem = qs(space2);
                // Returns DOM Element
                return getElem; 
            }  
        }
    }


    if (typeof myScriptData.waypointFieldReposition !== 'undefined' && myScriptData.waypointFieldReposition != null) {
        var waypointElemToMove = myScriptData.waypointFieldReposition;
        // Handles multiple spaces, if exist
        waypointElemToMove = "." + waypointElemToMove.replace(/ /g, '.'); 
        //console.log('waypointElemToMove', waypointElemToMove);
    }        

    /*  -------------------- USER CONFIGURABLE --------------------  */

    // Remove whitespace from both ends of
    //alignToElement = myScriptData.waypointFieldAlignToElement.trim();

    var spaceForWaypoint;



    function calcWaypointSpaceNeeded() {

        // Returns DOM Elem
        let contentArea = waypointScrub(myScriptData.waypointFieldAlignToElement, 'class');
        
        // get the left position of the user-defined content block
        if (typeof contentArea !== 'undefined') {

            var elemContentWidth = window.getComputedStyle(contentArea).width;
            var cleanElemContentWidth = elemContentWidth.replace(/px/g, '');

            var contentLeftEdge = contentArea.getBoundingClientRect().left;

            // found an error 3/20/2025 was an error, should have been contentRightEdge
            //var contentLeftEdge  = contentArea.getBoundingClientRect().right;

            // corrected
            var contentRightEdge  = contentArea.getBoundingClientRect().right;

        } else {

           var contentLeftEdge = 0;
           var contentRightEdge = 0;
        }

        var viewportWidth = window.innerWidth;
        var elementWaypoint = qs('.waypoint826-main');

        // Get Waypoint width, clean
        var elemWaypointWidth = window.getComputedStyle(elementWaypoint).width;
        var cleanElemWaypointWidth = elemWaypointWidth.replace(/px/g, '');

        var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);
        let waypointSpaceNeeded = (Number(cleanElemWaypointWidth));

        // Send the calc'd values back to the function
        return { value1: spaceForWaypoint, value2: contentLeftEdge, value3: contentRightEdge, value4: cleanElemWaypointWidth  }

    } // END calcWaypointSpaceNeeded

    const calculatorWaypointWidth = {

    }

    // error found 3.20.2025 - hard coded
    var wrapper = qs('.box-container');

    // Sets width of waypoint and maincontainer
    const calcWidthForWaypoint = {
          viewport: window.innerWidth,
          log: function(arg) {
            if ( arg !== undefined) {
                // commented out 3.20.2025
                // wrapper.style.width = ((this.viewport - arg + 3) / this.viewport) * 100 + '%';
            }
          },
          another: function () {
          // empty for now
          }
    };


    /*
        How does positioning work? 
        - user defines waypoint Left or Right side of screen
        - variable: waypointFieldReposition? = 
        - variable: waypointFieldAlignToElement? = contentArea = user defined content area
        - variable: waypointMasthead = class or ID of masthead div for vertical positioning
        - variable: waypointFieldAddTo = ?
        - variable: 
        - right=1 left=0
        - if the space for waypoint is less than 640, display is none
        - if it's between 640 and 700, 
        - if it's greater than 700
        - mainContainer is the holder for waypoint826
        - what function moves the maincontent to the left or right? 
        - contentArea is user defined = 
        -
        -
        -
        -
        -
        -

        How to simplify?
        - Only allow left
        - 

    */


    function calcWaypointWidth() {
        // Pass in
        const {value1, value2, value3, value4} = calcWaypointSpaceNeeded();
        // Values
        spaceForWaypoint = value1; // var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);
        contentLeftEdge = value2; // Left edge of content to left edge of viewport
        contentRightEdge = value3; // Right edge of content to left edge of viewport
        offset = value4;

        let rightAdjustCalc = 0 + 'px';
        let leftAdjustCalc;
        let waypointWidth;
        let multiplier;

        // Check how much screen real estate is left for waypoint to inhabit
        if ( spaceForWaypoint < 640) {

            mainContainer.style.display = 'none';

        } else if ( spaceForWaypoint >= 640 && spaceForWaypoint < 700) {

            mainContainer.style.display = 'block';
            waypointWidth = '210';
            mainContainer.style.width = waypointWidth + 'px';
            multiplier = 3;

        } else if ( spaceForWaypoint >= 700 ) {

            mainContainer.style.display = 'block';
            waypointWidth = '250';
            mainContainer.style.width = waypointWidth + 'px';
            multiplier = 5;
            console.log(multiplier);

        }

        leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * multiplier) + adjustMargin) + 'px';

        // 
        if ( waypointPosLeftOrRight == 1) {
            mainContainer.style.right = rightAdjustCalc;
        } else {
            mainContainer.style.left = leftAdjustCalc;
        }

        // Pass width of waypoint
        calcWidthForWaypoint.log(waypointWidth); 

    } // END calcWaypointWidth()




    /*----------   SET PADDING FOR ....?  -------------*/

        if ( typeof myScriptData.waypointLeftOrRight !== 'undefined' && myScriptData.waypointLeftOrRight != null) {

        let waypointLeftRightPadding = qsa('.waypoint826-main ol.list-wrapper li');
        let waypointContentLRPadding = qs('.waypoint826-main .content');


        // 3.20.2025 could eliminate if L or R variable is reduced to left only
        if (myScriptData.waypointLeftOrRight == 'Right') {
            // LI
            waypointLeftRightPadding.forEach((item) => {

                // Re-style waypoint LI
                item.style.paddingLeft = (baseMargin * 5) + 'px';
                item.style.paddingRight = (baseMargin * 3) + 'px';
            });
            // waypoint Main
            waypointContentLRPadding.style.paddingLeft = (baseMargin * 5) + 'px';
            waypointContentLRPadding.style.paddingRight = (baseMargin * 3) + 'px';
            // Scroll to top
            scrllTopArea.style.paddingLeft = (baseMargin * 5) + 'px';
            scrllTopArea.style.paddingRight = (baseMargin * 3) + 'px';

        } else { // Left

            waypointLeftRightPadding.forEach((item) => {

                // Re-style waypoint
                item.style.paddingLeft = (baseMargin * 3) + 'px';
                item.style.paddingRight = (baseMargin * 5) + 'px';
            }); 
            waypointContentLRPadding.style.paddingLeft = (baseMargin * 3) + 'px';
            waypointContentLRPadding.style.paddingRight = (baseMargin * 5) + 'px';
            // Scroll to top
            scrllTopArea.style.paddingLeft = (baseMargin * 3) + 'px';
            scrllTopArea.style.paddingRight = (baseMargin * 5) + 'px';
        }
    }



    /*----------   USER CONFIGS   -------------*/

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
        scrllTopArea.style.borderTop = `1px solid ${waypointBorderColorClean}`; 
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

        contentParagraph.style.color = setColorText;
        scrllTopArea.style.color = setColorText;
    }

    // Set text size
    //waypointTxtSz
    if (typeof waypointTxtSz !== 'undefined' && waypointTxtSz != null) {

        const waypointTxtElem = document.querySelectorAll('.list-wrapper li a');

        // Iterate through list and set colors
        waypointTxtElem.forEach(item => {
            item.style.fontSize = waypointTxtSz + 'px';
        });

        contentParagraph.style.fontSize = waypointTxtSz + 'px';
        scrllTopArea.style.fontSize = waypointTxtSz + 'px';
    }


    /*----------- SET RIGHT-HAND POSITION  --------------*/

    // Set the right-hand position of the waypoint826 plugin
    function positionMainContainer() {
        //console.log('posMainCont');

        mainContainer.style.opacity = '0.2';

        /*  -----------  USER CONFIGURABLE  ----------  */

        // What to do with this?

        // User can choose an element to align Waypoint to horizontally
        if (typeof alignToElement !== 'undefined' && alignToElement.length > 2) {
            calcWaypointWidth();
        }


        // END if ( alignToElement ) {

        // Start the pulse for 5 seconds
        startPulse(1500);

        


        /*

        Not sure why 'cleaned HEX value is here, leaving for now'

        */


        // Cleaned HEX value
        if (typeof myScriptData.waypointBorderColor !== 'undefined') {
            // Calls HEX color cleaning function
            const waypointBorderColorClean = waypointHandleHashDot(myScriptData.waypointBorderColor);
        }



        /*  ----------- INIT POSITION TO TOP ----------  */


        /*

        What am I trying to do? 
        - Find the initial position of the waypoint div
        - See if a masthead exists, else pin to top edge of viewport
        - scroll up until it hits the top viewport edge
        - stick
        - unless the user scrolls to the top again, at which point resume the init position
        - 

        */

        



        // does waypointMasthead have a #
        if (typeof myScriptData.waypointMasthead !== 'undefined' && myScriptData.waypointMasthead != null && myScriptData.waypointMasthead !== '') {
            // Log if it matches the ID pattern

            var waypointElementIDName = waypointHandleHashDot(myScriptData.waypointMasthead);
            console.log("ID name" + waypointElementIDName);
            var refToMasthead = document.getElementById(waypointElementIDName);
            console.log("ref to masthead " + refToMasthead);
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            console.log("distance from top" + distanceFromTop );


        } else if (typeof myScriptData.waypointMasthead == 'undefined' || myScriptData.waypointMasthead.length < 3 || myScriptData.waypointMasthead.length > 7 || myScriptData.waypointMasthead == null) {

            // it *IS* undefined OR it's less than 3 characters
            var refToMasthead = undefined;
            const waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);

        }

        // Default to height of the header element
       /*  if (typeof refToMasthead !== 'undefined' && refToMasthead != null && refToMasthead !== '' && refToMasthead.length >= 3) {

            //get height of masthead object
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            console.log("distance from top" + distanceFromTop );
            // console.log('Height of masthead: ', distanceFromTop);
        } else {
            var distanceFromTop = 0;
            console.log("distance from top" + distanceFromTop );
            // console.error('refToMasthead is null or undefined');
            var waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);
        }

        */

        // don't understand this

        mainContainer.style.top = '80px';

        var waypointBox = document.getElementById('waypoint826-primary-container');
        let waypointTop = waypointBox.offsetTop;
        console.log("waypointTop", waypointTop);


        /*  ----------- SCROLL FUNCTION ----------  */

        // Initial position update
        updatePosition();

        // Check if myScriptData.waypointFieldAddTo exists
        if (typeof myScriptData.waypointFieldAddTo !== 'undefined' && myScriptData.waypointFieldAddTo != null) {

            // Clean up? 
            const waypointFieldAppendTo = waypointHandleHashDot(myScriptData.waypointFieldAddTo);

            var waypointAddToElement = qs('.' + waypointFieldAppendTo);
        } else {
           
        }

        // this needs to work but doesn't currently
        // distanceFromTop is defined both above and below, I wonder if that's creating a confliect

        window.addEventListener('scroll', function(event) {

            var waypointY = document.documentElement.scrollTop || document.body.scrollTop;

            if (typeof waypointAddToElement !== 'undefined') {
                var distanceFromTop = waypointAddToElement.getBoundingClientRect().top + window.scrollY;
                console.log('Distance from top: ', distanceFromTop);
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

        let viewportHeight = window.innerHeight;
        // math - viewport height 100%, contents should never take up more than 80%, top elem and bottom elem = 20%
        let waypointUsableHeight = (viewportHeight * .8);

        // Get LIs height
        let allListItemsLi = document.querySelectorAll('.waypoint826-main ol li');

        let waypointLiHeight;

        allListItemsLi.forEach(function(item) {
            //waypointLiHeight = window.getComputedStyle(item).height();
            waypointLiHeight = item.getBoundingClientRect().height;
        });

        let waypointLiNumItems = allListItemsLi.length;

        if (waypointLiHeight * waypointLiNumItems > waypointUsableHeight) {

            // too much space being taken up
            let waypointSubtract = (waypointLiHeight * waypointLiNumItems - waypointUsableHeight);

            // If negative, ok, don't do anything
            if ( waypointSubtract > 0) {

                // Divide reminaing space by numItems, then divivde by two to get the space to be added to ea. top and bottom padding\
                let waypointCalc = ((waypointSubtract / waypointLiNumItems) / 2);
            }
            //console.log('waypointCalc', waypointCalc);
        }

        // Gives us space for each LI
        let waypointTotalSpace4Li = ((waypointUsableHeight / waypointLiNumItems) - (waypointTxtSz));

        var waypointLiMult = (((waypointTotalSpace4Li/baseMargin) - 4) / 2);

        if (waypointLiMult > 1.5) {
            waypointLiMult = 1.5;
        } else if (waypointLiMult < .25) { 
            waypointLiMult = .25;
        } else {
            waypointLiMult;
        }

        // Set the property
        document.documentElement.style.setProperty('--multiplier', waypointLiMult);

        // Get the bounding rectangle of the parent
        if (typeof positionedParent !== 'undefined' && positionedParent != null) {
            var parentRect = positionedParent.getBoundingClientRect().top;
        }

        // Test, find, replace and create var menuHeight
        if (typeof myScriptData.waypointMasthead !== 'undefined' && myScriptData.waypointMasthead != null && typeof waypointElementIDName !== 'undefined' && myScriptData.waypointMasthead !== '') {

            // Clean up - removes any hashes or dots
            const waypiontMH = waypointHandleHashDot(myScriptData.waypointMasthead );

            var refToMasthead = document.getElementById(waypointElementIDName);

        } else {
            // console.error('Masthead element not found');
            const waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);
        }

        // Default to height of the header element
        if (typeof refToMasthead !== 'undefined' && myScriptData.waypointMasthead !== '') {
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            // console.log('Height of masthead: ', distanceFromTop);
        } else {

            var distanceFromTop = 0;
            var waypointFindBody = qs('body');
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

        let alignToElement = myScriptData.waypointFieldAlignToElement.trim();

        //var offset = parseFloat(mainContainer.offsetWidth); // Number of pixels to offset
   
        calcWaypointWidth();

    } // end updatePosition


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

        // It would be handy to have a duplicates eliminator

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

                    const activeLink = qs(`.list-wrapper li a[href="#${entry.target.id}"]`);

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

    var waypointCount = 0;

    // 'Enter' keydown function
    window.addEventListener('keydown', function(event) {

        // Check if the key pressed is "Enter"
        if (event.key === 'Enter' || event.keyCode === 13) {

            waypointCount++;

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
                
                //safely access currentlyObserverd as sectionId

                for (let i = 0; i< sections2.length; i++) {
                    // Define observed
                    const sectionsConv = sections2[i].toString();
                    // Define section
                    const observedConv = sectionId.toString();
                    // If there's a match
                    if ( sectionsConv === observedConv) {
                        // If there's a match, get the next section
                        const nextElem = document.getElementById(sections2[i + 1]);
                        // Scroll down
                        if (nextElem && i <= sections2.length - 1 && j === 1) {
                            nextElem.scrollIntoView({
                                behavior: 'smooth',
                                block: 'center'
                            });
                            // Increment number of calls after scrolled
                            j++;
                            break;
                        } else if ( i === sections2.length - 1) {
                                window.scrollTo({
                                    top: 0,
                                    behavior: 'smooth' // Smooth scroll
                                });
                            break;
                        } else {
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
        const waypointBounce = qs('.waypoint-sc-scroll-down');

        if (typeof waypointBounce !== 'undefined' && waypointBounce != null) {
            
            waypointBounce.classList.add('bounce');

            // Remove the bounce class after animation ends so it can be reapplied
            waypointBounce.addEventListener('animationend', () => {
                waypointBounce.classList.remove('bounce');
            });
        }
            
    }, 6000);
    

    window.addEventListener('resize', debounce(handleResize, 200));

});
    