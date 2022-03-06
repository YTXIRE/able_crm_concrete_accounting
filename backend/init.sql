-- create the databases
DROP DATABASE IF EXISTS crm;
CREATE DATABASE crm;

-- create the users for each database
GRANT CREATE, ALTER, INDEX, LOCK TABLES, REFERENCES, UPDATE, DELETE, DROP, SELECT, INSERT ON `crm`.* TO 'crm'@'%';

FLUSH PRIVILEGES;