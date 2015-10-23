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
            $code = $this->_insertData($code);
            echo $code;
        } else {
            throw new Exception('HTML snippet "' . $fileName . '" not found.');
        }
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
                    if (isset($this->_data->$paramName)) {
                        $line = str_replace('{{' . $paramName . '}}', $this->_data->$paramName, $line);
                    } else {
                        if (ENV == 'production')
                            $line = str_replace('{{' . $paramName . '}}', '', $line);
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