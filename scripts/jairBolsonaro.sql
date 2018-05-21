#Data

SELECT tw.tweet_owner, tw.created_at, pla.full_name, tw.text 
FROM tweet tw 
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
LEFT JOIN place pla ON tw.pla_id = pla.id
WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018')
ORDER BY created_at, tweet_owner;

#R Studio

SELECT tw.text
FROM tweet tw 
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
LEFT JOIN place pla ON tw.pla_id = pla.id
WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018');

#TOTAL

SELECT COUNT(tw.id)
FROM tweet tw 
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
LEFT JOIN place pla ON tw.pla_id = pla.id
WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018');

