<?php
/**
 * Plugin Name: Waypoint 826 - Table of Contents
 * Description: Adds a table of contents to select pages and posts based on h2, h3 and h4 headings
 * Author: Jon Simmons
 * Version: 1.9.0
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


// Shortcode content
/* function waypoint_return_shortcode() { */

    /* 
    * Notes
    * 1. Need to be able to pass border color, drop-shadow color and intensity
    * 1.1 - Have a screen Y sensor so can position towards the bottom edge of the view port if needed (?)
    * 1.2 - Oh yeah, need the return on-press functionality
    * 2. Eventually pass the words to write in the button and around the button
    * 3. Really nice to have: pass an icon? Maybe not


    call via [waypoint_shortcode]

    */

  /*  //
    return "<div class='waypoint-sc-scroll-down'><span>Press</span><span class='waypoint-sc-button'><span class='waypoint-sc-return'>return</span><span class='waypoint-sc-icon'></span></span><!--end button--><span>to scroll down</span></div>";

}

// First item is what you call in the admin IE [waypoint_shortcode], the second itme is the actual function
add_shortcode('waypoint_shortcode', 'waypoint_return_shortcode');

*/
// 
function waypoint826_custom_box_html( $post ) {

    /*  
            
        HOW TO ADD AN ADMIN - places to look
        • NOTE: use Waypoint_ naming structure

        • Add to - Add input field
        • Add to - Retreive value for the admin field
        • Add to - Define the field in $fields
        • Add to - it in the forEach loop in waypoint_post_savedata()
        • Add to - it just before the DOMContentLoaded function to transition from php to js variables

    */


    /*
        
        To-Do NEXT
        - Background color for Waypoint PASSED TO JS
        - Text size PASSED TO JS
        - Text color  - For all
        - Text color - selected
        - Text color - contents
        - Add scroll to top - yes/no






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
        - DONE: Background selected color

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
    // 3.22.2025
    //$field_value_masthead_define = get_post_meta( $post->ID, '_waypoint_masthead_define', true );

    // Which element to add waypoint, which element to align waypoint to

    //3.21.2025
    //$field_value_add_to = get_post_meta( $post->ID, '_waypoint_add_to_page', true );

    //3.21.2025
    //$field_value_reposition = get_post_meta( $post->ID, '_waypoint_reposition', true );
    //$field_value_align_to_element = get_post_meta( $post->ID, '_waypoint_align_to_element', true );

    // Configuration (probably will correspond with a settings page)
    // May not need this:
    // $field_value_a_bg_select_color = get_post_meta( $post->ID, '_waypoint_a_bg_select_color', true );

    // Add a nonce field for security
    wp_nonce_field( 'waypoint826_save_postdata', 'waypoint_nonce' );

    ?>
    <h4>Enable Waypoint for: <?php the_title (); ?></h4>
    <input type="checkbox" id="waypoint_enable_for_post" name="waypoint_enable_for_post" value="1" <?php checked( $checkbox_value, '1' ); ?>>
    <label for="waypoint_enable_for_post">Enable Waypoint => Table of Contents</label><br>
    <br />
    <hr>

    <div class="">
        <h4>Choose which HTML headings to include in Waypoint</h4>
        <input type="checkbox" id="waypoint_H2_enable" name="waypoint_H2_enable" value="1" <?php checked( $checkbox_value_H2, '1' ); ?>>
        <label for="waypoint_H2_enable">H2</label><br>

        <input type="checkbox" id="waypoint_H3_enable" name="waypoint_H3_enable" value="1" <?php checked( $checkbox_value_H3, '1' ); ?>>
        <label for="waypoint_H3_enable">H3</label><br>
        <input type="checkbox" id="waypoint_H4_enable" name="waypoint_H4_enable" value="1" <?php checked( $checkbox_value_H4, '1' ); ?>>
        <label for="waypoint_H4_enable">H4</label><br>
        <input type="checkbox" id="waypoint_H5_enable" name="waypoint_H5_enable" value="1" <?php checked( $checkbox_value_H5, '1' ); ?>>
        <label for="waypoint_H5_enable">H5</label><br>
        <br>
        
        <!--input type="checkbox" id="waypoint_intro_enable" name="waypoint_intro_enable" value="1" <?php checked( $checkbox_value_intro, '1' ); ?>>
        <label for="waypoint_intro_enable">H5</label><br-->
    </div>

    <hr>
    <?php

    // 3.21.2025
    /*
    <div class="other-options">
        <h4 style="margin-bottom: .15em;">Choose where to place Waypoint</h4>
        <label for="waypoint_add_to_page">Enter a classname(s) to append waypoint as a child</label>
        <br /><!-- no dot --><br />
        <input type="text" id="waypoint_add_to_page" name="waypoint_add_to_page" value="<?php echo esc_attr( $field_value_add_to ); ?>">
        <!-- no dot necessary for class name -->
        <!-- .post-content for example -->
        <p></p>
        
        <br />
        <hr>
    */
        ?>

        <?php
        /*
        <h4 style="margin-bottom: .15em;">Define a wrapper to be re-positioned</h4>
        <label for="waypoint_reposition">Enter a classname of the content wrapper</label>
        <br /><!-- no dot --><br />
        <input type="text" id="waypoint_reposition" name="waypoint_reposition" value="<?php echo esc_attr( $field_value_reposition ); ?>">
        <!-- no dot necessary for class name -->
        <!-- .post-content for example -->
        <p></p>
        
        <br />
        <hr>
        */
        ?>

        <!--form>
          <label for="selection">Choose an option:</label>
          <select id="selection" name="selection">
            <option value="class">Class</option>
            <option value="id">ID</option>
          </select>
        </form-->
        <?php
        /*
        <h4 style="margin-bottom: .15em;">Float Waypoint next to content</h4>
        <label for="waypoint_align_to_element">Enter a class name of a tag with main content - we will use this to calculate the width of the main content</label>
        <br /><br /><!-- no dot -->
        <input type="text" id="waypoint_align_to_element" name="waypoint_align_to_element" value="<?php echo esc_attr( $field_value_align_to_element ); ?>">
        <p></p>

        <br />
        */
        /*

        <hr>

   
        <h4 style="margin-bottom: .15em;">Define a header or masthead</h4>
        <label for="waypoint_masthead_define">Define a masthead by ID or class(es) - we will use this to determine waypoint's position.top - generally aligning waypoint's top with the bottom of the menubar / header</label><!-- hastag or dot ok -->
        <br /><br />
        <input type="text" id="waypoint_masthead_define" name="waypoint_masthead_define" value="<?php echo esc_attr( $field_value_masthead_define ); ?>">
        <p></p>
        <br />
        */
    ?>
    </div>

    <?php
} 

/*  ----------- SETTINGS PAGE ----------  */

/**
 * @internal never define functions inside callbacks.
 * these functions could be run multiple times; this would result in a fatal error.
 */

 /*  
            
        HOW TO ADD AN ADMIN - places to look
        • NOTE: use Waypoint_ naming structure

        • Add to - Add input field
        • Add to - function waypoint826_settings_init()
        • Add function similar to EXAMPLE: function waypoint_bg_color_cb() - there's a variable that needs to be renamed, it appears in 2 places
        • Add variable to function waypoint826_enqueue_my_custom_scripts()



       PLAN
        • Choose where to place Waypoint
        • Define a wrapper to be re-positioned
        • Float waypoint next to content
        • Define a header or masthead


*/

/**
 * custom option and settings
 */
function waypoint826_settings_init() {
    // Register a new setting for "waypoint" page.
    register_setting( 'waypoint', 'waypoint_options' );

    // Register a new section in the "waypoint" page.
    add_settings_section(
        'waypoint_section_developers', // ID
        __( 'For posts only - customize the waypoint826 table of contents', 'waypoint' ), // Title
        'waypoint826_section_developers_callback', // Callback function
        'waypoint' // Page slug
    );

    /* MOVED SETTINGS FROM INDIVIDUAL PAGES */

    // Register a new field

    add_settings_field(
        'waypoint_append_to',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Append to for mobile', 'waypoint' ),     // Label
        'waypoint_append_to_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_append_to', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'waypoint_place_next_to',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Place next to', 'waypoint' ),     // Label
        'waypoint_place_next_to_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_place_next_to', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );

    add_settings_field(
        'waypoint_masthead',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Masthead', 'waypoint' ),     // Label
        'waypoint_masthead_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_masthead', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );



    /* ORIG SETTINGS */

    // Register a new field

    add_settings_field(
        'waypoint_show_or_hide',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Show or hide scroll to top', 'waypoint' ),     // Label
        'waypoint_show_or_hide_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_show_or_hide', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );


    // Register a new field
    add_settings_field(
        'waypoint_menu_title',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Show menu title', 'waypoint' ),     // Label
        'waypoint_menu_title_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_menu_title', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );

    // Register a new field in the "waypoint_section_developers" section, inside the "waypoint" page.
    add_settings_field(
        'waypoint_bg_color', // Field ID - As of WP 4.6 this value is used only internally.
                                // Use $args' label_for to populate the id inside the callback.
            __( 'Selected color', 'waypoint' ), // Label
        'waypoint_bg_color_cb', // callback function to display input field
        'waypoint', //page slug
        'waypoint_section_developers', // section slug
        array(
            'label_for'         => 'waypoint_bg_color', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );




    // Register a new field
    add_settings_field(
        'waypoint_border_color',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Left selected border color', 'waypoint' ),     // Label
        'waypoint_border_color_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_border_color', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );


    // Register a new field
    add_settings_field(
        'waypoint_bg',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Set page background color', 'waypoint' ),     // Label
        'waypoint_bg_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_bg', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );

       // Register a new field
    add_settings_field(
        'waypoint_text_size',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Set the text size', 'waypoint' ),     // Label
        'waypoint_text_size_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_text_size', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
        )
    );

        // Register a new field
    add_settings_field(
        'waypoint_text_color',                       // Field ID - As of WP 4.6 this value is used only internally.
                                                        // Use $args' label_for to populate the id inside the callback.
            __( 'Set the text color', 'waypoint' ),     // Label
        'waypoint_text_color_cb',                    // callback function to display input field
        'waypoint',                                     //page slug
        'waypoint_section_developers',                  // section slug
        array(
            'label_for'         => 'waypoint_text_color', 
            'class'             => 'waypoint_row',
            'waypoint_custom_data' => 'custom',
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
    <p id="<?php echo esc_attr( $args['id'] ); ?>"><?php esc_html_e( 'Customize Waypoint layout, colors and text', 'waypoint' ); ?></p>
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


/* NEW GLOBAL SETTINGS */

function waypoint_append_to_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_append_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $waypoint_append_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'On mobile, waypoint will appear in this area', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'Enter a classname(s) - No dot or hashtag necessary', 'waypoint' ); ?>
    </p>

    <?php

}

function waypoint_place_next_to_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_place_next_to_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $waypoint_place_next_to_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Place Waypoint next to element', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'Class identifier - no dot or hashtag necessary', 'waypoint' ); ?>
    </p>

    <?php

}

function waypoint_masthead_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_masthead_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $waypoint_masthead_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Define a navigation or masthead by ID', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'ID - no dot or hashtag necessary, this will stop waypoints scrolling at the masthead', 'waypoint' ); ?>
    </p>

    <?php

}



/* ORIG SETTINGS */

function waypoint_bg_color_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $bg_color_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $bg_color_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Add a HEX color for the active area background', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'No hashtag necessary', 'waypoint' ); ?>
    </p>



    <?php
    // Pass the value to JavaScript
    ?>
    <script type="text/javascript">
        //window.bgColorValue = <?php echo json_encode( $bg_color_value ); ?>;
        //console.log('Background Color Value:', bgColorValue); // Now the value is available in JS
    </script>
    <?php
}

function waypoint_text_color_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_text_color = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $waypoint_text_color ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Add a HEX color to be used as the color of the text', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'No hashtag necessary', 'waypoint' ); ?>
    </p>



    <?php
    // Pass the value to JavaScript
    ?>
    <script type="text/javascript">
        //window.bgColorValue = <?php echo json_encode( $bg_color_value ); ?>;
        //console.log('Background Color Value:', bgColorValue); // Now the value is available in JS
    </script>
    <?php
}

function waypoint_bg_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $bg_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $bg_value ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Describe the site background color as a HEX color', 'waypoint' ); ?>
    </p>
    <p class="description">
        <?php esc_html_e( 'No hashtag necessary', 'waypoint' ); ?>
    </p>



    <?php
    // Pass the value to JavaScript
    ?>
    <script type="text/javascript">
        //window.bgColorValue = <?php echo json_encode( $bg_color_value ); ?>;
        //console.log('Background Color Value:', bgColorValue); // Now the value is available in JS
    </script>
    <?php
}


function waypoint_border_color_cb( $args ) {
      // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_border_color_val = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <input 
        type="text" 
        id="<?php echo esc_attr( $args['label_for'] ); ?>" 
        name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]" 
        value="<?php echo esc_attr( $waypoint_border_color_val ); ?>" 
        data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>">

    <p class="description">
        <?php esc_html_e( 'Add a HEX color for the left selected border', 'waypoint' ); ?>
        <p class="description">
            <?php esc_html_e( 'No hashtag necessary', 'waypoint' ); ?>
        </p>
    </p>


    <?php
    // Pass the value to JavaScript
    ?>
    <script type="text/javascript">
        window.borderColorValue = <?php echo json_encode( $waypoint_border_color_val ); ?>;
        console.log('Background Color Value:', borderColorValue); // Now the value is available in JS
    </script>
    <?php

}


function waypoint_show_or_hide_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_show_or_hide_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <select 
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>"
            name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="show" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'show', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Show', 'waypoint' ); ?>
        </option>
        <option value="hide" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'hide', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Hide', 'waypoint' ); ?>
        </option> 
    </select>

      <p class="description">
        <?php esc_html_e( 'Which side of the screen should waypoint appear', 'waypoint' ); ?>
    </p>
    <?php

    ?>
    <script type="text/javascript">
       // window.leftOrRight = <?php echo json_encode( $waypoint_show_or_hide_value ); ?>;
       // console.log('Left or Right:', leftOrRight); // Now the value is available in JS
    </script>
    <?php
}

function waypoint_text_size_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_show_or_hide_value = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <select 
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>"
            name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="8" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '8', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '8px', 'waypoint' ); ?>
        </option>
        <option value="10" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '10', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '10px', 'waypoint' ); ?>
        </option> 
        <option value="12" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '12', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '12px', 'waypoint' ); ?>
        </option> 
        <option value="14" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '14', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '14px', 'waypoint' ); ?>
        </option> 
        <option value="16" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '16', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '16px', 'waypoint' ); ?>
        </option> 
        <option value="18" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '18', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '18px', 'waypoint' ); ?>
        </option> 
        <option value="20" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], '20', false ) ) : ( '' ); ?>>
            <?php esc_html_e( '20px', 'waypoint' ); ?>
        </option> 
    </select>

      <p class="description">
        <?php esc_html_e( 'Set the text size for Waypoint', 'waypoint' ); ?>
    </p>
    <?php

    ?>
    <script type="text/javascript">
       // window.leftOrRight = <?php echo json_encode( $waypoint_show_or_hide_value ); ?>;
       // console.log('Left or Right:', leftOrRight); // Now the value is available in JS
    </script>
    <?php
}

function waypoint_menu_title_cb( $args ) {
    // Get the value of the setting we've registered with register_setting()
    $options = get_option( 'waypoint_options' );
    $waypoint_menu_title_val = isset( $options[ $args['label_for'] ] ) ? $options[ $args['label_for'] ] : ''; // Get the current value
    ?>

    <select 
            id="<?php echo esc_attr( $args['label_for'] ); ?>"
            data-custom="<?php echo esc_attr( $args['waypoint_custom_data'] ); ?>"
            name="waypoint_options[<?php echo esc_attr( $args['label_for'] ); ?>]">
        <option value="visible" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'visible', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'Visible title', 'waypoint' ); ?>
        </option>
        <option value="invisible" <?php echo isset( $options[ $args['label_for'] ] ) ? ( selected( $options[ $args['label_for'] ], 'invisible', false ) ) : ( '' ); ?>>
            <?php esc_html_e( 'No title', 'waypoint' ); ?>
        </option> 
    </select>

      <p class="description">
        <?php esc_html_e( 'A title reading Page Contents will appear above the waypoint table of contents', 'waypoint' ); ?>
    </p>
    <?php

    ?>
    <script type="text/javascript">
       window.titleVal = <?php echo json_encode( $waypoint_menu_title_val ); ?>;
       console.log('Title Val:', titleVal); // Now the value is available in JS
    </script>
    <?php
}



/**
 * Add the top level menu page.
 */
function waypoint826_options_page() {
    add_menu_page(
        'Waypoint table of contents', // on settings page: title
        'Waypoint Options', // Title on left menu
        'manage_options',
        'waypoint',
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
        add_settings_error( 'waypoint_messages', 'waypoint_message', __( 'Settings Saved', 'waypoint' ), 'updated' );
    }

    // show error/update messages
    settings_errors( 'waypoint_messages' );
    ?>
    <div class="wrap">
        <h1><?php echo esc_html( get_admin_page_title() ); ?></h1>
        <form action="options.php" method="post">
            <?php
            // output security fields for the registered setting "waypoint"
            settings_fields( 'waypoint' );
            // output setting sections and their fields
            // (sections are registered for "waypoint", each field is registered to a specific section)
            do_settings_sections( 'waypoint' );
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
    if ( ! isset( $_POST['waypoint_nonce'] ) || ! wp_verify_nonce( $_POST['waypoint_nonce'], 'waypoint826_save_postdata' ) ) {
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
        //'waypoint_masthead_define',
        //'waypoint_align_to_element',
        //'waypoint_reposition',
        //'waypoint_add_to_page', /* 3.21.2025 */
        //'waypoint_field_three',
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


        } /*
            // 3.22.2025

            else if ( $waypoint8field === 'waypoint_masthead_define' ) { // Masthead field
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_masthead_define'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_masthead_define',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");


        } */

        /* 
        // 3.21.2025

        else if ( $waypoint8field === 'waypoint_align_to_element' ) { // Align to element
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_align_to_element'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_align_to_element',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");

        } else if ( $waypoint8field === 'waypoint_reposition' ) { // Align to element
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_reposition'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_reposition',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");
        }*/
        //3.21.2025
        /*else if ( $waypoint8field === 'waypoint_add_to_page' ) { // Add to page
            // Handle the checkbox field
            $checkbox_value = isset( $_POST['waypoint_add_to_page'] ) ? '1' : '0';
            update_post_meta(
                $waypoint826_post_id,
                '_waypoint_add_to_page',
                $checkbox_value
            );
            error_log("$waypoint8field saved with value: $checkbox_value");

        } */
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
                    $field_state = get_post_meta($waypoint826_post_id, '_waypoint_field', true);

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
            
            // 3.22.2025
            //$field_value_masthead_define = get_post_meta( $waypoint826_post_id, '_waypoint_masthead_define', true );



            //3.21.2025
            //$field_value_add_to = get_post_meta( $waypoint826_post_id, '_waypoint_add_to_page', true );

            //3.21.2025
            // $field_value_reposition = get_post_meta( $waypoint826_post_id, '_waypoint_reposition', true );

            // 3.21.2025
            // $field_value_align_to_element = get_post_meta( $waypoint826_post_id, '_waypoint_align_to_element', true );

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
                $options = get_option( 'waypoint_options' );
                $bg_color_value = isset( $options['waypoint_bg_color'] ) ? $options['waypoint_bg_color'] : '';
                $bg_value = isset( $options['waypoint_bg'] ) ? $options['waypoint_bg'] : '';
                $waypoint_text_color = isset( $options['waypoint_text_color'] ) ? $options['waypoint_text_color'] : '';
                $waypoint_show_or_hide_value = isset( $options['waypoint_show_or_hide'] ) ? $options['waypoint_show_or_hide'] : '';
                $waypoint_text_size = isset( $options['waypoint_text_size'] ) ? $options['waypoint_text_size'] : '';
                $waypoint_border_color_val = isset( $options['waypoint_border_color'] ) ? $options['waypoint_border_color'] : '';
                 $waypoint_menu_title_val = isset( $options['waypoint_menu_title'] ) ? $options['waypoint_menu_title'] : '';

                 // added 3.21.2025
                 $waypoint_append_value = isset( $options['waypoint_append_to'] ) ? $options['waypoint_append_to'] : '';

                 // added 3.21.2025
                 $waypoint_place_next_to_value = isset( $options['waypoint_place_next_to'] ) ? $options['waypoint_place_next_to'] : '';

                 $waypoint_masthead_value = isset( $options['waypoint_masthead'] ) ? $options['waypoint_masthead'] : '';

                 //echo $waypoint_place_next_to_value;


                // Make sure variables are defined
                $checkbox_value_H2 = isset( $checkbox_value_H2 ) ? $checkbox_value_H2 : '';
                $checkbox_value_H3 = isset( $checkbox_value_H3 ) ? $checkbox_value_H3 : '';
                $checkbox_value_H4 = isset( $checkbox_value_H4 ) ? $checkbox_value_H4 : '';
                $checkbox_value_H5 = isset( $checkbox_value_H5 ) ? $checkbox_value_H5 : '';
                $checkbox_value_intro = isset( $checkbox_value_intro ) ? $checkbox_value_intro : '';

                // 3.21.2025
                //$field_value_add_to = isset( $field_value_add_to ) ? $field_value_add_to : '';

                //3.21.2025
                //$field_value_reposition = isset( $field_value_reposition ) ? $field_value_reposition : '';

                // 3.21.2025
                // $field_value_align_to_element = isset( $field_value_align_to_element ) ? $field_value_align_to_element : '';

                // 3.22.2025
                //$field_value_masthead_define = isset( $field_value_masthead_define ) ? $field_value_masthead_define : '';

             
                /* if ( isset($bg_color_value, $checkbox_value_H2, $checkbox_value_H3, $checkbox_value_H4, $checkbox_value_H5, $checkbox_value_intro, $field_value_add_to, $field_value_align_to_element, $field_value_masthead_define) ) { */

                // Getting rid of $field_value_reposition 3.21.2025

                    // Pass the PHP variable to the JavaScript file using wp_localize_script
                    wp_localize_script( 'my-custom-js', 'myScriptData', array(
                        'bgColorValue' => $bg_color_value, // selected state
                        'bgValue' => $bg_value, // bg of page - passing to js
                        'waypointTextColor' => $waypoint_text_color, 
                        'waypointH2' => $checkbox_value_H2,
                        'waypointH3' => $checkbox_value_H3,
                        'waypointH4' => $checkbox_value_H4,
                        'waypointH5' => $checkbox_value_H5,
                        'waypointIntroEnable' => $checkbox_value_intro,
                        'waypointFieldAddTo' => $waypoint_append_value, // added 3.21.2025
                        'waypointFieldAlignToElement' => $waypoint_place_next_to_value,
                        'waypointShowScrollUp' => $waypoint_show_or_hide_value,
                        'waypointTextSize' => $waypoint_text_size, // passing to js
                        'waypointBorderColor' => $waypoint_border_color_val,
                        'waypointMenuTitleOnOff' => $waypoint_menu_title_val,
                        'waypointMasthead' => $waypoint_masthead_value,
                        // 'waypointFieldAddTo' => $field_value_add_to,  /* line 820 waypoint-custom.js */
                        // 'waypointFieldReposition' => $field_value_reposition, 

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

