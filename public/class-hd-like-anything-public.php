<?php

/**
 * The public-facing functionality of the plugin.
 *
 * @link       Jérémie Gisserot
 * @since      1.0.0
 *
 * @package    Hd_Like_Anything
 * @subpackage Hd_Like_Anything/public
 */

/**
 * The public-facing functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the public-facing stylesheet and JavaScript.
 *
 * @package    Hd_Like_Anything
 * @subpackage Hd_Like_Anything/public
 * @author     HD Team <jeremie@labubulle.com>
 */
class Hd_Like_Anything_Public
{

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of the plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct($plugin_name, $version)
    {

        $this->plugin_name = $plugin_name;
        $this->version = $version;
    }

    /**
     * Register the stylesheets for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_styles()
    {
        wp_enqueue_style($this->plugin_name . "_plublic", plugin_dir_url(__FILE__) . 'css/hd-like-anything-public.css', array(), $this->version, 'all');
    }

    /**
     * Register the JavaScript for the public-facing side of the site.
     *
     * @since    1.0.0
     */
    public function enqueue_scripts()
    {

        wp_register_script($this->plugin_name . "_public", plugin_dir_url(__FILE__) . 'js/hd-like-anything-public.js', array('jquery'), $this->version, false);
        $js_vars = array('ajax_url' => admin_url('admin-ajax.php', 'relative'));
        wp_localize_script($this->plugin_name . "_public", 'js_vars', $js_vars);
        wp_enqueue_script($this->plugin_name . "_public");
    }

    /**
     * Handles the data reception <> dispatch and returns the corrent likes of the liked post
     *
     * @since    1.0.0
     * @return string the current numbers of likes of the post
     */
    public function set_posts_likes()
    {

        if (isset($_POST['hdla_post_id'])  && isset($_POST['hdla_likes'])) {

            // We transpose de vars for cleaner code below
            $post_id = $_POST['hdla_post_id'];
            $action = $_POST['hdla_likes'];
            $meta_key = "hdla_likes";

            // We get the user likes (cookie or user meta if logged)
            $user_likes = $this->get_user_likes();

            // We get post likes if any
            $likes_meta = get_post_meta($post_id, $meta_key, true);

            // If we have a post meta
            if(!empty($likes_meta)) {
                // We count the number of items
                $likes_number = $likes_meta;
            }
            // If there is no post meta yet
            else {
                // We define the number of likes to 0
                $likes_number = 0;
            }

            // If the action is set to like and if the user didn't already liked the post :
            // - we increment the counter
            // - we update the users likes
            if ($action == 'like' && !in_array($post_id, $user_likes)) {
                $newLikes = $likes_number + 1;
                $this->set_user_like($post_id, "like");
            } 
            // If the action is NOT set to like and if the user already liked the post :
            // - we decrement the counter
            // - we update the users likes
            else {
                $newLikes = $likes_number - 1;
                if($newLikes < 0) {
                    $newLikes = 0;
                }
                $this->set_user_like($post_id, "unlike");
            }

            // To finish we update the post meta
            update_post_meta($post_id, $meta_key, $newLikes);

            // We return the likes number of the post meta
            echo $newLikes;
            die();
        }
    }
    /**
     * Stores the likes of user
     *  - if the user is connected the data is stored on the user meta table
     *  - if there is no current user the data is stored on a cookie
     *  - TODO : RGPB fix for the cookie
     * @since    1.0.0
     * @param int $post_id > the liked post id
     * @param int $action > like or unlike to remove item
     * @param int $user_id > an specific user (if not specified it fallsback to current user if connected or no user/cookie
     */
    public function set_user_like($post_id, $action = "like", $user_id = null)
    {
        // We get the current user if there is no user set in args
        if (null == $user_id && is_user_logged_in()) {
            $user_id = get_current_user_id();
        }
        ///
        // IF THE USER IS CONNECTED >> WE STORE DATA AS USER META...
        ///
        if (null !== $user_id) {

            // We get the user meta
            $likes_meta = get_user_meta($user_id, "hdla_liked", true);
            
            // We push the meta on an array
            if(null != $likes_meta) {    
                $likes_meta = explode(',', $likes_meta);
            }
            else{
                $likes_meta = array();
            } 

            // Do we add or remove stuff ?
            if($action == "like") {
                $likes_meta[] = $post_id;
            }
            else {
                if (($key = array_search($post_id, $likes_meta)) !== false) {
                    unset($likes_meta[$key]);
                }
            }

            // We update the data
            update_user_meta( $user_id, "hdla_liked", implode (",", $likes_meta) );

            $updated_meta = get_user_meta($user_id, "hdla_liked", true);        
            return $updated_meta;
        }

        ///
        // ... AND IF THE USER IS NOT CONNECTED >> WE STORE DATA AS COOKIE
        ///

        else {
            
            if($action == "like") {
                return $this->set_likes_cookie($post_id, "like");
            } else {
                return $this->set_likes_cookie($post_id, "unlike");
            }
        }
    }
    /**
     * Deals with connected/unconnected user and gets the the likes :
     *  - on user meta if connected
     *  - on cookie if is not connected 
     * @since    1.0.0
     * @param int $user_id > an specific user (if not specified it fallsback to current user if connected or no user/cookie
     */
    public static function get_user_likes($user_id = null)
    {
        if (is_user_logged_in() OR !null == $user_id ) {
            $user_id = get_current_user_id();
            return explode(',', get_user_meta($user_id, 'hdla_liked', true) );
        } 
        else if( isset( $_COOKIE['hdla_liked'] ) ) {    
            return explode(',', $_COOKIE['hdla_liked']);
        }
    }

    /**
     * Fetch and return the user cookie
     * @since    1.0.0
     */
    public function get_likes_cookie()
    {
        return stripslashes($_COOKIE['hdla_liked']);
    }
    /**
     * Set and/or update the likes cookie
     * @since    1.0.0
     * @param int $post_id the post id to push
     */
    public function set_likes_cookie($post_id, $action = "like")
    {
        $cookie_data = array();
        if(isset($_COOKIE["hdla_liked"])) {
            $cookie_data = explode(',', stripslashes($_COOKIE["hdla_liked"]) ); 
            //return $cookie_data;
 
        }
        if($action == "like") {
            $cookie_data[] = $post_id;
        }
        else {
            if (($key = array_search($post_id, $cookie_data)) !== false) {
                unset($cookie_data[$key]);
            }           
        }
        setcookie('hdla_liked', implode (", ", $cookie_data), time()+7889231,'/');

        return $this->get_likes_cookie();
    }
     /**
     * Helper fonction to look if the item is liked by the use
     * @access   public
     * @since    1.0.0
     * @param int $post_id > the specific post id to push to form (fallsback to current post id if no set)
     */
    public static function is_item_liked($post_id = "")
    {
        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
        $user_likes = Hd_Like_Anything_Public::get_user_likes();
        if(!empty($user_likes) && in_array($post_id, $user_likes)) {
            return true;
        }
        else {
            return false;
        } 
    }   
    /**
     * Helper fonction to show the like form on frontend
     * @access   public
     * @since    1.0.0
     * @param int $post_id > the specific post id to push to form (fallsback to current post id if no set)
     */
    public static function output_like_form($post_id = "")
    {

        if (empty($post_id)) {
            global $post;
            $post_id = $post->ID;
        }
        $likes_number = 0;
        $likes_number_meta = get_post_meta($post_id, "hdla_likes", true);

        if (!empty($likes_number_meta)) {
            $likes_number = $likes_number_meta;
        }

        $is_item_liked = Hd_Like_Anything_Public::is_item_liked($post_id);
        if($is_item_liked) {
            echo "<div class='hdla_container liked' data-post_id='" . $post_id . "'>";
        } else {
            echo "<div class='hdla_container notliked' data-post_id='" . $post_id . "'>";

        }
        echo "<span class='hdla_heart_container'>";
        include(plugin_dir_path(__FILE__) . "/assets/img/heart.svg");
        echo "</span>";
        echo "<b>" . $likes_number . "</b> j'aime";
        echo "</div>";
    }
}
