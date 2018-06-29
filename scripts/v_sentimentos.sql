CREATE OR REPLACE VIEW V_SENTIMENTOS AS 
SELECT sent.score, sent.magnitude, tw.precandidato_id, tw.tweet_created_at as tweet_criado_em FROM sentiment sent
INNER JOIN tweet tw ON sent.id = tw.sentiment_id;

SELECT * FROM V_SENTIMENTOS;