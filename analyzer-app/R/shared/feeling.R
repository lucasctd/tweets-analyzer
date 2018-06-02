library(jsonlite)
library(httr)
library(tm)
library(dplyr)

source("shared/database.r")

analyze_sentiment <- function(piece){
  #create a copy without non needed columns
  tweets_df_4rq <- piece
  tweets_df_4rq[3:4] <- list(NULL)
  tweets_df_4rq[1] <- NULL
  tweets_df_4rq["language"] = "pt";
  
  #convert to JSON
  json_data <- toJSON(list(documents = tweets_df_4rq))
  result_twitter_sentimental = httr::POST("https://westcentralus.api.cognitive.microsoft.com/text/analytics/v2.0/sentiment", 
                                          body = json_data, 
                                          add_headers(.headers = c("Content-Type"="application/json", 
                                                                   "Ocp-Apim-Subscription-Key"= 'f83fcbf0d86e4dd5b981409c4b1cd6e1')))
  #get result content
  req_result <- httr::content(result_twitter_sentimental)
  #cast to data frame
  feeling_score_df <- data.frame(matrix(unlist(req_result), nrow=length(req_result$documents), byrow=T))
  #rename columns
  colnames(feeling_score_df) <- list('score', 'id')
  return(feeling_score_df)
}

getFeeling <- function(hashtag1, hashtag2, hashtag3){

  query <- "SELECT tw.id_str, tw.text, tw_owner.name AS user, tw.tweet_created_at AS created_at FROM tweet tw 
            INNER JOIN hashtag_username hu ON tw.id = hu.tweet_id 
            INNER JOIN tweet_owner tw_owner ON tw.owner_id = tw_owner.id
            WHERE hu.name IN ("
  
  query <- paste(query, "'#", hashtag1, "',", "'#", hashtag2, "',", "'#", hashtag3, "')" , sep = '')
  
  res <- dbSendQuery(con, query)
  
  tweets <- fetch(res, -1)
  tweets_df <- as.data.frame(tweets)
  #clear
  #tweets_df$text <- iconv(tweets_df$text, "UTF-8", "latin1")
  #tweets_df$text <- tolower(tweets_df$text)
  tweets_df$text = gsub('https[^[:space:]]*', '', tweets_df$text);
  tweets_df$text = removePunctuation(tweets_df$text);
  tweets_df$text = gsub("(RT|via)((?:\\b\\W*@\\w+)+)", "",  tweets_df$text);
  tweets_df$text = gsub(":", "", tweets_df$text);
  # remove unnecessary spaces
  tweets_df$text = gsub("[ \t]{2,}", "", tweets_df$text)
  tweets_df$text = gsub("^\\s+|\\s+$", "", tweets_df$text)
  tweets_df["id"] = seq.int(nrow(tweets_df));
  
  blockSize <- 1000
  total <- nrow(tweets_df)
  blocks <- total %/% blockSize
  mod_num <- total %% blockSize
  feeling_piece <- data.frame("id" = NULL, "score" = NULL)
  
  if(total > blockSize){
    for(x in 0:(blocks - 1)){
      begin <- if(x*blockSize == 0) 1 else (x*blockSize) + 1
      end <- ((x+1)*blockSize)
      
      piece <- analyze_sentiment(slice(tweets_df, begin:end))
      feeling_piece <- rbind(feeling_piece, piece)
    }
  }
  
  if(mod_num > 0){
    piece <- analyze_sentiment(slice(tweets_df, ((blocks * blockSize) + 1):total))
    feeling_piece <- rbind(feeling_piece, piece)
  }
  
  #merge dataframes by id
  feeling_df <- merge(tweets_df, feeling_piece, by="id")
  
  return(feeling_df)
}