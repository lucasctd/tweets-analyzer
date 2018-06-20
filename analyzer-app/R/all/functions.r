calcPercentage <- function(qtd, total){
  return(qtd / total * 100)
}

groupAndCount <- function(sentiment){
  
  ruim <- with(sentiment, c(sum(sentiment$score <= -0.25 )))
  regular <- with(sentiment, c(sum(sentiment$score <= 0.25 & sentiment$score > -0.25)))
  bom <- with(sentiment, c(sum(sentiment$score > 0.25 )))
  
  values <- c(ruim, regular, bom)
  total <- sum(values)
  return(sapply(values, function(x) calcPercentage(x, total)))
}

getUsersMapData <- function(hashtags){
  query <- "SELECT latitude, longitude, COUNT(1) AS quantidade, local FROM V_LOCALIZACAO_USUARIOS WHERE hashtag = "
  query <- paste(query, "'#", hashtags[1], "'", sep = '')
  
  for(hashtag in hashtags[-1]){
    query <- paste(query, " OR hashtag = '#", hashtag, "'", sep = '')
  }
  
  query <- paste(query, " GROUP BY latitude, longitude, local", sep = '')
  
  res <- dbSendQuery(con, query)
  
  data <- fetch(res, n = -1)
  
  df <- data.frame("longitude" = data$LONGITUDE, "latitude" = data$LATITUDE, "local" = data$LOCAL, "quantidade" = data$quantidade)
  
  return(df)
}