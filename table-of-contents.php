<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 * Version: 1.0.1
 */

// Activation functions

// LOCALIZE SCRIPT: 

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


function waypoint826_custom_box_html( $post ) {

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

    // Intro li at top (IE content or other title)
    $checkbox_value_intro = get_post_meta( $post->ID, '_waypoint_intro_enable', true );

    // Define the masthead
    $field_value_masthead_define = get_post_meta( $post->ID, '_waypoint_masthead_define', true );

    // Which element to add waypoint, which element to align waypoint to
    $field_value_add_to = get_post_meta( $post->ID, '_waypoint_add_to_page', true );
    $field_value_align_to_element = get_post_meta( $post->ID, '_waypoint_align_to_element', true );

    // Configuration (probably will correspond with a settings page)
    // May not need this:
    // $field_value_a_bg_select_color = get_post_meta( $post->ID, '_waypoint_a_bg_select_color', true );

    // Add a nonce field for security
    wp_nonce_field( 'waypoint826_save_postdata', 'wporg_nonce' );

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
function waypoint826_settings_init() {
    // Register a new setting for "wporg" page.
    register_setting( 'wporg', 'wporg_options' );

    // Register a new section in the "wporg" page.
    add_settings_section(
        'wporg_section_developers', // ID
        __( 'The Matrix has you.', 'wporg' ), // Title
        'waypoint826_section_developers_callback', // Callback function
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
 * Register our waypoint826_settings_init to the admin_init action hook.
 */
add_action( 'admin_init', 'waypoint826_settings_init' );


/**
 * Custom option and settings:
 *  - callback functions
 */


/**
 * Developers section callback function.
 *
 * @param array $args  The settings array, defining title, id, callback.
 */
function waypoint826_section_developers_callback( $args ) {
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
function waypoint826_options_page() {
    add_menu_page(
        'WPOrg',
        'WPOrg Options',
        'manage_options',
        'wporg',
        'waypoint826_options_page_html'
    );
}

/**
 * Register our waypoint826_options_page to the admin_menu action hook.
 */
add_action( 'admin_menu', 'waypoint826_options_page' );


/**
 * Top level menu callback function
 */
function waypoint826_options_page_html() {
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


 function waypoint826_plugin_add_meta_box() {
    add_meta_box(
        'my_custom_plugin_meta_box',          // Unique ID
        'Post settings for Waypoint table of contents',                   // Box title
        'waypoint826_custom_box_html', // Content callback, must be of type callable
        ['post', 'page', 'portfolio'],        // Post types where the meta box should appear
        'normal',                             // Context (normal, side, advanced)
        'high'                                // Priority (default, high, low)
    );
}

add_action('add_meta_boxes', 'waypoint826_plugin_add_meta_box');


function waypoint826_save_postdata( $waypoint826_post_id ) {
    // Log the POST data for debugging
    //error_log("POST data: " . print_r($_POST, true));
    error_log("Save function triggered for post ID: $waypoint826_post_id");

    // Security checks
    if ( ! isset( $_POST['wporg_nonce'] ) || ! wp_verify_nonce( $_POST['wporg_nonce'], 'waypoint826_save_postdata' ) ) {
        error_log("Nonce verification failed.");
        return;
    }

    if ( defined( 'DOING_AUTOSAVE' ) && DOING_AUTOSAVE ) {
        error_log("Autosave detected, skipping save.");
        return;
    }

    if ( ! current_user_can( 'edit_post', $waypoint826_post_id ) ) {
        error_log("User does not have permission to edit this post.");
        return;
    }

  // Define an array of fields to save
    $waypoint826_fields = [
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

    foreach ( $waypoint826_fields as $waypoint8field ) {
           if ( $waypoint8field === 'waypoint_enable_for_post' ) {
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_enable_for_post'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_enable_for_post',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");

        } else if ( $waypoint8field === 'waypoint_H2_enable' ) { //H2
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H2_enable'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_H2_enable',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_H3_enable' ) { //H3
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H3_enable'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_H3_enable',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_H4_enable' ) { //H4
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H4_enable'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_H4_enable',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_H5_enable' ) { //H5
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_H5_enable'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_H5_enable',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_intro_enable' ) { // Intro enable
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_intro_enable'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_intro_enable',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_masthead_define' ) { // Masthead field
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_masthead_define'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_masthead_define',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } else if ( $waypoint8field === 'waypoint_add_to_page' ) { // Add to page
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_add_to_page'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_add_to_page',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");

        } else if ( $waypoint8field === 'waypoint_align_to_element' ) { // Align to element
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_align_to_element'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_align_to_element',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");

        }
            // Handle regular text fields
            if ( array_key_exists( $waypoint8field, $_POST ) ) {
                $sanitized_value = sanitize_text_field( $_POST[$waypoint8field] );
                update_post_meta(
                    $waypoint826_post_id,
                    '_' . $waypoint8field,  // Use the field name as the meta key
                    $sanitized_value
                );
                error_log("$waypoint8field saved with sanitized value: $sanitized_value");

            } else {
                error_log("$waypoint8field not found in POST data.");
            }
        }
    }

add_action( 'save_post', 'waypoint826_save_postdata' );

function waypoint826_run() {
            
            // Output the JavaScript in the footer of the page
            function custom_checkbox_js() {

                if (is_singular('post') || is_singular('page') || is_singular('portfolio')) {
                    
                    $waypoint826_post_id = get_the_ID(); 

                    // To get values, go get function waypoint826_custom_box_html()
                    $checkbox_state = get_post_meta($waypoint826_post_id, '_waypoint_enable_for_post', true);
                    $field_state = get_post_meta($waypoint826_post_id, '_wporg_field', true);

                    if ($checkbox_state === '1') {
                        error_log("checkbox state php $checkbox_state");
                        $post = $waypoint826_post_id;
                    }
               
                    ?>

                    <script type="text/javascript">

                        // may not need this, I think it's just a verification / test function

                        function run_state () {
                        
                            var postId = <?php echo json_encode($waypoint826_post_id); ?>;
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

    // In your theme's functions.php or custom plugin
    function waypoint826_enqueue_my_custom_scripts() {

        /*  ----------- VARIABLES, USER CONFIGURATION ----------  */

        // global $post;

         if (is_page() || is_single()) {

            $waypoint826_post_id = get_the_ID(); 

            // To get values, go get function waypoint826_custom_box_html()
            // Is the page waypoint enabled?
            $checkbox_state = get_post_meta($waypoint826_post_id, '_waypoint_enable_for_post', true);

            // To get values, go get function waypoint826_custom_box_html()
            // Individual page settings for H2 etc.
            $checkbox_value_H2 = get_post_meta( $waypoint826_post_id, '_waypoint_H2_enable', true );
            $checkbox_value_H3 = get_post_meta( $waypoint826_post_id, '_waypoint_H3_enable', true );
            $checkbox_value_H4 = get_post_meta( $waypoint826_post_id, '_waypoint_H4_enable', true );
            $checkbox_value_H5 = get_post_meta( $waypoint826_post_id, '_waypoint_H5_enable', true );

            // TEST
            // echo $checkbox_value_H3;
            // Shows at top left, right now the 

            // Indiv. page settings for masthead, add to page, align to element
            $field_value_masthead_define = get_post_meta( $waypoint826_post_id, '_waypoint_masthead_define', true );
            $field_value_add_to = get_post_meta( $waypoint826_post_id, '_waypoint_add_to_page', true );
            $field_value_align_to_element = get_post_meta( $waypoint826_post_id, '_waypoint_align_to_element', true );

            // Not sure what this variable is or is used for, investigate
            $checkbox_value_intro = get_post_meta( $waypoint826_post_id, '_waypoint_intro_enable', true );

            if ($checkbox_state === '1') {

                // Enqueue your custom JavaScript file
                wp_enqueue_script( 
                    'my-custom-js', 
                    plugins_url('js/waypoint-custom.js', __FILE__ ),
                    array(),
                    '1.0.0',
                    true // Loads scripts...where?
                );

                // Retrieve the option from the (global) settings
                $options = get_option( 'wporg_options' );
                $bg_color_value = isset( $options['wporg_bg_color'] ) ? $options['wporg_bg_color'] : '';

                // Make sure variables are defined
                $checkbox_value_H2 = isset( $checkbox_value_H2 ) ? $checkbox_value_H2 : '';
                $checkbox_value_H3 = isset( $checkbox_value_H3 ) ? $checkbox_value_H3 : '';
                $checkbox_value_H4 = isset( $checkbox_value_H4 ) ? $checkbox_value_H4 : '';
                $checkbox_value_H5 = isset( $checkbox_value_H5 ) ? $checkbox_value_H5 : '';
                $checkbox_value_intro = isset( $checkbox_value_intro ) ? $checkbox_value_intro : '';
                $field_value_add_to = isset( $field_value_add_to ) ? $field_value_add_to : '';
                $field_value_align_to_element = isset( $field_value_align_to_element ) ? $field_value_align_to_element : '';
                $field_value_masthead_define = isset( $field_value_masthead_define ) ? $field_value_masthead_define : '';

             
                /* if ( isset($bg_color_value, $checkbox_value_H2, $checkbox_value_H3, $checkbox_value_H4, $checkbox_value_H5, $checkbox_value_intro, $field_value_add_to, $field_value_align_to_element, $field_value_masthead_define) ) { */
                    // Pass the PHP variable to the JavaScript file using wp_localize_script
                    wp_localize_script( 'my-custom-js', 'myScriptData', array(
                        'bgColorValue' => $bg_color_value,
                        'waypointH2' => $checkbox_value_H2,
                        'waypointH3' => $checkbox_value_H3,
                        'waypointH4' => $checkbox_value_H4,
                        'waypointH5' => $checkbox_value_H5,
                        'waypointIntroEnable' => $checkbox_value_intro,
                        'waypointFieldAddTo' => $field_value_add_to,
                        'waypointFieldAlignToElement' => $field_value_align_to_element,
                        'waypointMasthead' => $field_value_masthead_define,

                    ));
                /* } else {
                     error_log('Some variables are not defined for wp_localize_script');
            } */

            } // end if
        }
    }
    add_action( 'wp_enqueue_scripts', 'waypoint826_enqueue_my_custom_scripts' );

} // end ...

add_action('wp', 'waypoint826_run');

