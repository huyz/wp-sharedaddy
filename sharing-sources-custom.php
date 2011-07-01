<?php
/**
 * File for Custom Services
 *
 * @category Wordpress
 * @package  Sharedaddy
 */

/**
 * Class for Google+1
 *
 * Implementation of Google+1 Service for Sharedaddy
 *
 * @category Wordpress
 * @package  Sharedaddy
 * @author Marco Neumann <webcoder_at_binware_dot_org>, Huy Z (http://huyz.us/)
 * @copyright Copyright (c) 2011, Marco Neumann
 * Licensed under the GNU GPLv3. 
 *
 */
class Share_GooglePlusOne extends Sharing_Advanced_Source
{
    /**
     * Button Size
     *
     * @var String
     */
    private $_size = 'medium';

    /**
     * Button Language
     *
     * @var String
     */
    private $_language = 'en-US';

    /**
     * Smart button?
     *
     * @var String
     */
    private $smart = true;

    // Configuration extracted from http://code.google.com/apis/+1button/#configuration
    /**
     * Available sizes
     *
     * @var Array
     */
    private $_optionsSize = array(
        'small'    => 'Small Size',
        'medium'   => 'Medium Size',
        'standard' => 'Standard Size',
        'tall'     => 'Tall Size'
    );

    /**
     * Available languages
     *
     * @var Array
     */
    private $_optionsLanguage = array(
        'ar'     => 'Arabic',
        'bg'     => 'Bulgarian',
        'ca'     => 'Catalan',
        'zh-CN'  => 'Chinese (Simplified)',
        'zh-TW'  => 'Chinese (Traditional)',
        'hr'     => 'Croatian',
        'cs'     => 'Czech',
        'da'     => 'Danish',
        'nl'     => 'Dutch',
        'en-GB'  => 'English (UK)',
        'en-US'  => 'English (US)',
        'et'     => 'Estonian',
        'fil'    => 'Filipino',
        'fi'     => 'Finnish',
        'fr'     => 'French',
        'de'     => 'German',
        'el'     => 'Greek',
        'iw'     => 'Hebrew',
        'hi'     => 'Hindi',
        'hu'     => 'Hungarian',
        'id'     => 'Indonesian',
        'it'     => 'Italian',
        'ja'     => 'Japanese',
        'ko'     => 'Korean',
        'lv'     => 'Latvian',
        'lt'     => 'Lithuanian',
        'ms'     => 'Malay',
        'no'     => 'Norwegian',
        'fa'     => 'Persian',
        'pl'     => 'Polish',
        'pt-BR'  => 'Portuguese (Brazil)',
        'pt-PT'  => 'Portuguese (Portugal)',
        'ro'     => 'Romanian',
        'ru'     => 'Russian',
        'sr'     => 'Serbian',
        'sk'     => 'Slovak',
        'sl'     => 'Slovenian',
        'es'     => 'Spanish',
        'es-419' => 'Spanish (Latin America)',
        'sv'     => 'Swedish',
        'th'     => 'Thai',
        'tr'     => 'Turkish',
        'uk'     => 'Ukrainian',
        'vi'     => 'Vietnamese'
    );

    /**
     * Constructor
     *
     * @param Integer $id
     * @param Array $settings
     */
    public function __construct($id, array $settings)
    {
        parent::__construct($id, $settings);
        
        if (isset($settings['size'])) {
            $this->_size = $settings['size'];
        }
        if (isset($settings['language'])) {
            $this->_language = $settings['language'];
        }
        if (isset($settings['smart'])) {
            $this->smart = $settings['smart'];
        }
    }

    /**
     * Get Service Name
     *
     * @return String
     */
    public function get_name()
    {
        return __('Google +1', 'sharedaddy');
    }

    public function has_custom_button_style() {
	    return $this->smart;
    }

    /**
     * Displays preview
     *
     * @return Share_GooglePlusOne
     */
    public function display_preview()
    {
?>
    <div class="option option-smart-<?php echo ($this->smart ? 'on' : 'off'); ?>"> 
        <?php
	    if ( !$this->smart ) {
		if ($this->button_style == 'text' || $this->button_style == 'icon-text') {
		    echo $this->get_name();
		} else {
		   echo '&nbsp;';
		}    
	    }
        ?>   
    </div>
<?php

        return $this;
    }    

    /**
     * Shows icon
     *
     * @return String
     */
    public function get_display($post)
    {
	static $added_googleplusone_js = false;
	$proto = ( is_ssl() ) ? 'https://' : 'http://';
	$permalink = get_permalink( $post->ID );
	$display = '';
	
	// So we don't spit out the googleplusone js for each post on index pages
	if( ! $added_googleplusone_js ) {
		$options = array(
		    'lang' => $this->_language
		);

		$display .= sprintf( '<script type="text/javascript" src="%sapis.google.com/js/plusone.js">', $proto );
		if (!empty($options)) {
		    $display .= json_encode($options);
		}
		$display .= '</script>';
		$added_googleplusone_js = true;
	}

        if ($this->smart) {
                $display .= '<span class="option-smart-on">';
        } else {
                $display .= '<span class="option-smart-off">';
        }

	$display .= '<g:plusone size="' . ($this->smart ? $this->_size : "small") . '" count="' . ($this->smart ? 'true' : 'false') . '" href="' . esc_url( $permalink ) . '"></g:plusone>';

	if (!$this->smart && ($this->button_style == 'text' || $this->button_style == 'icon-text')) {
		$display .= '<span style="padding-left:4px">' . _x( 'Google +1', 'share to', 'sharedaddy' ) . '</span>';
	}
        $display .= '</span>';
	
	return $display;
    }

    /**
     * Displays available Service options
     *
     * @return Share_GooglePlusOne
     */
    public function display_options()
    {
?>
<div class="input">
<!--
    <label>
        <select name="size">
            <?php foreach ($this->_optionsSize as $value=>$name): ?>
                <option value="<?php echo $value; ?>"<?php if ($this->_size == $value): ?> selected="selected"<?php endif; ?>><?php _e($name, 'sharedaddy'); ?>
            <?php endforeach; ?>
        </select>
    </label>
-->
    <label>
        <select name="language">
            <?php foreach ($this->_optionsLanguage as $value=>$name): ?>
                <option value="<?php echo $value; ?>"<?php if ($this->_language == $value): ?> selected="selected"<?php endif; ?>><?php _e($name, 'sharedaddy'); ?>
            <?php endforeach; ?>
        </select>
    </label>
    <label>
        <input type="checkbox"<?php if ($this->smart) { echo ' checked="checked"'; } ?> name="smart" /> Use smart button
    </label>
</div>
<?php

        return $this;
    }

    /**
     * Updates submitted options
     *
     * @return Share_GooglePlusOne
     */
    public function update_options(array $data)
    {
        if (isset($data['size']) && isset($this->_optionsSize[$data['size']])) {
            $this->_size = $data['size'];
        }

        if (isset($data['language']) && isset($this->_optionsLanguage[$data['language']])) {
            $this->_language = $data['language'];
        }

        $this->smart = isset($data['smart']);

        return $this;
    }

    /**
     * Returns current options
     *
     * @var Array
     */
    public function get_options()
    {
        return array(
            'size'     => $this->_size,
            'language' => $this->_language,
            'smart'    => $this->smart
        );
    }
}
