CREATE OR REPLACE VIEW V_QUANTIDADE_TWEETS_CANDIDATO AS 
SELECT (
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#alckmin', '#geraldoalckmin', '#alckmin2018', '#alckminpresidente', '#geraldoalckminpresidente')
) AS GERALDO_ALCKMIN,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018', '#jairbolsonaropresidente', '#bolsonaropresidente')
) AS JAIR_BOLSONARO,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#manueladavila','#manueladavila2018', '#manuela2018', '#manuela2018', '#manuelapresidente')
) AS MANUELA_DAVILA,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#marina2018', '#marinasilva', '#marinasilva2018', '#marinapresidente', '#marinasilvapresidente')
) AS MARINA_SILVA,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#cirogomes2018', '#ciro2018', '#cirogomes', '#ciropresidente', '#cirogomespresidente')
) AS CIRO_GOMES,
(
	SELECT COUNT(tw.id)
	FROM tweet tw 
	INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
	WHERE hu.name IN ('#joaoamoedo', '#joaoamoedo2018', '#amoedo2018', '#joaoamoedopresidente', '#amoedopresidente', '#JoaoAmoedoNaJovemPan')
) AS JOAO_AMOEDO

FROM DUAL;

select JAIR_BOLSONARO, GERALDO_ALCKMIN, MANUELA_DAVILA, MARINA_SILVA, CIRO_GOMES, JOAO_AMOEDO from V_QUANTIDADE_TWEETS_CANDIDATO;