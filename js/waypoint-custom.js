 document.addEventListener('DOMContentLoaded', function() {


    /*  ------  USER CONFIGURABLE, FROM INDIVIDUAL POSTS  ----------  */

    // Change into a number    
    let wph2 = parseFloat(myScriptData.waypointH2);
    let wph3 = parseFloat(myScriptData.waypointH3);
    let wph4 = parseFloat(myScriptData.waypointH4);
    let wph5 = parseFloat(myScriptData.waypointH5);
    
    let waypointTxtSz = parseFloat(myScriptData.waypointTextSize)


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

    /*  ----------- UTILITIES ----------  */

    function qs(selector, parent = document) {
        const el = parent.querySelector(selector);
        return el || null;
    }

    function qsa(selector, parent = document) {
        return [...parent.querySelectorAll(selector)];
    }

     function formatText(input) {
      if (!input || !input.trim()) return null; // or return a default value like '.default'


      return input
        .trim()
        .split(/\s+/)               // creates a new, same-length array split by one or more spaces
        .map(part => `.${part}`)    // creates a new array of the same length, prepends a dot to each
        .join('');                  // joins the array into a string (can be joined by an optional separator in this case, none)
    }

    const getCSSVar = (name) =>
    getComputedStyle(document.documentElement).getPropertyValue(name).trim();

    
    /*  ----------- CREATE WAYPOINT CONTAINTER ----------  */

    // Create the main container to hold the waypoint table of contents
    let mainContainer = document.createElement('div');
    mainContainer.className = 'waypoint826-main';
    mainContainer.id = 'waypoint826-primary-container';

    // append mainContainer to HTML body
    const appendTo = qs('body');
    appendTo.appendChild(mainContainer);
   

    // APPEND WAYPOINT TO BODY

    /* GOING TO DEPRECIATE UNTIL I FIGURE OUT MOBILE - 3.21.2025*/

    // ftm, waypoint will be appended to body
    if (myScriptData?.waypointFieldAddTo){
        
        // Clean up
        //const waypointFieldAdd = formatText(myScriptData.waypointFieldAddTo);
        
        // ORIG - Find DOM elem
        //const appendTo = qs(waypointFieldAdd);
        //appendTo.appendChild(mainContainer);
        
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

    /* Creating DIV and OL elements */

    const borderBox = document.createElement('div');
    borderBox.classList.add('border-box');

    // 
    const list = document.createElement('ol');
    list.classList.add('list-wrapper');

    // Create a header or title area
    var contentParagraph = document.createElement('p');
    contentParagraph.className = "content";
    contentParagraph.innerHTML = "Table of contents";

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




    
    /*  ----------- BUILDING THE LIST CONTENT ----------  */

    associatedElements.forEach(function(item) {

        var selector = item.selector; // The selector (h2, h3, etc)
        var element = item.element; // The DOM element

        // Duplicates how the h2, h3, h4 is written - 'dirty version'
        // Currently keeps the exact formatting IE uppercase, all caps etc. 
       var innerContent = element.innerText;

       // CREATE CLASS NAME

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



    /* -------- APPEND BORDERBOX AND LIST TO MAINCONTAINER -------- */

    if (mainContainer) {
         // 
        if (mainContainer.firstChild) {
             //mainContainer.insertBefore(list, mainContainer.firstChild);

        } else {
            // If mainContainer has 0 children, append
            mainContainer.appendChild(borderBox);
            borderBox.appendChild(list);
            borderBox.insertBefore(contentParagraph, borderBox.firstChild);
        }
    }


    /* SCROLL TO TOP FUNCTION */


        if ( myScriptData.waypointShowScrollUp == 'show') {

            mainContainer.appendChild(scrllTopArea);
            scrllTopArea.style.display = "inline";
        }

    //} // end 'append title area'



    /*  -----------  UTILITY: INPUT CLEAN & GET DOM ELEM  ----------  */


    // Parameters: param: element |  elemType: 'class' or 'id'
    function waypointScrub(param, elemType) {
        if (param) { 

            if ( elemType && elemType.toLowerCase() == 'class') {
                
                let space = param.trim(); 
                let space2 = "." + space.replace(/ /g, '.');
                let getElem = qs(space2);
                // Returns DOM Element
                return getElem; 
            }  
        }
    }
        

    /*  -------------------- USER CONFIGURATION --------------------  */

    var spaceForWaypoint;



    function calcWaypointSpaceNeeded() {

        // Returns DOM Elem
        if (myScriptData?.waypointFieldAlignToElement) {
            
            // changing the name of placeNextTo, its misleading - its really placeNextTo
            const placeNextTo = qs(formatText(myScriptData.waypointFieldAlignToElement));
            const elemContentWidth = window.getComputedStyle(placeNextTo).width;
            const cleanElemContentWidth = elemContentWidth.replace(/px/g, '');

            var contentLeftEdge = placeNextTo.getBoundingClientRect().left;


            // corrected
            var contentRightEdge = placeNextTo.getBoundingClientRect().right;

            const viewportWidth = window.innerWidth;
            const elementWaypoint = qs('.waypoint826-main');

            // Get Waypoint width, clean
            const elemWaypointWidth = window.getComputedStyle(elementWaypoint).width;
            
            var cleanElemWaypointWidth = elemWaypointWidth.replace(/px/g, '');


            var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);
            const waypointSpaceNeeded = (Number(cleanElemWaypointWidth));

            

            // Send the calc'd values back to the function
            

            
        } else if (!myScriptData?.waypointFieldAlignToElement) {

            var contentLeftEdge = 0;
            var contentRightEdge = 0; 

        }

        return { value1: spaceForWaypoint, value2: contentLeftEdge, value3: contentRightEdge, value4: cleanElemWaypointWidth  }

 
    } // END calcWaypointSpaceNeeded

    const calculatorWaypointWidth = {

    }

    // error found 3.20.2025 - hard coded
    //var wrapper = qs('.box-container');

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

    function applyStyling(elem, screensize) {

        // elem should always be mainContainer

        const primary = getCSSVar('--text-primary');

        // For Mobile
        if (elem && screensize == "small") {
            // Notes

            // Reset borderleft
            elem.style.borderLeft = "none";

            // Always show the Table of Contents title on Mobile
            elem.insertBefore(contentParagraph, elem.firstChild);

            Object.assign(contentParagraph.style, {
                paddingLeft: '0px',
                paddingBottom: '3px',
                marginBottom: '0px',
                borderBottom: '1px solid #ccc'
            });

            // Style
            Object.assign(elem.style,  {
                display: 'block',
                width: 'auto',
                position: 'static'
            });

            // Style LI within elem
            elem.querySelectorAll("li").forEach(li => {

                Object.assign(li.style, {
                    paddingLeft: '0px',
                    paddingTop: '0px',
                    paddingBottom: '0px',
                    borderLeftColor: 'none',
                    borderLeftWidth: '0px'
                });
            });

            // Style .active within elem
            elem.querySelectorAll(".active").forEach(active => {

                Object.assign(active.style, {
                    borderLeft: 'none',
                });
            });

            // style a within elem
            elem.querySelectorAll("a").forEach(a => {

                Object.assign(a.style, {
                    display: 'inline-flex',
                    height: '40px',
                    alignItems: 'center',
                    color: primary,
                    fontWeight: '500',
                    textDecoration: 'underline',
                });
            });

            // Style LI within elem
            const listWrapper = elem.querySelector(".list-wrapper");

            if (listWrapper){
                Object.assign(listWrapper.style, {
                    paddingBottom: '30px',
                    paddingTop: '20px',
                    borderBottom: '1px solid #ccc',
                });
            }         

        } else if (elem && screensize == "large") {

            // Table of Contents title takes users input
  
            // Notes
            Object.assign(elem.style,  {
                display: 'block',
                width: '190px'
            });

            // Style LI within elem
            elem.querySelectorAll("li").forEach(li => {

                Object.assign(li.style, {
                    paddingRight: '24px',
                });
            });

            // Style .active within elem
            elem.querySelectorAll(".active").forEach(active => {

                Object.assign(active.style, {
                    fontWeight: '500',
                });
            });

            // Only show the title area on large if user opts to
            if (myScriptData.waypointMenuTitleOnOff == 'visible') {
                
                //elem.insertBefore(contentParagraph, elem.firstChild);
                contentParagraph.style.display = 'block';

            } else if (myScriptData.waypointMenuTitleOnOff == 'invisible') {

                contentParagraph.style.display = 'none';

            }

            // Border Right
            const olWrapper = qs('.border-box');

            if (myScriptData.waypointBorderRight && olWrapper){

                const borderRightColor = '#' + myScriptData.waypointBorderRight

                Object.assign(olWrapper.style, {
                    borderRight: `1px solid ${borderRightColor}`,
                    paddingTop: '20px',
                    paddingBottom: '20px',
                }); 
            }
        }
    }


    function calcWaypointWidth(elem) {
        // Pass in
        const {value1, value2, value3, value4} = calcWaypointSpaceNeeded();
        // Values
        spaceForWaypoint = value1; // var spaceForWaypoint = (viewportWidth - cleanElemContentWidth);
        contentLeftEdge = value2; // Left edge of content to left edge of viewport
        contentRightEdge = value3; // Right edge of content to left edge of viewport
        offset = value4;

        let rightAdjustCalc = 0 + 'px'; // Don't need
        let leftAdjustCalc;
        let waypointWidth;
        let multiplier;

        

        // Check how much screen real estate is left for waypoint to inhabit
        if ( spaceForWaypoint < 640) {

            // Append
            const appendTo = qs(formatText(myScriptData.waypointFieldAddTo));
            appendTo.insertBefore(mainContainer, appendTo.firstChild);
            // Apply styles
            applyStyling(mainContainer, "small");


        } else if ( spaceForWaypoint >= 640 && spaceForWaypoint < 700) {

            // For leftAdjustCalc
            multiplier = 3;

            // Append mainContainer to HTML body
            const appendTo = qs('body');
            appendTo.appendChild(mainContainer);

            // Apply styles
            applyStyling(mainContainer, "large");

            if (elem) {  
            //Specific for setting the active state borderLeft
                elem.style.borderLeft = `3px solid ${waypointBorderColorClean}`;
            }


        } else if ( spaceForWaypoint >= 700 ) {

            // For leftAdjustCalc
            multiplier = 5;
            // Append mainContainer to HTML body
            const appendTo = qs('body');
            appendTo.appendChild(mainContainer);
            // Apply styles
            applyStyling(mainContainer, "large");
            //Specific for setting the active state borderLeft
            if (elem) { 
                elem.style.borderLeft = `3px solid ${waypointBorderColorClean}`;
            }

        }

        leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * multiplier) /* // removed 3.20.2025 + adjustMargin*/) + 'px';
        mainContainer.style.left = leftAdjustCalc;
        

        // Pass width of waypoint
        calcWidthForWaypoint.log(waypointWidth); 

    } // END calcWaypointWidth()




    /*----------   USER CONFIGS   -------------*/

    // Sets border colors
    if (typeof myScriptData.waypointBorderColor !== 'undefined' && myScriptData.waypointBorderColor != null) {
        // Calls cleaning function
        var waypointBorderColorClean = waypointHandleHashDot(myScriptData.waypointBorderColor);
        // Used to be set in setGlobalSettings
        waypointBorderColorClean = '#' + waypointBorderColorClean;

    }

    // SET BACKGROUND COLOR

    if (typeof myScriptData.bgValue !== 'undefined' && myScriptData.bgValue != null) {

        const setColor = waypointHandleHashDot(myScriptData.bgValue);
        setbgColor = '#' + setColor;
        mainContainer.style.backgroundColor = setbgColor;
    }

    // SET TEXT COLOR

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

    // SET TEXT SIZE

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


    /*----------- SET HORIZONTAL POSITION  --------------*/


    /* Find the POSITIONED PARENT to lock waypoint to viewport top */

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




    // Set the init position of the waypoint826 div
    function positionMainContainer() {

        mainContainer.style.opacity = '0.2';

        /*  -----------  USER CONFIGURABLE  ----------  */


        // Start the pulse for 1.5 seconds
        startPulse(500);

        // Cleaned HEX value
        if (myScriptData.waypointBorderColor) {
            // Calls HEX color cleaning function
            const waypointBorderColorClean = waypointHandleHashDot(myScriptData.waypointBorderColor);
        }

        /*  ----------- SET mainContainer's INIT POSITION TO TOP BASED ON MASTHEAD HEIGHT ----------  */


        // does waypointMasthead exist, and have a #

        // is there a conflict between this and 'place waypoint next to'
            
        if (myScriptData.waypointExtraPadding) {
            var extraPadding = Number(myScriptData.waypointExtraPadding);
            
        }
        
        if (myScriptData?.waypointMasthead) {
            // if waypointMasthead exists

            const waypointElementIDName = waypointHandleHashDot(myScriptData.waypointMasthead); //removes the hash or do
            //hashdot should probably return whether its a hash or a dot (ID or class) - for later
            
            const refToMasthead = document.getElementById(waypointElementIDName);
            const initDistanceFromTop = refToMasthead.getBoundingClientRect().height;
            
            
            mainContainer.style.top = (initDistanceFromTop + extraPadding) + 'px';
            
            
        } else if (!myScriptData.waypointMasthead) {
            
            // it *IS* undefined OR it's less than 3 characters
            const refToMasthead = undefined;
            const waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);

        }

    

        /*  ----------- SCROLL FUNCTION ----------  */

        

        // not necessary? 3.22.2025

        // Check if myScriptData.waypointFieldAddTo exists
        // waypointFieldAddTo is where waypoint will be appended to on a mobile view
        /*if (myScriptData.waypointFieldAddTo) {

            // Clean up? 
            const waypointFieldAppendTo = formatText(myScriptData.waypointFieldAddTo);

            var waypointAddToElement = qs(waypointFieldAppendTo);
        } else {
           
        }*/

        /* VERTICAL POSITION */

        // this needs to work but doesn't currently
        // distanceFromTop is defined both above and below, I wonder if that's creating a confliect

        
        //scrollable.addEventListener('scroll', handleScroll, { passive: true });

       // Get these references once, outside the scroll event
        
        // if adding extra padding, add it to the init
        if (myScriptData.waypointExtraPadding) {
            //myScriptData.waypointExtraPadding
        }

        const waypointElementIDName = waypointHandleHashDot(myScriptData.waypointMasthead);
        const refToMasthead = document.getElementById(waypointElementIDName);
        const initDistanceFromTop =  mainContainer.offsetTop;  //refToMasthead.getBoundingClientRect().height;

        // Initial position update
        updatePosition();

        

        function handleScroll() {
            
            var waypointY = document.documentElement.scrollTop || document.body.scrollTop;

            if (waypointY >= initDistanceFromTop) {
                mainContainer.classList.add('sticky');
                mainContainer.style.top = '0px';
            } else {
                mainContainer.classList.remove('sticky');
                mainContainer.style.top = initDistanceFromTop + 'px';
            }
        }
        
        requestAnimationFrame(() => {
          window.addEventListener('scroll', handleScroll, { passive: true });
          //const scrollable = document.querySelector('.mainContainer');
          //scrollable.addEventListener('scroll', handleScroll, { passive: true });
          handleScroll(); // Run it immediately
          
        });

        

    } // end positionMainContainer




    /*  ----------- FOR POS:ABSOLUTE BEHAVIOR IE SCROLLING WITH CONTENT ----------  */

    function updatePosition(initFromTop) {

        //mainContainer.style.top = initFromTop;

        // I might be able to depreciate this entire function


        // Call earlier function that calculates 1) spaceforwaypoint and 2) contentleftedge
        const {value1, value2, value3} = calcWaypointSpaceNeeded();
        spaceForWaypoint = value1;
        contentLeftEdge = value2;
        contentRightEdge = value3;

        let viewportHeight = window.innerHeight;
        // math - viewport height 100%, contents should never take up more than 80%, top elem and bottom elem = 20%
        let waypointUsableHeight = (viewportHeight * .8);

        // Get a single LIs height
        let allListItemsLi = document.querySelectorAll('.waypoint826-main ol li');
        

        let waypointLiHeight;

        allListItemsLi.forEach(function(item) {

            //waypointLiHeight = window.getComputedStyle(item).height();
            waypointLiHeight = item.getBoundingClientRect().height;
            
        });

        let waypointLiNumItems = allListItemsLi.length;

        /* 

        if (waypointLiHeight * waypointLiNumItems > waypointUsableHeight) {

            // too much space being taken up
            let waypointSubtract = (waypointLiHeight * waypointLiNumItems - waypointUsableHeight);

            // If negative, ok, don't do anything
            if ( waypointSubtract > 0) {

                // Divide reminaing space by numItems, then divivde by two to get the space to be added to ea. top and bottom padding\
                let waypointCalc = ((waypointSubtract / waypointLiNumItems) / 2);
            }
            
        }

        */

        // Gives us space for each LI
        /*
        3.24.2025
        let waypointTotalSpace4Li = ((waypointUsableHeight / waypointLiNumItems) - (waypointTxtSz));

        var waypointLiMult = (((waypointTotalSpace4Li/baseMargin) - 4) / 2);

        if (waypointLiMult > 1.5) {
            waypointLiMult = 1.5;
        } else if (waypointLiMult < .25) { 
            waypointLiMult = .25;
        } else {
            waypointLiMult;
        }
        */

        // Set the property
        //document.documentElement.style.setProperty('--multiplier', waypointLiMult);

        // Get the bounding rectangle of the parent
        if (typeof positionedParent !== 'undefined' && positionedParent != null) {
            var parentRect = positionedParent.getBoundingClientRect().top;
        }

        // MUCH OF THIS CODE IS REDUNDANT AND OVER COMPLICATED
        //onsole.log("wpeIDn2", waypointElementIDName);

        // Test, find, replace and create var menuHeight


        /* These CONST are blocks-scoped - nothing can use them... */

       /* if (myScriptData.waypointMasthead) {
            
            // Clean up - removes any hashes or dots
            const waypointElementIDName = waypointHandleHashDot(myScriptData.waypointMasthead)

            const refToMasthead = document.getElementById(waypointElementIDName);
            
            var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            

        } else {
            // 
            const waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);
        }*/
        /*
        // 3.21.2025
        // Default to height of the header element
        if (refToMasthead && myScriptData?.waypointMasthead) {
            
            
        } else {

            var distanceFromTop = 0;
            var waypointFindBody = qs('body');
            waypointFindBody.appendChild(mainContainer);

            // 
        }
        */
        

        // commmented out 3.20.2025

        /*if ( window.scrollY < distanceFromTop ){
            mainContainer.style.top = parentRect + 'px';
        } else if ( window.scrollY > distanceFromTop ) {
            //mainContainer.style.top = parentRect + 'px';
        }*/

        

        // let alignToElement = myScriptData.waypointFieldAlignToElement.trim();

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
                    tocListItems.forEach(li => {
                        li.classList.remove('active');
                        
                        li.style.borderLeft = ''; // clear inline style
                    });

                   

                    const activeLink = qs(`.list-wrapper li a[href="#${entry.target.id}"]`);

                    if (activeLink) {
                        activeLink.parentElement.classList.add('active');

                        const tocLIs = document.querySelectorAll('.list-wrapper li');
                        tocLIs.forEach(li => {
                            li.style.borderLeft = `3px solid ${setbgColor}`;
                        });

                        // since the .active class is assigned here, I thought I'd do the one-time setting of the color and border width (so it's ready in the DOM)
                        let selectedArea = qs('.active');

                        // Passes a param to calcWaypointWidth which adds the styling

                        //selectedArea.style.borderLeft = `3px solid ${waypointBorderColorClean}`;
                        calcWaypointWidth(selectedArea);
                        

                        /*if (i==0) {
                            let selectedArea = qs('.active');
                            
                            selectedArea.style.borderLeft = `3px solid ${waypointBorderColorClean}`;
                            i++;
                        }*/
                       /* const selectedArea = qsa('.active');
                        selectedArea.forEach(el => {
                            el.style.borderLeft = `3px solid ${waypointBorderColorClean}`;
                        });*/

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


    var waypointCount = 0;

    /* should I check if the shortcode is even being used? */

    // may be more than one instance
    // returns an array, need forEach

    // I can eventually delete this (even the add listener) 3.23.2025
    const shortCodeUsed = qsa('.waypoint-sc-scroll-down'); 

    if (shortCodeUsed) {
        // 
    
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
                        
                        
                    } // end if
                } // end for
            } // End handleSectionChange  
        } // end eventKey if
    }); // end eventListener

} // end if (shortcode)

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
    