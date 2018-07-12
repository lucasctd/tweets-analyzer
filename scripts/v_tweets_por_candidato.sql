CREATE OR REPLACE VIEW V_QUANTIDADE_TWEETS_CANDIDATO AS 
SELECT (
	SELECT COUNT(distinct tw.id)
	FROM tweet tw 
	WHERE tw.filter_id = 1
) AS GERALDO_ALCKMIN,
(
	SELECT COUNT(distinct tw.id)
	FROM tweet tw 
	WHERE tw.filter_id = 2
) AS JAIR_BOLSONARO,
(
	SELECT COUNT(distinct tw.id)
	FROM tweet tw
	WHERE tw.filter_id = 3
) AS MANUELA_DAVILA,
(
	SELECT COUNT(distinct tw.id)
	FROM tweet tw 
	WHERE tw.filter_id = 4
) AS MARINA_SILVA,
(
	SELECT COUNT(distinct tw.id)
	FROM tweet tw 
	WHERE tw.filter_id = 5
) AS CIRO_GOMES,
(
	SELECT COUNT(distinct tw.id)
	FROM tweet tw 
	WHERE tw.filter_id = 6
) AS JOAO_AMOEDO

FROM DUAL;

select JAIR_BOLSONARO, GERALDO_ALCKMIN, MANUELA_DAVILA, MARINA_SILVA, CIRO_GOMES, JOAO_AMOEDO from V_QUANTIDADE_TWEETS_CANDIDATO;