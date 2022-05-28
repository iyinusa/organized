<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
/*
 * reCaptcha File Config
 */

$config['public_key']   = '6Ldd1_oSAAAAADLEpJz5QtWGWTQLCICD4miTL3Qd';
$config['private_key']  = '6Ldd1_oSAAAAAPQlhpdO_GGrfjL67giXDvbUyxwT';

// Set Recaptcha options
// Reference at https://developers.google.com/recaptcha/docs/customization
$config['recaptcha_options']  = array(
    'theme'=>'white', // red/white/blackglass/clean
    'lang' => 'en' // en/nl/fl/de/pt/ru/es/tr
    //  'custom_translations' - Use this to specify custom translations of reCAPTCHA strings.
    //  'custom_theme_widget' - When using custom theming, this is a div element which contains the widget. See the custom theming section for how to use this.
    //  'tabindex' - Sets a tabindex for the reCAPTCHA text box. If other elements in the form use a tabindex, this should be set so that navigation is easier for the user
);

?>
