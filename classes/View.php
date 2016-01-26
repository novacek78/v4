<?php

abstract class View
{

    /**
     * @var stdClass
     */
    protected $_data;

    public abstract function render();


    /**
     * Nasetuje data pohladu
     *
     * @param stdClass $data
     */
    public function setData($data) {

        $this->_data = $data;
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
            preg_match_all('/{{snippet:(.*?)}}/', $line, $match);
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

        // cely HTML kod rozbijeme na riadky
        $lines = explode(PHP_EOL, $htmlCode);

        // v kazdom riadku hladame vyskyt placeholdrov {{xxxx}}
        foreach ($lines as $lineNum => $line) {
            preg_match_all('/{{(.*?)}}/', $line, $match);
            if ( ! empty($match[1])) {

                // nezavisle na tom, kolko placeholdrov sa naslo, vsetky nahradime datami (ak su nasetovane)
                $params = $match[1];
                foreach ($params as $paramName) {

                    // treba vlozit data z premennej "$paramName"
                    if (isset($this->_data->$paramName)) {

                        if (is_array($this->_data->$paramName)){
                            // naplnenie datami z pola
                            //....
                        } else {
                            // obyc.skalarne data
                            $line = str_replace('{{' . $paramName . '}}', $this->_data->$paramName, $line);
                        }

                    } else {
                        // ak nemame data, ktore tam treba doplnit, tak tam dame prazdny retazec (ak sme na vyvojovom prostredi, tak aj s upozornenim)
                        if (ENV == 'production')
                            $line = str_replace('{{' . $paramName . '}}', '', $line);
                        else {
                            $line = str_replace('{{' . $paramName . '}}', "Data '$paramName' not set!'", $line);
                            Logger::error("Missing data for parameter '$paramName' in snippet.");
                        }
                    }
                }

                // riadok s nahradenymi placeholdrami ulozime naspat do pola riadkov
                $lines[$lineNum] = $line;
            }
        }

        // vsetko spojime naspat do HTML a vratime
        return implode(PHP_EOL, $lines);
    }
}