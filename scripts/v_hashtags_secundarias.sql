CREATE OR REPLACE VIEW V_HASHTAGS_SECUNDARIAS AS 
SELECT hu.name, tw.text FROM hashtag_username hu
INNER JOIN tweet tw ON hu.tweet_id = tw.id
WHERE hu.primary = '0';