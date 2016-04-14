<?php

defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * core/MY_Controller.php
 *
 * Default application controller
 *
 */
class Application extends CI_Controller {

	protected $data = array();   // parameters for view components
	protected $id;  // identifier for our content
	protected $pageScripts = array(); // For loading page-specific Scripts
	protected $pageStyles = array(); // For loading page-specific Styles
	protected $serverURL = ""; // Server URL for this agent to connect to

	/**
	 * Constructor.
	 * Establish view parameters & load common helpers
	 */

	function __construct()
	{
		parent::__construct();
		$this->data = array();

		// our default title
		$this->data['site-title'] = 'GameBots G5 - CodingForDonuts';

		$this->serverURL = "http://ken-botcards.azurewebsites.net";

		$this->errors = array();

		// our default page
		$this->data['pageTitle'] = 'Welcome';

		// For debugging purposes only.
		$this->data['debug'] = "";

		$this->data['staticMessage'] = "";
		$this->data['staticMessageType'] = "";

		// This special line of code will check if the application is running in a folder or not
		// i.e.:  gamebot5.local will not return anything, whereas localhost/gamebot5 will return /gamebot5
		$this->data['appRoot'] = (strlen(dirname($_SERVER['SCRIPT_NAME'])) === 1 ? "" : dirname($_SERVER['SCRIPT_NAME']));

		/**
		 * Add in additional CSS files used by using:
		 * 
		 * 		$this->pageStyles[] = 'string';
		 * 
		 * in the INDIVIDUAL controllers.
		 * 
		 * You can specify:
		 * 	local css files (in the css folder) by just the filename WITHOUT the .css extension
		 * 	CDN Hotlinks by the full url (including http)
		 */
		// Set global styles to load on all pages
		$this->pageStyles = array('button', 'smartphone', 'style', 'tablet', 'table');

		/**
		 * Add in additional JS files used by using
		 * 
		 * 		$this->pageScripts[] = 'string';
		 * 
		 * You can specify:
		 * 	local js files (in the js folder) by just the filename WITHOUT the .js extension
		 * 	CDN Hotlinks by the full url (including http)
		 */
		// set global scripts to load on all pages
		$this->pageScripts = array('https://code.jquery.com/jquery-2.2.0.min.js');

		// Check if user is logged in or not, and display according login/logout part
		$this->userSession();

		$this->agentRegister();
	}

	/**
	 * Render this page
	 */
	function render()
	{
		// This is a workaround to dynamically append a folder name if application is not in root.
		$tempMenu = array();
		foreach ($this->config->item('menu_choices')['menuname'] as $record)
		{
			$record['appRoot'] = $this->data['appRoot'];
			$tempMenu['menuname'][] = $record;
		}
		// Additional links for logged in user
		if ($this->session->username != "")
		{
			// Admin Menu Link
			if ($this->session->accessLevel == 99)
			{
				$link['name'] = 'Admin Management';
				$link['link'] = '/admin';
				$link['appRoot'] = $this->data['appRoot'];

				$tempMenu['menuname'][] = $link;
			}
		}

		$tempMenu['userSession'] = $this->data['userSession'];

		// Parse and return the html string of the links for menubar navigation
		$this->data['menubar'] = $this->parser->parse('_menubar', $tempMenu, true);

		// CI Parser to insert into template
		$this->data['loadScripts'] = "";

		// Check if we need to load any JavaScript files
		if (!empty($this->pageScripts))
		{
			// at least one value was provided to the pageScripts array
			// Make sure only unique values are in the array
			$this->pageScripts = array_unique($this->pageScripts);
			$scripts = array();
			foreach ($this->pageScripts as $js)
			{
				// Check if the value is a local file or a hotlink
				if (strpos($js, "http") === false)
				{
					// Did not detect http, therefore it's a local file (hopefully)
					// NOTE:  We aren't checking for file existence
					$temp['link'] = $this->data['appRoot'] . "/assets/js/" . $js . ".js";
				} else
				{
					// Detected http, attempt to validate link via PHP Validation
					if (filter_var($js, FILTER_VALIDATE_URL))
					{
						// Link validated by PHP
						$temp['link'] = $js;
					} else
					{
						// Invalid Link... Do nothing.
					}
				}
				// Add into the proper array for CI Parsing
				$scripts['scripts'][] = $temp;
			}

			// Parse and return html string
			$this->data['loadScripts'] = $this->parser->parse('__js', $scripts, true);
		}

		// CI Parser to insert into template
		$this->data['loadStyles'] = "";

		// Check if we need to load any CSS files
		if (!empty($this->pageStyles))
		{
			// at least one value was provided to the pageStyles array
			// Make sure only unique values are in the array
			$this->pageStyles = array_unique($this->pageStyles);
			$styles = array();
			foreach ($this->pageStyles as $css)
			{
				// check if the value is a local file or a hotlink
				if (strpos($css, "http") === false)
				{
					// Did not detect http, therefore it's a local file (hopefully)
					// NOTE:  We aren't checking for file existence
					$temp['link'] = $this->data['appRoot'] . "/assets/css/" . $css . ".css";
				} else
				{
					// Detected http, attempt to validate link via PHP Validation
					if (filter_var($css, FILTER_VALIDATE_URL))
					{
						// Link validated by PHP
						$temp['link'] = $css;
					} else
					{
						// Invalid Link... Do nothing.
					}
				}

				// Add into proper array for CI Parsing
				$styles['styles'][] = $temp;
			}

			// Parse and return html string
			$this->data['loadStyles'] = $this->parser->parse('__css', $styles, true);
		}

		// Check if static message parameter is not empty.
		if (!empty($this->data['staticMessage']))
		{
			// Static Message Set.  Override the body content to render with a simple static message.
			$this->data['pagebody'] = "_message";
		}

		$this->data['statusMessage'] = $this->session->loginMessage;
		
		if (!empty($this->session->userdata('statusMessage')))
		{
			$this->data['statusMessage'] = $this->session->statusMessage;
			$this->session->unset_userdata('statusMessage');
		}
		

		$this->data['gameStatus'] = $this->parser->parse('_gameStatus', $this->getStatus(), TRUE);

		$this->data['content'] = $this->parser->parse($this->data['pagebody'], $this->data, true);
		// finally, build the browser page!
		$this->data['data'] = &$this->data;
		$this->parser->parse('_template', $this->data);
	}

	/*
	 * User Session Function - handles login / logout and its form to display
	 */

	function userSession()
	{
		$display = $this->load->view('_loginForm', '', true);
		if (is_null($this->session->username))
		{
			// Insert the page user tried to access recently into session.
			$this->session->pageurl = urlencode($_SERVER['REQUEST_URI']);

			// No username set in session
			if (!is_null($this->input->post('login')))
			{
				// login button clicked
				$username = strtolower(str_replace(" ", "", $this->input->post('username')));
				$password = $this->input->post('password');

				if (!empty($username) && !is_null($username) && !empty($password) & !is_null($password))
				{
					// Username & Password values exist. 
					// Check if username exists in system
					if ($this->players->exists($username))
					{
						// Username exists.  Get Player Record
						$account = $this->players->get($username);

						// Validate username with password given
						$valid = password_verify($password, $account->Password);

						if ($valid)
						{
							// password is a match.  Login successful.
							$this->session->username = ucfirst($username);
							$this->session->accessLevel = $account->AccessLevel;
							$player['player'] = $this->session->username;
							$display = $this->parser->parse('_loggedIn', $player, true);

							$this->session->loginMessage = "Login Successful -- Welcome aboard, " . $this->session->username . "!";
						} else
						{
							// invalid password supplied.
							$this->session->loginMessage = "Invalid Username and Password combination.";
						}
					} else
					{
						// Username does not exist.
						$this->session->loginMessage = "Invalid Username and Password combination.";
					}
				} else
				{
					// Either username/password field is blank
					$this->session->loginMessage = "Missing data in either username or password fields.";
				}
			} elseif (!is_null($this->input->post('register')))
			{
				// register button clicked
				redirect("/account/register");
			} else
			{
				$this->session->loginMessage = "You are currently viewing this site as a guest.  Login or register to do more awesome things!";
			}
		} else
		{
			// Username in session
			if (!is_null($this->input->post('logout')))
			{
				// logout button clicked
				$this->session->sess_destroy();
				// Normally a successfully logged out message would be generated, 
				// but since we're doing a redirect after destroying a session, it's useless.
				redirect($_SERVER['REQUEST_URI']);
			} else
			{
				// User still logged in.
				$this->session->loginMessage = "Done playing, " . $this->session->username . "?  If so, don't forget to log out.";

				$player['player'] = $this->session->username;
				$display = $this->parser->parse('_loggedIn', $player, true);
			}
		}

		// Send for CI parsing!
		$this->data['userSession'] = $display;
	}

	/*
	 * Gets the status of the botcards server
	 */

	function getStatus()
	{
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $this->serverURL . "/status");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		$result = curl_exec($ch);
		curl_close($ch);

		$xml = simplexml_load_string($result);
		// Convert object to array
		$status = get_object_vars($xml);
		// Just grammatical stuff
		if ($status['countdown'] == 1)
		{
			$status['s'] = "";
		} else
		{
			$status['s'] = "s";
		}

		return $status;
	}

	/*
	 * Generic function for avatar uploading
	 */

	function avatar_upload()
	{
		$config['upload_path'] = './assets/images/avatar/';
		$config['allowed_types'] = 'jpeg|jpg|png';
		$config['max_size'] = 2048;
		$config['max_width'] = 175;
		$config['max_height'] = 200;
		$config['encrypt_name'] = TRUE;

		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('avatarUpload'))
		{
			return array(
				'uploaded'		 => FALSE,
				'display_errors' => $this->upload->display_errors()
			);
		} else
		{
			return array(
				'uploaded'		 => TRUE,
				'upload_data'	 => $this->upload->data()
			);
		}
	}

	/*
	 *  function for agent registration
	 */

	function agentRegister()
	{

		$status = $this->getStatus(false);

		//check if status is ready or open
		if ($status['state'] == 2 || $status['state'] == 3)
		{
			$data = array("team" => "a007", "name" => "James_007", "password" => "tuesday");

			$string = http_build_query($data);

			//send post request to BCC/register 
			$posturl = curl_init($this->serverURL . '/register');
			curl_setopt($posturl, CURLOPT_POST, true);
			curl_setopt($posturl, CURLOPT_POSTFIELDS, $string);
			curl_setopt($posturl, CURLOPT_RETURNTRANSFER, true);

			$response = curl_exec($posturl);
			curl_close($posturl);

			$xml = simplexml_load_string($response);

			//check if agent exists in the record
			if (!$this->agent->exists((String) $xml->token))
			{
				// Token changed.  Update Database accordingly.
				// Delete Record / Truncate Table
				$this->agent->truncate();

				//add agent to database
				$agent = $this->agent->create();
				$agent->auth_token = (String) $xml->token;
				$agent->code = $data['team'];
				$agent->name = $data['name'];
				unset($agent->last_updated);
				$agent->round_registered = $status['round'];

				$this->agent->add((array) $agent);
			}
		}
	}

}

/* End of file MY_Controller.php */
/* Location: application/core/MY_Controller.php */
