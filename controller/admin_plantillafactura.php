<?php

require_model('agente.php');
require_model('factura_plantilla.php');

/**
 * Created by IntelliJ IDEA.
 * User: ggarcia
 * Date: 18/04/2016
 * Time: 11:32 PM
 */
class admin_plantillafactura extends fs_controller {

    /**
     * @var bool
     */
    public $allow_delete;

    /**
     * @var factura_plantilla
     */
    public $plantilla;

    /**
     * @var string
     */
    public $action;

    /*
     * Esta página está en la carpeta admin, pero no se necesita ser admin para usarla.
     * Está en la carpeta admin porque su antecesora también lo está (y debe estarlo).
     */
    public function __construct() {
        parent::__construct(__CLASS__, 'Plantillas de Facturas', 'admin', true);
    }

    public function new_url() {
        $this->page->extra_url = '&action=add';
        return $this->url();
    }

    public function edit_url(factura_plantilla $plantilla) {
        $this->page->extra_url = '&action=edit&id=' . (int) $plantilla->getId();
        return $this->url();
    }

    public function delete_url(factura_plantilla $plantilla) {
        $this->page->extra_url = '&action=delete&id=' . (int) $plantilla->getId();
        return $this->url();
    }

    protected function private_core() {
        $this->share_extensions();

        /// ¿El usuario tiene permiso para eliminar en esta página?
        $this->allow_delete = $this->user->allow_delete_on(__CLASS__);
        $this->plantilla = new factura_plantilla();

        $action = (string) isset($_GET['action']) ? $_GET['action'] : 'list';

        switch($action) {
            default:
            case 'list':
                $this->indexAction();
                break;
            case 'add':
                $this->addAction();
                break;
            case 'edit':
                $this->editAction();
                break;
            case 'delete':
                $this->deleteAction();
                break;
            case 'find':
                $this->findAction();
                break;
        }
    }

    public function indexAction() {
        $this->page->extra_url = '&action=find';
        $this->template = 'admin_plantillafactura/index';
    }
    
    public function addAction() {
        $this->page->extra_url = '&action=add';

        $this->template = 'admin_plantillafactura/form';
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $this->plantilla->setValues($_POST);
            if ($this->plantilla->save()) {
                $this->new_message("Plantilla de Factura agregada correctamente!");
                $this->indexAction();
            } else {
                $this->new_error_msg("¡Imposible agregar Plantilla de Factura!");
            }
        }
    }

    public function editAction() {
        $id = (int) isset($_GET['id']) ? $_GET['id'] : 0;
        $this->plantilla = factura_plantilla::get($id);
        $this->page->extra_url = '&action=edit&id=' . (int) $this->plantilla->getId();
        $this->action = 'edit';

        $this->template = 'admin_plantillafactura/form';

        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $this->plantilla->setValues($_POST);
            $this->plantilla->setEdit(true);
            if ($this->plantilla->save()) {
                $this->new_message("Plantilla de Factura actualizada correctamente!");
                $this->indexAction();
            } else {
                $this->new_error_msg("¡Imposible actualizar Plantilla de Factura!");
            }
        }

    }

    private function deleteAction() {
        $this->page->extra_url = '&action=delete';
        $id = (int) isset($_GET['id']) ? $_GET['id'] : 0;
        $plantilla = factura_plantilla::get($id);
        if($plantilla && $this->allow_delete && $plantilla->delete()) {
            $this->new_message("Plantilla eliminada corectamente!");
        } else {
            if(!$this->allow_delete) {
                $this->new_error_msg("No tiene permisos para eliminar en esta página");
            }
            $this->new_error_msg("Error al eliminar plantilla!");
        }
        $this->indexAction();
    }
    
    public function findAction() {
        $this->page->extra_url = '&action=find';


    }

    private function share_extensions() {
        if($this->page->get('factura_custom')) {
            $tpl = new factura_plantilla();
            foreach ($tpl->fetchAll() as $plantilla) {
                $fsext = new fs_extension(
                    array(
                        'name' => 'factura_custom',
                        'page_from' => 'factura_custom',
                        'page_to' => 'ventas_factura',
                        'type' => 'pdf',
                        'text' => 'Factura ' . htmlentities($plantilla->getNombre()),
                        'params' => '&action=generate&plantilla='. urlencode($plantilla->getNombre())
                    )
                );
                $fsext->save();
            }
        }
    }
}