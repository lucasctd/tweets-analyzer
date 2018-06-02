#Hashtags secundárias
library(wordcloud)
library(tm)

source("shared/database.r")

getWorldcloudHashtagsSecundarias <- function(hashtag1, hashtag2, hashtag3) {
  
  query <- "SELECT v.name AS hashtag FROM V_HASHTAGS_SECUNDARIAS v
                     WHERE v.text LIKE "
  query <- paste(query, "'%#jairbolsonaro%'")
  query <- paste(query, "'%#", hashtag1, "%' OR v.text LIKE '%#", hashtag2, "%' OR v.text LIKE '%#", hashtag3, "%'" , sep = '')
    
  res <- dbSendQuery(con, query)
  
  result <- fetch(res, n = -1)

  value <- sapply(result$hashtag, function(txt) txt)
  corpus <- Corpus(VectorSource(value))
  corpus <- tm_map(corpus, content_transformer(tolower))
  corpus <- tm_map(corpus, content_transformer(function(x) iconv(x, "UTF-8", "latin1")))
  
  wordcloud(corpus, min.freq = 5, random.order = F)
  
}