<?php
	$action = trim(isset($_GET['action'])?$_GET['action']:'');

	if ($action != ''){
		if ($action == 'sendprofile'){
			$idPersonne = isset($_GET['id'])?$_GET['id']:0;
			if ($idPersonne != 0){

				$personne = Personne::getById($idPersonne);

				include_once ROOTSCRIPTS.'phpmailer/src/Exception.php';
				include_once ROOTSCRIPTS.'phpmailer/src/PHPMailer.php';
				include_once ROOTSCRIPTS.'phpmailer/src/SMTP.php';
				include_once ROOTMODELS.'model_auth.php';

				$mail = new \PHPMailer\PHPMailer\PHPMailer(true);
				$mail->setLanguage('fr', ROOTSCRIPTS.'phpmailer/language');
				$mail->CharSet = 'utf-8';
				// Create the email object
				try {
					//Server settings
					$mail->SMTPDebug = 0;                                 // Enable verbose debug output
					$mail->isSMTP();                                      // Set mailer to use SMTP
					$mail->Host = $smtpParams['host'];  // Specify main and backup SMTP servers
					$mail->Port = $smtpParams['port'];                                    // TCP port to connect to
					$mail->Helo = $smtpParams['helo'];
					if ($smtpParams['auth']){
						$mail->SMTPAuth = $smtpParams['auth'];                               // Enable SMTP authentication
						$mail->Username = $smtpParams['user'];                 // SMTP username
						$mail->Password = $smtpParams['pass'];                           // SMTP password
						$mail->SMTPAutoTLS = true;
						$mail->SMTPSecure = 'TLS';                            // Enable TLS encryption, `ssl` also accepted
						$mail->SMTPOptions =
							array(
								"ssl" => array(
									"verify_peer" => false,
									"verify_peer_name" => false,
									"allow_self_signed" => true
								)
							);
					}

					//Recipients
					$mail->setFrom('epsinet@devatom.net', 'Epsinet');
					$mail->addAddress($personne->getEmail(), $personne->getNomComplet());     // Add a recipient

					//Content
					$mail->isHTML(true);                                  // Set email format to HTML
					$mail->Subject = "Vos informations d'accès à la plateforme EPSINET";
					$messageHtml = "<html><body>Bonjour,<br /> vous recevez ce message de la part de la plateforme EPSINET.<br />";
					$messageHtml .= 'Pour vous connecter : <a href="'.ROOTHTML.'" title="EPSINET">EPSINET</a><br />';
					if (!$personne->getUserAuth()->getId() == 0){
						$messageHtml .= 'Votre identifiant : '.$personne->getUserAuth()->getLogin().'<br />';
						$messageHtml .= 'Votre mot de passe : '.$personne->getUserAuth()->getPassword().'<br />';
					}else{
						$messageHtml .= "Vos information d'authentification ne sont pas encore enregistrées.<br />";
						$messageHtml .= "N'hésitez pas à nous demander de les créer pour que vous puissiez accéder à l'application.<br />";
					}
					$messageHtml .= 'Votre profil est '.$personne->getNomComplet().' => '.$personne->get_class().'<br /><br />';
					$messageHtml .= "Cordialement</body></html>";

					$messageText = "Bonjour,\r\n vous recevez ce message de la part de la plateforme EPSINET.\r\n";
					$messageText .= 'Pour vous connecter : '.ROOTHTML."\r\n";
					if (!$personne->getUserAuth()->exists()){
						$messageText .= 'Votre identifiant : '.$personne->getUserAuth()->getLogin()."\r\n";
						$messageText .= 'Votre mot de passe : '.$personne->getUserAuth()->getPassword()."\r\n";
					}else{
						$messageText .= "Vos information d'authentification ne sont pas encore enregistrées.<br />";
						$messageText .= "N'hésitez pas à nous demander de les créer pour que vous puissiez accéder à l'application.<br />";
					}
					$messageText .= 'Votre profil est '.$personne->getNomComplet().' => '.$personne->get_class()."\r\n\r\n";
					$messageText .= "Cordialement.";

					$mail->Body = $messageHtml;
					$mail->AltBody = $messageText;

					$mail->send();
					print 'Message envoyé';
				} catch (Exception $e) {
					print 'Message non envoyé.<br />';
					print 'Erreur : ' . $mail->ErrorInfo;
				}
			}
		}elseif ($action == 'delpersonne'){
			var_dump($_GET);
		}elseif ($action == 'renewpassword'){
			$idPersonne = isset($_GET['id'])?$_GET['id']:0;
			if ($idPersonne != 0) {
				$personne = Personne::getById($idPersonne);

				if ($personne->getUserAuth()->exists()){
					$personne->getUserAuth()->setPassword(randomPassword());
					User::update($personne->getUserAuth());
					print('1');
				}else{
					print("Opération impossible car la personne n'a pas de compte utilisateur !");
				}
			}
		}elseif ($action == 'dropuserauth'){
			$idPersonne = isset($_GET['id'])?$_GET['id']:0;
			if ($idPersonne != 0) {
				$personne = Personne::getById($idPersonne);
				if ($personne->getUserAuth()->exists()){
					$personne->removeUserAuth();
					print('1');
				}else{
					print("Opération impossible car la personne n'a pas de compte utilisateur !");
				}
			}
		}elseif ($action == 'dropmodule'){
			$idModule = isset($_GET['id'])?$_GET['id']:0;
			if ($idModule != 0){
				$module = Module::getById($idModule);

			}
		}elseif ($action == 'getmodulesforstudent'){
			include_once ROOTMODELS.'model_module.php';
			$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
			$idEtudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
			$listeModules = Module::getListeFromPf($idPf, $idEtudiant);
			$listeIdModules = Array();
			foreach ($listeModules as $module){
				$listeIdModules[] = $module->getId();
			}
			print(json_encode($listeIdModules));
		}elseif ($action == 'setmodulesforstudent'){
			include_once ROOTMODELS.'model_etudiant.php';
			$idPf = isset($_GET['idpf'])?$_GET['idpf']:0;
			$idModule = isset($_GET['idmodule'])?$_GET['idmodule']:0;
			$idEtudiant = isset($_GET['idetudiant'])?$_GET['idetudiant']:0;
			$participe = isset($_GET['participe'])?$_GET['participe']=='true':false;

			$etudiant = Etudiant::getById($idEtudiant);
			$retVal = $etudiant->setModuleParticipation($idPf, $idModule, $participe);
			print($retVal);
		}else{
		    print('404');
        }
	}
?>