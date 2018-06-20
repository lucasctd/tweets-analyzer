source("shared/database.r")

getSentiment <- function(hashtags){
  
  query <- "SELECT score, magnitude FROM V_SENTIMENTOS WHERE hashtag IN ("
  
  query <- paste(query, "'#", hashtags[1], "'", sep = '')
  
  for(hashtag in hashtags[-1]){
    query <- paste(query, ",'#", hashtag, "'", sep = '')
  }
  
  query <- paste(query, ')', sep = '')
  
  res <- dbSendQuery(con, query)
  
  return(as.data.frame(fetch(res, -1)))
}