#Hashtags secundárias
library(wordcloud)
library(tm)

source("shared/database.r")

wordcloudHashtagsSecundarias <- function(hashtags, min_freq, max_words) {
  
  query <- "SELECT v.name AS hashtag FROM V_HASHTAGS_SECUNDARIAS v WHERE v.text LIKE "
  query <- paste(query, "'%#", hashtags[1], "%'" , sep = '')
  
  for(hashtag in hashtags[-1]){
    query <- paste(query," OR v.text LIKE '%#", hashtag, "%'", sep = '')
  }
    
  res <- dbSendQuery(con, query)
  
  result <- fetch(res, n = -1)
  
  value <- sapply(result$hashtag, function(txt) txt)
  corpus <- Corpus(VectorSource(value))
  corpus <- tm_map(corpus, content_transformer(tolower))
  corpus <- tm_map(corpus, content_transformer(function(x) iconv(x, "UTF-8", "latin1")))
  
  wordcloud(corpus, min.freq = min_freq, max.words = max_words, random.order = F)
  
}