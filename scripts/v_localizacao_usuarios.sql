CREATE OR REPLACE VIEW V_LOCALIZACAO_USUARIOS AS 
SELECT 
CASE WHEN city.latitude IS NOT NULL THEN city.latitude ELSE stat.latitude END AS LATITUDE,
CASE WHEN city.longitude IS NOT NULL THEN city.longitude ELSE stat.longitude END AS LONGITUDE,
CASE WHEN city.nome IS NOT NULL THEN city.nome ELSE stat.nome END AS LOCAL,
hu.name AS HASHTAG
FROM tweet_owner tw_owner
INNER JOIN tweet tw ON tw_owner.id = tw.owner_id
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id AND hu.primary = 1
LEFT JOIN city ON tw_owner.city_id = city.codigo
LEFT JOIN br_state stat ON tw_owner.br_state_id = stat.codigo
WHERE (city.latitude IS NOT NULL AND city.longitude IS NOT NULL)  OR (stat.latitude IS NOT NULL AND stat.longitude IS NOT NULL);
   
    
select count(*) from V_LOCALIZACAO_USUARIOS WHERE hashtag IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018', '#jairbolsonaropresidente', '#bolsonaropresidente');