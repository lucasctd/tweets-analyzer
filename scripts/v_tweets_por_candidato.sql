CREATE OR REPLACE VIEW V_QUANTIDADE_TWEETS_CANDIDATO AS 
SELECT (
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#alckmin', '#geraldoalckmin', '#alckmin2018', '#geraldoalckmin2018')
) AS GERALDO_ALCKMIN,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018')
) AS JAIR_BOLSONARO,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#manueladavila','#manueladavila2018', '#manuela2018')
) AS MANUELA_DAVILA,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#marina2018', '#marinasilva', '#marinasilva2018')
) AS MARINA_SILVA,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#cirogomes2018', '#ciro2018', '#cirogomes')
) AS CIRO_GOMES,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
	WHERE hu.name IN ('#joaoamoedo', '#joaoamoedo2018', '#amoedo2018')
) AS JOAO_AMOEDO

FROM DUAL;

select * from V_QUANTIDADE_TWEETS_CANDIDATO;