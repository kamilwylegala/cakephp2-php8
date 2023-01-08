<?php
/**
 * MailTransportTest file
 *
 * CakePHP(tm) Tests <https://book.cakephp.org/2.0/en/development/testing.html>
 * Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 *
 * Licensed under The MIT License
 * For full copyright and license information, please see the LICENSE.txt
 * Redistributions of files must retain the above copyright notice
 *
 * @copyright     Copyright (c) Cake Software Foundation, Inc. (https://cakefoundation.org)
 * @link          https://book.cakephp.org/2.0/en/development/testing.html CakePHP(tm) Tests
 * @package       Cake.Test.Case.Network.Email
 * @since         CakePHP(tm) v 2.0.0
 * @license       https://opensource.org/licenses/mit-license.php MIT License
 */

App::uses('CakeEmail', 'Network/Email');
App::uses('AbstractTransport', 'Network/Email');
App::uses('MailTransport', 'Network/Email');

/**
 * Test case
 */
class MailTransportTest extends CakeTestCase {

/**
 * Setup
 *
 * @return void
 */
	public function setUp() : void {
		parent::setUp();
		$this->MailTransport = $this->getMock('MailTransport', array('_mail'));
		$this->MailTransport->config(array('additionalParameters' => '-f'));
	}

/**
 * testSend method
 *
 * @return void
 */
	public function testSendData() {
		$email = $this->getMock('CakeEmail', array('message'), array());
		$email->from('noreply@cakephp.org', 'CakePHP Test');
		$email->returnPath('pleasereply@cakephp.org', 'CakePHP Return');
		$email->to('cake@cakephp.org', 'CakePHP');
		$email->cc(array('mark@cakephp.org' => 'Mark Story', 'juan@cakephp.org' => 'Juan Basso'));
		$email->bcc('phpnut@cakephp.org');
		$email->messageID('<4d9946cf-0a44-4907-88fe-1d0ccbdd56cb@localhost>');
		$longNonAscii = 'Foø Bår Béz Foø Bår Béz Foø Bår Béz Foø Bår Béz';
		$email->subject($longNonAscii);
		$date = date(DATE_RFC2822);
		$email->setHeaders(array(
			'X-Mailer' => 'CakePHP Email',
			'Date' => $date,
			'X-add' => mb_encode_mimeheader($longNonAscii, 'utf8', 'B'),
		));
		$email->expects($this->any())->method('message')
			->will($this->returnValue(array('First Line', 'Second Line', '.Third Line', '')));

		$encoded = '=?UTF-8?B?Rm/DuCBCw6VyIELDqXogRm/DuCBCw6VyIELDqXogRm/DuCBCw6VyIELDqXog?=';
		$encoded .= ' =?UTF-8?B?Rm/DuCBCw6VyIELDqXo=?=';

		$data = "From: CakePHP Test <noreply@cakephp.org>" . "\r\n";
		$data .= "Return-Path: CakePHP Return <pleasereply@cakephp.org>" . "\r\n";
		$data .= "Cc: Mark Story <mark@cakephp.org>, Juan Basso <juan@cakephp.org>" . "\r\n";
		$data .= "Bcc: phpnut@cakephp.org" . "\r\n";
		$data .= "X-Mailer: CakePHP Email" . "\r\n";
		$data .= "Date: " . $date . "\r\n";
		$data .= "X-add: " . $encoded . "\r\n";
		$data .= "Message-ID: <4d9946cf-0a44-4907-88fe-1d0ccbdd56cb@localhost>" . "\r\n";
		$data .= "MIME-Version: 1.0" . "\r\n";
		$data .= "Content-Type: text/plain; charset=UTF-8" . "\r\n";
		$data .= "Content-Transfer-Encoding: 8bit";

		$this->MailTransport->expects($this->once())->method('_mail')
			->with(
				'CakePHP <cake@cakephp.org>',
				$encoded,
				implode("\r\n", array('First Line', 'Second Line', '.Third Line', '')),
				$data,
				'-f'
			);

		$result = $this->MailTransport->send($email);

		$this->assertStringContainsString('Subject: ', $result['headers']);
		$this->assertStringContainsString('To: ', $result['headers']);
	}

}
