<?php
/**
 * Email message building and sending
 *
 * @package    Gleez\Email
 * @author     Gleez Team
 * @version    1.1.2
 * @copyright  (c) 2011-2013 Gleez Technologies
 * @license    http://gleezcms.org/license Gleez CMS License
 * @link       https://github.com/Synchro/PHPMailer
 */
class Email {

	/**
	 * Mail object
	 * @var PHPMailer
	 */
	protected $mail;

	/**
	 * Create a new email message
	 *
	 * @return  Email
	 */
	public static function factory()
	{
		return new Email();
	}

	public function __construct()
	{
		require_once Kohana::find_file('vendor/PHPMailer', 'class.phpmailer');

		// Create phpmailer object
		$this->mail = new PHPMailer(true);

		// Set some defaults
		$this->mail->SetFrom(Config::get('site.site_email','webmaster@example.com'), Template::getSiteName());
		$this->mail->WordWrap = 70;
		$this->mail->CharSet  = 'UTF-8';
		$this->mail->XMailer  = Gleez::getVersion(FALSE, TRUE);
	}

	/**
	 * Set the message subject
	 *
	 * @param   string  $subject  New subject
	 * @return  Email
	 */
	public function subject($subject)
	{
		// Change the subject
		$this->mail->Subject = $subject;

		return $this;
	}

	/**
	 * Set the message body
	 *
	 * Multiple bodies with different types can be added by calling this method
	 * multiple times. Every email is required to have a "plain" message body.
	 *
	 * @param   string  $body  New message body
	 * @param   string  $type  Mime type: text/html, text/plain [Optional]
	 * @return  Email
	 */
	public function message($body, $type = NULL)
	{
		if ( ! $type OR $type === 'text/plain')
		{
			// Set the main text/plain body
			$this->mail->Body = $body;
		}
		else
		{
			// Add a custom mime type
			$this->mail->MsgHTML($body);
		}

		return $this;
	}

	/**
	 * Add one or more email recipients
	 *
	 * Example:
	 * ~~~
	 * // A single recipient
	 * $email->to('john.doe@domain.com', 'John Doe');
	 * ~~~
	 *
	 * @param   string  $email  Single email address
	 * @param   string  $name   Full name [Optional]
	 * @return  Email
	 */
	public function to($email, $name = NULL)
	{
		$this->mail->AddAddress($email, $name);

		return $this;
	}

	/**
	 * Add a "carbon copy" email recipient
	 *
	 * @param   string  $email  Email address
	 * @param   string  $name   Full name [Optional]
	 * @return  Email
	 */
	public function cc($email, $name = NULL)
	{
		$this->mail->AddCC($email, $name);

		return $this;
	}

	/**
	 * Add a "blind carbon copy" email recipient
	 *
	 * @param   string  $email  Email address
	 * @param   string  $name   Full name [Optional]
	 * @return  Email
	 */
	public function bcc($email, $name = NULL)
	{
		$this->mail->AddBCC($email, $name);

		return $this;
	}

	/**
	 * Add email senders
	 *
	 * @param   string  $email  Email address
	 * @param   string  $name   Full name [Optional]
	 * @return  Email
	 */
	public function from($email, $name = NULL )
	{
		$this->mail->SetFrom($email, $name);

		return $this;
	}

	/**
	 * Add "reply to" email sender
	 *
	 * @param   string  $email  Email address
	 * @param   string  $name   Full name [Optional]
	 * @return  Email
	 */
	public function reply_to($email, $name = NULL)
	{
		$this->mail->AddReplyTo($email, $name);

		return $this;
	}

	/**
	 * Set the return path for bounce messages
	 *
	 * @param   string  $email  Email address
	 * @return  Email
	 */
	public function return_path($email)
	{
		$this->mail->Sender = $email;

		return $this;
	}

	/**
	 * Sends the email
	 *
	 * @return  boolean
	 */
	public function send()
	{
		try
		{
			$this->mail->Send();
			return TRUE;
		}
		catch(Exception $e)
		{
			Log::error('Error sending mail error: :e', array(':e' => $e->getMessage()));
			return FALSE;
		}
	}

	/**
	 * Mail object of the instance
	 *
	 * @return  object phpmailer
	 */
	public function mail()
	{
		return $this->mail;
	}

}
