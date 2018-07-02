CREATE OR REPLACE VIEW V_SENTIMENTOS AS 
SELECT sent.score, sent.magnitude, tw.precandidato_id, tw.tweet_created_at as tweet_criado_em FROM sentiment sent
INNER JOIN tweet tw ON sent.id = tw.sentiment_id;

SELECT * FROM V_SENTIMENTOS where precandidato_id = 2 ORDER BY tweet_criado_em;

SELECT score, DATE_FORMAT(tweet_criado_em,'%d/%m/%Y %H:%i') AS criado_em  
FROM V_SENTIMENTOS WHERE precandidato_id = 2
ORDER BY tweet_criado_em


DATE_FORMAT(tweet_criado_em,'%d/%m/%Y %H:%i') AS tweet_criado_em 