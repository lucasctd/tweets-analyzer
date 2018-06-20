CREATE OR REPLACE VIEW V_SENTIMENTOS AS 
SELECT sent.score, sent.magnitude, hu.name AS hashtag FROM sentiment sent
INNER JOIN tweet tw ON sent.id = tw.sentiment_id
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1;

SELECT * FROM V_SENTIMENTOS;
