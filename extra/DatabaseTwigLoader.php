<?php

/**
 * Created by IntelliJ IDEA.
 * User: ggarcia
 * Date: 21/04/2016
 * Time: 07:42 AM
 *
 * Created from http://twig.sensiolabs.org/doc/recipes.html#using-a-database-to-store-templates
 */

class DatabaseTwigLoader implements Twig_LoaderInterface, Twig_ExistsLoaderInterface
{
    protected $dbh;

    public function __construct(factura_plantilla $dbh)
    {
        $this->dbh = $dbh;
    }

    public function getSource($name)
    {
        if (false === $source = $this->getValue('plantilla', $name)) {
            throw new Twig_Error_Loader(sprintf('Template "%s" does not exist.', $name));
        }

        return '<page backcolor="#FFFFFF" style="font-size: 12pt">' .
               html_entity_decode($source)
               . '</page>';
    }

    // Twig_ExistsLoaderInterface as of Twig 1.11
    public function exists($name)
    {
        return $name === $this->getValue('nombre', $name);
    }

    public function getCacheKey($name)
    {
        return $name;
    }

    public function isFresh($name, $time)
    {
        if (false === $lastModified = $this->getValue('update_date', $name)) {
            return false;
        }

        return $lastModified <= $time;
    }

    /**
     * @param $name
     * @return array
     */
    public function getMargins($name) {
        $plantilla = $this->dbh->findByName($name);
        return array(
            $plantilla->getMarginLeft(),
            $plantilla->getMarginTop(),
            $plantilla->getMarginRight(),
            $plantilla->getMarginBottom()
        );
    }

    protected function getValue($column, $name)
    {
        $value = $this->dbh->getValue($column, $name);
        return $value;
    }
}