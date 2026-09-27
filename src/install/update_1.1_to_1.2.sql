--
-- xml-weblog 1.1 to 1.2 SQL update script
--

--
-- add publish bit
--
ALTER TABLE article ADD COLUMN publish tinyint NOT NULL default 0 AFTER content;
UPDATE article SET publish=1;

--
-- add block enabled bit
--
ALTER TABLE block ADD COLUMN enabled tinyint NOT NULL default 0 AFTER sysblock;
UPDATE block SET enabled=1;
