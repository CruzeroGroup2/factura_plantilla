<?php

class factura_plantilla extends fs_model {

    /**
     * @var int
     */
    private $id = null;

    /**
     * @var string
     */
    private $nombre = null;

    /**
     * @var string
     */
    private $descripcion = null;

    /**
     * @var int
     */
    private $margin_top = 0;

    /**
     * @var int
     */
    private $margin_left = 0;

    /**
     * @var int
     */
    private $margin_right = 0;

    /**
     * @var int
     */
    private $margin_bottom = 0;


    /**
     * @var string
     */
    private $plantilla = null;

    /**
     * @var string
     */
    private $visible = null;

    /**
     * @var string
     */
    private $create_date;

    /**
     * @var string
     */
    private $update_date;

    private $edit = false;

    const CACHE_KEY_ALL = 'factura_plantilla_all';
    const CACHE_KEY_SINGLE = 'factura_plantilla_{id}';

    const DATE_FORMAT = 'd-m-Y';
    const DATE_FORMAT_FULL = 'd-m-Y H:i:s';

    /**
     * factura_plantilla constructor.
     * @param array $data
     */
    public function __construct($data = array()) {
        parent::__construct('factura_plantilla', 'plugins/impresion_por_template/');

        $this->setValues($data);
    }
    
    public function setValues($data = array()) {

        $this->setId($data);
        $this->nombre = (isset($data['nombre'])) ? $data['nombre'] : null;
        $this->descripcion = (isset($data['descripcion'])) ? $data['descripcion'] : null;
        $this->margin_top = (isset($data['margin_top'])) ? $data['margin_top'] : 0;
        $this->margin_left = (isset($data['margin_left'])) ? $data['margin_left'] : 0;
        $this->margin_right = (isset($data['margin_right'])) ? $data['margin_right'] : 0;
        $this->margin_bottom = (isset($data['margin_bottom'])) ? $data['margin_bottom'] : 0;
        $this->plantilla = (isset($data['plantilla'])) ? $data['plantilla'] : null;
        $this->visible = (isset($data['visible'])) ? boolval($data['visible']) : false;

        if(isset($data['create_date']) && !$this->create_date) {
            $this->create_date = $data['create_date'];
        } elseif (!isset($data['create_date']) && !$this->create_date) {
            $this->create_date = date('Y-m-d H:i:s');;
        }

        if(isset($data['update_date'])) {
            $this->update_date = $data['update_date'];
        }

    }

    /**
     * @param boolean $edit
     */
    public function setEdit($edit) {
        $this->edit = $edit;
    }

    /**
     * @return null
     */
    public function getId() {
        return $this->id;
    }

    /**
     * @param array $data
     * @return factura_plantilla
     */
    public function setId($data) {
        // This is an ugly thing use an Hydrator insted
        if(is_int($data)) {
            $this->id = $data;
        }

        if(is_array($data)) {
            if(isset($data['idfacturaplantilla'])) {
                $this->id = $data['idfacturaplantilla'];
            }

            if(isset($data['id'])) {
                $this->id = $data['id'];
            }
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getNombre() {
        return $this->nombre;
    }

    /**
     * @param string $nombre
     * @return factura_plantilla
     */
    public function setNombre($nombre) {
        $this->nombre = $nombre;
        return $this;
    }

    /**
     * @return string
     */
    public function getDescripcion() {
        return $this->descripcion;
    }

    /**
     * @param string $descripcion
     * @return factura_plantilla
     */
    public function setDescripcion($descripcion) {
        $this->descripcion = $descripcion;
        return $this;
    }

    /**
     * @return int
     */
    public function getMarginTop() {
        return $this->margin_top;
    }

    /**
     * @param int $margin_top
     * @return factura_plantilla
     */
    public function setMarginTop($margin_top) {
        $this->margin_top = $margin_top;
        return $this;
    }

    /**
     * @return int
     */
    public function getMarginLeft() {
        return $this->margin_left;
    }

    /**
     * @param int $margin_left
     * @return factura_plantilla
     */
    public function setMarginLeft($margin_left) {
        $this->margin_left = $margin_left;
        return $this;
    }

    /**
     * @return int
     */
    public function getMarginRight() {
        return $this->margin_right;
    }

    /**
     * @param int $margin_right
     * @return factura_plantilla
     */
    public function setMarginRight($margin_right) {
        $this->margin_right = $margin_right;
        return $this;
    }

    /**
     * @return int
     */
    public function getMarginBottom() {
        return $this->margin_bottom;
    }

    /**
     * @param int $margin_bottom
     * @return factura_plantilla
     */
    public function setMarginBottom($margin_bottom) {
        $this->margin_bottom = $margin_bottom;
        return $this;
    }
    
    

    /**
     * @return string
     */
    public function getPlantilla() {
        return $this->plantilla;
    }

    /**
     * @param null $plantilla
     * @return factura_plantilla
     */
    public function setPlantilla($plantilla) {
        $this->plantilla = $plantilla;
        return $this;
    }

    /**
     * @return string
     */
    public function getVisible() {
        return $this->visible;
    }

    /**
     * @param bool $visible
     * @return factura_plantilla
     */
    public function setVisible($visible) {
        $this->visible = $visible;
        return $this;
    }

    public function getPreview() {

    }


    /**
     * @param bool $full_date
     * @return string
     */
    public function getCreateDate($full_date = false) {
        $ret = null;
        if($this->create_date) {
            $fecha = new DateTime($this->create_date);
            $format = self::DATE_FORMAT;
            if($full_date) {
                $format = self::DATE_FORMAT_FULL;
            }
            $ret = $fecha->format($format);
        }

        return $ret;
    }

    /**
     * @param bool $full_date
     * @return string
     */
    public function getUpdateDate($full_date = false) {
        $ret = null;
        if($this->update_date) {
            $fecha = new DateTime($this->update_date);
            $format = self::DATE_FORMAT;
            if($full_date) {
                $format = self::DATE_FORMAT_FULL;
            }
            $ret = $fecha->format($format);
        }

        return $ret;
    }


    /**
     * @return void
     */
    private function clean_cache() {
        $this->cache->delete(self::CACHE_KEY_ALL);
    }

    /**
     * @return mixed
     */
    protected function install() {
        return '';
    }

    /**
     * @param int $id
     *
     * @return bool|factura_plantilla
     */
    public static function get($id = 0) {
        $plantilla = new self();

        return $plantilla->fetch($id);
    }

    /**
     * @param int $id
     *
     * @return bool|factura_plantilla
     */
    public function fetch($id) {
        $plantilla = $this->cache->get(str_replace('{id}',$id,self::CACHE_KEY_SINGLE));
        if($id && !$plantilla) {
            $plantilla = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE id = " . (int) $id . ";");
            $this->cache->set(str_replace('{id}',$id,self::CACHE_KEY_SINGLE), $plantilla);
        }
        if($plantilla) {
            return new self($plantilla[0]);
        } else {
            return false;
        }
    }

    /**
     * @return factura_plantilla[]
     */
    public function fetchAll() {
        $plantillalist = array();
        $plantillas = $this->cache->get(self::CACHE_KEY_ALL);
        if(!$plantillas) {
            $plantillas = $this->db->select("SELECT * FROM " . $this->table_name . " ORDER BY create_date ASC;");
            $this->cache->set(self::CACHE_KEY_ALL, $plantillas);
        }
        foreach($plantillas as $plantilla) {
            $plantillalist[] = new self($plantilla);
        }

        return $plantillalist;
    }

    public function findByName($name = '') {
        $plantilla = $this->cache->get(str_replace('{id}', '_'.$name, self::CACHE_KEY_SINGLE));
        if($name && !$plantilla) {
            $plantilla = $this->db->select("SELECT * FROM " . $this->table_name . " WHERE nombre = '" . $name . "';");
            $this->cache->set(str_replace('{id}', '_'.$name, self::CACHE_KEY_SINGLE), $plantilla);
        }
        if($plantilla) {
            return new self($plantilla[0]);
        } else {
            return false;
        }
    }

    /**
     * @return bool|array
     */
    public function exists() {
        if(is_null($this->id)) {
            return false;
        } else {
            return $this->db->select("SELECT * FROM " . $this->table_name . " WHERE id = " . (int) $this->id . ";");
        }
    }

    public function test() {
        $status = false;
        $this->id = (int) $this->id;


        if(!$this->get_errors()) {
            $status = true;
        }

        return $status;
    }

    protected function insert() {
        $sql = 'INSERT ' . $this->table_name .
           ' SET ' .
               'nombre = ' . $this->var2str($this->getNombre()) . ',' .
               'descripcion = ' . $this->var2str($this->getDescripcion()) . ',' .
               'margin_left = ' . $this->var2str($this->getMarginLeft()) . ',' .
               'margin_top = ' . $this->var2str($this->getMarginTop()) . ',' .
               'margin_right = ' . $this->var2str($this->getMarginRight()) . ',' .
               'margin_bottom = ' . $this->var2str($this->getMarginBottom()) . ',' .
               'plantilla = ' . $this->var2str(htmlentities($this->getPlantilla())) . ',' .
               'visible = ' . $this->var2str( (int) $this->getVisible()) . ',' .
               'create_date = ' . $this->var2str($this->getCreateDate(true)) .
        ';';

        $ret = $this->db->exec($sql);

        return $ret;
    }

    protected function update() {
        $sql = 'UPDATE ' . $this->table_name .
               ' SET ' .
               'nombre = ' . $this->var2str($this->getNombre()) . ',' .
               'descripcion = ' . $this->var2str($this->getDescripcion()) . ',' .
               'margin_left = ' . $this->var2str($this->getMarginLeft()) . ',' .
               'margin_top = ' . $this->var2str($this->getMarginTop()) . ',' .
               'margin_right = ' . $this->var2str($this->getMarginRight()) . ',' .
               'margin_bottom = ' . $this->var2str($this->getMarginBottom()) . ',' .
               'plantilla = ' . $this->var2str(htmlentities($this->getPlantilla())) . ',' .
               'visible = ' . $this->var2str( (int) $this->getVisible()) . ',' .
               'update_date = ' . $this->var2str(date('Y-m-d H:i:s')) ;
        $sql .= ' WHERE id = ' . $this->getId() . ';';

        $ret = $this->db->exec($sql);

        return $ret;
    }

    /**
     * @return mixed
     */
    public function save() {
        $ret = false;

        if($this->test()) {
            $this->clean_cache();
            if($this->exists()) {
                $this->update();
            } else {
                $this->insert();
                $this->setId(intval($this->db->lastval()));
            }
        }

        if(!$this->get_errors()) {
            $ret = true;
        }

        return $ret;
    }

    /**
     * @return mixed
     */
    public function delete() {
        // TODO: Implement delete() method.
    }

    public function getValue($column, $name) {
        $plant = $this->findByName($name);
        if($plant) {
            $met = 'get' . ucfirst(strtolower($column));
            if(method_exists($plant, $met)) {
                return $plant->$met();
            }
        }
        return false;
    }

}