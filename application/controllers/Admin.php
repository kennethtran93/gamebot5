<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Admin extends Application {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/admin
	 * 	- or -
	 * 		http://example.com/index.php/admin/index
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/player/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */
	public function index($page = '')
	{
		$this->data['pageTitle'] = 'Site Administration'; // Page Title
		// Check if username exist
		if (!is_null($this->session->username))
		{
			// Username exists; user logged in
			// Get and check access level
			$accessLevel = $this->session->accessLevel;
			if ($accessLevel != 99)
			{
				// User logged in does not have admin priviledges.
				$this->data['staticMessage'] = "Nice try, but you don't have admin priviledges on this site.";
				$this->data['staticMessageType'] = "staticError";
			} else
			{
				// User has admin priviledges
				if (!is_null($this->input->post('adminReturn')))
				{
					// Admin clicked return to main page button
					redirect('/admin');
				}
				switch ($page)
				{
					case 'agent':
						$this->data['pagebody'] = 'admin_agent';
						break;
					case 'player':
						$this->pageScripts[] = 'admin';
						$this->pageScripts[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.js";
						$this->pageStyles[] = "https://cdn.datatables.net/t/dt/dt-1.10.11,fh-3.1.1,r-2.0.2/datatables.min.css";
						$this->data['pagebody'] = 'admin_players';

						if (!is_null($this->input->post('resetPasswords')))
						{
							// Reset Passwords Button clicked
							$selected = $this->selectedPlayers('resetPasswords');

							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo_confirm', array("Players" => $selected), true);
							$this->data['action'] = 'ResetPasswords';
							$this->data['Description'] = 'Reset password(s) to their username';

							$this->data['pagebody'] = 'admin_players_confirm';
						} elseif (!is_null($this->input->post('deleteAccounts')))
						{
							// Delete Accounts Button Clicked
							$selected = $this->selectedPlayers('deleteAccounts');

							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo_confirm', array("Players" => $selected), true);
							$this->data['action'] = 'DeleteAccounts';
							$this->data['Description'] = 'Delete account(s) from server';
							$this->data['pagebody'] = 'admin_players_confirm';
						} elseif (!is_null($this->input->post('resetAvatars')))
						{
							// Reset Avatar button clicked
							$selected = $this->selectedPlayers('resetAvatars');

							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo_confirm', array("Players" => $selected), true);
							$this->data['action'] = 'ResetAvatars';
							$this->data['Description'] = 'Reset avatar(s) to the generic avatar';
							$this->data['pagebody'] = 'admin_players_confirm';
						} elseif (!is_null($this->input->post('promoteToAdmin')))
						{
							// Reset Avatar button clicked
							$selected = $this->selectedPlayers('promoteToAdmin');

							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo_confirm', array("Players" => $selected), true);
							$this->data['action'] = 'PromoteToAdmin';
							$this->data['Description'] = 'Promote account(s) to Admin Role';
							$this->data['pagebody'] = 'admin_players_confirm';
						} elseif (!is_null($this->input->post('removeAdmin')))
						{
							// Reset Avatar button clicked
							$selected = $this->selectedPlayers('removeAdmin');

							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo_confirm', array("Players" => $selected), true);
							$this->data['action'] = 'RemoveAdmin';
							$this->data['Description'] = 'Remove Admin Role from account';
							$this->data['pagebody'] = 'admin_players_confirm';
						} elseif (!is_null($this->input->post('confirmResetPasswords')))
						{
							// Reset Password(s) Confirmed.
							$count = $this->performAction('confirmResetPasswords');

							$this->session->statusMessage = "Action:  Reset Password to their username:  " . $count['success'] . " done/success; " . $count['failed'] . " failed.";
							redirect('/admin/player');
						} elseif (!is_null($this->input->post('confirmDeleteAccounts')))
						{
							// Delete Account(s) confirmed
							$count = $this->performAction('confirmDeleteAccounts');

							$this->session->statusMessage = "Action:  Delete Account from Database:  " . $count['success'] . " done/success; " . $count['failed'] . " failed.";
							redirect('/admin/player');
						} elseif (!is_null($this->input->post('confirmResetAvatars')))
						{
							// Reset Avatar(s) confirmed
							$count = $this->performAction('confirmResetAvatars');

							$this->session->statusMessage = "Action:  Reset avatar to generic avatar:  " . $count['success'] . " done/success; " . $count['failed'] . " failed.  Any custom avatars uploaded from these accounts have been deleted from the server.";
							redirect('/admin/player');
						} elseif (!is_null($this->input->post('confirmPromoteToAdmin')))
						{
							// Delete Account(s) confirmed
							$count = $this->performAction('confirmPromoteToAdmin');

							$this->session->statusMessage = "Action:  Admin Role Promotion:  " . $count['success'] . " done/success; " . $count['failed'] . " failed.";
							redirect('/admin/player');
						} elseif (!is_null($this->input->post('confirmRemoveAdmin')))
						{
							// Delete Account(s) confirmed
							$count = $this->performAction('confirmRemoveAdmin');

							$this->session->statusMessage = "Action:  Revoke Admin Role:  " . $count['success'] . " done/success; " . $count['failed'] . " failed.";
							redirect('/admin/player');
						} else
						{
							// Possibly an initial visit to the player maintenance page,
							// or a decline from one of the confirmation pages
							$players = $this->players->show();
							$output = array();
							foreach ($players as $player)
							{
								$row = get_object_vars($player);
								if (empty($row['LastUpdated']))
								{
									$row['LastUpdated'] = "Never";
								}
								$output[] = $row;
							}
							$this->data['botPlayers'] = $this->parser->parse('_admin_playerinfo', array("Players" => $output), TRUE);
							$this->data['pagebody'] = 'admin_players';
						}

						break;
					default:
						if (!is_null($this->input->post('adminAgent')))
						{
							// Admin Agent Management Button Clicked
							redirect('/admin/agent');
						} elseif (!is_null($this->input->post('adminPlayer')))
						{
							// Admin Player Management Button Clicked
							redirect('/admin/player');
						} elseif (!empty($page))
						{
							// User trying to access invalid page/function
							$this->session->statusMessage = "You were trying to manage something that doesn't exist!  Try again.";
							redirect('/admin');
						} else
						{
							// We're accessing the /admin page without any additional stuff
							$this->data['pagebody'] = 'admin';
						}
						break;
				}
			}
		} else
		{
			// User not logged in.
			$this->data['staticMessage'] = "Please login with an administrative account to access the administrative functions.";
			$this->data['staticMessageType'] = "staticError";
		}

		// Render Page!
		$this->render();
	}

	// Grabs selected players for a single action
	function selectedPlayers($action)
	{
		$selected = array();
		$post = $this->input->post();
		foreach ($post as $key => $value)
		{
			if ($key != $action)
			{
				$single = get_object_vars($this->players->showOne($key));
				if (empty($single['LastUpdated']))
				{
					$single['LastUpdated'] = "Never";
				}
				$selected[] = $single;
			}
		}

		return $selected;
	}

	// Confirmation received; perform the action
	function performAction($action)
	{
		$post = $this->input->post();
		$success = 0;
		$failed = 0;
		if (!empty($post))
		{
			// Unset the action key from the post array
			unset($post[$action]);
			foreach ($post as $key => $value)
			{
				switch ($action)
				{
					case 'confirmResetPasswords':
						$updatePlayer = array(
							'Player'		 => strtolower($key),
							'Password'		 => password_hash(strtolower($key), PASSWORD_DEFAULT),
							'LastUpdated'	 => date('Y-m-d H:i:s')
						);
						($this->players->update($updatePlayer) ? $success++ : $failed++);
						break;
					case 'confirmDeleteAccounts':
						(is_object($this->players->delete($key)) ? $success++ : $failed++);
						break;
					case 'confirmResetAvatars':
						$curAvatar = $this->players->get(strtolower($key))->Avatar;
						unlink($_SERVER['DOCUMENT_ROOT'] . $this->data['appRoot'] . "/assets/images/avatar/" . $curAvatar);

						$updatePlayer = array(
							'Player'		 => strtolower($key),
							'Avatar'		 => 'generic_photo.png',
							'LastUpdated'	 => date('Y-m-d H:i:s')
						);
						($this->players->update($updatePlayer) ? $success++ : $failed++);
						break;
					case 'confirmPromoteToAdmin':
						$updatePlayer = array(
							'Player'		 => strtolower($key),
							'AccessLevel'	 => 99,
							'LastUpdated'	 => date('Y-m-d H:i:s')
						);
						($this->players->update($updatePlayer) ? $success++ : $failed++);
						break;
					case 'confirmRemoveAdmin':
						$updatePlayer = array(
							'Player'		 => strtolower($key),
							'AccessLevel'	 => 1,
							'LastUpdated'	 => date('Y-m-d H:i:s')
						);
						($this->players->update($updatePlayer) ? $success++ : $failed++);
						break;
					default:
						break;
				}
			}
		}

		return array("success" => $success, "failed" => $failed);
	}

}
