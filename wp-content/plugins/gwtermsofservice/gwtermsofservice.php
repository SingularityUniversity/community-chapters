<?php 
/**
 * Plugin Name: GP Terms of Service Field
 * Description: Add a "Terms of Service" field to your forms.
 * Plugin URI: http://gravitywiz.com/
 * Version: 1.3.1
 * Author: David Smith
 * Author URI: http://gravitywiz.com
 * License: GPLv2
 * Perk: True
*/

/**
* Saftey net for individual perks that are active when core Gravity Perks plugin is inactive.
*/
$gw_perk_file = __FILE__;
if(!require_once(dirname($gw_perk_file) . '/safetynet.php'))
    return;

class GWTermsofService extends GWPerk {

    public $version = '1.3.1';
    protected $min_gravity_perks_version = '1.1.14';
	protected $min_gravity_forms_version = '1.9.3';
    
    public function init() {
        
        $this->add_tooltip($this->key('require_scroll'), __('<h6>Require Full Scroll</h6>Checking this option will disable the acceptance checkbox until the user has scrolled through the full Terms of Service.', 'gravityperks'));
        $this->add_tooltip($this->key('terms'), __('<h6>The Terms</h6>Specify the terms the user is agreeing to here.', 'gravityperks'));
        $this->include_field();
        
    }
    
    function documentation() { 
        return array(
            'type'  => 'url',
            'value' => 'http://gravitywiz.com/documentation/gp-terms-of-service/'
        );
    }
    
}
