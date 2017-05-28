<?php


class ApplySettings {

    function __construct(){

        add_filter('wpdm_custom_data', array( $this, 'SR_CheckPackageAccess' ), 10, 2);
        add_action('init', array( $this, 'AddWriteRules' ));
        add_action('save_post', array( $this, 'DashboardPages' ));
        add_action('wp_loaded', array( $this, 'Actions'));
        add_action('query_vars', array( $this, 'DashboardPageVars' ));

    }

    function Actions(){
        //add_action('save_post', array( $this, 'UserDashboardPages' ));
        //global $wp_rewrite;
        //dd($wp_rewrite->rewrite_rules());
    }

    function SR_CheckPackageAccess($data, $id){
        global $current_user;
        $skiplocks = maybe_unserialize(get_option('__wpdm_skip_locks', array()));
        if( is_user_logged_in() ){
            foreach($skiplocks as $lock){
                unset($data[$lock."_lock"]); // = 0;
            }
        }

        return $data;
    }

    function AddWriteRules(){
        $udb_page_id = get_option('__wpdm_user_dashboard', 0);
        if($udb_page_id) {
            $page_name = get_post_field("post_name", $udb_page_id);
            add_rewrite_rule('^' . $page_name . '/(.+)/?', 'index.php?page_id=' . $udb_page_id . '&udb_page=$matches[1]', 'top');
        }
        $adb_page_id = get_option('__wpdm_author_dashboard', 0);
        if($adb_page_id) {
            $page_name = get_post_field("post_name", $adb_page_id);
            add_rewrite_rule('^' . $page_name . '/(.+)/?', 'index.php?page_id=' . $adb_page_id . '&adb_page=$matches[1]', 'top');
        }


    }

    function DashboardPages($post_id){
        if ( wp_is_post_revision( $post_id ) )  return;
        $page_id = get_option('__wpdm_user_dashboard', 0);
        $post = get_post($post_id);
        if(!$page_id && has_shortcode($post->post_content, "wpdm_user_dashboard"))
            update_option('__wpdm_user_dashboard', $post_id);

        $page_id = get_option('__wpdm_author_dashboard', 0);
        $post = get_post($post_id);
        if(!$page_id && has_shortcode($post->post_content, "wpdm_frontend"))
            update_option('__wpdm_author_dashboard', $post_id);

        $this->AddWriteRules();

        global $wp_rewrite;
        $wp_rewrite->flush_rules();

    }


    function DashboardPageVars( $vars ){
        array_push($vars, 'udb_page', 'adb_page');
        return $vars;
    }




}

new ApplySettings();