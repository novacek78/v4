<?php

class Projecto_ModelEmail {

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

        if ($imapStream == null)
            $imapStream = $this->openMailbox();

        if ($folderName) {
        }

        $Date = new DateTime();
        $Date->sub(new DateInterval('P28D'));
        $last28Days = $Date->format('j F Y');

        $arrMessageUids = imap_search($imapStream, 'SINCE "' . $last28Days . '"', SE_UID);

        // zoznam hlaviciek emailov v mailboxe
        $imapHeaders = imap_fetch_overview($imapStream, $arrMessageUids[0] . ':' . $arrMessageUids[count($arrMessageUids) - 1], FT_UID);
        $imapHeaders = array_reverse($imapHeaders); // aby najnovsie boli prve
        imap_close($imapStream);

        foreach ($imapHeaders as $key => $Header) {

            $attr = array();
            if ( ! $Header->seen) $attr[] = 'N';
            if ( ! $Header->answered) $attr[] = 'A';
            $Header->status = implode(',', $attr);

            if (($pos = strpos($Header->date, '+')) !== false)
                $Header->date = substr($Header->date, 0, $pos - 4);
            if (($pos = strpos($Header->date, '-')) !== false)
                $Header->date = substr($Header->date, 0, $pos - 4);
            if (($pos = strpos($Header->date, ',')) !== false)
                $Header->date = substr($Header->date, $pos + 2);
            if (($pos = strpos($Header->date, '0')) === 0)
                $Header->date = substr($Header->date, 1);

            $Header->from = Utils::convertEncoding($Header->from);
            $Header->subject = Utils::convertEncoding($Header->subject);
        }

        return $imapHeaders;
    }
}