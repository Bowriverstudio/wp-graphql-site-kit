<?php
/**
 * Plugin Name: WP GraphQL Site Kit
 * Plugin URI: https://github.com/Bowriverstudio/wp-graphql-site-kit-wp
 * GitHub Plugin URI: https://github.com/Bowriverstudio/wp-graphql-site-kit-wp
 * Description: GraphQL API for Google Site Kit
 * Author: Maurice Tadros
 * Author URI: http://www.bowriverstudio.com
 * Version: 1.0.0
 * Text Domain: wp-graphql-site-kit
 * Domain Path: /languages/
 * Requires PHP: 7.1
 * License: GPL-3
 * License URI: https://www.gnu.org/licenses/gpl-3.0.html
 *
 */

// Exit if accessed directly.
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}


use WPGraphQL\AppContext;
/**
 * Ensures core depenecies are active
 * @see https://github.com/ashhitch/wp-graphql-yoast-seo/blob/master/wp-graphql-yoast-seo.php
 */
add_action('admin_init', function () {


	$options = get_option('active_plugins');

    $core_dependencies = [
        'WPGraphQL plugin' => class_exists('WPGraphQL'),
		'Google Site Kit' =>  in_array('google-site-kit/google-site-kit.php', $options),
		'Google Site Kit Dev Settings' =>  in_array('google-site-kit-dev-settings/google-site-kit-dev-settings.php', $options),
    ];

    $missing_dependencies = array_keys(
        array_diff($core_dependencies, array_filter($core_dependencies))
    );
    $display_admin_notice = static function () use ($missing_dependencies) {
        ?>
            <div class="notice notice-error">
              <p><?php esc_html_e(
                  'The WPGraphQL Site Kite plugin can\'t be loaded because these dependencies are missing:',
                  'wp-graphql-site-kit'
              ); ?>
              </p>
              <ul>
                <?php foreach ($missing_dependencies as $missing_dependency): ?>
                  <li><?php echo esc_html($missing_dependency); ?></li>
                <?php endforeach; ?>
              </ul>
            </div>
            <?php
    };


    if (!empty($missing_dependencies)) {
        add_action('network_admin_notices', $display_admin_notice);
        add_action('admin_notices', $display_admin_notice);

        return;
    }
});


add_action( 'graphql_register_types', function() {

	register_graphql_object_type(
		'GoogleSiteKit_Analytics_Settings',
		[
		  'fields' => [
			'propertyID' => [ 
			  'type' => [ 'non_null' => 'String' ], 
			  'description' => __( 'Google Site Kit Analytics - propertyID', 'your-textdomain' ) 
			],
		  ]
		]
	);

	register_graphql_object_type(
		'GoogleSiteKit_Analytics4_Settings',
		[
		  'fields' => [
			'propertyID' => [ 
			  'type' => [ 'non_null' => 'String' ], 
			  'description' => __( 'Google Site Kit Analytics-4 - propertyID', 'your-textdomain' ) 
			],
		  ]
		]
	);

	register_graphql_object_type(
	  'GoogleSiteKit',
	  [
		'fields' => [
		  'analytics' => [ 
			'type' => [ 'non_null' => 'GoogleSiteKit_Analytics_Settings' ], 
			'description' => __( 'Settings for Analytics', 'your-textdomain' ) 
		  ],
		  'analytics4' => [ 
			'type' => [ 'non_null' => 'GoogleSiteKit_Analytics4_Settings' ], 
			'description' => __( 'Settings for Analytics4', 'your-textdomain' ) 
		  ],
		]
	  ]
	);
  
	register_graphql_field( 'RootQuery', 'GoogleSiteKitSettings', [
	  'type' => 'GoogleSiteKit',
	   'description' => __( 'Data for ', 'your-textdomain' ) ,
	  'args' => [],
		'resolve' => function( $root, $args, $context, $info ) {

			$analytics_settings = get_option( 'googlesitekit_analytics_settings' );
			graphql_debug( $analytics_settings, [ 'type' => 'googlesitekit_analytics_settings'] );
			
			$analytics4_settings = get_option( 'googlesitekit_analytics-4_settings' );
			graphql_debug( $analytics4_settings, [ 'type' => 'googlesitekit_analytics-4_settings'] );
			
			return [
				'analytics' => [
					'propertyID' => $analytics_settings['propertyID']
				],
				'analytics4' => [
					'propertyID' => $analytics4_settings['propertyID']
				]
			];

		},
	]);
  
  
  });
