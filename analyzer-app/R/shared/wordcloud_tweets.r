library(wordcloud)
library(tm)

source("shared/database.r")

wordcloudTweets <- function(hashtags, min_freq, max_words){
  
  query <- "SELECT tw.text FROM tweet tw 
              INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id 
              INNER JOIN tweet_owner tw_owner ON tw.owner_id = tw_owner.id
              WHERE hu.name IN ("
  
  query <- paste(query, "'#", hashtags[1], "'", sep = '')
  for(hashtag in hashtags[-1]){
    query <- paste(query, ",'#", hashtag, "'", sep = '')
  }
  query <- paste(query, ')', sep = '')
  
  res <- dbSendQuery(con, query)
  
  tweets <- fetch(res, -1)
  tweets_df <- as.data.frame(tweets)
  #clear
  tweets_df$text <- iconv(tweets_df$text, "UTF-8", "latin1")
  tweets_df$text <- tolower(tweets_df$text)
  tweets_df$text = gsub('https[^[:space:]]*', '', tweets_df$text)
  tweets_df$text = removePunctuation(tweets_df$text)
  tweets_df$text = gsub("(rt|via)((?:\\b\\W*@\\w+)+)", "",  tweets_df$text)
  tweets_df$text = gsub("rt", "",  tweets_df$text)
  tweets_df$text = gsub(":", "", tweets_df$text)
  # remove unnecessary spaces
  tweets_df$text = gsub("[ \t]{2,}", "", tweets_df$text)
  tweets_df$text = gsub("^\\s+|\\s+$", "", tweets_df$text)
  #remove stopwords
  tweets_df$text = removeWords(tweets_df$text,stopwords("pt"))
  
  wordcloud(Corpus(VectorSource(tweets_df$text)), min.freq = min_freq, random.order = F, max.words = max_words)
}