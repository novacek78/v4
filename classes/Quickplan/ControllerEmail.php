<?php

class Quickplan_ControllerEmail extends Quickplan_ControllerAbstract
{

    public function run() {

        $ImapSrvr = new Quickplan_ModelImapServer();

        $this->_doMailboxList($ImapSrvr);
        $this->_doMessagesList($ImapSrvr);

        $this->_View->title = 'QuickPlan - emails';
        $this->_View->ajax_url_get_email_body = Request::makeUriAbsolute('ajax', 'getEmailBody');

        $ImapSrvr->closeMailServerConnection();
    }


    private function _doMailboxList($ImapSrvr) {

        $folders = $ImapSrvr->getFolders();

        $foldersHtml = "<table class='table table-hover'>\n";
        $foldersHtml .= "<tr><th>Folder</th></tr>\n";
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
            $unreadNum = ($folder['unreadNum'] > 0) ? "<strong>($folder[unreadNum])</strong>" : '';
            $foldersHtml .= "<tr class='$rowClass'><td>$row $unreadNum</td></tr>\n";
        }
        $foldersHtml .= '</table>';

        $this->_View->mailboxes = $foldersHtml;
    }

    private function _doMessagesList($ImapSrvr) {

        // stiahne zoznam vsetkych sprav v mailboxe
        $arrHeaders = $ImapSrvr->getFolderContents();

        $msgsHtml = "<table class='table table-hover table-condensed'>\n";
        foreach ($arrHeaders as $Header) {

            $trClass = $icon = '';
            if (strpos($Header->status, 'N') !== false) {
                $trClass = 'class="unseen"';
                $icon = "<span title=\"New message\" class=\"glyphicon glyphicon-asterisk text-alert\" aria-hidden=\"true\"></span>";
            }
            if (strpos($Header->status, 'A') !== false) {
                $trClass = '';
                $icon = "<span title=\"Replied\" class=\"glyphicon glyphicon-share-alt rotate-135 text-disabled\" aria-hidden=\"true\"></span>";
            }

            $MsgDate = new DateTime($Header->date);

            $msgsHtml .= "<tr id=\"email_" . $Header->uid . "\" $trClass><td>$icon</td><td>" . $MsgDate->format('d.m.y&\n\b\s\p;H:i') . "</td><td>" . htmlspecialchars($Header->from) . "</td><td>" . htmlspecialchars($Header->subject) . "</td></tr>\n";
        }
        $msgsHtml .= '</table>';

        $this->_View->messages = $msgsHtml;
    }
}