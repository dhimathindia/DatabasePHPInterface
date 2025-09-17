CREATE TABLE 'Subscriptions' (
  'email' varchar(255) NOT NULL,
  'name' varchar(255) DEFAULT NULL,
  'startDate' date DEFAULT NULL,
  'endDate' date DEFAULT NULL,
  'subscriptionType' tinyint(4) NOT NULL DEFAULT 0,
  PRIMARY KEY ('email')
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci	

