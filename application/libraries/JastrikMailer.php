<?php
  if (!defined('BASEPATH')) exit ('No direct script access allowed');

  /**
  * 
  */
  class JastrikMailer
  {
    // Prepare instance variable
    private $mailer;

    // Prepare sender variable
    private $senderMail;
    private $senderName;

    // Prepare recipient variable
    private $recipientMail;

    // Prepare mail variable
    private $subject;
    private $mailBody;
    private $alternateBody;

    function __construct()
    {
      $CI =& get_instance();
      $this->mailer = new PHPMailer();

      $this->mailer->isSMTP();                       
      //$this->mailer->SMTPDebug = 2;

      $this->mailer->Host = 'smtp.gmail.com';
      $this->mailer->Port = 587;
      $this->mailer->SMTPSecure = 'tls';
      $this->mailer->SMTPAuth = TRUE;

      $this->mailer->Username = 'grc.jastrik@gmail.com';
      $this->mailer->Password = 'jastrikgrc2016';
    }

    /**
     * Assign senderMail
     * 
     * @param String $mail    email address
     */
    public function setSenderMail ($mail)
    {
      $this->senderMail = $mail;
    }

    public function getSenderMail ()
    {
      if (empty($this->senderMail)) {
        return NULL;
      } else {
        return $this->senderMail;
      }
    }

    /**
     * Assign senderName
     * 
     * @param String $name    sender name
     */
    public function setSenderName ($name)
    {
      $this->senderName = $name;
    }

    public function getSenderName ()
    {
      if (empty($this->senderName)) {
        return NULL;
      } else {
        return $this->senderName;
      }
    }

    /**
     * Assign senderMail and senderName from setter method
     * 
     * @param String $email     sender email
     * @param String $name      sender name
     */
    public function setSender (
      $email, 
      $name
      )
    {
      $this->setSenderMail($email);
      $this->setSenderName($name);
    }

    /**
     * Assign recipientMail variable
     * 
     * @param String $email     recipient's email address
     */
    public function setRecipientMail ($email)
    {
      $this->recipientMail = $email;
    }

    public function getRecipientMail ()
    {
      if (empty($this->recipientMail)) {
        return NULL;
      } else {
        return $this->recipientMail;
      }
    }

    /**
     * Assign email subject
     * 
     * @param String $subject     Email subject
     */
    public function setSubject ($subject)
    {
      $this->subject = $subject;
    }

    public function getSubject ()
    {
      if (empty($this->subject)) {
        return NULL;
      } else {
        return $this->subject;
      }
    }

    /**
     * Assign mail body (HTML format)
     * 
     * @param String $mailBody          HTML email body
     */
    public function setMailBody ($mailBody)
    {
      $this->mailBody = $mailBody;
    }

    public function getMailBody ()
    {
      if (empty($this->mailBody)) {
        return NULL;
      } else {
        return $this->mailBody;
      }
    }

    /**
     * Assign mail body (String format)
     * 
     * @param String $altMailBody       String format of HTML email
     */
    public function setAlternateBody ($altMailBody)
    {
      $this->alternateBody = $altMailBody;
    }

    public function getAlternateBody ()
    {
      if (empty($this->alternateBody)) {
        return NULL;
      } else {
        return $this->alternateBody;
      }
    }

    public function kirimEmail ()
    {  
      if (
        empty($this->senderMail) 
        || empty($this->senderName) 
        || empty($this->recipientMail) 
        || empty($this->subject) 
        || empty($this->mailBody) 
        || empty($this->alternateBody) 
        ) {
        return FALSE;
      } else {
        $this->mailer->setFrom(
          $this->senderMail,
          $this->senderName
          );
        $this->mailer->addReplyTo(
          $this->senderMail,
          $this->senderName
          );
        $this->mailer->addAddress($this->recipientMail);

        $this->mailer->Subject = $this->subject;
        $this->mailer->Body = $this->mailBody;
        $this->mailer->AltBody = $this->alternateBody;

        if (!$this->mailer->send()) {
          return FALSE;
          // return $this->mailer->ErrorInfo;
        } else {
          return TRUE;
          // return $this->mailer->ErrorInfo;
        }
      }
    }
  }