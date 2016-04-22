<?php
/*
*
*     ADD CUSTOM SORTABLE COLUMNS INTO EDIT POST
*
*     1.) Customize Post Columns
*     2.) Make Custom Column Sortable
*     3.) Add Sortby into Sortable Columns
*     4.) Save Metafield Value into Column Table
*
*     ADD QUICK EDIT FOR CUSTOM META
*
*     1.) Populate the Custom Box with fieldsets
*     2.) Update Custom box value via Javascript     
*     3.) Add Meta Box
*     X.) Save Quick Edit Data & Post Meta Data
*     
*     ADD META CUSTOM META BOX
*
*     1.) Add Meta Box Action
*     2.) Display Metabox Callback
*     X.) Save Quick Edit Data & Post Meta Data
*/

/*------------- ADD CUSTOM SORTABLE COLUMNS INTO EDIT POST -------------*/
//* 1.) Customize Post Columns
function manage_columns_for_page($columns){
    //remove columns
    //unset($columns['comments']);
    //unset($columns['tags']);
    //add new columns
    $columns['page_featured_article'] = 'Featured Article';
    return $columns;
}
add_action('manage_post_posts_columns','manage_columns_for_page');

//* 2.) Make Custom Column Sortable
add_filter( 'manage_edit-post_sortable_columns', 'my_sortable_cake_column' );
function my_sortable_cake_column( $columns ) {
    $columns['page_featured_article'] = 'featured_article';
    //To make a column 'un-sortable' remove it from the array
    //unset($columns['date']);
    return $columns;
}

//* 3.) Add Sortby into sortable columns
add_action( 'pre_get_posts', 'featured_article_orderby' );
function featured_article_orderby( $query ) {
    /*Pre get post in Post page in Admin Dashboard*/
    if( ! is_admin() )
        return;
    $orderby = $query->get( 'orderby'); 
    if( 'featured_article' == $orderby ) {
        $query->set('meta_key','featured_post');
        $query->set('orderby','meta_value');
        $query->set('order','DESC');
    }
}

//* 4.) Save Metafield Value into Column Table
add_action( 'manage_post_posts_custom_column' , 'custom_featured_article_column', 10, 2 );
function custom_featured_article_column( $column, $post_id ) {
    switch ( $column ) {
      case 'page_featured_article':
        // the !! means translate the following item to a boolean value
        if ( !!get_post_meta( $post_id , 'featured_post' , true ) ) {
            $checked = 'checked';
        } else {
            $checked = '';
        }
        echo "<input type='checkbox' readonly $checked disabled/>";
        break;
    }
}


/*------------- ADD QUICK EDIT FOR CUSTOM META-------------*/
//* 1.) Populate the Quick Edit with new Custom Box
add_action( 'quick_edit_custom_box', 'display_featured_article_box', 10, 2 );

function display_featured_article_box( $column_name, $post_type ) {
    ?>
    <fieldset class="inline-edit-col-right inline-edit-featured-article">
      <div class="inline-edit-col column-<?php echo $column_name; ?>">
        <label class="inline-edit-group">
        <span class="title">Make this a Featured Post?</span><input name="featured_post" class="featured_post" type="checkbox" />
        </label>
      </div>
    </fieldset>
    <?php
}

//* 2.) Update Custom box value via Javascript
    //* --- Load script in the footer
if ( ! function_exists('wp_my_admin_enqueue_scripts') ):
    function wp_my_admin_enqueue_scripts( $hook ) {
        if ( 'edit.php' === $hook  ) {
            wp_enqueue_script( 'my_custom_script', get_stylesheet_directory_uri().'/assets/js/admin-edit.js', false, null, true );
        }
    }
endif;
add_action( 'admin_enqueue_scripts', 'wp_my_admin_enqueue_scripts' );



/*------------- REGISTER META BOX -------------*/
//* 1.) Add Meta Box Action 
    //* --- Register meta box(es). 

function wpdocs_register_meta_boxes() {
    add_meta_box( 'meta-box-id', __( 'Featured Post', 'genesis' ), 'featured_post_callback', 'post', 'side', 'high');
}
add_action( 'add_meta_boxes', 'wpdocs_register_meta_boxes' );
 
//* 2.) Display Metabox Callback
/**
 * Meta box display callback.
 * @param WP_Post $post Current post object.
 */
function featured_post_callback( $post ) {
    // Display code/markup goes here. Don't forget to include nonces! ?>
      <fieldset class="inline-edit-featured-article">
      <div class="meta-featured-article">
        <label>
        <span class="title">Make this a Featured Post?</span>
        <?php    
        if ( !!get_post_meta( $post->ID , 'featured_post' , true ) ) {
            $checked = 'checked';
        } else {
            $checked = '';
        } 
         echo "<input name='featured_post' class='featured_post' type='checkbox' $checked />";
        ?>
        </label>
      </div>
    </fieldset>
<?php }
 
//* X.) Save Quick Edit Data & Post Meta Data

add_action( 'save_post', 'wpdocs_save_meta_box' );
function wpdocs_save_meta_box( $post_id ) {
    $post = get_post($post_id);
    if (isset($_POST['featured_post'])) {
        update_post_meta( $post_id, 'featured_post', TRUE);     
    } else {
        update_post_meta( $post_id, 'featured_post', FALSE);     
    }  
}
?>