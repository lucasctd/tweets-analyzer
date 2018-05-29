#Hashtags secundárias
library(wordcloud)
library(tm)

source("database.r")

res <- dbSendQuery(con, "SELECT v.name AS hashtag FROM V_HASHTAGS_SECUNDARIAS v
                   WHERE v.text like '%#jairbolsonaro%'
                   OR v.text like '%#bolsonaro%'
                   OR v.text like '%#bolsonaro2018%'")
result <- fetch(res, n = -1)
attach(result)
value <- sapply(hashtag, function(txt) txt)
corpus <- Corpus(VectorSource(value))
corpus <- tm_map(corpus, content_transformer(tolower))
corpus <- tm_map(corpus, content_transformer(function(x) iconv(x, "UTF-8", "latin1")))
wordcloud(corpus, min.freq = 10, random.order = F)