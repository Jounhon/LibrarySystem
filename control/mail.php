<?php
	require("../phpmailer/PHPMailerAutoload.php");
	
	//$mail->addAddress('h6g2682@gmail.com');               // Name is optional
	// $mail->addReplyTo('h6g2682@gmail.com', 'Information');
	// $mail->addCC('h6g2682@gmail.com');
	// $mail->addBCC('h6g2682@gmail.com');

	//$mail->addAttachment('/var/tmp/file.tar.gz');         // Add attachments
	//$mail->addAttachment('/tmp/image.jpg', 'new.jpg');    // Optional name


	function sendMail($action,$account,$email,$name){
		$mail = new PHPMailer;

		//$mail->SMTPDebug = 3;                               // Enable verbose debug output

		$mail->isSMTP();                                      // Set mailer to use SMTP
		$mail->Host = 'smtp.gmail.com';  // Specify main and backup SMTP servers
		$mail->SMTPAuth = true;                               // Enable SMTP authentication
		$mail->Username = 'h6g2682@gmail.com';                 // SMTP username
		$mail->Password = 'ttlwrabxedebyowe';                           // SMTP password
		$mail->SMTPSecure = 'tls';                            // Enable TLS encryption, `ssl` also accepted
		$mail->Port = 587;                                    // TCP port to connect to
		$mail->CharSet = "utf-8";
		$mail->isHTML(true);                                  // Set email format to HTML
		$mail->setFrom('h6g2682@gmail.com', 'I\'m Server');
		switch($action){
			case 'signup':
				$mail->Subject = '圖書館系統Email認證通知';
				$mail->Body    = '親愛的'.$name."，您好：<br>下面是您的聯絡Email，請再次確認，並進行驗證。<br><br><p style='color:red;'><strong>請注意：您必續完成「Email認證」，才能在圖書館系統使用預約書及其他功能。</strong></p><br><a href='http://localhost/control/checkMember.php?token=".md5($account)."'>馬上進行認證</a><br>若無法點選上面「馬上進行認證」的連結，請複製下面的網址到您的瀏覽器中，並繼續進行認證。<br>認證網址：<a href='#'>http://localhost/control/checkMember.php?token=".md5($account)."</a>";
			break;
			case 'verify':
				$mail->Subject = '圖書館系統會員認證成功';
				$mail->Body    = '恭喜你已正式啟用會員，現在開始你可以使用系統各項功能：預約書、查看借書紀錄等...。<br>希望您會喜歡，有甚麼意見或問題可以透過<a href="#">意見回饋</a>給我們，管理員會盡快為您回復。</a>';
			break;
			case 'cancel':
				$mail->Subject = '您預約的書：'.$name.'已取消';
				$mail->Body    = '您預約的'.$name.'已超過3天卻尚未借書，為保障他人權益，系統已自動幫您取消。<br>如果有甚麼問題可以透過<a href="#">意見回饋</a>給我們，管理員會盡快為您回復。</a>';
			break;
			case 'overdue':
				$mail->Subject = '您預約的書：'.$name.'已逾期';
				$mail->Body    = '您預約的'.$name.'已超過30天！<br>親愛的系統來提醒您~每逾期一天是$5，如果超過30天未還或是遺失、損毀，依其定價的3倍計價賠現。<br>如果有甚麼問題可以透過<a href="#">意見回饋</a>給我們，管理員會盡快為您回復。</a>';
			break;
			case 'fine':
				$mail->Subject = $account;
				$mail->Body    = $name;
			break;
		}
		$mails=explode(";", $email);
		for($i=0;$i<count($mails);$i++){
			$mail->addAddress($mails[$i]);     // Add a recipient
		}
		if(!$mail->send()) {
		    echo 'Message could not be sent.';
		    echo 'Mailer Error: ' . $mail->ErrorInfo;
		} else {
			//echo 'success';
		    //echo 'Message has been sent';
		}
		
	}
	
	//$mail->AltBody = 'This is the body in plain text for non-HTML mail clients';
?>