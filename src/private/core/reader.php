<?php


class Reader
{
    /**
     * @property XMLReader $reader
     */
    private $reader;

    /**
     * Create a new instance of Reader
     *
     * @param string $file The XML file full path
     */
    public function __construct($file)
    {
        if (!is_readable($file)) {
            throw new \UnexpectedValueException("Failed to open file:
                {$file} Permission denied " . __METHOD__ . ' ' . 'method');
        }

        $this->reader = new XMLReader();
        $this->reader->open($file, LIBXML_NOBLANKS | LIBXML_COMPACT);
    }

    public function read($path)
    {
        if (empty($path)) {
            throw new \UnexpectedValueException('Node path can\'t be empty: ' . __METHOD__ . ' method');
        }

        // Establece el camino recorrido por el reader
        $pathNode = '';
        // Comenzar a leer el XML desde el primer nodo
        while ($this->reader->read()) {
            // Nombre y tipo del nodo en el cual se encuentra el reader
            $nodeName = $this->reader->name;
            $nodeType = $this->reader->nodeType;
            /**
             * Analizar, si el nodo es un "start element"
             * @see https://secure.php.net/manual/es/class.xmlreader.php
             */
            if (XMLReader::ELEMENT == $nodeType) {
                if (empty($pathNode)) {
                    $pathNode = $nodeName;
                } else {
                    $newPath = implode('/', [$pathNode, $nodeName]);
                    /**
                     * Adiciona el nombre del nodo actual al camino recorrido si
                     * forma parte del camino que se está analizando
                     */
                    if (false !== strpos($path, $newPath)) {
                        $pathNode = $newPath;
                    }
                }
                // Comparar el camino recorrido con el node que se desea encontrar
                if ($pathNode == $path) {
                    // Eliminar el nombre del nodo del camino recorrido
                    $pathNode = preg_replace("/\/?{$nodeName}$/", '', $pathNode);
                    /**
                     * Obtener la representación XML como cadena del nodo encontrado
                     * se incluyen los tags del nodo, se crea un Objeto SimpleXMLElement
                     * y se retorna un Generador
                     */
                    yield (new SimpleXMLElement($this->reader->readOuterXML()));
                }
            }
        }
    }
}
