<?php

class Projecto_ControllerEmail extends Projecto_ControllerAbstract
{

    public function run() {

        $Email = new Projecto_ModelEmail();
        $folders = $Email->getFolders();

        $foldersHtml = "<table class='table table-hover'>\n";
        $foldersHtml .= "<tr><th>Folder</th><th>Messages (new)</th></tr>\n";
        $parents = array();
        foreach ($folders as $folder) {

            // odstranime z nazvu mailboxu nazov rodica
            $folderName = str_replace($parents, '', $folder['name']);

            // ak na zaciatku este ostala bodka (delimiter) odstranime
            while (strpos($folderName, $folder['delimiter']) === 0)
                $folderName = substr($folderName, 1);

            $row = $folderName;
            // ak ma tento folder podradene, ulozime si jeho meno
            if ($folder['attribs'] == LATT_HASCHILDREN) {
                $parents[] = $folderName;
            }

            $rowClass = ($folder['newNum'] > 0) ? 'success' : '';
            $unreadNum = ($folder['unreadNum'] > 0) ? "<strong>($folder[unreadNum])</strong>" : '';;
            $foldersHtml .= "<tr class='$rowClass'><td>$row</td><td>$folder[msgNum] $unreadNum</td></tr>\n";
        }
        $foldersHtml .= '</table>';

        // stiahne zoznam vsetkych sprav v mailboxe
        $inboxContent = $Email->getFolderContents();
        $inboxContent = array_reverse($inboxContent);

        $msgsHtml = "<table class='table table-hover table-striped'>\n";
        foreach ($inboxContent as $key => $message) {
            $msgsHtml .= "<tr><td>$message</td></tr>\n";
            if ($key == 30) break;
        }
        $msgsHtml .= '</table>';

        $this->_setViewData('title', 'Projecto');
        $this->_setViewData('mailboxes', $foldersHtml);
        $this->_setViewData('messages', $msgsHtml);
        $this->_setViewData('message_body', 'Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>
Ahoj Janko....<br>');
    }
}