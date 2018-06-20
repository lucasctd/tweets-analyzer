CREATE OR REPLACE VIEW V_TOP5_USERS AS 
SELECT towner.screen_name AS screen_name, COUNT(tw.id) AS qtd FROM tweet tw
INNER JOIN tweet_owner towner ON tw.owner_id = towner.id
GROUP BY screen_name
ORDER BY qtd DESC
LIMIT 5;

select * from V_TOP5_USERS;