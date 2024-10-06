<?php
/**
* Merlin WP configuration file.
*
* @package   Merlin WP
* @version   @@pkg.version
* @link      https://merlinwp.com/
* @author    Rich Tabor, from ThemeBeans.com & the team at ProteusThemes.com
* @copyright Copyright (c) 2018, Merlin WP of Inventionn LLC
* @license   Licensed GPLv3 for Open Source Use
*/

if ( ! class_exists( 'Merlin' ) ) {
    return;
}

/**
* Set directory locations, text strings, and settings.
*/
$wizard = new Merlin(

    $config = array(
        'directory' => 'inc/core/merlin', // Location / directory where Merlin WP is placed in your theme.
        'merlin_url' => 'merlin', // The wp-admin page slug where Merlin WP loads.
        'parent_slug' => 'themes.php', // The wp-admin parent page slug for the admin menu item.
        'capability' => 'manage_options', // The capability required for this menu to be displayed to the user.
        'child_action_btn_url' => 'https://codex.wordpress.org/child_themes', // URL for the 'child-action-link'.
        'dev_mode' => true, // Enable development mode for testing.
        'license_step' => true, // EDD license activation step.
        'license_required' => true, // Require the license activation step.
        'license_help_url' => '', // URL for the 'license-tooltip'.
        'edd_remote_api_url' => '', // EDD_Theme_Updater_Admin remote_api_url.
        'edd_item_name' => '', // EDD_Theme_Updater_Admin item_name.
        'edd_theme_slug' => '', // EDD_Theme_Updater_Admin item_slug.
        'ready_big_button_url' => site_url(), // Link for the big button on the ready step.
    ),
    $strings = array(
        'admin-menu' => esc_html__( 'Theme Setup', 'styler' ),
        /* translators: 1: Title Tag 2: Theme Name 3: Closing Title Tag */
        'title%s%s%s%s' => esc_html__( '%1$s%2$s Themes &lsaquo; Theme Setup: %3$s%4$s', 'styler' ),
        'return-to-dashboard' => esc_html__( 'Return to the dashboard', 'styler' ),
        'ignore' => esc_html__( 'Disable this wizard', 'styler' ),
        'btn-skip' => esc_html__( 'Skip', 'styler' ),
        'btn-next' => esc_html__( 'Next', 'styler' ),
        'btn-start' => esc_html__( 'Start', 'styler' ),
        'btn-no' => esc_html__( 'Cancel', 'styler' ),
        'btn-plugins-install' => esc_html__( 'Install', 'styler' ),
        'btn-child-install' => esc_html__( 'Install', 'styler' ),
        'btn-content-install' => esc_html__( 'Install', 'styler' ),
        'btn-import' => esc_html__( 'Import', 'styler' ),
        'btn-license-activate' => esc_html__( 'Activate', 'styler' ),
        'btn-license-skip' => esc_html__( 'Later', 'styler' ),
        /* translators: Theme Name */
        'license-header%s' => esc_html__( 'Activate %s', 'styler' ),
        /* translators: Theme Name */
        'license-header-success%s' => esc_html__( '%s is Activated', 'styler' ),
        /* translators: Theme Name */
        'license%s' => esc_html__( 'Enter your license key to enable remote updates and theme support.', 'styler' ),
        'license-label' => esc_html__( 'License key', 'styler' ),
        'license-success%s' => esc_html__( 'The theme is already registered, so you can go to the next step!', 'styler' ),
        'license-json-success%s' => esc_html__( 'Your theme is activated! Remote updates and theme support are enabled.', 'styler' ),
        'license-tooltip' => esc_html__( 'Need help?', 'styler' ),
        /* translators: Theme Name */
        'welcome-header%s' => esc_html__( 'Welcome to %s', 'styler' ),
        'welcome-header-success%s' => esc_html__( 'Hi. Welcome back', 'styler' ),
        'welcome%s' => esc_html__( 'This wizard will set up your theme, install plugins, and import content. It is optional & should take only a few minutes.', 'styler' ),
        'welcome-success%s' => esc_html__( 'You may have already run this theme setup wizard. If you would like to proceed anyway, click on the "Start" button below.', 'styler' ),
        /* translators: child-header */
        'child-header' => esc_html__( 'Install Child Theme', 'styler' ),
        'child-header-success' => esc_html__( 'You\'re good to go!', 'styler' ),
        'child' => esc_html__( 'Let\'s build & activate a child theme so you may easily make theme changes.', 'styler' ),
        'child-success%s' => esc_html__( 'Your child theme has already been installed and is now activated, if it wasn\'t already.', 'styler' ),
        'child-action-link' => esc_html__( 'Learn about child themes', 'styler' ),
        'child-json-success%s' => esc_html__( 'Awesome. Your child theme has already been installed and is now activated.', 'styler' ),
        'child-json-already%s' => esc_html__( 'Awesome. Your child theme has been created and is now activated.', 'styler' ),
        /* translators: plugins-header */
        'plugins-header' => esc_html__( 'Install Plugins', 'styler' ),
        'plugins-header-success' => esc_html__( 'You\'re up to speed!', 'styler' ),
        'plugins' => esc_html__( 'Let\'s install some essential WordPress plugins to get your site up to speed.', 'styler' ),
        'plugins-success%s' => esc_html__( 'The required WordPress plugins are all installed and up to date. Press "Next" to continue the setup wizard.', 'styler' ),
        'plugins-action-link' => esc_html__( 'Advanced', 'styler' ),
        /* translators: import-header */
        'import-header' => esc_html__( 'Import Content', 'styler' ),
        'import' => esc_html__( 'Let\'s import content to your website, to help you get familiar with the theme.', 'styler' ),
        'import-action-link' => esc_html__( 'Advanced', 'styler' ),
        /* translators: ready-header */
        'ready-header' => esc_html__( 'All done. Have fun!', 'styler' ),
        /* translators: Theme Author */
        'ready%s' => esc_html__( 'Your theme has been all set up. Enjoy your new theme by %s.', 'styler' ),
        'ready-action-link' => esc_html__( 'Extras', 'styler' ),
        'ready-big-button' => esc_html__( 'View your website', 'styler' ),
        'ready-link-1' => sprintf( '<a href="%1$s">%2$s</a>', admin_url( 'customize.php' ), esc_html__( 'Start Customizing', 'styler' ) ),
    )
);
