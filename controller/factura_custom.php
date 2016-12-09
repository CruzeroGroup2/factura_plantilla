<?php

require_model('cliente.php');
require_model('factura_cliente.php');
require_model('factura_plantilla.php');
require_model('recibo_cliente.php');
require_model('forma_pago.php');

require_once dirname(__FILE__).'/../extra/html2pdf/vendor/autoload.php';
require_once dirname(__FILE__).'/../extra/twig/lib/Twig/Autoloader.php';
Twig_Autoloader::register();
require_once dirname(__FILE__).'/../extra/DatabaseTwigLoader.php';

use Spipu\Html2Pdf\Html2Pdf;
use Spipu\Html2Pdf\Exception\Html2PdfException;
use Spipu\Html2Pdf\Exception\ExceptionFormatter;

/**
 * Created by IntelliJ IDEA.
 * User: ggarcia
 * Date: 15/04/2016
 * Time: 04:03 PM
 */
class factura_custom extends fs_controller {

    public $factura = null;

    public $cliente = null;

    public function __construct() {
        parent::__construct(__CLASS__, 'Factura Custom', 'ventas', FALSE, FALSE);
    }

    private static function get_cliente($codcliente) {
        $cli = new cliente();

        return $cli->get($codcliente);
    }

    private static function get_factura($idfactura = 0) {
        $fac = new factura_cliente();

        return $fac->get($idfactura);
    }

    private static function get_recibos($idfactura = 0) {
    	$rec = new recibo_cliente();

    	return $rec->all_from_factura($idfactura);
    }

    private static function get_formas_pagos() {
    	$fp = new forma_pago();

    	return $fp->all();
    }

    protected function process() {
        $id_factura = (int) isset($_GET['id']) ? $_GET['id'] : 0;
        $id_plantilla = isset($_GET['plantilla']) ? $_GET['plantilla'] : 'default';
        $factura = new factura_cliente();

        $this->factura = $factura->get($id_factura);

        $action = (string) isset($_GET['action']) ? $_GET['action'] : 'list';

        switch($action) {
            case 'generate':
                $this->generate_pdf($id_factura, $id_plantilla);
                break;
            default:
        }
    }

    private function generate_pdf($idfactura, $plantilla) {
        $this->template = false;
        try {
            $factura = self::get_factura($idfactura);
            $cliente = self::get_cliente($factura->codcliente);
            $recibos = self::get_recibos($idfactura);
            $formas_pago = self::get_formas_pagos();

            $tplLoader = new DatabaseTwigLoader(new factura_plantilla());
            $twig = new Twig_Environment($tplLoader);

            $content = $twig->render($plantilla, array(
                'empresa' => $this->empresa,
                'user' => $this->user,
                'factura' => $factura,
                'cliente' => $cliente,
                'recibos' => $recibos,
                'formas_pagos' => $formas_pago
            ));

            $html2pdf = new Html2Pdf('P', 'A4', 'es', false, 'UTF-8', $tplLoader->getMargins($plantilla));
            $html2pdf->pdf->SetAuthor('');
            $html2pdf->pdf->SetTitle('Factura #'.$factura->numero2);
            $html2pdf->pdf->SetDisplayMode('fullpage');
            $html2pdf->writeHTML($content);
            ob_clean();
            $html2pdf->Output('factura_' . $idfactura .'.pdf', 'E');
        } catch (Html2PdfException $e) {
            $formatter = new ExceptionFormatter($e);
            echo $formatter->getHtmlMessage();
        }

    }
}