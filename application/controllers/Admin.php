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
						$this->data['serverURLFeedback'] = "";
						$this->data['serverURLFeedbackType'] = "";
						$this->data['serverPasswordFeedback'] = "";
						$this->data['serverPasswordFeedbackType'] = "";
						$this->data['agentCodeFeedback'] = "";
						$this->data['agentCodeFeedbackType'] = "";
						$this->data['agentNameFeedback'] = "";
						$this->data['agentNameFeedbackType'] = "";
						$this->data['toggleAgentFeedback'] = "";
						$this->data['toggleAgentFeedbackType'] = "";
						if (!is_null($this->input->post('toggleAgentStatus')))
						{
							// Toggle Agent Status
							if ($this->agent->get('agent_online')->value)
							{
								// Agent currently online.  Bring offline

								$this->agent->clear(array('auth_token', 'date_registered', 'round_registered', 'last_active_round'));
								$this->agent->update('agent_online', FALSE);

								// Truncate all related table
								$this->transactions->truncate();
								$this->collections->truncate();
								$this->players->resetPeanuts();

								$this->data['toggleAgentFeedback'] = " --- Successfully changed the state of the agent.";
								$this->data['toggleAgentFeedbackType'] = "success";
								$this->session->statusMessage = "This agent is now offline.  All outgoing connections to the server will not run.  Any existing game records have been removed.";
							} else
							{
								// Agent currently offline.  Bring online
								// But first...validate password!
								$testPW = $this->agentRegister(false);
								if ($testPW === TRUE)
								{
									$this->agent->update('agent_online', TRUE);
									$this->agentRegister();

									$this->data['toggleAgentFeedback'] = " --- Successfully changed the state of the agent.";
									$this->data['toggleAgentFeedbackType'] = "success";
									$this->session->statusMessage = "This agent is now online, and has been re-registered with the server.";
								} elseif ($testPW === FALSE)
								{
									$this->data['toggleAgentFeedback'] = " --- Could not bring agent online.  See additional error notes below.";
									$this->data['toggleAgentFeedbackType'] = "error";
									$this->data['serverPasswordFeedback'] = "Existing password for the URL above is invalid.";
									$this->data['serverPasswordFeedbackType'] = "error";

									$this->session->statusMessage = "Could not bring agent online.  See error notes below.";
								} else
								{
									// Can't check password at the moment - invalid state.
									$this->data['toggleAgentFeedback'] = " --- Could not bring agent online.  See additional error notes below.";
									$this->data['toggleAgentFeedbackType'] = "error";
									$this->data['serverPasswordFeedback'] = "Server is not ready for us to validate existing password.";
									$this->data['serverPasswordFeedbackType'] = "error";
									$this->session->statusMessage = "Cannot bring this agent online.  See error notes below.";
								}
							}
						} elseif (!is_null($this->input->post('changeServerProperties')))
						{
							// Change Server Properties button clicked
							$testURL = trim($this->input->post('serverURL'));
							$updatedURL = $this->agent->get('server_URL')->value != $testURL;
							$validURL = true;
							if ($updatedURL && !empty($testURL))
							{
								$validURL = false;
								// Updated url provided
								if (filter_var($testURL, FILTER_VALIDATE_URL))
								{
									// PHP deemed it's a valid url
									if ($this->getStatus(TRUE, $testURL))
									{
										// At this point the new URL appears to have met the reqirements of a Botcards server.
										// Update Server variables
										$this->agent->update('server_URL', $testURL);
										$validURL = true;

										$this->data['serverURLFeedback'] = "Server URL successfully updated!";
										$this->data['serverURLFeedbackType'] = "success";
										$this->session->statusMessage = "Botcards Server Properties Updated.";
									} else
									{
										$this->data['serverURLFeedback'] = "Update Failed - This is not a Botcard server URL ( " . $testURL . " ).";
										$this->data['serverURLFeedbackType'] = "error";
										$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
									}
								} else
								{
									$this->data['serverURLFeedback'] = "Update Failed - Invalid URL specified ( " . $testURL . " ).";
									$this->data['serverURLFeedbackType'] = "error";
									$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
								}
							} else
							{
								if (empty($this->input->post('serverURL')))
								{
									$this->data['serverURLFeedback'] = "Update Failed - This field cannot be empty!";
									$this->data['serverURLFeedbackType'] = "error";
									$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
								}
							}

							if ($validURL)
							{
								$updatedPW = $this->agent->get('server_password')->value != $this->input->post('serverPassword');
								if (($updatedPW && !empty($this->input->post('serverPassword'))) || $updatedURL)
								{
									// Updated Password provided, or Server URL Changed
									$testPW = $this->agentRegister(false, $this->input->post('serverPassword'));
									if ($testPW === TRUE)
									{
										// Password valid on server side.
										$this->agent->update('server_password', $this->input->post('serverPassword'));

										if (!$updatedURL)
										{
											// Unchanged URL, just changed password
											$this->data['serverPasswordFeedback'] = "Server Password is correct and updated!";
										} else
										{
											// Updated URL and Password both matches.
											$this->data['serverPasswordFeedback'] = "Server Password is correct for the new URL!";
										}
										$this->data['serverPasswordFeedbackType'] = "success";
										$this->session->statusMessage = "Botcards Server Properties Updated.";
									} elseif ($testPW === FALSE)
									{
										if (!$updatedURL)
										{
											// Invalid Password with existing URL
											$this->data['serverPasswordFeedback'] = "Update Failed - Server did not accept the password ( " . $this->input->post('serverPassword') . " ).";
										} else
										{
											// Updated URL with incorrect password
											// To be safe we will put the agent offline.
											$this->agent->clear(array('auth_token', 'date_registered', 'round_registered', 'last_active_round'));
											$this->agent->update('agent_online', FALSE);

											// Truncate all related table
											$this->transactions->truncate();
											$this->collections->truncate();
											$this->players->resetPeanuts();

											$this->data['serverPasswordFeedback'] = "Existing password for the updated URL is invalid.  As such this agent is now offline.";
										}
										$this->data['serverPasswordFeedbackType'] = "error";
										$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
									} else
									{
										// Can't check password at the moment - invalid state.
										$this->data['serverPasswordFeedback'] = "Update Failed - Server is not ready for us to check password of ( " . $this->input->post('serverPassword') . " ).";
										$this->data['serverPasswordFeedbackType'] = "error";
										$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
									}
								} else
								{
									if (empty($this->input->post('serverPassword')))
									{
										$this->data['serverPasswordFeedback'] = "Update Failed - This field cannot be empty!";
										$this->data['serverPasswordFeedbackType'] = "error";
										$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
									}
								}
							} else
							{
								$this->data['serverPasswordFeedback'] = "Cannot validate password ( " . $this->input->post('serverPassword') . " ) as Server URL is invalid.";
								$this->session->statusMessage = "Updating Botcards Server Properties failed.  See below.";
							}

							if (!$updatedURL && !$updatedPW)
							{
								$this->session->statusMessage = "Nothing to update for the Botcards Server Properties.";
								// prevent rechecking the same data posted.
								redirect("/admin/agent");
							}
						} elseif (!is_null($this->input->post('changeAgentProperties')))
						{
							// Change Agent Properties clicked

							$updatedCode = $this->agent->get('code')->value != $this->input->post('agentCode');
							if ($updatedCode && !empty($this->input->post('agentCode')))
							{
								if (preg_match('/^([AaBb]\d{1,3})$/', $this->input->post('agentCode')))
								{
									// Updated Code provided
									$this->agent->update('code', strtolower(trim($this->input->post('agentCode'))));
									$this->session->statusMessage = "Agent Properties Updated.";
								} else
								{
									// Invalid code entered
									$this->data['agentCodeFeedback'] = "Update Failed - Invalid Agent Code.  Code starts with either a or b, followed by one to three numbers.";
									$this->session->statusMessage = "Updating Agent Properties failed.  See below.";
								}
							} else
							{
								if (empty($this->input->post('agentCode')))
								{
									$this->data['agentCodeFeedback'] = "Update Failed - This field cannot be empty!";
									$this->session->statusMessage = "Updating Agent Properties failed.  See below.";
								}
							}

							$updatedName = $this->agent->get('name')->value != $this->input->post('agentName');
							if ($updatedName && !empty($this->input->post('agentName')))
							{
								// Updated Code provided
								$this->agent->update('name', trim($this->input->post('agentName')));
								$this->session->statusMessage = "Agent Properties Updated.";
							} else
							{
								if (empty($this->input->post('agentName')))
								{
									$this->data['agentNameFeedback'] = "Update Failed - This field cannot be empty!";
									$this->session->statusMessage = "Updating Agent Properties failed.  See below.";
								}
							}

							if (!$updatedCode && !$updatedName)
							{
								$this->session->statusMessage = "Nothing to update for the Agent Properties.";
								// prevent rechecking the same data posted.
								redirect("/admin/agent");
							}
						} elseif (!is_null($this->input->post('forceAgentRegistration')))
						{
							// Force Agent Registerion (when it's online only)
							if ($this->agent->get('agent_online')->value)
							{
								$this->agentRegister();
								$this->data['toggleAgentFeedback'] = " --- Successfully re-registered the agent with the server.";
								$this->data['toggleAgentFeedbackType'] = "success";
								$this->session->statusMessage = "Force / Manual Agent Registration performed.";
							} else
							{
								$this->data['toggleAgentFeedback'] = " --- Cannot force agent registration while agent is offline.";
								$this->data['toggleAgentFeedbackType'] = "error";
								$this->session->statusMessage = "You can't force / manually perform agent registration while the agent is offline!";
							}
						}

						// Get Latest Properties
						$agent = $this->agent->all();

						// display updated status
						if ($agent['agent_online'])
						{
							$this->data['agentStatus'] = 'Online';
						} else
						{
							$this->data['agentStatus'] = 'OFFLINE';
						}

						$this->data['serverURL'] = $agent['server_URL'];
						$this->data['serverPassword'] = $agent['server_password'];

						$this->data['agentCode'] = $agent['code'];
						$this->data['agentName'] = $agent['name'];

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
						// If player's avatar is already generic, do nothing.
						if ($curAvatar != 'generic_photo.png')
						{
							unlink($_SERVER['DOCUMENT_ROOT'] . $this->data['appRoot'] . "/assets/images/avatar/" . $curAvatar);

							$updatePlayer = array(
								'Player'		 => strtolower($key),
								'Avatar'		 => 'generic_photo.png',
								'LastUpdated'	 => date('Y-m-d H:i:s')
							);
							($this->players->update($updatePlayer) ? $success++ : $failed++);
						}
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
