<?php

class Quickplan_ModelEmail {


    private static $_mailServerConnection = null;


    /**
     * Vrati pole vsetkych najdenych mailboxov na emailovom konte
     *
     * @return array
     * @throws Exception
     */
    public function getFolders() {

        $mbox = $this->openMailServerConnection();

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

//        imap_close($mbox);

        return $result;
    }

    public function openMailServerConnection() {

        if ( ! isset(self::$_mailServerConnection)) {

            $connection = imap_open(
                '{' . IMAP_HOST . ':' . IMAP_PORT . '/imap' . IMAP_SSL . '}INBOX',
                IMAP_USERNAME,
                IMAP_PASSWORD,
                OP_READONLY);

            if ($connection === false) {
                throw new Exception('Can not connect to IMAP server.');
            }

            self::$_mailServerConnection = $connection;
        }

        return self::$_mailServerConnection;
    }

    public function closeMailServerConnection() {

        if ( ! isset(self::$_mailServerConnection)) {
            imap_close(self::$_mailServerConnection);
            self::$_mailServerConnection = null;
        }
    }

    public function getFolderContents($folderName = null, $imapStream = null) {

        if ($imapStream == null)
            $imapStream = $this->openMailServerConnection();

        if ($folderName) {
        }

        $Date = new DateTime();
        $Date->sub(new DateInterval('P28D'));
        $last28Days = $Date->format('j F Y');

        $arrMessageUids = imap_search($imapStream, 'SINCE "' . $last28Days . '"', SE_UID);

        // zoznam hlaviciek emailov v mailboxe
        $imapHeaders = imap_fetch_overview($imapStream, $arrMessageUids[0] . ':' . $arrMessageUids[count($arrMessageUids) - 1], FT_UID);
        $imapHeaders = array_reverse($imapHeaders); // aby najnovsie boli prve
//        imap_close($imapStream);

        foreach ($imapHeaders as $key => $Header) {

            $attr = array();
            if ( ! $Header->seen) $attr[] = 'N';
            if ($Header->answered) $attr[] = 'A';
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

    public function getEmailBody($uid, $imapStream = null) {

        if ($imapStream == null)
            $imapStream = $this->openMailServerConnection();

        //TODO zbavit sa tychto globalov
        global $charset, $htmlmsg, $plainmsg, $attachments;

        $htmlmsg = $plainmsg = $charset = '';
        $attachments = array();

        // BODY
        $struct = imap_fetchstructure($imapStream, $uid, FT_UID);
        if ( ! $struct->parts)  // simple
            $this->_getEmailPart($imapStream, $uid, $struct, 0);  // pass 0 as part-number
        else {  // multipart: cycle through each part
            foreach ($struct->parts as $partNo_0 => $p)
                $this->_getEmailPart($imapStream, $uid, $p, $partNo_0 + 1);
        }

        return $htmlmsg;
    }

    private function _getEmailPart($imapStream, $uid, $p, $partno) {

        // $partno = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
        //TODO zbavit sa tychto globalov
        global $htmlmsg, $plainmsg, $charset, $attachments;

        // DECODE DATA
        $data = ($partno) ?
            imap_fetchbody($imapStream, $uid, $partno, FT_UID) :  // multipart
            imap_body($imapStream, $uid, FT_UID);  // simple
        // Any part may be encoded, even plain text messages, so check everything.
        if ($p->encoding == 4)
            $data = quoted_printable_decode($data);
        elseif ($p->encoding == 3)
            $data = base64_decode($data);

        // PARAMETERS
        // get all parameters, like charset, filenames of attachments, etc.
        $params = array();
        if (isset($p->parameters))
            foreach ($p->parameters as $x)
                $params[strtolower($x->attribute)] = $x->value;
        if (isset($p->dparameters))
            foreach ($p->dparameters as $x)
                $params[strtolower($x->attribute)] = $x->value;

        // ATTACHMENT
        // Any part with a filename is an attachment,
        // so an attached text file (type 0) is not mistaken as the message.
        if (isset($params['filename']) || isset($params['name'])) {
            // filename may be given as 'Filename' or 'Name' or both
            $filename = (isset($params['filename'])) ? $params['filename'] : $params['name'];
            // filename may be encoded, so see imap_mime_header_decode()
            $attachments[$filename] = $data;  // this is a problem if two files have same name
        }

        // TEXT
        if ($p->type == 0 && $data) {
            // Messages may be split in different parts because of inline attachments,
            // so append parts together with blank row.
            if (strtolower($p->subtype) == 'plain')
                $plainmsg .= trim($data) . "\n\n";
            else
                $htmlmsg .= $data . "<br><br>";
            $charset = $params['charset'];  // assume all parts are same charset
        }

        // EMBEDDED MESSAGE
        // Many bounce notifications embed the original message as type 2,
        // but AOL uses type 1 (multipart), which is not handled here.
        // There are no PHP functions to parse embedded messages,
        // so this just appends the raw source to the main message.
        elseif ($p->type == 2 && $data) {
            $plainmsg .= $data . "\n\n";
        }

        // SUBPART RECURSION
        if (isset($p->parts)) {
            foreach ($p->parts as $partno0 => $p2)
                $this->_getEmailPart($imapStream, $uid, $p2, $partno . '.' . ($partno0 + 1));  // 1.2, 1.2.1, etc.
        }
    }
}