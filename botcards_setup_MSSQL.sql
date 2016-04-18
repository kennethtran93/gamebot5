--
-- Database: gamebot5
--

-- --------------------------------------------------------

--
-- Table structure for table ci_sessions
-- As copied from http://www.codeigniter.com/userguide3/libraries/sessions.html#id19
--

DROP TABLE [ci_sessions];
CREATE TABLE [ci_sessions] (
	id			VARCHAR(40)								NOT NULL,
	ip_address	VARCHAR(45)								NOT NULL,
	[timestamp]		INT			DEFAULT 0	NOT NULL,
	data			VARCHAR(MAX)									NOT NULL,
	CONSTRAINT ci_sessions_pk PRIMARY KEY (timestamp)
);

-- --------------------------------------------------------

--
-- Table structure for table agent
--
DROP TABLE [agent];
CREATE TABLE [agent] (
	property		VARCHAR(100)	NOT NULL,
	value			VARCHAR(255)	NOT NULL,
	CONSTRAINT agent_pk
		PRIMARY KEY (property)
);

INSERT INTO [agent] (property, value) VALUES
('auth_token', ''),
('code', 'a05'),
('name', 'CodingForDonuts'),
('date_registered', ''),
('round_registered', ''),
('last_active_round', ''),
('server_URL', 'http://ken-botcards.azurewebsites.net'),
('server_password', 'tuesday'),
('agent_online', 'TRUE');

--
-- Table structure for table players
--

DROP TABLE [players];
CREATE TABLE [players] (
	Player			VARCHAR(25)									NOT NULL,
	[Password]			VARCHAR(255)								NOT NULL,
	Avatar			VARCHAR(255)	DEFAULT 'generic_photo.png' NOT NULL,
	AccessLevel		INT				DEFAULT '1'					NOT NULL,
	DateRegistered	DATETIME		DEFAULT CURRENT_TIMESTAMP	NOT NULL,
	LastUpdated		DATETIME,
	Peanuts			INT			DEFAULT 100					NOT NULL,
	last_active_round	INT			DEFAULT 0					NOT NULL,
	CONSTRAINT players_pk
		PRIMARY KEY (Player)
);

--
-- Initial Bogus data for table players
-- Initial Passwords for predefined passwords are the same as their username, in lowercase
--

INSERT INTO [players] (Player, [Password], AccessLevel, Peanuts) VALUES
('mickey', '$2y$10$hQM3A1zrJ3Qv/kcUKurn3eztXX/zn9XjHNblYFSOx8RFnQy8Awvv2', 1, 200),
('donald', '$2y$10$AajhaQypQNXsqfNHIAsbAO70vX2DHYfI3L4ePWys8FISVRy7enIom', 1, 35),
('george', '$2y$10$YjuXoNlaq6c3u/qxoa/y.uFeImB2m6Pblq21icKXJpRvciWVy2Kau', 1, 500),
('henry', '$2y$10$ppIiFlsaeAcYoYIXLMB4.OO06Szk6a9qj0OXi592B434kr3KGRfSm', 1, 100),
('joti', '$2y$10$EmYNrk0D89u/v63nDmaoW.12If.OpUU.hQ2s4eX6Cringks9Bmcqm', 99, 500),
('kenneth', '$2y$10$H14vPLByczPbmNM.ccM26Ob9BotwneB.lB3cve6bpfEpqK30RhRRS', 99, 900),
('liz', '$2y$10$tArSxekA31n0MfvENV7FmugPst8VW0hK0Y1Yh7mC5Hs0H1kNru2tS', 99, 502),
('yoseph', '$2y$10$y8NB3GVaFnNWAbzZP/5IBudVKUsNHfziL.UOePqtvB0yhWmREMSq.', 99, 404),
('admin', '$2y$10$NKavK9B2UJAbkQHyzmcbyeGRjOJOe30mqXef/MoB3epYbqZTgiVGu', 99, 0);

-- --------------------------------------------------------

--
-- Table structure for table collections
--

DROP TABLE [collections];
CREATE TABLE [collections] (
	Token		VARCHAR(6)								NOT NULL,
	Piece		VARCHAR(5)								NOT NULL,
	Player	VARCHAR(25)								NOT NULL,
	[Datetime]	DATETIME	DEFAULT CURRENT_TIMESTAMP	NOT NULL,
	CONSTRAINT collections_pk
		PRIMARY KEY (Token)
);

--
-- Initial Bogus data for table collections
--

INSERT INTO [collections] (Token, Piece, Player, [Datetime]) VALUES
('1BB155', '11b-2', 'George', '2016.02.01 09:01:00'),
('1E654C', '11b-2', 'Mickey', '2016.02.01 09:01:02'),
('1DE9BB', '11b-2', 'Donald', '2016.02.01 09:01:04'),
('1BE8FA', '11c-0', 'George', '2016.02.01 09:01:06'),
('135745', '11a-0', 'Donald', '2016.02.01 09:01:08'),
('1A2EE5', '11c-0', 'Donald', '2016.02.01 09:01:10'),
('11F084', '11a-1', 'Donald', '2016.02.01 09:01:12'),
('1ADF71', '11a-1', 'George', '2016.02.01 09:01:14'),
('1C292C', '11b-0', 'George', '2016.02.01 09:01:16'),
('1E095A', '11c-2', 'Donald', '2016.02.01 09:01:18'),
('132956', '11c-0', 'George', '2016.02.01 09:01:20'),
('1359B6', '11a-0', 'Mickey', '2016.02.01 09:01:22'),
('139244', '11c-0', 'George', '2016.02.01 09:01:24'),
('12072C', '11c-0', 'Henry', '2016.02.01 09:01:26'),
('1C58FB', '11c-2', 'Donald', '2016.02.01 09:01:28'),
('11F0C5', '11b-1', 'George', '2016.02.01 09:01:30'),
('1AB11B', '11a-2', 'Henry', '2016.02.01 09:01:32'),
('1BB8CC', '11b-2', 'Henry', '2016.02.01 09:01:34'),
('14338A', '11c-0', 'George', '2016.02.01 09:01:36'),
('1D17DE', '11a-0', 'George', '2016.02.01 09:01:38'),
('17DC94', '11b-1', 'George', '2016.02.01 09:01:40'),
('1E5222', '11c-2', 'Donald', '2016.02.01 09:01:42'),
('19573B', '11a-2', 'Donald', '2016.02.01 09:01:44'),
('150417', '11b-2', 'Mickey', '2016.02.01 09:01:46'),
('1CA087', '11c-1', 'Mickey', '2016.02.01 09:01:48'),
('154281', '11c-0', 'Donald', '2016.02.01 09:01:50'),
('10DA3E', '11a-1', 'Mickey', '2016.02.01 09:01:52'),
('141117', '11c-2', 'Henry', '2016.02.01 09:01:54'),
('12268C', '11b-0', 'Mickey', '2016.02.01 09:01:56');

-- --------------------------------------------------------

--
-- Table structure for table series
--

DROP TABLE [series];
CREATE TABLE [series] (
	Series		INT			NOT NULL,
	[Description]	VARCHAR(25)		NOT NULL,
	Frequency		INT			NOT NULL,
	Value			INT			NOT NULL,
	CONSTRAINT series_pk
		PRIMARY KEY (Series)
);

--
-- Initial Bogus data for table series
--

INSERT INTO series (Series, [Description], Frequency, Value) VALUES
(11, 'Basic house bots', 100, 20),
(13, 'House butlers', 50, 50),
(26, 'Home companions', 20, 200);

-- --------------------------------------------------------

--
-- Table structure for table transactions
--

DROP TABLE [transactions];
CREATE TABLE [transactions] (
	[DateTime]	DATETIME	DEFAULT CURRENT_TIMESTAMP	NOT NULL,
	Player	VARCHAR(25)								NOT NULL,
	Series	INT		DEFAULT NULL,
	Trans		VARCHAR(4)								NOT NULL,
	CONSTRAINT transactions_pk
		PRIMARY KEY (DateTime, Player)
);

--
-- Initial Bogus data for table transactions
--

INSERT INTO [transactions] ([DateTime], Player, Series, Trans) VALUES
('2016.02.01 09:01:00', 'Mickey', '11', 'sell'),
('2016.02.01 09:01:05', 'Henry', null, 'buy'),
('2016.02.01 09:01:10', 'Mickey', null, 'buy'),
('2016.02.01 09:01:15', 'Donald', '13', 'sell'),
('2016.02.01 09:01:20', 'Donald', null, 'buy'),
('2016.02.01 09:01:25', 'Donald', null, 'buy'),
('2016.02.01 09:01:30', 'Donald', null, 'buy'),
('2016.02.01 09:01:35', 'Donald', null, 'buy'),
('2016.02.01 09:01:40', 'Henry', null, 'buy'),
('2016.02.01 09:01:45', 'Donald', '26', 'sell'),
('2016.02.01 09:01:50', 'George', '11', 'sell'),
('2016.02.01 09:01:55', 'George', null, 'buy'),
('2016.02.01 09:02:00', 'George', null, 'buy');