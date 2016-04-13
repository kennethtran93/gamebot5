<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Account extends Application {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/account
	 * 	- or -
	 * 		http://example.com/index.php/account/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/player/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($action = 'info')
	{
		// Check if username exist
		if (!is_null($this->session->username))
		{
			// Username exists; user logged in
			$name = $this->session->username;
		}

		$this->data['pageTitle'] = ''; // Page Title
		$this->data['pagebody'] = 'register'; // this is the view we want shown
		$this->data['regUsernameError'] = "";
		$this->data['regPasswordError'] = "";
		$this->data['avatarError'] = "";

		switch ($action)
		{
			case 'login':
				if (!empty($name))
				{
					// User is logged in already.  Redirect to main account page
					redirect("/account");
				}
				$this->data['pageTitle'] = 'Login';
				$this->data['staticMessage'] = "Use the login form in the navigation section to login.";
				break;
			case 'register':
				if (!empty($name))
				{
					// Player already logged in.
					redirect("/account");
				}

				//  At this point Not Logged In
				$this->data['pageTitle'] = 'Player Account Registration';
				$this->data['regUsername'] = $this->session->regUsername;
				if (!is_null($this->input->post('regSubmit')))
				{
					// Create New Account button clicked
					$valid = true;
					$regUsername = strtolower($this->input->post('regUsername'));
					$this->session->regUsername = $regUsername;
					$this->data['regUsername'] = $this->session->regUsername;
					$regPassword = $this->input->post('regPassword');
					$regConfirmPassword = $this->input->post('regConfirmPassword');

					// Check Username
					if (is_null($regUsername) || empty($regUsername))
					{
						// No value found for username
						$valid = false;
						$this->data['regUsernameError'] = "Username is required.";
					} else
					{
						// check if username already exists
						if ($this->players->exists($regUsername))
						{
							// Username already exist in database
							$valid = false;
							$this->data['regUsernameError'] = "Username already taken by someone else.";
						}
					}

					// Check Password
					if (is_null($regPassword) || empty($regPassword) || is_null($regConfirmPassword) || empty($regConfirmPassword))
					{
						// at least one password field is empty
						$valid = false;
						$this->data['regPasswordError'] = "Both password fields are required.";
					}

					// Confirm both passwords match
					if ($regPassword !== $regConfirmPassword)
					{
						$valid = false;
						$this->data['regPasswordError'] = "Passwords entered do not match.";
					}

					// Prepare generic avatar filename
					$avatarFile = 'generic_photo.png';
					// Check if all previous checks are validated before running this
					if ($valid && is_null($this->input->post('useGenericAvatar')))
					{
						// Generic Avatar Checkbox not checked.
						// Attempt to perform file upload
						$result = $this->avatar_upload();
						if ($result['uploaded'])
						{
							// File Uploaded, supposedly passed validation
							// Insert encoded filename for insert with new player registration
							$avatarFile = $result['upload_data']['file_name'];
						} else
						{
							// File Upload failed.  Reasons stated.
							$valid = false;
							$this->data['avatarError'] = $result['display_errors'];
						}
					}

					if ($valid)
					{
						$newPlayer = array(
							'Player'	 => $regUsername,
							'Password'	 => password_hash($regPassword, PASSWORD_DEFAULT),
							'Avatar'	 => $avatarFile
						);
						$this->players->add($newPlayer);
						$this->data['pageTitle'] = "Player Registration Complete";
						$this->session->statusMessage = "Player Registration values have passed validation!";
						$this->data['staticMessage'] = "Congratulations!  Your account has been created with the username [ " . $regUsername . " ].  Simply login above to get started.";
					}
				} else
				{
					$this->data['pageTitle'] = 'New Player Registration';
					$this->data['pagebody'] = 'register';
				}
				break;
			case 'info':
				$this->data['pageTitle'] = "Player Account Info";
				$this->data['newPasswordError'] = "";
				$this->data['curPasswordError'] = "";
				if (isset($name))
				{
					// logged in
					if (!is_null($this->input->post('pwSubmit')))
					{
						// Change Password Button clicked
						$valid = true;
						$curPassword = $this->input->post('curPassword');

						// Check Password
						if (is_null($curPassword) || empty($curPassword))
						{
							// current password field is empty
							$valid = false;
							$this->data['curPasswordError'] = "Your current password is required.";
						} else
						{
							// current passwored field has a value
							$player = $this->players->get(strtolower($name));
							if (password_verify($curPassword, $player->Password))
							{
								// Current password matches
								$newPassword = $this->input->post('newPassword');
								$newConfirmPassword = $this->input->post('newConfirmPassword');
								if (is_null($newPassword) || empty($newPassword) || is_null($newConfirmPassword) || empty($newConfirmPassword))
								{
									// at least one password field is empty
									$valid = false;
									$this->data['newPasswordError'] = "Both password fields are required.";
								}

								// Confirm both new passwords match
								if ($newPassword !== $newConfirmPassword)
								{
									// New Passwords do not match
									$valid = false;
									$this->data['newPasswordError'] = "Passwords entered do not match.";
								}
							} else
							{
								// current password doesn't match
								$valid = false;
								$this->data['curPasswordError'] = "Current password is not correct.";
							}
						}

						if ($valid)
						{
							$updatePlayer = array(
								'Player'		 => strtolower($name),
								'Password'		 => password_hash($newPassword, PASSWORD_DEFAULT),
								'LastUpdated'	 => date('Y-m-d H:i:s')
							);
							$this->players->update($updatePlayer);
							$this->data['pageTitle'] = "Password Change Successful!";
							$this->session->statusMessage = "Player password changed!";
							$this->data['staticMessage'] = "Congratulations, " . $name . "!  Your account password has been successfully changed.  ";
						}
					}

					if (!is_null($this->input->post('avatarChange')))
					{
						// Prepare generic avatar filename
						$avatarFile = 'generic_photo.png';
						$valid = true;
						// Check if all previous checks are validated before running this
						if (is_null($this->input->post('useGenericAvatar')))
						{
							// Generic Avatar Checkbox not checked.
							// Attempt to perform file upload
							$result = $this->avatar_upload();
							if ($result['uploaded'])
							{
								// File Uploaded, supposedly passed validation
								// Insert encoded filename for insert with new player registration
								$avatarFile = $result['upload_data']['file_name'];

								// Get current avatar for player.  Delete if not generic.
								$curAvatar = $this->players->get(strtolower($name))->Avatar;
								if (strcasecmp($curAvatar, "generic_photo.png") <> 0)
								{
									// Player's current avatar is not a generic image.
									// Using PHP's Delete file function
									$deleted = unlink($_SERVER['DOCUMENT_ROOT'] . $this->data['appRoot'] . "/assets/images/avatar/" . $curAvatar);
									if ($deleted)
									{
										$this->session->statusMessage = "Your avatar has been successfully replaced with the uploaded avatar you've chosen.";
									} else
									{
										$this->session->statusMessage = "Your avatar has been replaced, but the original file could not be deleted.  This is just a message for the administrators.";
									}
								}
							} else
							{
								// File Upload failed.  Reasons stated.
								$valid = false;
								$this->data['avatarError'] = $result['display_errors'];
							}
						} else
						{
							// Generic Avatar Photo checkbox checked.
							// Get current avatar for player.  Delete if not generic.
							$curAvatar = $this->players->get(strtolower($name))->Avatar;
							if (strcasecmp($curAvatar, "generic_photo.png") <> 0)
							{
								// Player's current avatar is not a generic image.
								// Using PHP's Delete file function
								$deleted = unlink($_SERVER['DOCUMENT_ROOT'] . $this->data['appRoot'] . "/assets/images/avatar/" . $curAvatar);
								if ($deleted)
								{
									$this->session->statusMessage = "Your avatar has been successfully replaced with our generic avatar.";
								} else
								{
									$this->session->statusMessage = "Your avatar has been replaced, but the original file could not be deleted.  This is just a message for the administrators.";
								}
							}
						}

						if ($valid)
						{
							$updatePlayer = array(
								'Player'		 => strtolower($name),
								'Avatar'		 => $avatarFile,
								'LastUpdated'	 => date('Y-m-d H:i:s')
							);
							$this->players->update($updatePlayer);
							$this->data['pageTitle'] = "Player Avatar Change Complete";
							$this->data['staticMessage'] = "Congratulations, " . $name . "!  Your account avatar has been successfully changed.  ";
						}
					}
					// Get Player Data
					$player = $this->players->get($name);
					$this->data['username'] = $player->Player;
					$this->data['role'] = ($player->AccessLevel == 99 ? "Admin" : "Player");
					$this->data['dateRegistered'] = $player->DateRegistered;
					$this->data['lastUpdated'] = (is_null($player->LastUpdated) ? "Never" : $player->LastUpdated);
					$this->data['pagebody'] = "account";
				} else
				{
					// not logged in
					$this->data['staticMessage'] = "Viewing or Modifiying your account information requires you to be logged in first!";
				}
				break;
			default:
				redirect("/account");
				break;
		}

		// Render Page!
		$this->render();
	}

}
