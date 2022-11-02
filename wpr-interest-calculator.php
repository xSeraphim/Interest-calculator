<?php
/**
 * Plugin Name: Simulator Economii
 * Author: Aldea Daniel
 * Version: 1.0.0
 * Description: Acesta este un mic simulator pentru economii
 * Text Domain: wpr-interest-calculator
 */
if ( ! defined( 'ABSPATH' ) ) {
	exit;
}
// Plugin URL.
define( 'WPR_INTEREST_URL', untrailingslashit( plugins_url( basename( plugin_dir_path( __FILE__ ) ), basename( __FILE__ ) ) ) );
// Plugin path.
define( 'WPR_INTEREST_PATH', untrailingslashit( plugin_dir_path( __FILE__ ) ) );



class interest {

    public function __construct () {
		add_shortcode( 'shortcode_interest', array($this, 'interest' ));
		add_action( 'wp_ajax_interest', array($this,'interest_callback' ));
		add_action( 'wp_ajax_nopriv_interest', array($this,'interest_callback' ));

	}

    public function interest() {
        $this->interest_scripts();

        ob_start();

        ?>

<div class="sec-calculator" id="compound-interest-calc">
    <div class="calculator">
        <h2 class="element-invisible">Calculator economii</h2>
        <div id="calculator_wrapper" class="calculator_wrapper js-form-wrapper form-wrapper">
        <div class="calculator_step">
            <h3>Pasul 1: Depunere Initiala</h3>
            <div class="calculator__form-input">
            <div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-principal form-item-principal">
                <label for="edit-principal" class="js-form-required form-required">Suma depusa initial</label>
                <input class="monetary-input num-input form-text required" novalidate="" aria-describedby="edit-principal--description" type="text" id="edit-principal" name="suma" value="" size="10" maxlength="128" required="required" aria-required="true">
                <div id="edit-principal--description" class="description"> Suma de bani pe care doresti sa o depui initial. </div>
            </div>
            </div>
        </div>
        <div class="calculator_step">
            <h3>Pasul 2: Contributia ta lunara</h3>
            <div class="calculator__form-input">
            <div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-addition form-item-addition">
                <label for="edit-addition">Contributie lunara</label>
                <input class="monetary-input neg-input form-text" novalidate="" type="text" id="edit-addition" name="contributia" value="" size="10" maxlength="128">
                <div id="edit-addition--description" class="description"> Suma pe care intenționați să o depui în fiecare lună. </div>
            </div>
            </div>
            <div class="calculator__form-input">
            <div class="js-form-item form-item js-form-type-textfield form-type-textfield js-form-item-num-years form-item-num-years">
                <label for="edit-num-years" class="js-form-required form-required">Perioada de timp in ani</label>
                <input class="num-years num-input fractional-num-year form-text required" aria-labelledby="num_years_label" novalidate="" type="text" id="edit-num-years" name="perioada" value="" size="10" maxlength="128" required="required" aria-required="true">
                <div id="edit-num-years--description" class="description"> Perioada de timp, in ani, pe care intenționați sa economisiti. </div>
            </div>
            </div>
        </div>
        
        <div id="compound-calc__buttons" class="buttons">
            <div class="form-actions js-form-wrapper form-wrapper" id="edit-actions">
            <div id="compound-calc__errors" class="calc-errors"></div>
            <input class="submit button js-form-submit form-submit" role="button" type="submit" id="edit-submit" name="op" value="CALCULEAZA">
            <input type="submit" id="edit-reset" name="op" value="RESETEAZA" class="button button--reset js-form-submit form-submit">
            </div>
        </div>
        </div>
        <div id="results_container" class="results-container ajax-changed" style="display:block;">

        </div>
    </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/chart.js@2.8.0"></script>
<?php 
    return ob_get_clean();

    }

    public function interest_callback() {
        header( 'Content-Type: application/json' );
        $p = $_GET['suma']; // starting ammount
        $r = $_GET['contributia']; // monthly investment
        $perioada = $_GET['perioada']; // number of years
        $n = $perioada; // time invested
        $i = 0.08; // interest rate
        $c = 12; // monthly compound interest
        $x = $i / $c;
        
        $y = pow((1 + $x), ($n * $c));
        $vf = $p * $y + ($r * ($y - 1) / $x);
        // $vf = $p * $y + ($r * (1 + $x) * ($y - 1) / $x); this one is good but it outputs different values compared to other calculators

        $final = round($vf, 2);
        $rezultat = array(
            'total' => $final,
            'perioada' => $perioada,
        );
        echo wp_json_encode($rezultat);
        wp_die();

    }

    public function interest_scripts() {
        wp_enqueue_script( 'interest', WPR_INTEREST_URL . '/assets/interest.js', array( 'jquery' ), '1.0.0', true );
        wp_localize_script(
			'interest',
			'WPR',
			array(
				'ajax_url'   => admin_url( 'admin-ajax.php' ),
				'ajax_nonce' => wp_create_nonce( 'interest' ),
			)
		);
        wp_enqueue_style('interest_styles', WPR_INTEREST_URL . '/assets/interest.css');
    }
}

new interest();