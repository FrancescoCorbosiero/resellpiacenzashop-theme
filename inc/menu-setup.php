<?php
/**
 * Menu Strategy Setup
 * 
 * Handles programmatic creation of categories and menu structure.
 * Trigger via URL: /wp-admin/?rp_run_menu_setup=1
 */

if ( ! defined( 'ABSPATH' ) ) {
	exit;
}

/**
 * Main setup function
 */
function rp_run_menu_strategy_setup() {
    if ( ! is_admin() || ! current_user_can( 'manage_options' ) ) {
        return;
    }

    if ( isset( $_GET['rp_run_menu_setup'] ) && $_GET['rp_run_menu_setup'] == '1' ) {
        
        $structure = rp_get_menu_structure();
        
        // 1. Create Categories
        rp_create_product_categories( $structure );
        
        // 2. Create Menu
        rp_create_main_menu( $structure );
        
        add_action( 'admin_notices', function() {
            echo '<div class="notice notice-success is-dismissible"><p>ResellPiacenza Menu Strategy Setup Completed!</p></div>';
        });
    }
}
add_action( 'admin_init', 'rp_run_menu_strategy_setup' );

/**
 * Define the full structure
 */
function rp_get_menu_structure() {
    return [
        'SNEAKERS' => [
            'type' => 'category',
            'children' => [
                'AIR JORDAN' => [
                    'children' => [
                        'Air Jordan 1 High', 'Air Jordan 1 Mid', 'Air Jordan 1 Low', 
                        'Air Jordan 1 CMFT', 'Air Jordan 2', 'Air Jordan 3', 
                        'Air Jordan 4', 'Air Jordan 5', 'Air Jordan 11',
                        'Air Jordan Lego', 'Air Jordan Laces'
                    ]
                ],
                'ADIDAS' => [
                    'children' => [
                        'Campus', 'Gazelle', 'Samba', 'Spezial', 'Forum', 
                        'Yeezy 350', 'Yeezy Foam', 'Yeezy Slide', 'Altre Yeezy', 'Wales Bonner'
                    ]
                ],
                'DUNK' => [
                    'children' => [
                        'DUNK LOW', 'DUNK HIGH', 'DUNK SB', 'DUNK BABY'
                    ]
                ],
                'NEW BALANCE' => [
                    'children' => [
                        'New Balance 550', 'New Balance 2002r', 'New Balance 9060'
                    ]
                ],
                'COLLAB' => [
                    'children' => [
                        'Nike x Travis Scott', 'Nike x Off White', 'Nike x Patta', 'Nike x Sacai'
                    ]
                ],
                'NIKE' => [
                    'children' => [
                        'Air Force 1', 'Nike Hot Step', 'Nike Kobe'
                    ]
                ],
                'MSCHF' => [
                    'children' => [
                        'BIG RED BOOT'
                    ]
                ],
                'UGG' => [
                    'children' => [
                        'LOWMEL', 'TAZZ SLIPPER'
                    ]
                ],
                'ACCESSORI SNEAKERS' => [
                    'children' => [
                        'Sneakers Shields', 'Prodotti Per La Pulizia', 'Sneakers Laces'
                    ]
                ],
                'BABY/INFANT SNEAKERS' => [
                    'children' => [
                        'Jordan Baby', 'Dunk Baby'
                    ]
                ]
            ]
        ],
        'CLOTHING' => [
            'type' => 'category',
            'children' => [
                'Tee', 'Hoodie', 'Pants', 'Sweater', 'Shorts', 'Jeans', 
                'Jackets', 'Boxer', 'Socks', 'Suit', 'Handbags', 'Beanie', 'Cap/Hat'
            ]
        ],
        'ACCESSORIES' => [
            'type' => 'category',
            'children' => [
                'ACCESSORI SNEAKERS' => [ // Note: Duplicate name, might merge if exists
                    'children' => [
                        'Sneakers Cleaning', 'Sneakers Laces', 'Sneakers Shields', 
                        'Sneakers Sole', 'Socks Tie Dye', 'Socks Supreme'
                    ]
                ],
                'ACCESSORI ESPOSITIVI' => [
                    'children' => [
                        'Funko Pop', 'Hype Doormat', 'Skateboard', 'Keychains'
                    ]
                ],
                'BOXER', 'HANDBAGS', 'MINISO',
                'SUPREME ACCESSORIES' => [
                    'children' => [
                        'Boxer', 'Socks', 'Skateboard', 'Tutti Gli Accessori'
                    ]
                ],
                'POKEMON',
                'POPMART' => [
                    'children' => [
                        'Crybaby', 'Labubu', 'Skullpanda'
                    ]
                ],
                'SONNY ANGEL', 'SMISKI',
                'SWATCH' => [
                    'children' => [
                        'Bioceramic Moonswatch Collection', 'Mission To Moonshine Gold',
                        'Mission On Earth', 'Mission To The Moonphase', 'Swatch x Blancpain'
                    ]
                ],
                'LABUBU' => [
                    'children' => [
                        'ACCESSORI LABUBU', 'TUTTI I LABUBU'
                    ]
                ]
            ]
        ],
        'BRAND' => [
            'type' => 'category', // Or custom link with mega menu, but let's stick to category structure for now
            'children' => [
                'Adidas', 'Alessio Giffi', 'Barrow', 'Crocs', 'Corteiz', 'Funko Pop',
                'Garment Workshop', 'Golden Goose', 'Hamrah', 'Hangover', 'Horda',
                'Jacquemus', 'Malessere', 'Miniso', 'Molly', 'New Balance', 'Nike',
                'Noissey', 'Louis Vuitton', 'Pogo', 'Pokemon', 'Popmart', 'Popmart Labubu',
                'Replacement', 'Reshoevn8r', 'Shoebuya Design', 'Smiski', 'Sonny Angel',
                'Supreme', 'Swatch', 'Travis Scott', 'The Double E', 'Ugg',
                'Uniqlo x Kaws', 'Virgil Abloh', 'We Are All Ash', 'Yves Saint Laurent'
            ]
        ],
        'BLOG' => [
            'type' => 'page', // Special handling
            'url'  => '/blog/'
        ]
    ];
}

/**
 * Create WooCommerce Categories
 */
function rp_create_product_categories( $structure ) {
    if ( ! class_exists( 'WooCommerce' ) ) return;

    foreach ( $structure as $top_level_name => $data ) {
        if ( isset($data['type']) && $data['type'] === 'page' ) continue;

        // Create Top Level
        $top_id = rp_get_or_create_term( $top_level_name );

        if ( isset( $data['children'] ) ) {
            foreach ( $data['children'] as $key => $value ) {
                if ( is_array( $value ) ) {
                    // Level 2 with children
                    $l2_name = $key;
                    $l2_id = rp_get_or_create_term( $l2_name, $top_id );

                    if ( isset( $value['children'] ) ) {
                        foreach ( $value['children'] as $l3_name ) {
                            rp_get_or_create_term( $l3_name, $l2_id );
                        }
                    }
                } else {
                    // Level 2 without children (value is the name)
                    rp_get_or_create_term( $value, $top_id );
                }
            }
        }
    }
}

/**
 * Helper to get or create term
 */
function rp_get_or_create_term( $name, $parent_id = 0 ) {
    $term = term_exists( $name, 'product_cat', $parent_id );
    
    if ( $term ) {
        return $term['term_id'];
    }
    
    $new_term = wp_insert_term( $name, 'product_cat', [
        'parent' => $parent_id
    ] );
    
    if ( ! is_wp_error( $new_term ) ) {
        return $new_term['term_id'];
    }
    
    return 0;
}

/**
 * Create Main Menu
 */
function rp_create_main_menu( $structure ) {
    $menu_name = 'Main Navigation Menu';
    $menu_exists = wp_get_nav_menu_object( $menu_name );

    if ( $menu_exists ) {
        wp_delete_nav_menu( $menu_name ); // Re-create to ensure structure matches
    }

    $menu_id = wp_create_nav_menu( $menu_name );

    // Set as Primary Menu
    $locations = get_theme_mod( 'nav_menu_locations' );
    $locations['primary'] = $menu_id; // Adjust 'primary' based on theme location slug
    set_theme_mod( 'nav_menu_locations', $locations );

    foreach ( $structure as $top_name => $data ) {
        
        // Add Top Level Item
        $top_item_id = 0;
        
        if ( isset($data['type']) && $data['type'] === 'page' ) {
             // For BLOG, maybe link to a page or custom link
             $top_item_id = wp_update_nav_menu_item( $menu_id, 0, [
                'menu-item-title'  => $top_name,
                'menu-item-url'    => isset($data['url']) ? $data['url'] : home_url('/blog/'),
                'menu-item-status' => 'publish',
                'menu-item-type'   => 'custom',
            ]);
        } else {
            // Category Link
            // We use custom links for flexibility, or we can find the category object
            $term = get_term_by( 'name', $top_name, 'product_cat' );
            $url = $term ? get_term_link( $term ) : '#';
            
            $top_item_id = wp_update_nav_menu_item( $menu_id, 0, [
                'menu-item-title'  => $top_name,
                'menu-item-url'    => $url,
                'menu-item-status' => 'publish',
                'menu-item-type'   => 'custom', // Using custom to ensure we control the label
            ]);
        }

        // Process Children
        if ( isset( $data['children'] ) ) {
            foreach ( $data['children'] as $key => $value ) {
                if ( is_array( $value ) ) {
                    // Level 2
                    $l2_name = $key;
                    $term = get_term_by( 'name', $l2_name, 'product_cat' );
                    $url = $term ? get_term_link( $term ) : '#';

                    $l2_item_id = wp_update_nav_menu_item( $menu_id, 0, [
                        'menu-item-title'  => $l2_name,
                        'menu-item-url'    => $url,
                        'menu-item-status' => 'publish',
                        'menu-item-type'   => 'custom',
                        'menu-item-parent-id' => $top_item_id
                    ]);

                    // Level 3
                    if ( isset( $value['children'] ) ) {
                        foreach ( $value['children'] as $l3_name ) {
                            $term = get_term_by( 'name', $l3_name, 'product_cat' );
                            $url = $term ? get_term_link( $term ) : '#';

                            wp_update_nav_menu_item( $menu_id, 0, [
                                'menu-item-title'  => $l3_name,
                                'menu-item-url'    => $url,
                                'menu-item-status' => 'publish',
                                'menu-item-type'   => 'custom',
                                'menu-item-parent-id' => $l2_item_id
                            ]);
                        }
                    }

                } else {
                    // Level 2 simple
                    $l2_name = $value;
                    $term = get_term_by( 'name', $l2_name, 'product_cat' );
                    $url = $term ? get_term_link( $term ) : '#';

                    wp_update_nav_menu_item( $menu_id, 0, [
                        'menu-item-title'  => $l2_name,
                        'menu-item-url'    => $url,
                        'menu-item-status' => 'publish',
                        'menu-item-type'   => 'custom',
                        'menu-item-parent-id' => $top_item_id
                    ]);
                }
            }
        }
    }
}
