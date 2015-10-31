<?php

class Quickplan_ModelImapServer {


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

            $Header->from = $this->headerConvertEncoding($Header->from);
            $Header->subject = $this->headerConvertEncoding($Header->subject);
        }

        return $imapHeaders;
    }

    /**
     * Prekonvertuje hlavicky do pozadovaneho kodovania
     *
     * @param string $text
     * @param string $targetEncoding
     * @return string
     */
    public function headerConvertEncoding($text, $targetEncoding = 'utf-8') {

        $result = array();

        $arr = imap_mime_header_decode($text);

        foreach ($arr as $Part) {

            if (($Part->charset != 'default') && (strtolower($Part->charset) != $targetEncoding)) {
                $result[] = mb_convert_encoding($Part->text, $targetEncoding, $Part->charset);
            } else {
                $result[] = $Part->text;
            }
        }

        return implode(' ', $result);
    }

    public function getEmailBody($uid, $imapStream = null) {

        if ($imapStream == null)
            $imapStream = $this->openMailServerConnection();

        //TODO zbavit sa tychto globalov
        global $htmlmsg, $plainmsg, $attachments;

        $htmlmsg = $plainmsg = '';
        $attachments = array();

        // BODY
        $struct = imap_fetchstructure($imapStream, $uid, FT_UID);
        if ( ! isset($struct->parts)) { // simple txt email
            $this->_getEmailPart($imapStream, $uid, $struct, 0);  // pass 0 as part-number
        } else {  // multipart: cycle through each part
            foreach ($struct->parts as $partNo_0 => $p)
                $this->_getEmailPart($imapStream, $uid, $p, $partNo_0 + 1);
        }

        if ( ! empty($htmlmsg))
            return $htmlmsg;
        else
            return nl2br($plainmsg);
    }

    private function _getEmailPart($imapStream, $uid, $emailPart, $partNumber) {

        // $partNumber = '1', '2', '2.1', '2.1.3', etc for multipart, 0 if simple
        //TODO zbavit sa tychto globalov
        global $htmlmsg, $plainmsg, $attachments;

        // DECODE DATA
        $data = ($partNumber) ?
            imap_fetchbody($imapStream, $uid, $partNumber, FT_UID) :  // multipart
            imap_body($imapStream, $uid, FT_UID);  // simple

        // Any part may be encoded, even plain text messages, so check everything.
        if ($emailPart->encoding == 4)
            $data = quoted_printable_decode($data);
        elseif ($emailPart->encoding == 3)
            $data = base64_decode($data);

        // PARAMETERS
        // get all parameters, like charset, filenames of attachments, etc.
        $params = array();
        if (isset($emailPart->parameters))
            foreach ($emailPart->parameters as $x)
                $params[strtolower($x->attribute)] = $x->value;
        if (isset($emailPart->dparameters))
            foreach ($emailPart->dparameters as $x)
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
        if ($emailPart->type == 0 && $data) {
            // Messages may be split in different parts because of inline attachments,
            // so append parts together with blank row.
            $data = mb_convert_encoding($data, 'utf-8', $params['charset']);
            if (strtolower($emailPart->subtype) == 'plain')
                $plainmsg .= trim($data) . "\n\n";
            else
                $htmlmsg .= $data . "<br><br>";
        }

        // EMBEDDED MESSAGE
        // Many bounce notifications embed the original message as type 2,
        // but AOL uses type 1 (multipart), which is not handled here.
        // There are no PHP functions to parse embedded messages,
        // so this just appends the raw source to the main message.
        if ($emailPart->type == 2 && $data) {
            $plainmsg .= $data . "\n\n";
        }

        // SUBPART RECURSION
        if (isset($emailPart->parts)) {
            foreach ($emailPart->parts as $partno0 => $p2)
                $this->_getEmailPart($imapStream, $uid, $p2, $partNumber . '.' . ($partno0 + 1));  // 1.2, 1.2.1, etc.
        }
    }

}