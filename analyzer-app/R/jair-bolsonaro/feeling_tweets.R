#library(wordcloud)
#library(tm)
library(jsonlite)
library(httr)
#library(plyr)
#library(stringi)

source("database.r")

res <- dbSendQuery(con, "SELECT tw.id_str as id, tw.text FROM tweet tw 
                        INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id 
                        INNER JOIN tweet_owner tw_owner ON tw.owner_id = tw_owner.id
                        WHERE hu.name IN ('#bolsonaro', '#jairbolsonaro', '#bolsonaro2018')")

tweets <- fetch(res, -1)
tweets_df <- as.data.frame(tweets)
#clear
#tweets_df$text <- iconv(tweets_df$text, "UTF-8", "latin1")
tweets_df$text <- tolower(tweets_df$text)
tweets_df$text = gsub('https[^[:space:]]*', '', tweets_df$text);
tweets_df$text = removePunctuation(tweets_df$text);
tweets_df$text = gsub("(RT|via)((?:\\b\\W*@\\w+)+)", " ",  tweets_df$text);
tweets_df$text = gsub(":", "", tweets_df$text);
# remove unnecessary spaces
tweets_df$text = gsub("[ \t]{2,}", "", tweets_df$text)
tweets_df$text = gsub("^\\s+|\\s+$", "", tweets_df$text)

tweets_df["language"] = "pt";
#tweets_df["id"] = seq.int(nrow(tweets_df));

#convert to JSON
json_data <- toJSON(list(documents = tweets_df))
result_twitter_sentimental = POST("https://westcentralus.api.cognitive.microsoft.com/text/analytics/v2.0/sentiment", 
                                  body = json_data, 
                                  add_headers(.headers = c("Content-Type"="application/json", 
                                                           "Ocp-Apim-Subscription-Key"= '499f10034b9a4fe085452b21714c086d')))

#wordcloud(corpus, min.freq = 10, max.words = 100, random.order = F)