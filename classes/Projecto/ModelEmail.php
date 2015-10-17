<?php

class Projecto_ModelEmail
{

    /**
     * Vrati pole vsetkych najdenych mailboxov na emailovom konte
     *
     * @return array
     * @throws Exception
     */
    public function getFolders() {

        $mbox = $this->openMailbox();

        $result = array();

        $boxes = imap_getmailboxes($mbox, '{' . IMAP_HOST . '}', '*');

        if (is_array($boxes)) {
            foreach ($boxes as $key => $val) {
                $result[$key]['name'] = str_replace('{' . IMAP_HOST . '}', '', imap_utf7_decode($val->name));
                $result[$key]['delimiter'] = $val->delimiter;
                $result[$key]['attribs'] = $val->attributes;
                $Status = imap_status($mbox, $val->name, SA_ALL);
                $result[$key]['msgNum'] = $Status->messages;
                $result[$key]['newNum'] = (isset($Status->recent)) ? $Status->recent : 0;
                $result[$key]['unreadNum'] = (isset($Status->unseen)) ? $Status->unseen : 0;
            }
        } else {
            Logger::error("imap_getmailboxes() failed: " . imap_last_error());
        }

        imap_close($mbox);

        return $result;
    }

    public function openMailbox() {

        $return = imap_open(
            '{' . IMAP_HOST . ':' . IMAP_PORT . '/imap' . IMAP_SSL . '}INBOX',
            IMAP_USERNAME,
            IMAP_PASSWORD,
            OP_READONLY);

        if ($return === false) {
            throw new Exception('Can not connect to IMAP server.');
        } else {
            return $return;
        }
    }

    public function getFolderContents($folderName = null, $imapStream = null) {

        if ($imapStream == null) $imapStream = $this->openMailbox();

        if ($folderName) {
        }

        return imap_headers($imapStream);
    }
}