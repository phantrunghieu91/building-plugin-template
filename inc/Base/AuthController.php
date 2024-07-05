<?php
/**
 * @package JinsPlugin
 * Login/Register with Ajax
 */
namespace Inc\Base;

class AuthController extends BaseController {
  private $action;
  public function register() {
    if ( ! $this->isActivated( 'login_manager' ) ) return;

    $this->action = 'jins_auth';

    add_action('wp_head', [ $this, 'addAuthFormTemplate' ]);
    add_action("wp_ajax_nopriv_$this->action", [ $this, 'handleLogin' ]);
  }
  public function addAuthFormTemplate() {
    if( is_user_logged_in() ) return;

    $template_file = $this->plugin_path . 'templates/auth.php';
    if ( file_exists( $template_file ) ) {
      load_template( $template_file, true );  // true: require_once
    }
  }

  public function handleLogin(){
    $send_message = function( $message, $status = 'error', $code = 200 ) {
      wp_send_json( ['message' => $message, 'status' => $status], $code );
      wp_die();
    };

    // validate nonce
    if( !DOING_AJAX || !check_ajax_referer( $this->action, "{$this->action}_nonce", false ) && wp_verify_nonce( $_POST["{$this->action}_nonce"], "{$this->action}_nonce" ) ) {
      $send_message( 'Nonce is invalid', 'error', 403 );
    }

    // sanitize input
    $info = [
      'user_login' => sanitize_text_field( $_POST['username'] ),
      'user_password' => sanitize_text_field( $_POST['password'] ),
      'remember' => true
    ];

    $user = get_user_by( 'login', $info['user_login'] );
    
    // check if user exists
    $user_signon = wp_signon( $info, true ); // false: don't redirect
    if( is_wp_error( $user_signon ) ) {
      $send_message( $user_signon->get_error_message() );
    }

    $send_message( 'Login successful', 'success' );
  }
}