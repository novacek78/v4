<?php

abstract class View
{

    /**
     * @var stdClass
     */
    protected $_data;

    /**
     * Inicializacia pohladu
     */
    public function __construct() {

        $this->_data = new stdClass();
    }

    public abstract function render();

    public function __get($name) {

        return $this->_data->$name;
    }

    /**
     * Ulozi data pre pouzitie v pohlade.
     * Ak je $value asociativne pole, bude vytvoreny objekt stdClass.
     * Pracuje len s 1-rozmernymi poliami !
     * Ak je $value NULL, danu polozku z pola hodnot vymaze.
     *
     * @param string $name
     * @param mixed  $value Moze to byt : hodnota | non-assoc.pole | assoc.pole | null
     */
    public function __set($name, $value) {

        if ($value === null) {

            // vymazanie danej polozky z pola dat
            if (isset($this->_data->$name))
                unset($this->_data->$name);
        } else {

            if (is_array($value) && Utils::isAssociativeArray($value)) {

                if ( ! isset($this->_data->$name)) {
                    $this->_data->$name = new stdClass();
                }
                foreach ($value as $key => $val) {
                    if ($val === null)
                        unset($this->_data->$name->$key);
                    else
                        $this->_data->$name->$key = $val;
                }
            } else {
                $this->_data->$name = $value;
            }
        }
    }

    /**
     * Nahra utrzok HTML kodu, nahradi v nom placeholdre datami a vyechuje ho.
     *
     * @param string $name
     * @throws Exception
     */
    protected function _renderSnippet($name) {

        // podporuje aj zanorenie snippetov do podadresarov cez '_'
        $name = str_replace('_', DIRECTORY_SEPARATOR, $name);

        $fileName = CLASS_DIR . APP_DIR . "viewSnippets/$name.html";
        if (file_exists($fileName)) {
            $code = file_get_contents($fileName);
            $code = $this->_importSnippets($code);
            $code = $this->_insertData($code);
            $code = $this->_insertArrays($code);
            echo $code;
        } else {
            throw new Exception('HTML snippet "' . $fileName . '" not found.');
        }
    }

    /**
     * V HTML kode snippetu nahradime placeholdre dalsimi snippetmi (ak sa nejake maju vlozit)
     *
     * @param string $snippetBody
     * @return string
     * @throws Exception
     */
    protected function _importSnippets($snippetBody) {

        // cely HTML kod rozbijeme na riadky
        $lines = explode(PHP_EOL, $snippetBody);

        // v kazdom riadku hladame vyskyt placeholdrov {snippet{xxxx}}
        foreach ($lines as $lineNum => $line) {
            preg_match_all('/{{snippet:(.*)}}/', $line, $match);
            if ( ! empty($match[1])) {

                // nezavisle na tom, kolko placeholdrov sa naslo, vsetky nahradime vlozenim html snippetu
                $snippets = $match[1];
                foreach ($snippets as $snippetName) {

                    // podporuje aj zanorenie snippetov do podadresarov cez '_'
                    $snippetName = str_replace('_', DIRECTORY_SEPARATOR, $snippetName);
                    $snippetFileName = CLASS_DIR . APP_DIR . "viewSnippets/$snippetName.html";

                    if (file_exists($snippetFileName)) {
                        $snippetHtmlCode = file_get_contents($snippetFileName);
                        $line = str_replace('{{snippet:' . $snippetName . '}}', $snippetHtmlCode, $line);
                    } else {
                        throw new Exception("Snippet not found: $snippetFileName");
                    }
                }

                // riadok s nahradenymi placeholdrami ulozime naspat do pola riadkov
                $lines[$lineNum] = $line;
            }
        }

        // vsetko spojime naspat do HTML a vratime
        return implode(PHP_EOL, $lines);
    }

    /**
     * V HTML kode nahradime placeholdre datami pohladu
     *
     * @param string $htmlCode
     * @return string
     */
    protected function _insertData($htmlCode) {

        preg_match_all('/{{([a-zA-Z0-9_]*)}}/', $htmlCode, $match);
        $params = $match[1];

        if ( ! empty($params)) {

            // nezavisle na tom, kolko placeholdrov sa naslo, vsetky nahradime datami (ak su nasetovane)
            foreach ($params as $paramName) {

                // treba vlozit data z premennej "$paramName"
                if (isset($this->_data->$paramName)) {

                    $htmlCode = str_replace('{{' . $paramName . '}}', $this->_data->$paramName, $htmlCode);

                } else {
                    // ak nemame data, ktore tam treba doplnit, tak tam dame prazdny retazec (ak sme na vyvojovom prostredi, tak aj s upozornenim)
                    if (ENV == 'production')
                        $htmlCode = str_replace('{{' . $paramName . '}}', '', $htmlCode);
                    else {
                        $htmlCode = str_replace('{{' . $paramName . '}}', "Data '$paramName' not set!'", $htmlCode);
                        Logger::error("Missing data for parameter '$paramName' in snippet.");
                    }
                }
            }
        }

        // vsetko spojime naspat do HTML a vratime
        return $htmlCode;
    }

    /**
     * V HTML kode nahradime placeholdre array datami
     *
     * @param string $htmlCode
     * @return string
     */
    protected function _insertArrays($htmlCode) {

        preg_match_all('/{{arraystart:([\s[:graph:]]*){{arrayend:}}/u', $htmlCode, $match); // \s = any whitespace , [:graph:] = any visible character , u = "treat as unicode" modifier
        $blocks = $match[1];

        if ( ! empty($blocks)) {

            // nezavisle na tom, kolko placeholdrov sa naslo, vsetky nahradime datami (ak su nasetovane)
            foreach ($blocks as $htmlBlock) {

                $arrayName = substr($htmlBlock, 0, strpos($htmlBlock, '}}'));
                if ( ! isset($this->_data->$arrayName)) {
                    Logger::debug("Array '$arrayName' not found in View's data.");
                    continue;
                }

                $htmlBlock = str_replace("$arrayName}}", '', $htmlBlock); // ocistime zaciatok bloku od nazvu pola
                $htmlResult = '';
                foreach($this->_data->$arrayName as $zaznam) {
                    preg_match_all('/\[\[([a-zA-Z0-9_]*)\]\]/', $htmlBlock, $match);
                    $keys = $match[1]; // vsetky najdene kluce/indexy pola

                    // nahradime v jednej instancii HTML bloku vsetky vyskyty klucov pola
                    $tmpBlock = $htmlBlock;
                    foreach ($keys as $key) {
                        $tmpBlock = str_replace("[[$key]]", $zaznam[$key], $tmpBlock);
                    }
                    // takto opraveny blok pripojime k vysledku
                    $htmlResult .= $tmpBlock;
                }
                // povodny blok nahradime vysledkom
                $htmlCode = str_replace(
                    "{{arraystart:$arrayName}}" . $htmlBlock . "{{arrayend:}}",
                    $htmlResult,
                    $htmlCode
                );
            }
        }

        // vsetko spojime naspat do HTML a vratime
        return $htmlCode;
    }
}