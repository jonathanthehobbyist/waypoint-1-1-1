<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 * Version: 1.0.1
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

add_action('wp_enqueue_scripts', 'waypoint826_enqueue_styles');


function wporg_custom_box_html( $post ) {

    /*  
            
        HOW TO ADD AN ADMIN - places to look
        • NOTE: use Waypoint_ naming structure

        • Add to - Add input field
        • Add to - Retreive value for the admin field
        • Add to - Define the field in $fields
        • Add to - it in the forEach loop in wporg_post_savedata()
        • Add to - it just before the DOMContentLoaded function to transition from php to js variables

    */


    /*
        ADMIN - what else is needed? 

        POSITION

        - DONE: need an element to attach to on the right - individual
        - DONE: need to adjust the center DIV right if margin: 0 auto;
        - DONE: peg to top element
        - DONE: Additional right margin for mainContainer
        - What happens in the case of sticky headers?

        SETTINGS PAGE
        - Masthead
        - Background color
        - Background selected color

        STYLE

        - background color / transparent - individ.
        - Selection color - settings
        - Color of normal type - settings
        - Border right style (or not at all) - settings
        - Typeography - settings
        - Make base margin user configurabl - settings

        INDIVIDUAL POST/PAGE vs. SETTINGS

        CLEAN UP
        - DONE: Nicer animation of Waypoint moving around after a window resize
        - Probably needs to be scrollable in case it goes off the page
        - Release log

        DEBUG
        - DONE: There's a bug when you zoom to the top of the page, might be around my implementation of the header. Might need to save that value as a unchangeable variatble rarther than recalculating each time 
        - Class or ID input on waypoint_add_to_page - need logic to handle this

    */


    // LIST OF ALL ADMIN functions goes here

    // Retrieve current value for the checkbox field
    $checkbox_value = get_post_meta( $post->ID, '_waypoint_enable_for_post', true );
    $checkbox_value_H2 = get_post_meta( $post->ID, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post->ID, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post->ID, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post->ID, '_waypoint_H5_enable', true );

    // Intro li at top
    $checkbox_value_intro = get_post_meta( $post->ID, '_waypoint_intro_enable', true );

    $field_value_masthead_define = get_post_meta( $post->ID, '_waypoint_masthead_define', true );

    $field_value_add_to = get_post_meta( $post->ID, '_waypoint_add_to_page', true );
    $field_value_align_to_element = get_post_meta( $post->ID, '_waypoint_align_to_element', true );

    // Configuration (probably will correspond with a settings page)
    // May not need this:
    // $field_value_a_bg_select_color = get_post_meta( $post->ID, '_waypoint_a_bg_select_color', true );

    // Add a nonce field for security
    wp_nonce_field( 'wporg_save_postdata', 'wporg_nonce' );

    ?>

    <input type="checkbox" id="waypoint_enable_for_post" name="waypoint_enable_for_post" value="1" <?php checked( $checkbox_value, '1' ); ?>>
    <label for="waypoint_enable_for_post">Enable Waypoint => Table of Contents</label><br>

    <hr>

    <div class="">
        <p>Include in table of contents</p>
        <input type="checkbox" id="waypoint_H2_enable" name="waypoint_H2_enable" value="1" <?php checked( $checkbox_value_H2, '1' ); ?>>
        <label for="waypoint_H2_enable">H2</label><br>

        <input type="checkbox" id="waypoint_H3_enable" name="waypoint_H3_enable" value="1" <?php checked( $checkbox_value_H3, '1' ); ?>>
        <label for="waypoint_H3_enable">H3</label><br>
        <input type="checkbox" id="waypoint_H4_enable" name="waypoint_H4_enable" value="1" <?php checked( $checkbox_value_H4, '1' ); ?>>
        <label for="waypoint_H4_enable">H4</label><br>
        <input type="checkbox" id="waypoint_H5_enable" name="waypoint_H5_enable" value="1" <?php checked( $checkbox_value_H5, '1' ); ?>>
        <label for="waypoint_H5_enable">H5</label><br>
        <br>
        <hr>
        <input type="checkbox" id="waypoint_intro_enable" name="waypoint_intro_enable" value="1" <?php checked( $checkbox_value_intro, '1' ); ?>>
        <label for="waypoint_intro_enable">H5</label><br>
    </div>

    <hr>

    <div class="">
        <p>DOM element to attach table of contents to</p>
        <label for="waypoint_add_to_page">Specify a class, no dot - we will append waypoint as a child to this element</label><br>
        <input type="text" id="waypoint_add_to_page" name="waypoint_add_to_page" value="<?php echo esc_attr( $field_value_add_to ); ?>">
        <!-- no dot necessary for class name -->
        <!-- .post-content for example -->
        <p></p>
        
        <br />

        <!--form>
          <label for="selection">Choose an option:</label>
          <select id="selection" name="selection">
            <option value="class">Class</option>
            <option value="id">ID</option>
          </select>
        </form-->
        <p>DOM element to calculate main content width</p>
        <label for="waypoint_align_to_element">Enter a class name of a tag with main content - we will use this to calculate the width of the main content (no dot)</label><br>
        <input type="text" id="waypoint_align_to_element" name="waypoint_align_to_element" value="<?php echo esc_attr( $field_value_align_to_element ); ?>">

        <!-- this one needs a viewport width backup -->

        <!-- no dot necessary -->
        <!-- This actually moves Waypoint closer to the attached element rather than doing anything with vertical position -->
        <!-- it also can accept multiple classes separated by a space -->

        <p></p>
        <br />
            <p>Does your website have a header or masthead?</p>
            <label for="waypoint_masthead_define">Define a masthead by ID or class(es) - we will use this to determine waypoint's position.top - generally aligning waypoint's top with the bottom of the menubar / header - hastag or dot ok</label><br>
                <input type="text" id="waypoint_masthead_define" name="waypoint_masthead_define" value="<?php echo esc_attr( $field_value_masthead_define ); ?>">
        
    </div>

    <?php
} 

/*  ----------- SETTINGS PAGE ----------  */

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

/**
 * custom option and settings
 */
function wporg_settings_init() {
    // Register a new setting for "wporg" page.
    register_setting( 'wporg', 'wporg_options' );

    // Register a new section in the "wporg" page.
    add_settings_section(
        'wporg_section_developers', // ID
        __( 'The Matrix has you.', 'wporg' ), // Title
        'wporg_section_developers_callback', // Callback function
        'wporg' // Page slug
    );

    // Register a new field in the "wporg_section_developers" section, inside the "wporg" page.
    add_settings_field(
        'wporg_bg_color', // As of WP 4.6 this value is used only internally.
                                // Use $args' label_for to populate the id inside the callback.
            __( 'Pill', 'wporg' ),
        'wporg_bg_color_cb',
        'wporg',
        'wporg_section_developers',
        array(
            'label_for'         => 'wporg_bg_color',
            'class'             => 'wporg_row',
            'wporg_custom_data' => 'custom',
        )
    );
}

/**
 * Register our wporg_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'wporg_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function wporg_section_developers_callback( $args ) {
    ?>
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Follow the white rabbit.', 'wporg' ); ?></p>
    <?php
}

/**
 * Pill field callbakc function.
 *
 * WordPress has magic interaction with the following keys: label_for, class.
 * - the "label_for" key value is used for the "for" attribute of the <label>.
 * - the "class" key value is used for the "class" attribute of the <tr> containing the field.
 * Note: you can add custom key value pairs to be used inside your callbacks.
 *
 * @param array $args
 */
function wporg_bg_color_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'wporg_options' );
    $bg_color_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="wporg_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $bg_color_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['wporg_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Add a HEX color to be used as the background of the current / selected li:a section in the waypoint side menu', 'wporg' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'No hashtag necessary', 'wporg' ); ?>
    </p>

    <?php
    // Pass the value to JavaScript
    ?>
    <script type="text/javascript">
        window.bgColorValue = <?php echo json_encode( $bg_color_value ); ?>;
        console.log('Background Color Value:', bgColorValue); // Now the value is available in JS
    </script>
    <?php
}


/**
 * Add the top level menu page.
 */
function wporg_options_page() {
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'wporg_options_page_html'
    );
}

/**
 * Register our wporg_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'wporg_options_page' );


/**
 * Top level menu callback function
 */
function wporg_options_page_html() {
    // check user capabilities
    if ( ! current_user_can( 'manage_options' ) ) {
        return;
    }

    // add error/update messages

    // check if the user have submitted the settings
    // WordPress will add the "settings-updated" $_GET parameter to the url
    if ( isset( $_GET['settings-updated'] ) ) {
        // add settings saved message with the class of "updated"
        add_settings_error( 'wporg_messages', 'wporg_message', __( 'Settings Saved', 'wporg' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'wporg_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "wporg"
            settings_fields( 'wporg' );
            // output setting sections and their fields
            // (sections are registered for "wporg", each field is registered to a specific section)
            do_settings_sections( 'wporg' );
            // output save settings button
            submit_button( 'Save Settings' );
            ?>
        </form>
    </div>
    <?php
}


/*  ----------- SETTINGS FOR INDIVIDUAL POSTS ----------  */


 function my_custom_plugin_add_meta_box() {
    add_meta_box(
        'my_custom_plugin_meta_box',          // Unique ID
        'Post settings for Waypoint table of contents',                   // Box title
        'wporg_custom_box_html', // Content callback, must be of type callable
        ['post', 'page', 'portfolio'],        // Post types where the meta box should appear
        'normal',                             // Context (normal, side, advanced)
        'high'                                // Priority (default, high, low)
    );
}

add_action('add_meta_boxes', 'my_custom_plugin_add_meta_box');


function wporg_save_postdata( $post_id ) {
    // Log the POST data for debugging
    //error_log("POST data: " . print_r($_POST, true));
    error_log("Save function triggered for post ID: $post_id");

    // Security checks
    if ( ! isset( $_POST['wporg_nonce'] ) || ! wp_verify_nonce( $_POST['wporg_nonce'], 'wporg_save_postdata' ) ) {
        error_log("Nonce verification failed.");
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        error_log("Autosave detected, skipping save.");
        return;
    }

    if ( ! current_user_can( 'edit_post', $post_id ) ) {
        error_log("User does not have permission to edit this post.");
        return;
    }

  // Define an array of fields to save
    $fields = [
        'waypoint_enable_for_post',
        'waypoint_H2_enable',
        'waypoint_H3_enable',
        'waypoint_H4_enable',
        'waypoint_H5_enable',
        'waypoint_intro_enable',
        'waypoint_masthead_define',
        'waypoint_add_to_page',
        'waypoint_align_to_element',
        //'wporg_field_three',
        // Add more fields as needed
    ];

    foreach ( $fields as $field ) {
           if ( $field === 'waypoint_enable_for_post' ) {
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_enable_for_post'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_enable_for_post',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        } else if ( $field === 'waypoint_H2_enable' ) { //H2
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H2_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H2_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H3_enable' ) { //H3
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H3_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H3_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H4_enable' ) { //H4
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H4_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H4_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_H5_enable' ) { //H5
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H5_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_H5_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_intro_enable' ) { // Intro enable
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_intro_enable'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_intro_enable',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_masthead_define' ) { // Masthead field
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_masthead_define'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_masthead_define',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");


        } else if ( $field === 'waypoint_add_to_page' ) { // Add to page
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_add_to_page'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_add_to_page',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        } else if ( $field === 'waypoint_align_to_element' ) { // Align to element
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_align_to_element'] ) ? '1' : '0';
            update_post_meta(
                $post_id,
                '_waypoint_align_to_element',
                $checkbox_value
            );
            error_log("$field saved with value: $checkbox_value");

        }
            // Handle regular text fields
            if ( array_key_exists( $field, $_POST ) ) {
                $sanitized_value = sanitize_text_field( $_POST[$field] );
                update_post_meta(
                    $post_id,
                    '_' . $field,  // Use the field name as the meta key
                    $sanitized_value
                );
                error_log("$field saved with sanitized value: $sanitized_value");

            } else {
                error_log("$field not found in POST data.");
            }
        }
    }

add_action( 'save_post', 'wporg_save_postdata' );

function waypoint826_run() {
            
            // Output the JavaScript in the footer of the page
            function custom_checkbox_js() {

                if (is_singular('post') || is_singular('page') || is_singular('portfolio')) {
                    
                    $post_id = get_the_ID(); 

                    // To get values, go get function wporg_custom_box_html()
                    $checkbox_state = get_post_meta($post_id, '_waypoint_enable_for_post', true);
                    $field_state = get_post_meta($post_id, '_wporg_field', true);

                    if ($checkbox_state === '1') {
                        error_log("checkbox state php $checkbox_state");
                        $post = $post_id;
                    }
               
                    ?>

                    <script type="text/javascript">

                        // may not need this, I think it's just a verification / test function

                        function run_state () {
                        
                            var postId = <?php echo json_encode($post_id); ?>;
                            var checkboxState = <?php echo json_encode($checkbox_state); ?>;

                            if (checkboxState === '1') {
                                applyCustomFunction(postId);
                            } else {
                                
                            }

                            function applyCustomFunction(postId) {
                                //alert('Checkbox is checked for post ID: ' + postId);
                            }
                        };

                        run_state();
                    </script>

                    <?php
                }
            }
        add_action('wp_footer', 'custom_checkbox_js');

    /*  ----------- VARIABLES, USER CONFIGURATION ----------  */

    global $post;

    $post_id = get_the_ID(); 

    // To get values, go get function wporg_custom_box_html()
    $checkbox_state = get_post_meta($post_id, '_waypoint_enable_for_post', true);

    // To get values, go get function wporg_custom_box_html()
    $checkbox_value_H2 = get_post_meta( $post_id, '_waypoint_H2_enable', true );
    $checkbox_value_H3 = get_post_meta( $post_id, '_waypoint_H3_enable', true );
    $checkbox_value_H4 = get_post_meta( $post_id, '_waypoint_H4_enable', true );
    $checkbox_value_H5 = get_post_meta( $post_id, '_waypoint_H5_enable', true );
    $checkbox_value_intro = get_post_meta( $post_id, '_waypoint_intro_enable', true );


    $field_value_masthead_define = get_post_meta( $post_id, '_waypoint_masthead_define', true );

    $field_value_add_to = get_post_meta( $post_id, '_waypoint_add_to_page', true );
    $field_value_align_to_element = get_post_meta( $post_id, '_waypoint_align_to_element', true );

    if (is_page() || is_single() && $checkbox_state === '1' ) {

    ?>

    <script>
    document.addEventListener('DOMContentLoaded', function() {

        /*  ------  USER CONFIGURABLE, FROM INDIVIDUAL POSTS  ----------  */

        // Transfer php var into js var
        // Change into a number
        let waypointH2 = <?php echo json_encode($checkbox_value_H2); ?>;
        let wph2 = parseFloat(waypointH2);
        let waypointH3 = <?php echo json_encode($checkbox_value_H3); ?>;
        let wph3 = parseFloat(waypointH3);
        var waypointH4 = <?php echo json_encode($checkbox_value_H4); ?>;
        let wph4 = parseFloat(waypointH4);
        var waypointH5 = <?php echo json_encode($checkbox_value_H5); ?>;
        let wph5 = parseFloat(waypointH5);

        // Enable intro at top of list
        var waypointIntroEnable = <?php echo json_encode($checkbox_value_intro); ?>;

        // a class (for now) or ID (later) to add waypoint826-main to 
        let waypointFieldAddTo = <?php echo json_encode($field_value_add_to); ?>;
        // console.log(waypointFieldAddTo);

        // Horizontal alignment to an element
        let waypointFieldAlignToElement = <?php echo json_encode($field_value_align_to_element); ?>;

        // Define a masthead or menu
        let waypointMasthead = <?php echo json_encode($field_value_masthead_define); ?>;

        /*  ------  USER CONFIGURABLE, FROM GLOBAL SETTINGS  ----------  */

        // console.log('Background Color Value:', window.bgColorValue); // Now the value is available globally in JS
    


      



       /*  ----------- CREATE WAYPOINT CONTAINTER ----------  */

        // Create the main container to hold the waypoint table of contents
        let mainContainer = document.createElement('div');
        mainContainer.className = 'waypoint826-main';
        mainContainer.id = 'waypoint826-primary-container';

        // Append the main waypoint container to a DIV element on the page
        var entirePage = document.querySelector('.' + waypointFieldAddTo);
        entirePage.appendChild(mainContainer);

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

        var baseMargin = 8;



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
            var parentLeft = positionedParent.getBoundingClientRect().left;
        }

        // 
        if (parentLeft){
            //
            var adjustMargin = (parseFloat(parentLeft));
        } else {
            // 
            var adjustMargin = 0;
        }

        /*  ----------- USER CONFIGURABLE ----------  */

        // Remove whitespace from both ends of
        waypointFieldAlignToElement = waypointFieldAlignToElement.trim();

        var spaceForWaypoint;

        function calcWaypointSpaceNeeded() {

            // waypointFieldAlignToElement might have more than one class so, replace spaces with dots
            const checkAndCombineField = "." + waypointFieldAlignToElement.replace(/ /g, '.'); // replaces spaces with dots
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
            return { value1: spaceForWaypoint, value2: contentLeftEdge }
            // contentArea is a user configurable area 

        }

        // Set the right-hand position of the waypoint826 plugin
        function positionMainContainer() {

            const {value1, value2} = calcWaypointSpaceNeeded();
            spaceForWaypoint = value1;
            contentLeftEdge = value2;

            mainContainer.style.opacity = '0.2';

            /*  ----------- WINDOW RESIZE & HORZ. ALIGNMENT ----------  */

            /*  -----------  USER CONFIGURABLE  ----------  */

            // User can choose an element to align Waypoint to horizontally
            if ( waypointFieldAlignToElement ) {

                // Class or ID
                // Remove spaces and add a dot '.' from the class or ID data passed from the user
                var alignElement = waypointFieldAlignToElement.replace(/ /g, '.');

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
                    // Apply left offset
                    mainContainer.style.left = leftAdjustCalc;
                } // Cut an else if and put it into notes

            } // END if ( waypointFieldAlignToElement ) {

            // Start the pulse for 5 seconds
            startPulse(1500);

            /*  ----------- POSITION TO TOP ----------  */

            // searches for # or .
            const elementHasID = /#/g;
            const elementHasClassEs = /\./g; 

            // Test, find, replace and create var menuHeight
            if (elementHasID.test(waypointMasthead)) {
               
               //console.log("true");
               var elementIDName = String(waypointMasthead.replace('#', ''));
               var refToMasthead = document.getElementById(elementIDName);
               // console.log('refToMasthead' + refToMasthead);

            } else if (document.getElementById('masthead')) {

                // Test to see if there's a masthead
                var refToMasthead = document.getElementById('masthead'); //outputs an HTMLCollection
            }

            // default to height of the header element with a user configurable override
            if (refToMasthead) {
                var distanceFromTop = refToMasthead.getBoundingClientRect().height;
            }

            //console.log('Height of menu ' + distanceFromTop + 'px');
            mainContainer.style.top = ('0px');

            var box = document.getElementById('waypoint826-primary-container');
            let top = box.offsetTop;

            /*  ----------- SCROLL FUNCTION ----------  */

            var positionVar;
            
            // Initial position update
            updatePosition();

            window.addEventListener('scroll', function(event) {
                
                var y = document.documentElement.scrollTop || document.body.scrollTop;

                // Assuming there is only one element with this class
                var element = document.querySelector('.' + waypointFieldAddTo);

                if (element) {


                    // Which distanceFromTop should I use?


                    var distanceFromTop = element.getBoundingClientRect().top + window.scrollY;
                }
                // (y) how far page scrolled minus how far elem is from top of page, great than offset of box from containing elem

                if ( (y - distanceFromTop) >= top ) {  // if (which element?) is at the viewport top

                    // scrolling and masthead has disappeared, mainContainer should be aligned at the top IE FIXED
                     mainContainer.style.top = `0px`;

                } else  {
                    // for pos:ABSOLUTE behavior
                    updatePosition();
                }
            }); // End scroll function
        } // end positionMainContainer

        /*  ----------- FOR POS:ABSOLUTE BEHAVIOR ----------  */

        function updatePosition() {

            // Call earlier function that calculates 1) spaceforwaypoint and 2) contentleftedge
            const {value1, value2} = calcWaypointSpaceNeeded();
            // Define variables
            spaceForWaypoint = value1;
            contentLeftEdge = value2;

            // Get the bounding rectangle of the parent
            const parentRect = positionedParent.getBoundingClientRect();

            // Apply fixed positioning but adjust based on parent's position in the viewport
            mainContainer.style.top = `${parentRect.top + 0}px`;  // 50px from top of parent
            var offset = parseFloat(mainContainer.offsetWidth); // Number of pixels to offset

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
                    // Set left to calc
                    mainContainer.style.left = leftAdjustCalc;
           
                } else if ( spaceForWaypoint >= 700 ) {

                    // Waypoint displays BLOCK, 200 width
                    mainContainer.style.display = 'block';
                    mainContainer.style.width = '250px';

                    // Calc the left offset to give to 
                    var leftAdjustCalc = (contentLeftEdge - offset - (baseMargin * 5) + adjustMargin) + 'px';
                    // Set left to calc
                    mainContainer.style.left = leftAdjustCalc;

                }// Cut an else if and put it into notes

        }

        //window.addEventListener('scroll', updatePosition);

        /*--------  End positionMainContainer  -----------*/

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
        // Mapping links to sections
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

window.addEventListener('resize', debounce(handleResize, 200));

        /* ADMIN  */

        //https://medium.com/@hbahonar/how-to-create-wordpress-custom-admin-page-and-menu-from-scratch-ultimate-guide-updated-d7b4d2e57f96

        // https://developer.wordpress.org/plugins/settings/

    });
    </script>
    <?php
    }
}

add_action('wp', 'waypoint826_run');








