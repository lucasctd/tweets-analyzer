#Data

SELECT tw_own.screen_name, tw.created_at, tw.text 
FROM tweet tw 
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
INNER JOIN tweet_owner tw_own ON tw.owner_id = tw_own.id
WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018')
ORDER BY tw.tweet_created_at, tw_own.screen_name;

#R Studio

SELECT tw.text 
FROM tweet tw 
INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id
INNER JOIN tweet_owner tw_own ON tw.owner_id = tw_own.id
WHERE hu.name IN ('#jairbolsonaro', '#bolsonaro', '#bolsonaro2018');

#hashtags

SELECT v.name AS hashtag FROM V_HASHTAGS_SECUNDARIAS v
WHERE v.text like '%#jairbolsonaro%'
OR v.text like '%#bolsonaro%'
OR v.text like '%#bolsonaro2018%';

SELECT v.name AS hashtag FROM V_HASHTAGS_SECUNDARIAS v
WHERE v.text like '%#jairbolsonaro%'
OR v.text like '%#bolsonaro%'
OR v.text like '%#bolsonaro2018%';

